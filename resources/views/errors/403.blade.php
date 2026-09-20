<x-layouts.guest title="Access denied">
    <x-empty
        heading-level="h1"
        title="Access denied"
        subtitle="You don't have permission to view this page."
    >
        <x-slot:image>
            @include('errors.illustrations.client-error')
        </x-slot:image>

        <x-slot:actions>
            <x-button href="{{ url('/') }}" color="primary" icon="arrow-left">Take me home</x-button>
        </x-slot:actions>
    </x-empty>
</x-layouts.guest>
