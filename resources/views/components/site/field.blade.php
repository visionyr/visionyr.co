@props([
    'name',
    'label',
    'type' => 'text',
    'value' => null,
    'placeholder' => null,
    'autocomplete' => null,
    'hint' => null,
    'rows' => 6,
])

@php
    $id = 'field-'.$name;
    $invalid = $errors->has($name);

    $control = 'w-full rounded-xl border bg-white py-3 text-base text-navy outline-none transition-colors placeholder:text-ink/40 focus:ring-2 '
        .($invalid
            ? 'border-danger/50 focus:border-danger focus:ring-danger/10'
            : 'border-navy/15 focus:border-navy focus:ring-navy/10');
@endphp

<div {{ $attributes->only('class') }}>
    <label for="{{ $id }}" class="block text-xs uppercase tracking-wide text-ink/50">
        {{ $label }}
    </label>

    @if ($type === 'textarea')
        <textarea
            id="{{ $id }}"
            name="{{ $name }}"
            rows="{{ $rows }}"
            placeholder="{{ $placeholder }}"
            {{ $attributes->except('class')->class([$control, 'mt-2 resize-none px-4']) }}
        >{{ old($name, $value) }}</textarea>

    @elseif ($type === 'password')
        <div class="relative mt-2">
            <input
                type="password"
                id="{{ $id }}"
                name="{{ $name }}"
                placeholder="{{ $placeholder }}"
                autocomplete="{{ $autocomplete ?? 'new-password' }}"
                {{ $attributes->except('class')->class([$control, 'pl-4 pr-11']) }}
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
    @else
        <input
            type="{{ $type }}"
            id="{{ $id }}"
            name="{{ $name }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            @if ($autocomplete) autocomplete="{{ $autocomplete }}" @endif
            {{ $attributes->except('class')->class([$control, 'mt-2 px-4']) }}
        />
    @endif

    @if ($errors->has($name))
        <p class="mt-1.5 flex items-start gap-1.5 text-xs text-danger">
            <i data-lucide="circle-alert" class="mt-0.5 h-3.5 w-3.5 shrink-0"></i>
            <span>{{ $errors->first($name) }}</span>
        </p>
    @elseif ($hint)
        <p class="mt-1.5 text-xs text-ink/45">{{ $hint }}</p>
    @endif
</div>
