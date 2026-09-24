{{-- ===== ACCELERATE ===== --}}
<section id="accelerate" class="relative py-28" style="background-color:oklch(0.985 0.005 240)">
    <div class="mx-auto max-w-7xl px-6">
        <x-site.section-header
            eyebrow="Accelerate"
            title='Accelerate<span class="text-navy/30">&trade;</span>'
            subtitle="Grow a brand worth following."
        />

        <div data-reveal-group class="mt-14 grid gap-5 md:grid-cols-3">
            @foreach (config('marketing.accelerate_features') as $feature)
                <x-site.feature-card :icon="$feature['icon']" :title="$feature['title']">
                    {{ $feature['body'] }}
                </x-site.feature-card>
            @endforeach

            {{-- Growth output --}}
            <div data-reveal class="relative overflow-hidden rounded-2xl border border-navy/15 bg-navy p-6 text-white md:col-span-3">
                <div class="grid items-center gap-8 md:grid-cols-2">
                    <div>
                        <p class="text-xs uppercase tracking-[0.18em] text-mint">Output</p>
                        <h3 class="mt-2 text-3xl font-semibold tracking-tight">Growth Blueprint&trade;</h3>
                        <p class="mt-3 max-w-md text-sm text-white/70">
                            A living dashboard of campaigns, content, and category signal — your brand on a growth curve.
                        </p>
                        <a href="{{ route('blueprint.create') }}" class="mt-6 inline-flex items-center gap-1.5 text-sm font-medium text-mint">
                            See the Growth view <i data-lucide="arrow-right" class="h-3.5 w-3.5"></i>
                        </a>
                    </div>

                    <div data-figures class="rounded-xl border border-white/10 bg-white p-5 text-ink shadow-card">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[10px] uppercase tracking-wider text-ink/50">Growth this quarter</p>
                                <p
                                    class="mt-1 text-2xl font-semibold tracking-tight text-navy"
                                    data-count="42.8"
                                    data-count-prefix="+"
                                    data-count-suffix="%"
                                    data-count-decimals="1"
                                >+42.8%</p>
                            </div>
                            <div class="flex items-center gap-1 rounded-full bg-mint px-2.5 py-1 text-[10px] font-medium text-navy">
                                <i data-lucide="line-chart" class="h-3 w-3"></i> On track
                            </div>
                        </div>

                        <div class="mt-5 flex h-28 items-end gap-1.5">
                            @foreach ([[38, 0.35], [52, 0.41], [44, 0.47], [70, 0.53], [58, 0.59], [78, 0.65], [66, 0.71], [84, 0.77], [72, 0.83], [92, 0.89]] as $index => [$height, $opacity])
                                <div
                                    data-bar-height
                                    class="flex-1 rounded-t-sm bg-navy"
                                    style="--bar-target:{{ $height }}%;opacity:{{ $opacity }};transition-delay:{{ $index * 55 }}ms"
                                ></div>
                            @endforeach
                        </div>

                        @php
                            $totals = [
                                ['value' => '12', 'label' => 'Campaigns', 'count' => 12, 'decimals' => 0, 'suffix' => ''],
                                ['value' => '184', 'label' => 'Posts', 'count' => 184, 'decimals' => 0, 'suffix' => ''],
                                ['value' => '2.1k', 'label' => 'Mentions', 'count' => 2.1, 'decimals' => 1, 'suffix' => 'k'],
                            ];
                        @endphp

                        <div class="mt-4 grid grid-cols-3 gap-3 text-center">
                            @foreach ($totals as $total)
                                <div class="rounded-lg bg-steel/50 py-2">
                                    <p
                                        class="text-sm font-semibold text-navy"
                                        data-count="{{ $total['count'] }}"
                                        data-count-decimals="{{ $total['decimals'] }}"
                                        data-count-suffix="{{ $total['suffix'] }}"
                                    >{{ $total['value'] }}</p>
                                    <p class="text-[10px] uppercase tracking-wider text-ink/50">{{ $total['label'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
