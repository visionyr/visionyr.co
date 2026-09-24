<x-webcms.layout
    title="Brand Blueprint"
    :heading="$blueprint->brand_name"
    :subheading="$blueprint->industry.' · '.$blueprint->price_position"
>
    <x-slot:actions>
        <x-webcms.button :href="route('blueprint.show', $blueprint)" variant="secondary" icon="eye" target="_blank" rel="noopener">
            Open public page
        </x-webcms.button>
        <x-webcms.button :href="route('webcms.brand-blueprints.index')" variant="ghost" icon="arrow-left">
            Back
        </x-webcms.button>
    </x-slot:actions>

    @php
        $meta = [
            ['label' => 'Member', 'value' => $blueprint->member?->name ?? 'Guest'],
            ['label' => 'Email', 'value' => $blueprint->member?->email ?? '—'],
            ['label' => 'Generated', 'value' => $blueprint->created_at->format('d M Y, H:i')],
            ['label' => 'Source', 'value' => $blueprint->wasGeneratedByAi() ? 'OpenRouter' : 'Template fallback'],
            ['label' => 'Model', 'value' => $blueprint->model ?? '—'],
            ['label' => 'Took', 'value' => $blueprint->generation_ms ? number_format($blueprint->generation_ms).' ms' : '—'],
        ];

        $answers = [
            'Brand name' => $blueprint->brand_name,
            'Industry' => $blueprint->industry,
            'Price position' => $blueprint->price_position,
            'Target audience' => $blueprint->audience,
            'Brand vision' => $blueprint->vision,
        ];
    @endphp

    @if ($blueprint->failure_reason)
        <div class="mb-6 flex items-start gap-3 rounded-2xl border border-danger/20 bg-danger/5 px-4 py-3 text-sm text-danger">
            <i data-lucide="circle-alert" class="mt-0.5 h-4 w-4 shrink-0"></i>
            <span>
                <span class="font-medium">This blueprint used the template fallback.</span>
                {{ $blueprint->failure_reason }}
            </span>
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-3">

        {{-- ===== WHAT THEY ANSWERED ===== --}}
        <x-webcms.card class="p-6 lg:col-span-1">
            <h2 class="text-sm font-semibold uppercase tracking-wide text-navy">Options selected</h2>

            <dl class="mt-5 space-y-4">
                @foreach ($answers as $label => $value)
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-ink/50">{{ $label }}</dt>
                        <dd class="mt-1 whitespace-pre-line text-sm text-navy">{{ $value }}</dd>
                    </div>
                @endforeach
            </dl>

            <div class="mt-6 border-t border-border/70 pt-5">
                <h3 class="text-xs uppercase tracking-wide text-ink/50">Generation</h3>
                <dl class="mt-3 space-y-2">
                    @foreach ($meta as $row)
                        <div class="flex items-baseline justify-between gap-3">
                            <dt class="text-xs text-ink/50">{{ $row['label'] }}</dt>
                            <dd class="truncate text-right text-sm text-navy">{{ $row['value'] }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>
        </x-webcms.card>

        {{-- ===== WHAT WAS GENERATED ===== --}}
        <div class="space-y-6 lg:col-span-2">

            <x-webcms.card class="p-6">
                <div class="flex flex-wrap items-baseline justify-between gap-3">
                    <h2 class="text-sm font-semibold uppercase tracking-wide text-navy">Result</h2>
                    <p class="text-sm italic text-ink/60">{{ $bp['tagline'] ?? '' }}</p>
                </div>

                <div class="mt-5 grid gap-4 sm:grid-cols-3">
                    @foreach (['brand' => 'Brand Score', 'market' => 'Market Fit', 'growth' => 'Growth'] as $key => $label)
                        <div class="rounded-2xl border border-border/70 p-4">
                            <p class="text-xs uppercase tracking-wide text-ink/50">{{ $label }}</p>
                            <p class="mt-2 text-2xl font-semibold tracking-tight text-navy">
                                {{ $bp['scores'][$key] ?? '—' }}%
                            </p>
                            <div class="mt-3 h-1 w-full overflow-hidden rounded-full bg-navy/10">
                                <div class="h-full rounded-full bg-navy" style="width:{{ $bp['scores'][$key] ?? 0 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 space-y-5 border-t border-border/70 pt-5">
                    @foreach (['Market opportunity' => 'market_opportunity', 'Positioning' => 'positioning'] as $label => $key)
                        <div>
                            <p class="text-xs uppercase tracking-wide text-ink/50">{{ $label }}</p>
                            <p class="mt-1.5 text-sm leading-relaxed text-ink/75">{{ $bp[$key] ?? '' }}</p>
                        </div>
                    @endforeach

                    <div>
                        <p class="text-xs uppercase tracking-wide text-ink/50">Brand story</p>
                        <p class="mt-1.5 whitespace-pre-line text-sm leading-relaxed text-ink/75">{{ $bp['story'] ?? '' }}</p>
                    </div>
                </div>
            </x-webcms.card>

            <x-webcms.card class="p-6">
                <h2 class="text-sm font-semibold uppercase tracking-wide text-navy">Identity</h2>

                <div class="mt-5 space-y-5">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-ink/50">Personality</p>
                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach ($bp['personality'] ?? [] as $trait)
                                <span class="rounded-full border border-navy/10 bg-white px-2.5 py-1 text-xs font-medium text-navy/80">{{ $trait }}</span>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <p class="text-xs uppercase tracking-wide text-ink/50">Colour direction</p>
                        <div class="mt-2 grid grid-cols-5 gap-2">
                            @foreach ($bp['colors'] ?? [] as $color)
                                <div class="overflow-hidden rounded-xl border border-border/70">
                                    <div class="h-12 w-full" style="background:{{ $color['hex'] }}"></div>
                                    <div class="px-2 py-1.5">
                                        <p class="truncate text-[11px] font-medium text-navy">{{ $color['name'] }}</p>
                                        <p class="truncate text-[10px] uppercase text-ink/50">{{ $color['hex'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-ink/50">Naming</p>
                            <ul class="mt-2 space-y-1.5 text-sm text-ink/75">
                                @foreach ($bp['naming'] ?? [] as $name)
                                    <li class="truncate">{{ $name }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-ink/50">Taglines</p>
                            <ul class="mt-2 space-y-1.5 text-sm text-ink/75">
                                @foreach ($bp['taglines'] ?? [] as $tagline)
                                    <li>{{ $tagline }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </x-webcms.card>

            <x-webcms.card class="p-6">
                <h2 class="text-sm font-semibold uppercase tracking-wide text-navy">Launch</h2>

                <ol class="mt-5 space-y-3">
                    @foreach ($bp['launch'] ?? [] as $index => $phase)
                        <li class="flex gap-3">
                            <span class="mt-0.5 grid h-6 w-6 shrink-0 place-items-center rounded-full bg-navy text-xs font-medium text-white">
                                {{ $index + 1 }}
                            </span>
                            <div>
                                <p class="text-sm font-medium text-navy">{{ $phase['title'] }}</p>
                                <p class="text-sm text-ink/65">{{ $phase['desc'] }}</p>
                            </div>
                        </li>
                    @endforeach
                </ol>

                <div class="mt-6 grid gap-3 border-t border-border/70 pt-5 sm:grid-cols-4">
                    @foreach ($bp['plan'] ?? [] as $week)
                        <div class="rounded-2xl bg-steel/25 p-4">
                            <p class="text-[10px] uppercase tracking-wide text-ink/50">{{ $week['week'] }}</p>
                            <p class="mt-0.5 text-sm font-medium text-navy">{{ $week['title'] }}</p>
                            <ul class="mt-2 space-y-1 text-xs text-ink/65">
                                @foreach ($week['items'] as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </x-webcms.card>
        </div>
    </div>
</x-webcms.layout>
