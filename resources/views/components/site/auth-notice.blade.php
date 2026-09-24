{{--
    Shown when someone was sent here from a page that needs an account, so the
    sign-in screen explains itself instead of appearing out of nowhere.
--}}
@props(['action' => 'Sign in'])

@php
    $intended = session('url.intended');
    $wantsBlueprint = $intended && str_starts_with($intended, route('blueprint.create'));
@endphp

@if ($wantsBlueprint)
    <div class="mt-6 flex items-start gap-3 rounded-2xl border border-navy/10 bg-steel/30 px-4 py-3 text-sm text-navy/80">
        <i data-lucide="lock-keyhole" class="mt-0.5 h-4 w-4 shrink-0 text-navy/60"></i>
        <span>{{ $action }} to generate your Brand Blueprint&trade; — we will take you straight there.</span>
    </div>
@endif
