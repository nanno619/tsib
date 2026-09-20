@props(['title' => null])

<!doctype html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    data-bs-theme-base="slate"
    data-bs-theme-primary="purple"
    data-bs-theme-radius="1"
>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />

        <title>{{ $title ? "{$title} - " . config('app.name') : config('app.name') }}</title>

        <link rel="icon" href="{{ asset('vendor/tabler/favicon.ico') }}" type="image/x-icon" />
        <link rel="shortcut icon" href="{{ asset('vendor/tabler/favicon.ico') }}" type="image/x-icon" />

        @vite(['resources/css/app.scss', 'resources/js/app.js'])
        @fonts
    </head>
    <body>
        {{-- Not part of the Vite bundle on purpose — see layouts.app for why. --}}
        <script src="{{ asset('js/tabler-theme.js') }}"></script>

        <main class="page page-center" id="content">
            <div class="container container-tight py-4">
                <div class="text-center mb-4">
                    <x-brand />
                </div>

                {{ $slot }}
            </div>
        </main>
    </body>
</html>
