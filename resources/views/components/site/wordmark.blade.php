@props(['href' => null])

<{{ $href ? 'a' : 'span' }} @if ($href) href="{{ $href }}" @endif {{ $attributes->class('flex items-center gap-2') }}>
    <span class="grid h-7 w-7 place-items-center rounded-md bg-navy text-white">
        <span class="block h-2 w-2 rounded-full bg-mint"></span>
    </span>
    <span class="text-[17px] font-semibold tracking-tight text-navy">
        Visionyr<span class="text-navy/40">&trade;</span>
    </span>
</{{ $href ? 'a' : 'span' }}>
