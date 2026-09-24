{{-- ===== HOW IT WORKS ===== --}}
<section id="how-it-works" class="border-y border-border/60 bg-white py-28">
    <div class="mx-auto max-w-7xl px-6">
        <x-site.section-header
            eyebrow="How It Works"
            title="How Visionyr Works"
            subtitle="Three simple steps from vision to brand."
        />

        <div data-reveal-group class="mt-14 grid gap-5 md:grid-cols-3">
            @foreach (config('marketing.how_it_works') as $index => $step)
                <x-site.feature-card
                    :icon="$step['icon']"
                    :title="$step['title']"
                    :number="str_pad($index + 1, 2, '0', STR_PAD_LEFT)"
                    class="p-7"
                >
                    {{ $step['body'] }}
                </x-site.feature-card>
            @endforeach
        </div>
    </div>
</section>
