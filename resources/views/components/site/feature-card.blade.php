@props([
    'icon',
    'title',
    'number' => null,
])

<div data-reveal {{ $attributes->class('group relative overflow-hidden rounded-2xl border border-border bg-white p-6 transition-all hover:-translate-y-1 hover:border-navy/20 hover:shadow-card') }}>
    @if ($number)
        <span class="font-serif text-sm italic text-navy/40">{{ $number }}</span>
    @endif

    <span @class([
        'grid h-10 w-10 place-items-center rounded-xl bg-steel/60 text-navy transition-colors group-hover:bg-mint',
        'mt-4' => $number,
    ])>
        <i data-lucide="{{ $icon }}" class="h-5 w-5"></i>
    </span>

    <h3 class="mt-5 text-lg font-semibold tracking-tight text-navy">{{ $title }}</h3>
    <p class="mt-1.5 text-sm leading-relaxed text-ink/65">{{ $slot }}</p>
</div>
