@props([
    'href',
    'icon',
    'active' => false,
])

<a
    href="{{ $href }}"
    @if ($active) aria-current="page" @endif
    {{ $attributes->class([
        'group relative flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition-colors',
        'bg-white/10 font-medium text-white' => $active,
        'text-white/60 hover:bg-white/5 hover:text-white' => ! $active,
    ]) }}
>
    @if ($active)
        <span class="absolute left-0 top-1/2 h-5 w-0.5 -translate-y-1/2 rounded-full bg-mint"></span>
    @endif

    <i data-lucide="{{ $icon }}" class="h-4 w-4 shrink-0"></i>
    <span class="truncate">{{ $slot }}</span>
</a>
