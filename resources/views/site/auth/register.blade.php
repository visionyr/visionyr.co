<x-site.auth-layout
    title="Create your account — Visionyr"
    heading="Create your account"
    subheading="Start building the brand behind the vision."
>
    <x-site.auth-notice action="Create an account" />

    <form method="POST" action="{{ route('register.store') }}" class="mt-8 space-y-5">
        @csrf

        <x-site.field
            name="name"
            label="Name"
            placeholder="Jane Cooper"
            autocomplete="name"
            required
            autofocus
        />

        <x-site.field
            name="email"
            label="Email"
            type="email"
            placeholder="jane@example.com"
            autocomplete="email"
            required
        />

        <x-site.field
            name="phone"
            label="Phone"
            type="tel"
            placeholder="0812 3456 7890"
            autocomplete="tel"
            required
        />

        <x-site.field
            name="password"
            label="Password"
            type="password"
            placeholder="••••••••"
            hint="At least 8 characters."
            required
        />

        <x-site.field
            name="password_confirmation"
            label="Confirm password"
            type="password"
            placeholder="••••••••"
            required
        />

        <button
            type="submit"
            class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-navy px-5 py-3 text-sm font-medium text-white shadow-soft transition-all hover:bg-navy/90 hover:shadow-glow"
        >
            Create account
            <i data-lucide="arrow-right" class="h-4 w-4"></i>
        </button>
    </form>

    <x-slot:below>
        Already have an account?
        <a href="{{ route('login') }}" class="font-medium text-navy transition-colors hover:text-navy/70">Sign in</a>
    </x-slot:below>
</x-site.auth-layout>
