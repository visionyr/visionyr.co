@props([
    'id',
    'name',
    'invalid' => false,
    'autocomplete' => 'new-password',
])

<div class="relative">
    <input
        type="password"
        id="{{ $id }}"
        name="{{ $name }}"
        autocomplete="{{ $autocomplete }}"
        {{ $attributes->class([
            'w-full rounded-xl border bg-white py-2.5 pl-4 pr-11 text-sm text-navy outline-none transition-colors',
            'placeholder:text-ink/40 focus:ring-2',
            'border-navy/15 focus:border-navy focus:ring-navy/10' => ! $invalid,
            'border-danger/50 focus:border-danger focus:ring-danger/10' => $invalid,
        ]) }}
    />

    <button
        type="button"
        data-password-toggle="{{ $id }}"
        class="absolute inset-y-0 right-0 grid w-11 place-items-center text-ink/40 transition-colors hover:text-navy"
        aria-label="Toggle password visibility"
    >
        <i data-lucide="eye" data-icon-show class="h-4 w-4"></i>
        <i data-lucide="eye-off" data-icon-hide class="hidden h-4 w-4"></i>
    </button>
</div>
