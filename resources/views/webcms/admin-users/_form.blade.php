@csrf
@if ($adminUser->exists)
    @method('PUT')
@endif

@php $isSelf = $adminUser->exists && $adminUser->is(auth('admin')->user()); @endphp

<div class="grid gap-5 md:grid-cols-2">
    <div>
        <x-webcms.label for="name" required>Name</x-webcms.label>
        <x-webcms.input
            id="name"
            name="name"
            class="mt-2"
            :value="old('name', $adminUser->name)"
            :invalid="$errors->has('name')"
            placeholder="Jane Cooper"
            required
            autofocus
        />
        <x-webcms.error :messages="$errors->get('name')" />
    </div>

    <div>
        <x-webcms.label for="email" required>Email</x-webcms.label>
        <x-webcms.input
            id="email"
            name="email"
            type="email"
            class="mt-2"
            :value="old('email', $adminUser->email)"
            :invalid="$errors->has('email')"
            placeholder="jane@visionyr.com"
            autocomplete="off"
            required
        />
        <x-webcms.error :messages="$errors->get('email')" />
    </div>
</div>

<div class="mt-6 border-t border-border/70 pt-6">
    <div class="grid gap-5 md:grid-cols-2">
        <div>
            <x-webcms.label for="password" :required="! $adminUser->exists">Password</x-webcms.label>
            <x-webcms.password-input
                id="password"
                name="password"
                class="mt-2"
                :invalid="$errors->has('password')"
                placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;"
                :required="! $adminUser->exists"
            />
            <x-webcms.error :messages="$errors->get('password')" />

            @if ($adminUser->exists)
                <p class="mt-1.5 text-xs text-ink/45">Leave blank to keep the current password.</p>
            @endif
        </div>

        <div>
            <x-webcms.label for="password_confirmation" :required="! $adminUser->exists">Confirm password</x-webcms.label>
            <x-webcms.password-input
                id="password_confirmation"
                name="password_confirmation"
                class="mt-2"
                placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;"
                :required="! $adminUser->exists"
            />
        </div>
    </div>
</div>

<div class="mt-6 border-t border-border/70 pt-6">
    <label class="flex items-start gap-3">
        <input type="hidden" name="is_active" value="0" />
        <input
            type="checkbox"
            name="is_active"
            value="1"
            @checked(old('is_active', $adminUser->is_active ?? true))
            @disabled($isSelf)
            class="mt-0.5 h-4 w-4 accent-navy disabled:opacity-40"
        />
        <span>
            <span class="block text-sm font-medium text-navy">Active</span>
            <span class="block text-xs text-ink/50">
                {{ $isSelf ? 'You cannot deactivate the account you are signed in with.' : 'Inactive admins cannot sign in to the CMS.' }}
            </span>
        </span>
    </label>
</div>

<div class="mt-8 flex items-center justify-between gap-3 border-t border-border/70 pt-6">
    <x-webcms.button :href="route('webcms.admin-users.index')" variant="secondary" icon="arrow-left">
        Cancel
    </x-webcms.button>

    <x-webcms.button type="submit">
        {{ $adminUser->exists ? 'Save changes' : 'Create admin' }}
    </x-webcms.button>
</div>
