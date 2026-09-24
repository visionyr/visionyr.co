{{-- ===== WHO IT'S FOR ===== --}}
<section id="who-its-for" class="border-y border-border bg-white py-28">
    <div class="mx-auto max-w-7xl px-6">
        <x-site.section-header eyebrow="Audience" title="Who Visionyr Is For" />

        <div data-reveal-group class="mt-14 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach (config('marketing.audiences') as $audience)
                <x-site.feature-card :icon="$audience['icon']" :title="$audience['title']" class="p-7">
                    {{ $audience['body'] }}
                </x-site.feature-card>
            @endforeach
        </div>
    </div>
</section>
