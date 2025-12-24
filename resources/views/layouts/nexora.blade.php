
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}" />
    <link rel="alternate icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />

    <!-- CSS files -->
    <link href="{{ asset('dist/css/nexora.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('css/custom-colors.css') }}" rel="stylesheet"/>
    <link href="{{ asset('css/mobile-responsive.css') }}" rel="stylesheet"/>
    <style>
        @import url('https://rsms.me/inter/inter.css');
        :root {
            --nexora-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }
        body {
            font-feature-settings: "cv03", "cv04", "cv11";
        }

        /* Brand styling */
        .navbar-brand-text {
            color: #1f2937 !important;
            text-decoration: none !important;
        }

        .navbar-brand:hover .navbar-brand-text {
            color: #1f2937 !important;
            text-decoration: none !important;
        }

        /* Fix navbar dropdown visibility */
        .navbar-expand-md {
            overflow: visible !important;
        }

        .navbar-collapse {
            overflow: visible !important;
        }

        .navbar {
            overflow: visible !important;
        }

        /* Remove all space constraints from page containers */
        .page {
            overflow: visible !important;
            height: auto !important;
            min-height: 100vh !important;
        }

        .page-wrapper {
            overflow: visible !important;
            height: auto !important;
        }

        .page-body {
            overflow: visible !important;
            height: auto !important;
        }

        /* Ensure navbar and header don't have height constraints */
        header.navbar,
        header.navbar-expand-md {
            overflow: visible !important;
            height: auto !important;
            max-height: none !important;
            border: none !important;
        }

        /* Remove container constraints */
        .container-xxl {
            max-width: 100% !important;
            padding-left: 1.5rem !important;
            padding-right: 1.5rem !important;
        }

        /* Remove borders from navbar elements */
        .navbar,
        .navbar-collapse {
            border: none !important;
        }

        .navbar .dropdown-menu {
            position: absolute !important;
            max-height: none !important;
            overflow-y: visible !important;
            box-shadow: 0 4px 16px rgba(0,0,0,0.15) !important;
            border: 1px solid #e6e8ea !important;
            z-index: 1030 !important;
            background-color: #ffffff !important;
            backdrop-filter: blur(10px) !important;
        }

        .navbar .dropdown-menu-columns {
            display: flex !important;
            flex-wrap: nowrap !important;
            min-width: 500px !important;
        }

        .navbar .dropdown-menu-column {
            flex: 1 1 auto !important;
            min-width: 200px !important;
            padding: 0.5rem !important;
        }

        /* Fix header user dropdown visibility */
        header.navbar .nav-item.dropdown {
            position: static !important;
        }

        header.navbar .dropdown-menu {
            z-index: 1050 !important;
        }

        header.navbar .navbar-nav {
            position: static !important;
        }
    </style>

    <!-- Custom CSS for specific page.  -->
    @stack('page-styles')
    @livewireStyles
</head>
    <body>

        <div class="page">

            @include('layouts.body.header')

            @include('layouts.body.navbar')

            <div class="page-wrapper">
                <div class="page-body">
                    <div class="container-xxl">
                        @yield('content')
                    </div>
                </div>

                @include('layouts.body.footer')
            </div>
        </div>

        <!-- Nexora Core -->
        <script src="{{ asset('dist/js/nexora.min.js') }}" defer></script>
        {{--- Page Scripts ---}}
        @stack('page-scripts')

        @livewireScripts
    </body>
</html>
