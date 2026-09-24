<x-site.layout
    :title="$document['title'].' — Visionyr'"
    :description="$document['intro']"
>
    {{-- Reading progress, pinned just under the sticky header. --}}
    <div class="fixed inset-x-0 top-16 z-40 h-0.5" aria-hidden="true">
        <div data-reading-progress class="h-full w-0 bg-navy/70"></div>
    </div>

    {{-- No overflow-hidden here: it would break the sticky contents list below.
         The decorations are inset to this container, so nothing escapes anyway. --}}
    <div class="relative">
        <div class="absolute inset-0 grid-bg opacity-50" aria-hidden="true"></div>
        <div class="pointer-events-none absolute inset-x-0 top-0 h-[420px] bg-gradient-to-b from-steel/40 to-transparent" aria-hidden="true"></div>

        <div class="relative mx-auto max-w-6xl px-6 pb-24 pt-14 lg:pt-20">

            {{-- ===== HEADER ===== --}}
            <div class="mx-auto max-w-2xl text-center animate-rise">
                <p class="text-xs uppercase tracking-[0.22em] text-navy/60">{{ $document['eyebrow'] }}</p>

                <h1 class="mt-3 text-balance text-4xl font-semibold tracking-[-0.02em] text-navy md:text-5xl">
                    {{ $document['title'] }}
                </h1>

                <p class="mx-auto mt-4 max-w-xl text-pretty text-base text-ink/70">
                    {{ $document['intro'] }}
                </p>

                <p class="mt-5 inline-flex items-center gap-2 rounded-full border border-navy/10 bg-white/70 px-3 py-1.5 text-xs text-navy/70 shadow-soft">
                    <i data-lucide="calendar" class="h-3 w-3"></i>
                    Effective {{ $document['effective'] }}
                </p>
            </div>

            <div class="mt-14 grid gap-10 lg:grid-cols-12">

                {{-- ===== CONTENTS ===== --}}
                <aside class="lg:col-span-4">
                    <nav class="lg:sticky lg:top-24" aria-label="On this page">
                        <p class="text-xs uppercase tracking-wide text-ink/50">On this page</p>

                        <ol class="mt-4 space-y-1 border-l border-border">
                            @foreach ($document['sections'] as $index => $section)
                                <li>
                                    <a
                                        href="#{{ $section['id'] }}"
                                        data-toc-link="{{ $section['id'] }}"
                                        class="flex gap-3 border-l-2 border-transparent -ml-px py-1.5 pl-4 text-sm text-ink/60 transition-colors hover:text-navy"
                                    >
                                        <span class="font-serif text-xs italic text-navy/30">
                                            {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                        </span>
                                        <span>{{ $section['heading'] }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ol>
                    </nav>
                </aside>

                {{-- ===== DOCUMENT ===== --}}
                <article class="space-y-12 lg:col-span-8">
                    @foreach ($document['sections'] as $index => $section)
                        <section id="{{ $section['id'] }}" data-reveal>
                            <div class="flex items-baseline gap-3">
                                <span class="font-serif text-sm italic text-navy/40">
                                    {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                </span>
                                <h2 class="text-2xl font-semibold tracking-tight text-navy">
                                    {{ $section['heading'] }}
                                </h2>
                            </div>

                            <div class="mt-4 space-y-4 text-[15px] leading-relaxed text-ink/75">
                                @foreach ($section['body'] as $block)
                                    @if (is_array($block))
                                        <ul class="space-y-2.5">
                                            @foreach ($block['list'] as $item)
                                                <li class="flex gap-2.5">
                                                    <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-navy/40"></span>
                                                    <span>{{ $item }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p>{{ $block }}</p>
                                    @endif
                                @endforeach
                            </div>
                        </section>
                    @endforeach

                    {{-- ===== CLOSER ===== --}}
                    <div data-reveal class="rounded-3xl border border-navy/15 bg-navy p-8 text-white">
                        <h2 class="text-xl font-semibold tracking-tight">Still have questions?</h2>
                        <p class="mt-2 text-sm text-white/70">
                            If anything here is unclear, ask us — we would rather explain it than have you guess.
                        </p>
                        <a href="{{ route('contact') }}" class="mt-5 inline-flex items-center gap-1.5 rounded-full bg-mint px-4 py-2.5 text-sm font-medium text-navy transition-colors hover:bg-white">
                            Contact us <i data-lucide="arrow-right" class="h-3.5 w-3.5"></i>
                        </a>
                    </div>

                    <p class="text-xs text-ink/45">
                        {{ $document['title'] }} &middot; effective {{ $document['effective'] }} &middot;
                        @if ($document['slug'] === 'privacy')
                            See also our <a href="{{ route('terms') }}" class="text-navy underline-offset-2 hover:underline">Terms &amp; Conditions</a>.
                        @else
                            See also our <a href="{{ route('privacy') }}" class="text-navy underline-offset-2 hover:underline">Privacy Policy</a>.
                        @endif
                    </p>
                </article>
            </div>
        </div>
    </div>

    {{-- Back to top, revealed once you are into the document. --}}
    <button
        type="button"
        data-back-to-top
        class="pointer-events-none fixed bottom-6 right-6 z-40 grid h-11 w-11 translate-y-3 place-items-center rounded-full border border-navy/15 bg-white text-navy opacity-0 shadow-card transition-all hover:bg-steel/40"
        aria-label="Back to top"
    >
        <i data-lucide="arrow-right" class="h-4 w-4 -rotate-90"></i>
    </button>
</x-site.layout>
