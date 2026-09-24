@php
    $profile = [
        ['label' => 'Name', 'value' => $member->name, 'icon' => 'user-round'],
        ['label' => 'Email', 'value' => $member->email, 'icon' => 'mail'],
        ['label' => 'Phone', 'value' => $member->phone, 'icon' => 'phone'],
        ['label' => 'Member since', 'value' => $member->created_at->format('d F Y'), 'icon' => 'calendar'],
        [
            'label' => 'Last sign-in',
            'value' => $member->last_login_at?->diffForHumans() ?? 'This is your first visit',
            'icon' => 'log-out',
        ],
    ];
@endphp

<x-site.layout title="Dashboard — Visionyr" nav="minimal" :footer="false">
    <div class="relative">
        <div class="absolute inset-0 grid-bg opacity-50" aria-hidden="true"></div>
        <div class="pointer-events-none absolute inset-x-0 top-0 h-[420px] bg-gradient-to-b from-steel/40 to-transparent" aria-hidden="true"></div>

        <div class="relative mx-auto max-w-3xl px-6 pb-24 pt-14 animate-rise lg:pt-20">

            {{-- ===== HEADER ===== --}}
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <span class="grid h-14 w-14 shrink-0 place-items-center rounded-full bg-navy text-base font-semibold text-white">
                        {{ $member->initials() }}
                    </span>
                    <div class="min-w-0">
                        <h1 class="truncate text-3xl font-semibold tracking-[-0.02em] text-navy">
                            {{ $member->name }}
                        </h1>
                        <p class="truncate text-sm text-ink/60">{{ $member->email }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="inline-flex items-center gap-1.5 rounded-full border border-navy/15 bg-white px-4 py-2.5 text-sm font-medium text-navy transition-colors hover:bg-steel/40"
                    >
                        <i data-lucide="log-out" class="h-3.5 w-3.5"></i> Sign out
                    </button>
                </form>
            </div>

            {{-- ===== PROFILE ===== --}}
            <div class="mt-10 rounded-3xl border border-navy/10 bg-white shadow-card">
                <div class="flex items-center justify-between gap-3 border-b border-border/70 px-6 py-5 md:px-8">
                    <h2 class="text-sm font-semibold uppercase tracking-wide text-navy">Profile</h2>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-success/10 px-2.5 py-1 text-xs font-medium text-success">
                        <span class="h-1.5 w-1.5 rounded-full bg-success"></span>
                        Active
                    </span>
                </div>

                <dl class="divide-y divide-border/70">
                    @foreach ($profile as $row)
                        <div class="flex items-center gap-4 px-6 py-4 md:px-8">
                            <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-steel/50 text-navy/60">
                                <i data-lucide="{{ $row['icon'] }}" class="h-4 w-4"></i>
                            </span>
                            <dt class="w-32 shrink-0 text-xs uppercase tracking-wide text-ink/50">{{ $row['label'] }}</dt>
                            <dd class="min-w-0 flex-1 truncate text-sm text-navy">{{ $row['value'] }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>

            {{-- ===== BLUEPRINTS ===== --}}
            <div class="mt-6 rounded-3xl border border-navy/10 bg-white shadow-card">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-border/70 px-6 py-5 md:px-8">
                    <h2 class="text-sm font-semibold uppercase tracking-wide text-navy">
                        Your Brand Blueprints
                    </h2>
                    <span @class([
                        'inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium',
                        'bg-success/10 text-success' => $quota['remaining'] > 0,
                        'bg-ink/5 text-ink/50' => $quota['remaining'] < 1,
                    ])>
                        {{ $quota['remaining'] }} of {{ $quota['limit'] }} left this month
                    </span>
                </div>

                @if ($blueprints->isEmpty())
                    <div class="flex flex-col items-center px-6 py-12 text-center">
                        <span class="grid h-12 w-12 place-items-center rounded-2xl bg-steel/60 text-navy/50">
                            <i data-lucide="file-text" class="h-5 w-5"></i>
                        </span>
                        <p class="mt-4 text-sm font-medium text-navy">No blueprints yet</p>
                        <p class="mt-1 max-w-sm text-sm text-ink/50">
                            Answer five questions and Visionyr will generate a complete brand system.
                        </p>
                    </div>
                @else
                    <ul data-reveal-group class="divide-y divide-border/70">
                        @foreach ($blueprints as $blueprint)
                            <li data-reveal>
                                <a
                                    href="{{ route('blueprint.show', $blueprint) }}"
                                    class="group flex items-center gap-4 px-6 py-4 transition-colors hover:bg-steel/25 md:px-8"
                                >
                                    <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-steel/60 text-navy transition-colors group-hover:bg-mint">
                                        <i data-lucide="sparkles" class="h-4 w-4"></i>
                                    </span>

                                    <span class="min-w-0 flex-1">
                                        <span class="block truncate text-sm font-medium text-navy">
                                            {{ $blueprint->brand_name }}
                                        </span>
                                        <span class="block truncate text-xs text-ink/50">
                                            {{ $blueprint->industry }} &middot; {{ $blueprint->price_position }}
                                            &middot; {{ $blueprint->created_at->format('d M Y') }}
                                        </span>
                                    </span>

                                    <i data-lucide="arrow-right" class="h-4 w-4 shrink-0 text-navy/30 transition-transform group-hover:translate-x-0.5"></i>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif

                <div class="flex flex-wrap items-center gap-3 border-t border-border/70 px-6 py-5 md:px-8">
                    @if ($quota['remaining'] > 0)
                        <a href="{{ route('blueprint.create') }}" class="inline-flex items-center gap-2 rounded-full bg-navy px-5 py-2.5 text-sm font-medium text-white shadow-soft transition-all hover:bg-navy/90 hover:shadow-glow">
                            <i data-lucide="plus" class="h-4 w-4"></i>
                            Generate a new Blueprint
                        </a>
                    @else
                        <span class="inline-flex cursor-not-allowed items-center gap-2 rounded-full bg-navy/40 px-5 py-2.5 text-sm font-medium text-white">
                            <i data-lucide="plus" class="h-4 w-4"></i>
                            Generate a new Blueprint
                        </span>
                        <span class="text-xs text-ink/50">
                            Renews {{ $quota['resets_on']->format('j F') }}
                        </span>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-site.layout>
