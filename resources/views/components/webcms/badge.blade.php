@props(['active' => true])

<span {{ $attributes->class([
    'inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium',
    'bg-success/10 text-success' => $active,
    'bg-ink/5 text-ink/50' => ! $active,
]) }}>
    <span @class([
        'h-1.5 w-1.5 rounded-full',
        'bg-success' => $active,
        'bg-ink/30' => ! $active,
    ])></span>
    {{ $slot }}
</span>
