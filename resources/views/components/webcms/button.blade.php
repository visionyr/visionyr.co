@props([
    'variant' => 'primary',
    'href' => null,
    'icon' => null,
    'iconAfter' => null,
    'type' => 'submit',
])

@php
    $variants = [
        'primary' => 'bg-navy text-white shadow-soft hover:bg-navy/90 hover:shadow-glow',
        'secondary' => 'border border-navy/15 bg-white text-navy hover:bg-steel/40',
        'danger' => 'border border-danger/20 bg-danger/5 text-danger hover:bg-danger/10',
        'ghost' => 'text-ink/60 hover:bg-steel/50 hover:text-navy',
    ];

    $classes = 'inline-flex items-center justify-center gap-2 rounded-full px-4 py-2.5 text-sm font-medium transition-all disabled:cursor-not-allowed disabled:opacity-40 '
        .($variants[$variant] ?? $variants['primary']);
@endphp

<{{ $href ? 'a' : 'button' }}
    @if ($href) href="{{ $href }}" @else type="{{ $type }}" @endif
    {{ $attributes->class($classes) }}
>
    @if ($icon)
        <i data-lucide="{{ $icon }}" class="h-4 w-4"></i>
    @endif

    {{ $slot }}

    @if ($iconAfter)
        <i data-lucide="{{ $iconAfter }}" class="h-4 w-4"></i>
    @endif
</{{ $href ? 'a' : 'button' }}>
