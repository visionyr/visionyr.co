@php
    $steps = config('blueprint.steps');
    $loadingSteps = config('blueprint.loading_steps');
@endphp

<x-site.layout
    title="Generate Your Brand Blueprint™ — Visionyr"
    description="Tell us about your vision and Visionyr will generate a complete Brand Blueprint in minutes."
    nav="minimal"
    :footer="false"
>
    <div class="relative">
        <div class="absolute inset-0 grid-bg opacity-50" aria-hidden="true"></div>
        <div class="pointer-events-none absolute inset-x-0 top-0 h-[420px] bg-gradient-to-b from-steel/40 to-transparent" aria-hidden="true"></div>

        <div class="relative mx-auto max-w-5xl px-6 pb-24 pt-14 lg:pt-20">

            @if ($quota['remaining'] < 1)
                {{-- ===== STAGE: OUT OF QUOTA ===== --}}
                <div class="mx-auto max-w-lg text-center animate-rise">
                    <span class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-steel/60 text-navy">
                        <i data-lucide="circle-alert" class="h-6 w-6"></i>
                    </span>

                    <h1 class="mt-6 text-balance text-3xl font-semibold tracking-[-0.02em] text-navy md:text-4xl">
                        You have used this month's <span class="font-serif italic">Blueprints.</span>
                    </h1>

                    <p class="mx-auto mt-4 max-w-md text-pretty text-base text-ink/70">
                        Your plan includes {{ $quota['limit'] }} Brand Blueprints a month, and all
                        {{ $quota['limit'] }} are spent. Your allowance renews on
                        <span class="font-medium text-navy">{{ $quota['resets_on']->format('j F Y') }}</span>.
                    </p>

                    <div class="mt-8 flex flex-wrap items-center justify-center gap-2">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-full bg-navy px-5 py-3 text-sm font-medium text-white shadow-soft transition-all hover:bg-navy/90 hover:shadow-glow">
                            <i data-lucide="arrow-left" class="h-4 w-4"></i>
                            Back to your blueprints
                        </a>
                        <a href="{{ route('contact') }}" class="inline-flex items-center gap-1.5 rounded-full border border-navy/15 bg-white px-4 py-3 text-sm font-medium text-navy transition-colors hover:bg-steel/40">
                            Need more? Talk to us
                        </a>
                    </div>
                </div>
            @else
            {{-- ===== STAGE: FORM ===== --}}
            <div data-stage="form" class="animate-rise">

                <div class="mx-auto max-w-2xl text-center">
                    <div class="inline-flex items-center gap-2 rounded-full border border-navy/10 bg-white/70 px-3 py-1.5 text-xs text-navy/80 shadow-soft">
                        <i data-lucide="sparkles" class="h-3 w-3 text-navy"></i>
                        {{ $quota['remaining'] }} of {{ $quota['limit'] }} left this month
                    </div>
                    <h1 class="mt-5 text-balance text-4xl font-semibold tracking-[-0.02em] text-navy md:text-5xl">
                        Generate Your Brand <span class="font-serif italic">Blueprint&trade;</span>
                    </h1>
                    <p class="mx-auto mt-4 max-w-xl text-pretty text-base text-ink/70">
                        Tell us about your vision and Visionyr will generate a complete Brand Blueprint in minutes.
                    </p>
                </div>

                <form
                    method="POST"
                    action="{{ route('blueprint.store') }}"
                    data-blueprint-form
                    data-minimum-ms="{{ config('blueprint.minimum_ms') }}"
                    class="mx-auto mt-10 max-w-2xl"
                >
                    @csrf

                    <div class="mb-6 flex items-center justify-between gap-3">
                        <p data-step-counter class="text-xs uppercase tracking-wide text-ink/50">Step 1 of {{ count($steps) }}</p>
                        <p data-step-name class="text-xs text-ink/50">{{ $steps[0]['label'] }}</p>
                    </div>

                    <div class="mb-10 flex gap-1.5">
                        @foreach ($steps as $index => $step)
                            <div data-progress-segment class="h-1 flex-1 rounded-full {{ $index === 0 ? 'bg-navy' : 'bg-navy/10' }}"></div>
                        @endforeach
                    </div>

                    <div
                        data-form-error
                        class="mb-6 rounded-2xl border border-danger/20 bg-danger/5 px-4 py-3 text-sm text-danger @if (! $errors->any() && ! session('error')) hidden @endif"
                    >{{ session('error') ?: $errors->first() }}</div>

                    <div class="rounded-3xl border border-navy/10 bg-white p-6 shadow-card md:p-10">

                        @foreach ($steps as $index => $step)
                            @php $field = $step['field']; @endphp

                            <div data-step data-label="{{ $step['label'] }}" @class(['step-current' => $index === 0])>
                                <label for="input-{{ $field }}" class="text-xs uppercase tracking-wide text-ink/50">
                                    {{ $step['label'] }}
                                </label>
                                <h2 class="mt-1 text-2xl font-semibold tracking-tight text-navy">{{ $step['question'] }}</h2>

                                @if ($step['type'] === 'text')
                                    <input
                                        type="text"
                                        id="input-{{ $field }}"
                                        name="{{ $field }}"
                                        value="{{ old($field) }}"
                                        placeholder="{{ $step['placeholder'] }}"
                                        maxlength="120"
                                        class="mt-3 w-full rounded-xl border border-navy/15 bg-white px-4 py-3 text-base text-navy outline-none transition-colors placeholder:text-ink/40 focus:border-navy focus:ring-2 focus:ring-navy/10"
                                    />

                                @elseif ($step['type'] === 'textarea')
                                    <textarea
                                        id="input-{{ $field }}"
                                        name="{{ $field }}"
                                        rows="{{ $step['rows'] }}"
                                        placeholder="{{ $step['placeholder'] }}"
                                        class="mt-3 w-full resize-none rounded-xl border border-navy/15 bg-white px-4 py-3 text-base text-navy outline-none transition-colors placeholder:text-ink/40 focus:border-navy focus:ring-2 focus:ring-navy/10"
                                    >{{ old($field) }}</textarea>

                                @else
                                    @php
                                        $options = $field === 'industry'
                                            ? config('blueprint.industries')
                                            : config('blueprint.price_positions');
                                    @endphp

                                    <div class="mt-4 grid grid-cols-2 gap-2 {{ $step['columns'] }}">
                                        @foreach ($options as $option)
                                            <label class="cursor-pointer">
                                                <input
                                                    type="radio"
                                                    name="{{ $field }}"
                                                    value="{{ $option }}"
                                                    class="peer sr-only"
                                                    @checked(old($field) === $option)
                                                />
                                                <span class="block rounded-xl border border-navy/15 bg-white px-4 py-3 text-center text-sm font-medium text-navy transition-all not-peer-checked:hover:bg-steel/40 peer-checked:border-navy peer-checked:bg-navy peer-checked:text-white peer-checked:shadow-soft peer-focus-visible:ring-2 peer-focus-visible:ring-navy/20">
                                                    {{ $option }}
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach

                        <div data-wizard-nav class="mt-8 items-center justify-between gap-3">
                            <button
                                type="button"
                                data-step-back
                                class="inline-flex items-center gap-1.5 rounded-full border border-navy/15 bg-white px-4 py-2.5 text-sm font-medium text-navy transition-colors hover:bg-steel/40 disabled:cursor-not-allowed disabled:opacity-40"
                            >
                                <i data-lucide="arrow-left" class="h-3.5 w-3.5"></i> Back
                            </button>

                            <button
                                type="button"
                                data-step-next
                                class="group inline-flex items-center gap-2 rounded-full bg-navy px-5 py-2.5 text-sm font-medium text-white shadow-soft transition-all hover:bg-navy/90 hover:shadow-glow disabled:cursor-not-allowed disabled:opacity-40"
                            >
                                <span data-step-next-label>Continue</span>
                                <i data-lucide="arrow-right" class="h-4 w-4"></i>
                            </button>
                        </div>

                        <div data-wizard-submit class="mt-8">
                            <button
                                type="submit"
                                class="inline-flex items-center gap-2 rounded-full bg-navy px-5 py-2.5 text-sm font-medium text-white shadow-soft transition-all hover:bg-navy/90"
                            >
                                Generate Brand Blueprint&trade;
                                <i data-lucide="arrow-right" class="h-4 w-4"></i>
                            </button>
                        </div>
                    </div>
                </form>

                <p class="mt-6 text-center text-xs text-ink/50">
                    Your inputs are private. Visionyr never shares your brand vision.
                </p>
            </div>

            {{-- ===== STAGE: GENERATING ===== --}}
            <div data-stage="loading" class="mx-auto max-w-2xl text-center" style="display:none">
                <div class="inline-flex items-center gap-2 rounded-full border border-navy/10 bg-white/70 px-3 py-1.5 text-xs text-navy/80 shadow-soft">
                    <span class="spinner spinner-lg text-navy"></span>
                    Generating
                </div>

                <h1 class="mt-5 text-balance text-4xl font-semibold tracking-[-0.02em] text-navy md:text-5xl">
                    Building Your Brand <span class="font-serif italic">Blueprint&hellip;</span>
                </h1>

                <p class="mx-auto mt-4 max-w-md text-pretty text-base text-ink/70">
                    Visionyr is crafting a complete brand system for
                    <span data-loading-brand-name class="font-medium text-navy">your brand</span>.
                </p>

                <div class="mt-10 rounded-3xl border border-navy/10 bg-white p-8 text-left shadow-card">
                    <div class="flex items-center justify-between text-xs uppercase tracking-wide text-ink/50">
                        <span>Progress</span>
                        <span data-progress-pct>0%</span>
                    </div>
                    <div class="mt-3 h-1.5 w-full overflow-hidden rounded-full bg-navy/10">
                        <div data-progress-fill class="h-full rounded-full bg-navy" style="width:0%"></div>
                    </div>

                    <ul class="mt-8 space-y-3">
                        @foreach ($loadingSteps as $index => $label)
                            <li data-loading-step class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition-colors">
                                <span data-state="done" class="hidden grid h-5 w-5 shrink-0 place-items-center rounded-full bg-navy text-white">
                                    <i data-lucide="check" class="h-3 w-3"></i>
                                </span>
                                <span data-state="active" class="hidden grid h-5 w-5 shrink-0 place-items-center rounded-full bg-mint text-navy">
                                    <span class="spinner" style="width:10px;height:10px"></span>
                                </span>
                                <span data-state="pending" class="grid h-5 w-5 shrink-0 place-items-center rounded-full bg-navy/10 text-navy/40">
                                    <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                </span>
                                <span data-step-label class="text-ink/50">{{ $label }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

        </div>
    </div>
</x-site.layout>
