<x-webcms.layout
    title="Dashboard"
    heading="Dashboard"
    :subheading="'Welcome back, '.auth('admin')->user()->name.'.'"
>
    <x-slot:actions>
        <x-webcms.button :href="route('webcms.members.create')" icon="user-plus">
            Add member
        </x-webcms.button>
    </x-slot:actions>

    @php
        $tiles = [
            ['label' => 'Total members', 'value' => $stats['members'], 'icon' => 'users'],
            ['label' => 'Active members', 'value' => $stats['active_members'], 'icon' => 'circle-check'],
            ['label' => 'New this month', 'value' => $stats['new_members'], 'icon' => 'trending-up'],
            ['label' => 'Active admins', 'value' => $stats['admins'], 'icon' => 'shield-check'],
        ];

        $peak = max(1, $signupTrend->max('total'));
    @endphp

    {{-- ===== STAT TILES ===== --}}
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($tiles as $tile)
            <x-webcms.card class="p-5">
                <div class="flex items-start justify-between gap-3">
                    <p class="text-xs uppercase tracking-wide text-ink/50">{{ $tile['label'] }}</p>
                    <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-steel/70 text-navy/60">
                        <i data-lucide="{{ $tile['icon'] }}" class="h-4 w-4"></i>
                    </span>
                </div>
                <p class="mt-3 text-3xl font-semibold tracking-tight text-navy">
                    {{ number_format($tile['value']) }}
                </p>
            </x-webcms.card>
        @endforeach
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-5">

        {{-- ===== SIGN-UP TREND ===== --}}
        <x-webcms.card class="flex flex-col p-6 lg:col-span-3">
            <div class="flex items-baseline justify-between gap-3">
                <h2 class="text-base font-semibold tracking-tight text-navy">Member sign-ups</h2>
                <p class="text-xs text-ink/50">Last 12 months</p>
            </div>

            <div class="mt-6 flex min-h-48 flex-1 items-end gap-2">
                @foreach ($signupTrend as $month)
                    <div class="group flex h-full flex-1 flex-col justify-end gap-2">
                        <div
                            class="w-full rounded-t-md bg-navy/85 transition-colors group-hover:bg-navy"
                            style="height: {{ max(2, round($month['total'] / $peak * 100)) }}%"
                            title="{{ $month['label'] }}: {{ $month['total'] }}"
                        ></div>
                        <span class="text-center text-[10px] text-ink/40">{{ $month['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </x-webcms.card>

        {{-- ===== RECENT MEMBERS ===== --}}
        <x-webcms.card class="lg:col-span-2">
            <div class="flex items-baseline justify-between gap-3 px-6 pt-6">
                <h2 class="text-base font-semibold tracking-tight text-navy">Newest members</h2>
                <a href="{{ route('webcms.members.index') }}" class="text-xs text-ink/50 transition-colors hover:text-navy">
                    View all
                </a>
            </div>

            @if ($recentMembers->isEmpty())
                <x-webcms.empty-state icon="users" title="No members yet">
                    Members you add will appear here.
                </x-webcms.empty-state>
            @else
                <ul class="mt-4 divide-y divide-border/70">
                    @foreach ($recentMembers as $member)
                        <li class="flex items-center gap-3 px-6 py-3">
                            <x-webcms.avatar :initials="$member->initials()" />
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium text-navy">{{ $member->name }}</p>
                                <p class="truncate text-xs text-ink/50">{{ $member->email }}</p>
                            </div>
                            <span class="shrink-0 text-xs text-ink/40">{{ $member->created_at->diffForHumans(short: true) }}</span>
                        </li>
                    @endforeach
                </ul>
                <div class="h-4"></div>
            @endif
        </x-webcms.card>
    </div>
</x-webcms.layout>
