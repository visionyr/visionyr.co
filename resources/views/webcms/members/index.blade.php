<x-webcms.layout
    title="Members"
    heading="Members"
    subheading="Everyone with a Visionyr account."
>
    <x-slot:actions>
        <x-webcms.button :href="route('webcms.members.create')" icon="plus">
            Add member
        </x-webcms.button>
    </x-slot:actions>

    <x-webcms.card>
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-border/70 px-6 py-4">
            <form method="GET" action="{{ route('webcms.members.index') }}" class="relative w-full max-w-sm">
                <i data-lucide="search" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-ink/40"></i>
                <input
                    type="search"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Search name, email, or phone"
                    class="w-full rounded-full border border-navy/15 bg-white py-2.5 pl-10 pr-4 text-sm text-navy outline-none transition-colors placeholder:text-ink/40 focus:border-navy focus:ring-2 focus:ring-navy/10"
                />
            </form>

            <p class="text-sm text-ink/50">{{ number_format($members->total()) }} total</p>
        </div>

        @if ($members->isEmpty())
            <x-webcms.empty-state icon="users" :title="$search ? 'No members match your search' : 'No members yet'">
                {{ $search ? 'Try a different name, email, or phone number.' : 'Add your first member to get started.' }}
            </x-webcms.empty-state>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-border/70 text-xs uppercase tracking-wide text-ink/50">
                            <th scope="col" class="px-6 py-3 font-medium">Member</th>
                            <th scope="col" class="px-6 py-3 font-medium">Phone</th>
                            <th scope="col" class="px-6 py-3 font-medium">Status</th>
                            <th scope="col" class="px-6 py-3 font-medium">Blueprints left</th>
                            <th scope="col" class="px-6 py-3 font-medium">Joined</th>
                            <th scope="col" class="px-6 py-3 text-right font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border/70">
                        @foreach ($members as $member)
                            <tr class="transition-colors hover:bg-steel/25">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <x-webcms.avatar :initials="$member->initials()" />
                                        <div class="min-w-0">
                                            <p class="truncate font-medium text-navy">{{ $member->name }}</p>
                                            <p class="truncate text-xs text-ink/50">{{ $member->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-ink/70">{{ $member->phone }}</td>
                                <td class="px-6 py-4">
                                    <x-webcms.badge :active="$member->is_active">
                                        {{ $member->is_active ? 'Active' : 'Inactive' }}
                                    </x-webcms.badge>
                                </td>
                                @php $quota = $member->blueprintQuota(); @endphp

                                <td class="whitespace-nowrap px-6 py-4">
                                    <span @class([
                                        'font-medium',
                                        'text-navy' => $quota['remaining'] > 0,
                                        'text-danger' => $quota['remaining'] < 1,
                                    ])>{{ $quota['remaining'] }}</span><span class="text-ink/40"> / {{ $quota['limit'] }}</span>

                                    @if ($member->blueprint_quota_refreshed_at)
                                        <p class="text-[11px] text-ink/40">
                                            reset {{ $member->blueprint_quota_refreshed_at->diffForHumans(short: true) }}
                                        </p>
                                    @endif
                                </td>

                                <td class="whitespace-nowrap px-6 py-4 text-ink/60">
                                    {{ $member->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-1">
                                        <form
                                            method="POST"
                                            action="{{ route('webcms.members.refresh-quota', $member) }}"
                                            data-confirm="Give {{ $member->name }} a fresh {{ $quota['limit'] }} Brand Blueprints for this month?"
                                        >
                                            @csrf
                                            <button
                                                type="submit"
                                                @disabled($quota['used'] === 0)
                                                class="grid h-9 w-9 place-items-center rounded-lg text-ink/50 transition-colors hover:bg-steel/60 hover:text-navy disabled:cursor-not-allowed disabled:opacity-30 disabled:hover:bg-transparent"
                                                title="{{ $quota['used'] === 0 ? 'Allowance is already full' : 'Refresh blueprint quota' }}"
                                                aria-label="Refresh quota for {{ $member->name }}"
                                            >
                                                <i data-lucide="refresh-cw" class="h-4 w-4"></i>
                                            </button>
                                        </form>

                                        <a
                                            href="{{ route('webcms.members.edit', $member) }}"
                                            class="grid h-9 w-9 place-items-center rounded-lg text-ink/50 transition-colors hover:bg-steel/60 hover:text-navy"
                                            aria-label="Edit {{ $member->name }}"
                                        >
                                            <i data-lucide="pencil" class="h-4 w-4"></i>
                                        </a>

                                        <form
                                            method="POST"
                                            action="{{ route('webcms.members.destroy', $member) }}"
                                            data-confirm="Delete {{ $member->name }}? This cannot be undone."
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="grid h-9 w-9 place-items-center rounded-lg text-ink/50 transition-colors hover:bg-danger/10 hover:text-danger"
                                                aria-label="Delete {{ $member->name }}"
                                            >
                                                <i data-lucide="trash-2" class="h-4 w-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($members->hasPages())
                <div class="border-t border-border/70 px-6 py-4">
                    {{ $members->links('webcms.pagination') }}
                </div>
            @endif
        @endif
    </x-webcms.card>
</x-webcms.layout>
