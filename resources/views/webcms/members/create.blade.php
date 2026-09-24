<x-webcms.layout
    title="Add member"
    heading="Add member"
    subheading="Create a new Visionyr member account."
>
    <x-webcms.card class="p-6 md:p-8">
        <form method="POST" action="{{ route('webcms.members.store') }}">
            @include('webcms.members._form')
        </form>
    </x-webcms.card>
</x-webcms.layout>
