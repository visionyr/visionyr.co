{{-- ===== HERO ===== --}}
<section class="relative overflow-hidden">
    <div class="absolute inset-0 grid-bg opacity-60" aria-hidden="true"></div>
    <div class="pointer-events-none absolute inset-x-0 top-0 h-[520px] bg-gradient-to-b from-steel/40 to-transparent" aria-hidden="true"></div>

    <div class="relative mx-auto max-w-7xl px-6 pb-24 pt-20 lg:pt-28">

        <div class="mx-auto max-w-3xl text-center animate-rise">
            <div class="inline-flex items-center gap-2 rounded-full border border-navy/10 bg-white/70 px-3 py-1.5 text-xs text-navy/80 shadow-soft">
                <span class="h-1.5 w-1.5 rounded-full bg-mint animate-pulse-soft"></span>
                Trusted by Founders Building the Next Generation of Brands
            </div>

            <h1 class="mt-6 text-balance text-5xl font-semibold tracking-[-0.03em] text-navy md:text-7xl">
                Build a Brand Worth Remembering.
            </h1>

            <p class="mx-auto mt-6 max-w-2xl text-pretty text-lg leading-relaxed text-ink/70">
                Visionyr helps founders turn ideas into clear, distinctive, and scalable brands.
                Generate positioning, identity, launch strategies, campaigns, and content systems in minutes.
            </p>

            <div class="mt-9 flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('blueprint.create') }}" class="group inline-flex items-center gap-2 rounded-full bg-navy px-5 py-3 text-sm font-medium text-white shadow-soft transition-all hover:bg-navy/90 hover:shadow-glow">
                    Generate My Brand Blueprint
                    <i data-lucide="arrow-right" class="h-4 w-4"></i>
                </a>
                <a href="#showcase" class="inline-flex items-center gap-2 rounded-full border border-navy/15 bg-white px-5 py-3 text-sm font-medium text-navy transition-colors hover:bg-steel/40">
                    See Examples
                </a>
            </div>

            <p class="mt-5 text-xs text-ink/50">From idea to Brand Blueprint in minutes.</p>
        </div>

        {{-- Product mock --}}
        <div class="relative mx-auto mt-20 max-w-6xl">
            <div class="relative rounded-3xl border border-navy/10 bg-white p-3 shadow-card animate-rise">
                <div data-figures class="rounded-2xl bg-gradient-to-br from-steel/40 via-white to-mint/30 p-6 md:p-8">

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="h-2.5 w-2.5 rounded-full bg-navy/20"></span>
                            <span class="h-2.5 w-2.5 rounded-full bg-navy/20"></span>
                            <span class="h-2.5 w-2.5 rounded-full bg-navy/20"></span>
                        </div>
                        <div class="rounded-full border border-navy/10 bg-white/70 px-3 py-1 text-xs text-navy/70">
                            visionyr.co / scentrism
                        </div>
                        <span class="grid h-6 w-6 place-items-center rounded-full bg-navy text-[10px] font-medium text-white">S</span>
                    </div>

                    @php
                        $metrics = [
                            ['label' => 'Brand Score', 'value' => '91%', 'width' => 91, 'dot' => 'bg-navy', 'bar' => 'bg-navy'],
                            ['label' => 'Consistency', 'value' => '94%', 'width' => 94, 'dot' => 'bg-mint', 'bar' => 'bg-navy/70'],
                            ['label' => 'Growth Potential', 'value' => '88%', 'width' => 88, 'dot' => 'bg-steel', 'bar' => 'bg-navy/40'],
                        ];

                        $recommendations = [
                            ['title' => 'Launch Discovery Set', 'body' => 'Convert browsers into first-purchase rituals.'],
                            ['title' => 'Create New Campaign', 'body' => 'Frame the seasonal story around mindful scent.'],
                            ['title' => 'Improve Product Story', 'body' => 'Tighten the founder POV on the PDP hero.'],
                        ];
                    @endphp

                    <div class="mt-8 grid gap-5 md:grid-cols-3">
                        @foreach ($metrics as $metric)
                            <div class="rounded-2xl border border-navy/10 bg-white p-5">
                                <div class="flex items-center justify-between">
                                    <p class="text-xs uppercase tracking-wide text-ink/50">{{ $metric['label'] }}</p>
                                    <span class="h-2 w-2 rounded-full {{ $metric['dot'] }}"></span>
                                </div>
                                <p
                                    class="mt-3 text-3xl font-semibold tracking-tight text-navy"
                                    data-count="{{ $metric['width'] }}"
                                    data-count-suffix="%"
                                >{{ $metric['value'] }}</p>
                                <div class="mt-4 h-1.5 w-full rounded-full bg-steel/60">
                                    <div data-bar class="h-full rounded-full {{ $metric['bar'] }}" style="--bar-target:{{ $metric['width'] }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-5 grid gap-5 md:grid-cols-5">
                        <div class="rounded-2xl border border-navy/10 bg-white p-5 md:col-span-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs uppercase tracking-wide text-ink/50">Today's Recommendations</p>
                                    <p class="mt-1 text-base font-medium text-navy">3 strategic moves</p>
                                </div>
                                <i data-lucide="sparkles" class="h-4 w-4 text-navy/50"></i>
                            </div>
                            <ul class="mt-4 divide-y divide-border">
                                @foreach ($recommendations as $item)
                                    <li class="flex items-center justify-between gap-4 py-3">
                                        <div class="min-w-0">
                                            <p class="truncate text-sm font-medium text-navy">{{ $item['title'] }}</p>
                                            <p class="truncate text-xs text-ink/60">{{ $item['body'] }}</p>
                                        </div>
                                        <i data-lucide="arrow-up-right" class="h-4 w-4 shrink-0 text-navy/40"></i>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="rounded-2xl border border-navy/15 bg-navy p-5 text-white md:col-span-2">
                            <p class="text-xs uppercase tracking-wide text-white/50">Brand Blueprint&trade;</p>
                            <p class="mt-1 text-base font-medium">Scentrism &middot; Mindful Fragrance</p>
                            <div class="mt-4 space-y-2 text-xs text-white/70">
                                @foreach (['Positioning', 'Identity', 'Audience', 'Tone of Voice'] as $facet)
                                    <div class="flex items-center justify-between rounded-md bg-white/5 px-3 py-2">
                                        <span>{{ $facet }}</span><span class="text-mint">Defined</span>
                                    </div>
                                @endforeach
                            </div>
                            <a href="{{ route('blueprint.create') }}" class="mt-5 inline-flex items-center gap-1.5 text-xs font-medium text-mint">
                                Open blueprint <i data-lucide="arrow-right" class="h-3 w-3"></i>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</section>
