<x-site.auth-layout
    title="Sign in — Visionyr"
    heading="Welcome back"
    subheading="Sign in to your Visionyr account."
>
    <x-site.auth-notice action="Sign in" />

    <form method="POST" action="{{ route('login.store') }}" class="mt-8 space-y-5">
        @csrf

        <x-site.field
            name="email"
            label="Email"
            type="email"
            placeholder="jane@example.com"
            autocomplete="username"
            required
            autofocus
        />

        <x-site.field
            name="password"
            label="Password"
            type="password"
            placeholder="••••••••"
            autocomplete="current-password"
            required
        />

        <label class="flex items-center gap-2 text-sm text-ink/60">
            <input type="checkbox" name="remember" value="1" class="h-4 w-4 accent-navy" />
            Keep me signed in
        </label>

        <button
            type="submit"
            class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-navy px-5 py-3 text-sm font-medium text-white shadow-soft transition-all hover:bg-navy/90 hover:shadow-glow"
        >
            Sign in
            <i data-lucide="arrow-right" class="h-4 w-4"></i>
        </button>
    </form>

    <x-slot:below>
        @if (config('features.member_registration'))
            New to Visionyr?
            <a href="{{ route('register') }}" class="font-medium text-navy transition-colors hover:text-navy/70">Create an account</a>
        @else
            Need an account?
            <a href="{{ route('contact') }}" class="font-medium text-navy transition-colors hover:text-navy/70">Get in touch</a>
            and we will set one up for you.
        @endif
    </x-slot:below>
</x-site.auth-layout>
