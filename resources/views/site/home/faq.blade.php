{{-- ===== FAQ ===== --}}
<section id="faq" class="border-t border-border bg-white py-28">
    <div class="mx-auto max-w-3xl px-6">
        <x-site.section-header eyebrow="FAQ" title="Questions, answered." />

        <div data-reveal class="mt-12 divide-y divide-border rounded-2xl border border-border bg-white">
            @foreach (config('marketing.faqs') as $index => $faq)
                <div>
                    <button
                        type="button"
                        data-faq-toggle
                        aria-expanded="false"
                        aria-controls="faq-answer-{{ $index }}"
                        class="block w-full px-6 py-5 text-left"
                    >
                        <div class="flex items-center justify-between gap-4">
                            <span class="text-base font-medium text-navy">{{ $faq['question'] }}</span>
                            <span class="grid h-7 w-7 shrink-0 place-items-center rounded-full border border-border text-navy">
                                <i data-lucide="minus" data-faq-minus class="hidden h-3.5 w-3.5"></i>
                                <i data-lucide="plus" data-faq-plus class="h-3.5 w-3.5"></i>
                            </span>
                        </div>
                    </button>

                    <div id="faq-answer-{{ $index }}" class="faq-answer px-6">
                        <div class="pb-5 pr-10 text-sm text-ink/70">{{ $faq['answer'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
