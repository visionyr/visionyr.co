@props([
    'eyebrow' => null,
    'title',
    'subtitle' => null,
])

<div data-reveal {{ $attributes->class('mx-auto max-w-2xl text-center') }}>
    @if ($eyebrow)
        <p class="text-xs uppercase tracking-[0.22em] text-navy/60">{{ $eyebrow }}</p>
    @endif

    <h2 class="mt-3 text-4xl font-semibold tracking-[-0.02em] text-navy md:text-5xl">{!! $title !!}</h2>

    @if ($subtitle)
        <p class="mt-4 text-lg text-ink/70">{{ $subtitle }}</p>
    @endif
</div>
