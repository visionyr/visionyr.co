@php
    $sent = session('contact_sent');

    $channels = [
        [
            'icon' => 'mail',
            'label' => 'Email us',
            'value' => 'hello@visionyr.co',
            'href' => 'mailto:hello@visionyr.co',
            'external' => false,
        ],
        [
            'icon' => 'calendar',
            'label' => 'Book a demo',
            'value' => 'Chat on WhatsApp',
            'href' => config('marketing.demo_whatsapp'),
            'external' => true,
        ],
    ];
@endphp

<x-site.layout
    title="Contact — Visionyr"
    description="Questions, partnerships, demos, or enterprise enquiries. Tell us what you are building and we will get back to you."
>
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 grid-bg opacity-50" aria-hidden="true"></div>
        <div class="pointer-events-none absolute inset-x-0 top-0 h-[420px] bg-gradient-to-b from-steel/40 to-transparent" aria-hidden="true"></div>

        <div class="relative mx-auto max-w-6xl px-6 pb-24 pt-14 lg:pt-20">

            {{-- ===== HEADER ===== --}}
            <div class="mx-auto max-w-2xl text-center animate-rise">
                <div class="inline-flex items-center gap-2 rounded-full border border-navy/10 bg-white/70 px-3 py-1.5 text-xs text-navy/80 shadow-soft">
                    <span class="h-1.5 w-1.5 rounded-full bg-mint animate-pulse-soft"></span>
                    Usually replies within one business day
                </div>

                <h1 class="mt-5 text-balance text-4xl font-semibold tracking-[-0.02em] text-navy md:text-5xl">
                    Let's build something <span class="font-serif italic">worth remembering.</span>
                </h1>

                <p class="mx-auto mt-4 max-w-xl text-pretty text-base text-ink/70">
                    Questions, partnerships, demos, or enterprise enquiries — tell us what you are
                    building and the right person will get back to you.
                </p>
            </div>

            <div class="mt-14 grid gap-6 lg:grid-cols-5">

                {{-- ===== CHANNELS ===== --}}
                <div data-reveal-group class="space-y-4 lg:col-span-2">
                    @foreach ($channels as $channel)
                        <a
                            href="{{ $channel['href'] }}"
                            data-reveal
                            @if ($channel['external']) target="_blank" rel="noopener noreferrer" @endif
                            class="group flex items-center gap-4 rounded-2xl border border-navy/10 bg-white p-5 shadow-soft transition-all hover:-translate-y-1 hover:border-navy/20 hover:shadow-card"
                        >
                            <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-steel/60 text-navy transition-colors group-hover:bg-mint">
                                <i data-lucide="{{ $channel['icon'] }}" class="h-5 w-5"></i>
                            </span>
                            <span class="min-w-0 flex-1">
                                <span class="block text-xs uppercase tracking-wide text-ink/50">{{ $channel['label'] }}</span>
                                <span class="block truncate text-sm font-medium text-navy">{{ $channel['value'] }}</span>
                            </span>
                            <i data-lucide="arrow-up-right" class="h-4 w-4 shrink-0 text-navy/30 transition-transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5"></i>
                        </a>
                    @endforeach

                    <div data-reveal class="rounded-2xl border border-navy/15 bg-navy p-6 text-white">
                        <p class="text-xs uppercase tracking-[0.18em] text-mint">Already exploring?</p>
                        <h2 class="mt-2 text-xl font-semibold tracking-tight">
                            Generate a Brand Blueprint first.
                        </h2>
                        <p class="mt-2 text-sm text-white/70">
                            It takes five questions, and gives us both something concrete to talk about.
                        </p>
                        <a href="{{ route('blueprint.create') }}" class="mt-5 inline-flex items-center gap-1.5 rounded-full bg-mint px-4 py-2.5 text-sm font-medium text-navy transition-colors hover:bg-white">
                            Start free <i data-lucide="arrow-right" class="h-3.5 w-3.5"></i>
                        </a>
                    </div>
                </div>

                {{-- ===== FORM ===== --}}
                <div data-reveal class="lg:col-span-3">
                    <div class="rounded-3xl border border-navy/10 bg-white p-6 shadow-card md:p-8">

                        @if ($sent)
                            <div class="flex flex-col items-center py-10 text-center animate-rise">
                                <span class="grid h-14 w-14 place-items-center rounded-full bg-mint text-navy">
                                    <i data-lucide="check" class="h-6 w-6"></i>
                                </span>
                                <h2 class="mt-5 text-2xl font-semibold tracking-tight text-navy">
                                    Thanks, {{ $sent }}.
                                </h2>
                                <p class="mt-2 max-w-sm text-sm text-ink/60">
                                    Your message is with us. We usually reply within one business day —
                                    check the inbox you gave us.
                                </p>

                                <div class="mt-7 flex flex-wrap items-center justify-center gap-2">
                                    <a href="{{ route('blueprint.create') }}" class="inline-flex items-center gap-2 rounded-full bg-navy px-5 py-2.5 text-sm font-medium text-white shadow-soft transition-all hover:bg-navy/90 hover:shadow-glow">
                                        Generate a Brand Blueprint
                                        <i data-lucide="arrow-right" class="h-4 w-4"></i>
                                    </a>
                                    <a href="{{ route('contact') }}" class="inline-flex items-center gap-1.5 rounded-full border border-navy/15 bg-white px-4 py-2.5 text-sm font-medium text-navy transition-colors hover:bg-steel/40">
                                        Send another
                                    </a>
                                </div>
                            </div>
                        @else
                            <h2 class="text-xl font-semibold tracking-tight text-navy">Send us a message</h2>
                            <p class="mt-1 text-sm text-ink/60">All fields are required.</p>

                            <form method="POST" action="{{ route('contact.store') }}" data-submitting class="mt-7 space-y-5">
                                @csrf

                                <div class="grid gap-5 md:grid-cols-2">
                                    <x-site.field
                                        name="name"
                                        label="Name"
                                        placeholder="Jane Cooper"
                                        autocomplete="name"
                                        required
                                        autofocus
                                    />

                                    <x-site.field
                                        name="phone"
                                        label="Phone"
                                        type="tel"
                                        placeholder="0812 3456 7890"
                                        autocomplete="tel"
                                        required
                                    />
                                </div>

                                <x-site.field
                                    name="email"
                                    label="Email"
                                    type="email"
                                    placeholder="jane@example.com"
                                    autocomplete="email"
                                    required
                                />

                                <div>
                                    <x-site.field
                                        name="message"
                                        label="Message"
                                        type="textarea"
                                        :rows="7"
                                        placeholder="Tell us what you're building, and what you need from us."
                                        maxlength="4000"
                                        data-char-input
                                        required
                                    />

                                    <p class="mt-1.5 text-right text-xs text-ink/40">
                                        <span data-char-count>0</span> / 4000
                                    </p>
                                </div>

                                <button
                                    type="submit"
                                    class="group inline-flex w-full items-center justify-center gap-2 rounded-full bg-navy px-5 py-3 text-sm font-medium text-white shadow-soft transition-all hover:bg-navy/90 hover:shadow-glow disabled:cursor-not-allowed disabled:opacity-60"
                                >
                                    <span class="spinner hidden" data-submitting-spinner></span>
                                    <span data-submitting-label>Send message</span>
                                    <i data-lucide="arrow-right" class="h-4 w-4 transition-transform group-hover:translate-x-0.5"></i>
                                </button>

                                <p class="text-center text-xs text-ink/45">
                                    By sending this you agree to our
                                    <a href="{{ route('privacy') }}" class="text-navy underline-offset-2 hover:underline">Privacy Policy</a>.
                                </p>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-site.layout>
