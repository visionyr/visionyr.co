{{-- ===== CREATE ===== --}}
<section id="create" class="relative py-28">
    <div class="mx-auto max-w-7xl px-6">
        <x-site.section-header
            eyebrow="Create"
            title='Create<span class="text-navy/30">&trade;</span>'
            subtitle="Everything you need to build a brand from scratch."
        />

        <div data-reveal-group class="mt-14 grid gap-5 md:grid-cols-3">
            @foreach (config('marketing.create_features') as $feature)
                <x-site.feature-card :icon="$feature['icon']" :title="$feature['title']">
                    {{ $feature['body'] }}
                </x-site.feature-card>
            @endforeach

            {{-- Blueprint output --}}
            <div data-reveal class="relative overflow-hidden rounded-2xl border border-navy/15 bg-navy p-6 text-white md:col-span-3">
                <div class="grid items-center gap-8 md:grid-cols-2">
                    <div>
                        <p class="text-xs uppercase tracking-[0.18em] text-mint">Output</p>
                        <h3 class="mt-2 text-3xl font-semibold tracking-tight">Brand Blueprint&trade;</h3>
                        <p class="mt-3 max-w-md text-sm text-white/70">
                            Your complete brand operating system, including positioning, audience, personality,
                            messaging, visual direction, and launch strategy.
                        </p>
                        <a href="{{ route('blueprint.create') }}" class="mt-6 inline-flex items-center gap-1.5 text-sm font-medium text-mint">
                            Preview a Blueprint <i data-lucide="arrow-right" class="h-3.5 w-3.5"></i>
                        </a>
                    </div>

                    <div class="relative">
                        <div class="absolute inset-x-6 -bottom-6 h-12 rounded-full bg-black/40 blur-2xl" aria-hidden="true"></div>
                        <div class="relative rounded-xl border border-white/10 bg-white p-5 text-ink shadow-card">
                            <div class="flex items-center justify-between border-b border-border pb-3">
                                <div>
                                    <p class="font-serif text-base italic text-navy">Scentrism</p>
                                    <p class="text-[10px] uppercase tracking-wider text-ink/50">Brand Blueprint &middot; v1.2</p>
                                </div>
                                <span class="rounded-full bg-mint px-2 py-0.5 text-[10px] font-medium text-navy">Live</span>
                            </div>

                            @php
                                $rows = [
                                    'Positioning' => 'The mindful fragrance house.',
                                    'Audience' => 'Quiet luxury seekers, 28–45.',
                                    'Tone' => 'Considered · Sensorial · Warm',
                                    'Identity' => 'Editorial serif + soft neutrals',
                                ];
                            @endphp

                            <div class="mt-4 space-y-3 text-xs text-ink/70">
                                @foreach ($rows as $label => $value)
                                    <div class="flex items-baseline justify-between gap-4">
                                        <span class="text-[10px] uppercase tracking-wider text-ink/50">{{ $label }}</span>
                                        <span class="truncate text-right text-navy">{{ $value }}</span>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-4 grid grid-cols-4 gap-1.5">
                                <span class="h-6 rounded bg-navy"></span>
                                <span class="h-6 rounded bg-mint"></span>
                                <span class="h-6 rounded bg-steel"></span>
                                <span class="h-6 rounded bg-ink/80"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
