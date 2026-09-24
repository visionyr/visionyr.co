@csrf
@if ($member->exists)
    @method('PUT')
@endif

<div class="grid gap-5 md:grid-cols-2">
    <div>
        <x-webcms.label for="name" required>Name</x-webcms.label>
        <x-webcms.input
            id="name"
            name="name"
            class="mt-2"
            :value="old('name', $member->name)"
            :invalid="$errors->has('name')"
            placeholder="Jane Cooper"
            required
            autofocus
        />
        <x-webcms.error :messages="$errors->get('name')" />
    </div>

    <div>
        <x-webcms.label for="phone" required>Phone</x-webcms.label>
        <x-webcms.input
            id="phone"
            name="phone"
            type="tel"
            class="mt-2"
            :value="old('phone', $member->phone)"
            :invalid="$errors->has('phone')"
            placeholder="0812 3456 7890"
            required
        />
        <x-webcms.error :messages="$errors->get('phone')" />
    </div>

    <div class="md:col-span-2">
        <x-webcms.label for="email" required>Email</x-webcms.label>
        <x-webcms.input
            id="email"
            name="email"
            type="email"
            class="mt-2"
            :value="old('email', $member->email)"
            :invalid="$errors->has('email')"
            placeholder="jane@example.com"
            autocomplete="off"
            required
        />
        <x-webcms.error :messages="$errors->get('email')" />
    </div>
</div>

<div class="mt-6 border-t border-border/70 pt-6">
    <div class="grid gap-5 md:grid-cols-2">
        <div>
            <x-webcms.label for="password" :required="! $member->exists">Password</x-webcms.label>
            <x-webcms.password-input
                id="password"
                name="password"
                class="mt-2"
                :invalid="$errors->has('password')"
                placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;"
                :required="! $member->exists"
            />
            <x-webcms.error :messages="$errors->get('password')" />

            @if ($member->exists)
                <p class="mt-1.5 text-xs text-ink/45">Leave blank to keep the current password.</p>
            @endif
        </div>

        <div>
            <x-webcms.label for="password_confirmation" :required="! $member->exists">Confirm password</x-webcms.label>
            <x-webcms.password-input
                id="password_confirmation"
                name="password_confirmation"
                class="mt-2"
                placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;"
                :required="! $member->exists"
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
            @checked(old('is_active', $member->is_active ?? true))
            class="mt-0.5 h-4 w-4 accent-navy"
        />
        <span>
            <span class="block text-sm font-medium text-navy">Active</span>
            <span class="block text-xs text-ink/50">Inactive members cannot sign in.</span>
        </span>
    </label>
</div>

<div class="mt-8 flex items-center justify-between gap-3 border-t border-border/70 pt-6">
    <x-webcms.button :href="route('webcms.members.index')" variant="secondary" icon="arrow-left">
        Cancel
    </x-webcms.button>

    <x-webcms.button type="submit">
        {{ $member->exists ? 'Save changes' : 'Create member' }}
    </x-webcms.button>
</div>
