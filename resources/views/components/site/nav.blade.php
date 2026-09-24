@props(['variant' => 'full'])

@php
    $links = [
        'How It Works' => '#how-it-works',
        'Create' => '#create',
        'Accelerate' => '#accelerate',
        'Showcase' => '#showcase',
        'Pricing' => '#pricing',
        'FAQ' => '#faq',
    ];
@endphp

<header data-site-header class="sticky top-0 z-50 border-b border-border/60 bg-background/80 backdrop-blur-xl">
    <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-6">
        <x-site.wordmark :href="route('home')" />

        @if ($variant === 'full')
            <nav class="hidden items-center gap-8 md:flex">
                @foreach ($links as $label => $href)
                    <a
                        href="{{ route('home') }}{{ $href }}"
                        data-nav-link="{{ ltrim($href, '#') }}"
                        class="text-sm text-ink/70 transition-colors hover:text-navy"
                    >
                        {{ $label }}
                    </a>
                @endforeach
            </nav>

            <div class="flex items-center gap-2">
                @auth
                    <a href="{{ route('dashboard') }}" class="hidden text-sm text-ink/70 transition-colors hover:text-navy sm:inline">
                        {{ auth()->user()->name }}
                    </a>
                @else
                    <a href="{{ route('login') }}" class="hidden text-sm text-ink/70 transition-colors hover:text-navy sm:inline">Sign in</a>
                @endauth

                <a href="{{ route('blueprint.create') }}" class="inline-flex items-center gap-1.5 rounded-full bg-navy px-4 py-2 text-sm font-medium text-white transition-all hover:bg-navy/90">
                    Generate My Brand Blueprint
                    <i data-lucide="arrow-right" class="h-3.5 w-3.5"></i>
                </a>
            </div>
        @else
            <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 rounded-full border border-navy/15 bg-white px-3.5 py-1.5 text-sm text-navy/80 transition-colors hover:bg-steel/40">
                <i data-lucide="arrow-left" class="h-3.5 w-3.5"></i> Back to home
            </a>
        @endif
    </div>
</header>
