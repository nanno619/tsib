<x-layouts.guest title="Page not found">
    <x-empty
        heading-level="h1"
        title="Page not found"
        subtitle="We are sorry but the page you are looking for was not found."
    >
        <x-slot:image>
            @include('errors.illustrations.client-error')
        </x-slot:image>

        <x-slot:actions>
            <x-button href="{{ url('/') }}" color="primary" icon="arrow-left">Take me home</x-button>
        </x-slot:actions>
    </x-empty>
</x-layouts.guest>
