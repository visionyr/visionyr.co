{{-- ===== SHOWCASE ===== --}}
<section id="showcase" class="py-28">
    <div class="mx-auto max-w-7xl px-6">
        <x-site.section-header
            eyebrow="Showcase"
            title="Built with Visionyr"
            subtitle="Brands shaped, defined, and accelerated by Visionyr."
        />

        <div data-reveal-group class="mt-14 grid gap-5 md:grid-cols-3">
            @foreach (config('marketing.showcase') as $brand)
                <article data-reveal class="group overflow-hidden rounded-2xl border border-border bg-white transition-all hover:-translate-y-1 hover:shadow-card">
                    <div class="relative overflow-hidden" style="aspect-ratio:4/3">
                        <div class="absolute inset-0" style="background:{{ $brand['gradient'] }}"></div>
                        <div class="absolute inset-0 grid place-items-center">
                            <span class="font-serif text-3xl italic" style="color:rgba(255,255,255,0.95)">{{ $brand['name'] }}</span>
                        </div>
                        <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between rounded-xl bg-white/85 px-3 py-2 text-xs backdrop-blur-sm">
                            <span class="truncate text-navy/70">{{ $brand['positioning'] }}</span>
                            <div class="flex shrink-0 gap-1">
                                @foreach ($brand['swatches'] as $swatch)
                                    <span class="h-3 w-3 rounded-full border border-black/5" style="background:{{ $swatch }}"></span>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold tracking-tight text-navy">{{ $brand['name'] }}</h3>
                            <span class="rounded-full border border-border px-2 py-0.5 text-[10px] uppercase tracking-wider text-ink/60">
                                {{ $brand['category'] }}
                            </span>
                        </div>

                        <div class="mt-4 space-y-2 text-sm">
                            @foreach (['Positioning' => 'positioning', 'Audience' => 'audience', 'Identity' => 'identity'] as $label => $key)
                                <div class="flex items-baseline justify-between gap-4">
                                    <span class="text-[10px] uppercase tracking-wider text-ink/50">{{ $label }}</span>
                                    <span class="text-right text-navy">{{ $brand[$key] }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4 flex items-center gap-2 rounded-lg bg-mint/30 px-3 py-2">
                            <i data-lucide="check" class="h-3.5 w-3.5 text-navy"></i>
                            <span class="text-xs font-medium text-navy">Complete Brand Blueprint Generated</span>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
