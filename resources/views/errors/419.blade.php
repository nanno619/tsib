<x-layouts.guest title="Page expired">
    <x-empty
        heading-level="h1"
        title="Page expired"
        subtitle="Your session has expired. Go back, refresh the page, and try again."
    >
        <x-slot:image>
            @include('errors.illustrations.client-error')
        </x-slot:image>

        <x-slot:actions>
            <x-button href="{{ url('/') }}" color="primary" icon="arrow-left">Take me home</x-button>
        </x-slot:actions>
    </x-empty>
</x-layouts.guest>
