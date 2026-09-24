{{-- ===== PRICING ===== --}}
<section id="pricing" class="py-28">
    <div class="mx-auto max-w-7xl px-6">
        <x-site.section-header
            eyebrow="Pricing"
            title="Simple Pricing"
            subtitle="Start free. Scale when your brand needs to."
        />

        <div data-reveal-group class="mt-14 grid items-stretch gap-5 md:grid-cols-3">
            @foreach (config('marketing.pricing') as $plan)
                <div data-reveal @class([
                    'relative flex flex-col rounded-2xl p-7 transition-shadow hover:shadow-card',
                    'border border-navy bg-navy text-white shadow-glow md:-translate-y-3' => $plan['featured'],
                    'border border-border bg-white' => ! $plan['featured'],
                ])>
                    @if ($plan['featured'])
                        <span class="absolute -top-3 left-1/2 inline-flex -translate-x-1/2 items-center gap-1 rounded-full bg-mint px-3 py-1 text-[10px] font-medium uppercase tracking-wider text-navy">
                            <i data-lucide="star" class="h-3 w-3"></i> Recommended
                        </span>
                    @endif

                    <p @class([
                        'text-xs uppercase tracking-[0.18em]',
                        'text-mint' => $plan['featured'],
                        'text-navy/60' => ! $plan['featured'],
                    ])>{{ $plan['name'] }}</p>

                    <div class="mt-4 flex items-baseline gap-1">
                        <span @class([
                            'text-4xl font-semibold tracking-tight',
                            'text-white' => $plan['featured'],
                            'text-navy' => ! $plan['featured'],
                        ])>{{ $plan['price'] }}</span>
                        <span @class([
                            'text-sm',
                            'text-white/60' => $plan['featured'],
                            'text-ink/50' => ! $plan['featured'],
                        ])>{{ $plan['period'] }}</span>
                    </div>

                    <ul @class([
                        'mt-6 flex-1 space-y-3 text-sm',
                        'text-white/85' => $plan['featured'],
                        'text-ink/75' => ! $plan['featured'],
                    ])>
                        @foreach ($plan['features'] as $feature)
                            <li class="flex items-start gap-2.5">
                                <i data-lucide="check" @class([
                                    'mt-0.5 h-4 w-4 shrink-0',
                                    'text-mint' => $plan['featured'],
                                    'text-navy' => ! $plan['featured'],
                                ])></i>
                                {{ $feature }}
                            </li>
                        @endforeach
                    </ul>

                    @php $external = $plan['external'] ?? false; @endphp

                    <a
                        href="{{ $plan['url'] ?? route('blueprint.create') }}"
                        @if ($external) target="_blank" rel="noopener noreferrer" @endif
                        @class([
                            'mt-8 inline-flex items-center justify-center gap-1.5 rounded-full px-4 py-2.5 text-sm font-medium transition-colors',
                            'bg-mint text-navy hover:bg-white' => $plan['featured'],
                            'border border-navy/15 bg-white text-navy hover:bg-steel/40' => ! $plan['featured'],
                        ])
                    >
                        {{ $plan['cta'] }}
                        <i data-lucide="{{ $external ? 'arrow-up-right' : 'arrow-right' }}" class="h-3.5 w-3.5"></i>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
