{{-- ===== FINAL CTA ===== --}}
<section class="relative overflow-hidden bg-navy py-28 text-white">
    <div
        class="pointer-events-none absolute inset-0 animate-drift opacity-30"
        style="background-image:radial-gradient(600px circle at 20% 20%, rgba(184,255,241,0.18), transparent 60%), radial-gradient(500px circle at 80% 80%, rgba(220,234,247,0.12), transparent 60%)"
        aria-hidden="true"
    ></div>

    <div data-reveal class="relative mx-auto max-w-3xl px-6 text-center">
        <h2 class="text-balance text-5xl font-semibold tracking-[-0.02em] md:text-6xl">
            Ready to Build Your <span class="font-serif italic">Brand?</span>
        </h2>
        <p class="mx-auto mt-5 max-w-xl text-white/70">
            Join the first generation of entrepreneurs building brands with AI.
        </p>

        <div class="mt-9 flex flex-wrap items-center justify-center gap-3">
            <a href="{{ route('blueprint.create') }}" class="inline-flex items-center gap-2 rounded-full bg-mint px-5 py-3 text-sm font-medium text-navy transition-colors hover:bg-white">
                Generate My Brand Blueprint <i data-lucide="arrow-right" class="h-4 w-4"></i>
            </a>
            <a
                href="{{ config('marketing.demo_whatsapp') }}"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-flex items-center gap-2 rounded-full border border-white/20 px-5 py-3 text-sm font-medium text-white transition-colors hover:bg-white/10"
            >
                Book a Demo
                <i data-lucide="arrow-up-right" class="h-4 w-4"></i>
            </a>
        </div>
    </div>
</section>
