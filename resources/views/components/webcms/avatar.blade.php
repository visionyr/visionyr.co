@props([
    'initials' => '',
    'tone' => 'steel',
])

<span {{ $attributes->class([
    'grid h-9 w-9 shrink-0 place-items-center rounded-full text-xs font-semibold',
    'bg-steel text-navy' => $tone === 'steel',
    'bg-white/10 text-white' => $tone === 'navy',
]) }}>
    {{ $initials }}
</span>
