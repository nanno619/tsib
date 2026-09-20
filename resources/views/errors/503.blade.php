<x-layouts.guest title="Temporarily down for maintenance">
    <x-empty
        heading-level="h1"
        title="Temporarily down for maintenance"
        subtitle="Sorry for the inconvenience but we are performing some maintenance at the moment. We will be back online shortly."
    >
        <x-slot:image>
            @include('errors.illustrations.maintenance')
        </x-slot:image>

        <x-slot:actions>
            <x-button href="{{ url('/') }}" color="primary" icon="arrow-left">Take me home</x-button>
        </x-slot:actions>
    </x-empty>
</x-layouts.guest>
