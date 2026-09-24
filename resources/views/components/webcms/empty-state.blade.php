@props([
    'icon' => 'search',
    'title' => 'Nothing here yet',
])

<div class="flex flex-col items-center px-6 py-16 text-center">
    <span class="grid h-12 w-12 place-items-center rounded-2xl bg-steel/60 text-navy/50">
        <i data-lucide="{{ $icon }}" class="h-5 w-5"></i>
    </span>
    <p class="mt-4 text-sm font-medium text-navy">{{ $title }}</p>

    @if (trim($slot))
        <p class="mt-1 max-w-sm text-sm text-ink/50">{{ $slot }}</p>
    @endif
</div>
