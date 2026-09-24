@props([
    'title',
    'heading',
    'subheading' => null,
])

<x-site.layout :title="$title" nav="minimal" :footer="false">
    <div class="relative flex min-h-[calc(100vh-4rem)] flex-col overflow-hidden">
        <div class="absolute inset-0 grid-bg opacity-50" aria-hidden="true"></div>
        <div class="pointer-events-none absolute inset-x-0 top-0 h-[420px] bg-gradient-to-b from-steel/40 to-transparent" aria-hidden="true"></div>

        <div class="relative flex flex-1 items-center justify-center px-6 py-16">
            <div class="w-full max-w-md animate-rise">
                <div class="rounded-3xl border border-navy/10 bg-white p-8 shadow-card">
                    <div class="text-center">
                        <h1 class="text-2xl font-semibold tracking-tight text-navy">{{ $heading }}</h1>

                        @if ($subheading)
                            <p class="mt-1 text-sm text-ink/60">{{ $subheading }}</p>
                        @endif
                    </div>

                    {{ $slot }}
                </div>

                @isset($below)
                    <p class="mt-6 text-center text-sm text-ink/60">{{ $below }}</p>
                @endisset
            </div>
        </div>
    </div>
</x-site.layout>
