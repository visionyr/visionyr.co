{{-- ===== CONTACT ===== --}}
<section id="contact" class="border-t border-border bg-white py-28">
    <div class="mx-auto max-w-3xl px-6 text-center">
        <x-site.section-header
            eyebrow="Contact"
            title="Let's Build Something Worth Remembering."
            subtitle="Questions, partnerships, demos, or enterprise inquiries."
        />

        <div data-reveal-group class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach (config('marketing.contacts') as $contact)
                <a
                    href="{{ $contact['href'] }}"
                    data-reveal
                    @if ($contact['external'] ?? false) target="_blank" rel="noopener noreferrer" @endif
                    class="group flex flex-col items-center gap-3 rounded-2xl border border-border bg-white p-6 transition-all hover:-translate-y-1 hover:border-navy/20 hover:shadow-card"
                >
                    <span class="grid h-10 w-10 place-items-center rounded-xl bg-steel/60 text-navy transition-colors group-hover:bg-mint">
                        @if ($contact['brand'] ?? false)
                            <x-site.brand-icon :name="$contact['icon']" />
                        @else
                            <i data-lucide="{{ $contact['icon'] }}" class="h-5 w-5"></i>
                        @endif
                    </span>
                    <span class="text-sm font-medium text-navy">{{ $contact['label'] }}</span>
                </a>
            @endforeach
        </div>
    </div>
</section>
