<x-layouts.guest title="Too many requests">
    <x-empty
        heading-level="h1"
        title="Too many requests"
        subtitle="You have made too many requests in a short time. Please wait a moment and try again."
    >
        <x-slot:image>
            @include('errors.illustrations.client-error')
        </x-slot:image>

        <x-slot:actions>
            <x-button href="{{ url('/') }}" color="primary" icon="arrow-left">Take me home</x-button>
        </x-slot:actions>
    </x-empty>
</x-layouts.guest>
