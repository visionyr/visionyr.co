@props([
    'for' => null,
    'required' => false,
])

<label for="{{ $for }}" {{ $attributes->class('block text-xs font-medium uppercase tracking-wide text-ink/50') }}>
    {{ $slot }}

    @if ($required)
        <span class="text-danger" aria-hidden="true">*</span>
    @endif
</label>
