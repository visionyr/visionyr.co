@props([
    'invalid' => false,
])

<input
    {{ $attributes->class([
        'w-full rounded-xl border bg-white px-4 py-2.5 text-sm text-navy outline-none transition-colors',
        'placeholder:text-ink/40 focus:ring-2',
        'border-navy/15 focus:border-navy focus:ring-navy/10' => ! $invalid,
        'border-danger/50 focus:border-danger focus:ring-danger/10' => $invalid,
    ]) }}
/>
