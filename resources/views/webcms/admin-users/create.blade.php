<x-webcms.layout
    title="Add admin"
    heading="Add admin user"
    subheading="Give a colleague access to this CMS."
>
    <x-webcms.card class="p-6 md:p-8">
        <form method="POST" action="{{ route('webcms.admin-users.store') }}">
            @include('webcms.admin-users._form')
        </form>
    </x-webcms.card>
</x-webcms.layout>
