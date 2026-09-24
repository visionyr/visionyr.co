<x-webcms.layout
    title="Admin Users"
    heading="Admin Users"
    subheading="Staff accounts with access to this CMS."
>
    <x-slot:actions>
        <x-webcms.button :href="route('webcms.admin-users.create')" icon="plus">
            Add admin
        </x-webcms.button>
    </x-slot:actions>

    <x-webcms.card>
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-border/70 px-6 py-4">
            <form method="GET" action="{{ route('webcms.admin-users.index') }}" class="relative w-full max-w-sm">
                <i data-lucide="search" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-ink/40"></i>
                <input
                    type="search"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Search name or email"
                    class="w-full rounded-full border border-navy/15 bg-white py-2.5 pl-10 pr-4 text-sm text-navy outline-none transition-colors placeholder:text-ink/40 focus:border-navy focus:ring-2 focus:ring-navy/10"
                />
            </form>

            <p class="text-sm text-ink/50">{{ number_format($adminUsers->total()) }} total</p>
        </div>

        @if ($adminUsers->isEmpty())
            <x-webcms.empty-state icon="shield-check" :title="$search ? 'No admins match your search' : 'No admin users yet'">
                {{ $search ? 'Try a different name or email address.' : 'Add an admin to give someone CMS access.' }}
            </x-webcms.empty-state>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-border/70 text-xs uppercase tracking-wide text-ink/50">
                            <th scope="col" class="px-6 py-3 font-medium">Admin</th>
                            <th scope="col" class="px-6 py-3 font-medium">Status</th>
                            <th scope="col" class="px-6 py-3 font-medium">Last sign-in</th>
                            <th scope="col" class="px-6 py-3 font-medium">Created</th>
                            <th scope="col" class="px-6 py-3 text-right font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border/70">
                        @foreach ($adminUsers as $adminUser)
                            @php $isSelf = $adminUser->is(auth('admin')->user()); @endphp

                            <tr class="transition-colors hover:bg-steel/25">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <x-webcms.avatar :initials="$adminUser->initials()" />
                                        <div class="min-w-0">
                                            <p class="truncate font-medium text-navy">
                                                {{ $adminUser->name }}
                                                @if ($isSelf)
                                                    <span class="ml-1 rounded-full bg-steel px-2 py-0.5 text-[10px] font-medium uppercase tracking-wide text-navy/60">You</span>
                                                @endif
                                            </p>
                                            <p class="truncate text-xs text-ink/50">{{ $adminUser->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <x-webcms.badge :active="$adminUser->is_active">
                                        {{ $adminUser->is_active ? 'Active' : 'Inactive' }}
                                    </x-webcms.badge>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-ink/60">
                                    {{ $adminUser->last_login_at?->diffForHumans() ?? 'Never' }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-ink/60">
                                    {{ $adminUser->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-1">
                                        <a
                                            href="{{ route('webcms.admin-users.edit', $adminUser) }}"
                                            class="grid h-9 w-9 place-items-center rounded-lg text-ink/50 transition-colors hover:bg-steel/60 hover:text-navy"
                                            aria-label="Edit {{ $adminUser->name }}"
                                        >
                                            <i data-lucide="pencil" class="h-4 w-4"></i>
                                        </a>

                                        @unless ($isSelf)
                                            <form
                                                method="POST"
                                                action="{{ route('webcms.admin-users.destroy', $adminUser) }}"
                                                data-confirm="Delete {{ $adminUser->name }}? This cannot be undone."
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="grid h-9 w-9 place-items-center rounded-lg text-ink/50 transition-colors hover:bg-danger/10 hover:text-danger"
                                                    aria-label="Delete {{ $adminUser->name }}"
                                                >
                                                    <i data-lucide="trash-2" class="h-4 w-4"></i>
                                                </button>
                                            </form>
                                        @endunless
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($adminUsers->hasPages())
                <div class="border-t border-border/70 px-6 py-4">
                    {{ $adminUsers->links('webcms.pagination') }}
                </div>
            @endif
        @endif
    </x-webcms.card>
</x-webcms.layout>
