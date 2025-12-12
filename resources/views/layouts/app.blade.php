<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}" />

    <!-- Main CSS -->
    <link href="{{ asset('dist/css/nexora.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('css/custom-colors.css') }}" rel="stylesheet"/>
    @stack('page-styles')
    @livewireStyles
</head>
<body>
    <div class="page">
        @include('layouts.body.header')
        @include('layouts.body.navbar')

        <div class="page-wrapper">
            <div class="container-fluid py-4">
                @yield('content')
            </div>
        </div>

        @include('layouts.body.footer')
    </div>

    <script src="{{ asset('dist/js/nexora.min.js') }}"></script>
    @stack('page-scripts')
    @livewireScripts
</body>
</html>
