@php
    $columns = [
        'Product' => [
            'Create™' => route('home').'#create',
            'Accelerate™' => route('home').'#accelerate',
            'Pricing' => route('home').'#pricing',
            'FAQ' => route('home').'#faq',
        ],
        'Company' => [
            'Contact' => route('contact'),
            'Privacy Policy' => route('privacy'),
            'Terms' => route('terms'),
        ],
    ];
@endphp

<footer class="bg-white">
    <div class="mx-auto grid max-w-7xl gap-10 px-6 py-16 md:grid-cols-4">
        <div class="md:col-span-2">
            <x-site.wordmark :href="route('home')" />
            <p class="mt-4 max-w-sm font-serif text-lg italic text-navy/70">
                Build the Brand Behind the Vision&trade;
            </p>
        </div>

        @foreach ($columns as $heading => $links)
            <div>
                <p class="text-xs uppercase tracking-[0.18em] text-navy/60">{{ $heading }}</p>
                <ul class="mt-4 space-y-2.5 text-sm">
                    @foreach ($links as $label => $href)
                        <li>
                            <a href="{{ $href }}" class="text-ink/70 transition-colors hover:text-navy">{{ $label }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>

    <div class="border-t border-border">
        <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-3 px-6 py-6 text-xs text-ink/55 md:flex-row">
            <span>&copy; <span data-current-year>{{ now()->year }}</span> Visionyr&trade; &middot; visionyr.co</span>
            <span>Made for entrepreneurs who think brand-first.</span>
        </div>
    </div>
</footer>
