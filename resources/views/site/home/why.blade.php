{{-- ===== WHY ===== --}}
<section class="border-y border-border bg-white py-28">
    <div class="mx-auto max-w-7xl px-6">
        <x-site.section-header
            eyebrow="Why Visionyr"
            title="Three disciplines. One operating system for brand."
        />

        <div data-reveal-group class="mt-14 grid gap-5 md:grid-cols-3">
            @foreach (config('marketing.disciplines') as $index => $discipline)
                <div data-reveal class="relative rounded-2xl border border-border bg-white p-7 transition-all hover:-translate-y-1 hover:border-navy/20 hover:shadow-card">
                    <span class="font-serif text-sm italic text-navy/40">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    <h3 class="mt-3 text-xl font-semibold tracking-tight text-navy">{{ $discipline['title'] }}</h3>
                    <p class="mt-3 text-sm leading-relaxed text-ink/70">{{ $discipline['body'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
