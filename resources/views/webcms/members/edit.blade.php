<x-webcms.layout
    title="Edit member"
    heading="Edit member"
    :subheading="$member->name"
>
    <x-webcms.card class="p-6 md:p-8">
        <form method="POST" action="{{ route('webcms.members.update', $member) }}">
            @include('webcms.members._form')
        </form>
    </x-webcms.card>
</x-webcms.layout>
