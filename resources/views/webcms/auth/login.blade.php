<x-webcms.guest-layout title="Sign in">
    <x-webcms.card class="mt-8 p-8">
        <div class="text-center">
            <h1 class="text-2xl font-semibold tracking-tight text-navy">Sign in to the CMS</h1>
            <p class="mt-1 text-sm text-ink/50">Manage admin users and members.</p>
        </div>

        <form method="POST" action="{{ route('webcms.login.store') }}" class="mt-8 space-y-5">
            @csrf

            <div>
                <x-webcms.label for="email" required>Email</x-webcms.label>
                <x-webcms.input
                    id="email"
                    name="email"
                    type="email"
                    class="mt-2"
                    :value="old('email')"
                    :invalid="$errors->has('email')"
                    placeholder="you@visionyr.com"
                    autocomplete="username"
                    required
                    autofocus
                />
                <x-webcms.error :messages="$errors->get('email')" />
            </div>

            <div>
                <x-webcms.label for="password" required>Password</x-webcms.label>
                <x-webcms.password-input
                    id="password"
                    name="password"
                    class="mt-2"
                    :invalid="$errors->has('password')"
                    placeholder="••••••••"
                    autocomplete="current-password"
                    required
                />
                <x-webcms.error :messages="$errors->get('password')" />
            </div>

            <label class="flex items-center gap-2 text-sm text-ink/60">
                <input
                    type="checkbox"
                    name="remember"
                    value="1"
                    class="h-4 w-4 accent-navy"
                />
                Keep me signed in
            </label>

            <x-webcms.button type="submit" class="w-full" icon="lock-keyhole">
                Sign in
            </x-webcms.button>
        </form>
    </x-webcms.card>
</x-webcms.guest-layout>
