@props([
    'title' => null,
    'heading' => null,
    'subheading' => null,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $title ? $title.' — Visionyr CMS' : 'Visionyr CMS' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/cms.js'])
</head>
<body class="h-full bg-steel/25 text-foreground">

@php
    $navigation = [
        ['route' => 'webcms.dashboard', 'pattern' => 'webcms', 'icon' => 'layout-dashboard', 'label' => 'Dashboard'],
        ['route' => 'webcms.admin-users.index', 'pattern' => 'webcms/admin-users*', 'icon' => 'shield-check', 'label' => 'Admin Users'],
        ['route' => 'webcms.members.index', 'pattern' => 'webcms/members*', 'icon' => 'users', 'label' => 'Members'],
        ['route' => 'webcms.brand-blueprints.index', 'pattern' => 'webcms/brand-blueprints*', 'icon' => 'sparkles', 'label' => 'Brand Blueprints'],
    ];

    $admin = auth('admin')->user();
@endphp

<div class="min-h-full lg:flex">

    <div data-sidebar-backdrop class="fixed inset-0 z-40 hidden bg-navy/40 backdrop-blur-sm lg:hidden"></div>

    {{-- ===== SIDEBAR ===== --}}
    <aside
        data-sidebar
        class="fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col bg-navy transition-transform duration-300 lg:sticky lg:top-0 lg:h-screen lg:translate-x-0"
    >
        <div class="flex h-16 items-center justify-between px-6">
            <a href="{{ route('webcms.dashboard') }}" class="flex items-center gap-2">
                <span class="grid h-7 w-7 place-items-center rounded-md bg-white/10">
                    <span class="block h-2 w-2 rounded-full bg-mint"></span>
                </span>
                <span class="text-[17px] font-semibold tracking-tight text-white">Visionyr</span>
                <span class="rounded-full bg-mint/15 px-2 py-0.5 text-[10px] font-medium uppercase tracking-wide text-mint">CMS</span>
            </a>

            <button type="button" data-sidebar-close class="text-white/50 transition-colors hover:text-white lg:hidden" aria-label="Close menu">
                <i data-lucide="x" class="h-5 w-5"></i>
            </button>
        </div>

        <nav class="flex-1 space-y-1 overflow-y-auto px-4 py-4">
            @foreach ($navigation as $item)
                <x-webcms.nav-link
                    :href="route($item['route'])"
                    :icon="$item['icon']"
                    :active="request()->is($item['pattern'])"
                >
                    {{ $item['label'] }}
                </x-webcms.nav-link>
            @endforeach
        </nav>

        <div class="border-t border-white/10 p-4">
            <div class="flex items-center gap-3 rounded-xl px-2 py-2">
                <x-webcms.avatar :initials="$admin->initials()" tone="navy" />
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-medium text-white">{{ $admin->name }}</p>
                    <p class="truncate text-xs text-white/50">{{ $admin->email }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('webcms.logout') }}" class="mt-1">
                @csrf
                <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm text-white/60 transition-colors hover:bg-white/5 hover:text-white">
                    <i data-lucide="log-out" class="h-4 w-4 shrink-0"></i>
                    Sign out
                </button>
            </form>
        </div>
    </aside>

    {{-- ===== CONTENT ===== --}}
    <div class="flex min-w-0 flex-1 flex-col">

        <header class="sticky top-0 z-30 border-b border-border/70 bg-background/80 backdrop-blur-xl">
            <div class="flex min-h-16 items-center gap-4 px-6 py-3">
                <button type="button" data-sidebar-open class="text-ink/50 transition-colors hover:text-navy lg:hidden" aria-label="Open menu">
                    <i data-lucide="menu" class="h-5 w-5"></i>
                </button>

                <div class="min-w-0 flex-1">
                    <h1 class="truncate text-lg font-semibold tracking-tight text-navy">{{ $heading ?? $title }}</h1>
                    @if ($subheading)
                        <p class="truncate text-sm text-ink/50">{{ $subheading }}</p>
                    @endif
                </div>

                @isset($actions)
                    <div class="flex shrink-0 items-center gap-2">{{ $actions }}</div>
                @endisset
            </div>
        </header>

        <main class="flex-1 px-6 py-8">
            <div class="mx-auto max-w-6xl animate-rise">
                <x-webcms.flash />
                {{ $slot }}
            </div>
        </main>

        <footer class="px-6 pb-8 pt-2">
            <p class="mx-auto max-w-6xl text-xs text-ink/40">
                Visionyr CMS &middot; &copy; {{ now()->year }}
            </p>
        </footer>
    </div>
</div>

</body>
</html>
