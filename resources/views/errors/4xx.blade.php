{{-- Fallback for any 4xx without a dedicated view (400, 401, 405, 410, …).
     Laravel picks errors/{code}.blade.php first, then errors/4xx.blade.php. --}}
<x-layouts.guest title="Bad request">
    <x-empty
        heading-level="h1"
        title="That request could not be processed"
        subtitle="Something about the request was not right. Check the address and try again."
    >
        <x-slot:image>
            @include('errors.illustrations.client-error')
        </x-slot:image>

        <x-slot:actions>
            <x-button href="{{ url('/') }}" color="primary" icon="arrow-left">Take me home</x-button>
        </x-slot:actions>
    </x-empty>
</x-layouts.guest>
