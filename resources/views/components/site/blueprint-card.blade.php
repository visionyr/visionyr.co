@props([
    'icon',
    'title',
])

<section {{ $attributes->class('rounded-3xl border border-navy/10 bg-white p-6 shadow-soft md:p-7') }}>
    <div class="flex items-center gap-2.5">
        <span class="grid h-8 w-8 place-items-center rounded-lg bg-steel/50">
            <i data-lucide="{{ $icon }}" class="h-4 w-4 text-navy"></i>
        </span>
        <h3 class="text-sm font-semibold uppercase tracking-wide text-navy">{{ $title }}</h3>
    </div>

    <div class="mt-5 text-[15px] leading-relaxed">
        {{ $slot }}
    </div>
</section>
