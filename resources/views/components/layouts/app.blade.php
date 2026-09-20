@props(['title' => null])

<!doctype html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    data-bs-theme-base="slate"
    data-bs-theme-primary="purple"
    data-bs-theme-radius="1"
    data-bs-navbar="sticky"
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

        {{ $styles ?? '' }}
    </head>
    <body>
        <a href="#content" class="visually-hidden-focusable skip-link">Skip to main content</a>

        {{-- Not part of the Vite bundle on purpose: @vite() emits a
        type="module" script, which the HTML spec defers until after the
        document parses — too late to set the theme before first paint. --}}
        <script src="{{ asset('js/tabler-theme.js') }}"></script>

        <div class="page">
            @include('layouts.partials.navbar')

            <div class="page-wrapper">
                {{ $header ?? '' }}

                <div class="page-body">
                    <div class="container-xl">
                        {{ $slot }}
                    </div>
                </div>

                @include('layouts.partials.footer')
            </div>
        </div>

        {{-- One shared confirmation dialog for every "are you sure?" in the
             app — see resources/js/confirm.js. Outside .page on purpose: a
             fixed-position modal nested in a transformed or overflowing
             ancestor can be clipped. --}}
        <x-confirm-modal />

        {{ $scripts ?? '' }}
    </body>
</html>
