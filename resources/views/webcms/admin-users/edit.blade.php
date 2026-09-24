<x-webcms.layout
    title="Edit admin"
    heading="Edit admin user"
    :subheading="$adminUser->name"
>
    <x-webcms.card class="p-6 md:p-8">
        <form method="POST" action="{{ route('webcms.admin-users.update', $adminUser) }}">
            @include('webcms.admin-users._form')
        </form>
    </x-webcms.card>
</x-webcms.layout>
