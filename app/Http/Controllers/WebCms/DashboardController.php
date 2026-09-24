<?php

namespace App\Http\Controllers\WebCms;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use App\Models\Member;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the CMS dashboard.
     */
    public function __invoke(): View
    {
        return view('webcms.dashboard', [
            'stats' => $this->stats(),
            'signupTrend' => $this->signupTrend(),
            'recentMembers' => Member::latest()->take(6)->get(),
        ]);
    }

    /**
     * Get the headline counters shown at the top of the dashboard.
     *
     * @return array<string, int>
     */
    protected function stats(): array
    {
        return [
            'members' => Member::count(),
            'active_members' => Member::active()->count(),
            'new_members' => Member::where('created_at', '>=', now()->startOfMonth())->count(),
            'admins' => AdminUser::active()->count(),
        ];
    }

    /**
     * Get member sign-ups for each of the last twelve months.
     *
     * @return \Illuminate\Support\Collection<int, array{label: string, total: int}>
     */
    protected function signupTrend()
    {
        $start = now()->startOfMonth()->subMonths(11);

        $counts = Member::query()
            ->where('created_at', '>=', $start)
            ->groupBy('period')
            ->pluck(DB::raw('count(*)'), DB::raw("DATE_FORMAT(created_at, '%Y-%m') as period"));

        return collect(range(0, 11))->map(function (int $offset) use ($start, $counts) {
            $month = $start->copy()->addMonths($offset);

            return [
                'label' => $month->format('M'),
                'total' => (int) ($counts[$month->format('Y-m')] ?? 0),
            ];
        });
    }
}
