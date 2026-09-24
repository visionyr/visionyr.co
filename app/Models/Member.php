<?php

namespace App\Models;

use Database\Factories\MemberFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'phone', 'password', 'is_active'])]
#[Hidden(['password', 'remember_token'])]
class Member extends Authenticatable
{
    /** @use HasFactory<MemberFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'blueprint_quota_refreshed_at' => 'datetime',
        ];
    }

    /**
     * How much of this month's blueprint allowance is left.
     *
     * @return array{limit: int, used: int, remaining: int, resets_on: \Illuminate\Support\Carbon}
     */
    public function blueprintQuota(): array
    {
        $this->rolloverQuota();

        $limit = (int) config('blueprint.monthly_quota');

        return [
            'limit' => $limit,
            'used' => $this->blueprint_quota_used,
            'remaining' => max(0, $limit - $this->blueprint_quota_used),
            'resets_on' => now()->startOfMonth()->addMonth(),
        ];
    }

    /**
     * Whether this member may generate another blueprint this month.
     */
    public function hasBlueprintQuota(): bool
    {
        return $this->blueprintQuota()['remaining'] > 0;
    }

    /**
     * Count one generation against this month's allowance.
     */
    public function consumeBlueprintQuota(): void
    {
        $this->rolloverQuota();

        $this->increment('blueprint_quota_used');
    }

    /**
     * Give this member their full allowance back, ahead of the monthly rollover.
     */
    public function refreshBlueprintQuota(): void
    {
        $this->forceFill([
            'blueprint_quota_used' => 0,
            'blueprint_quota_period' => now()->format('Y-m'),
            'blueprint_quota_refreshed_at' => now(),
        ])->save();
    }

    /**
     * Zero the counter when the stored month is no longer the current one, so the
     * allowance renews without a scheduled job.
     */
    protected function rolloverQuota(): void
    {
        $period = now()->format('Y-m');

        if ($this->blueprint_quota_period === $period) {
            return;
        }

        $this->forceFill([
            'blueprint_quota_used' => 0,
            'blueprint_quota_period' => $period,
        ])->save();
    }

    /**
     * Get the blueprints this member generated while signed in.
     */
    public function brandBlueprints(): HasMany
    {
        return $this->hasMany(BrandBlueprint::class);
    }

    /**
     * Scope the query to members who may still sign in.
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    /**
     * Scope the query to members matching a CMS search term.
     */
    public function scopeSearch(Builder $query, ?string $term): void
    {
        $query->when($term, function (Builder $query, string $term) {
            $query->where(function (Builder $query) use ($term) {
                $query->where('name', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%")
                    ->orWhere('phone', 'like', "%{$term}%");
            });
        });
    }

    /**
     * Get the member's initials, used by the CMS avatar.
     */
    public function initials(): string
    {
        return collect(preg_split('/\s+/', trim($this->name)))
            ->filter()
            ->take(2)
            ->map(fn (string $part) => mb_strtoupper(mb_substr($part, 0, 1)))
            ->implode('');
    }
}
