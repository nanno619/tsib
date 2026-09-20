<x-layouts.guest title="Something went wrong">
    <x-empty
        heading-level="h1"
        title="Something went wrong"
        subtitle="We are sorry but our server encountered an internal error."
    >
        <x-slot:image>
            @include('errors.illustrations.server-error')
        </x-slot:image>

        <x-slot:actions>
            <x-button href="{{ url('/') }}" color="primary" icon="arrow-left">Take me home</x-button>
        </x-slot:actions>
    </x-empty>
</x-layouts.guest>
