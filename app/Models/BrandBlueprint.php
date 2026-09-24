<?php

namespace App\Models;

use Database\Factories\BrandBlueprintFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'member_id', 'brand_name', 'industry', 'audience', 'price_position', 'vision',
    'payload', 'generator', 'model', 'generation_ms', 'failure_reason',
])]
class BrandBlueprint extends Model
{
    /** @use HasFactory<BrandBlueprintFactory> */
    use HasFactory, HasUuids;

    /**
     * Get the columns that should receive a unique identifier.
     *
     * @return array<int, string>
     */
    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'payload' => 'array',
        ];
    }

    /**
     * Get the member this blueprint belongs to, once members can sign in.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Read a section out of the generated payload.
     */
    public function section(string $key, mixed $default = null): mixed
    {
        return data_get($this->payload, $key, $default);
    }

    /**
     * Whether this blueprint came from the model rather than the fallback template.
     */
    public function wasGeneratedByAi(): bool
    {
        return $this->generator === 'openrouter';
    }

    /**
     * Scope the query to blueprints matching a CMS search term.
     */
    public function scopeSearch(Builder $query, ?string $term): void
    {
        $query->when($term, function (Builder $query, string $term) {
            $query->where(function (Builder $query) use ($term) {
                $query->where('brand_name', 'like', "%{$term}%")
                    ->orWhere('industry', 'like', "%{$term}%")
                    ->orWhere('audience', 'like', "%{$term}%");
            });
        });
    }
}
