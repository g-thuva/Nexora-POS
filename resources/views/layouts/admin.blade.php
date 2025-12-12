<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - Admin Panel</title>
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
        
        /* Admin specific styling */
        .admin-navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .admin-navbar .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
        }
        
        .admin-navbar .nav-link:hover {
            color: #ffffff !important;
        }
        
        .admin-navbar .nav-link.active {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 0.375rem;
        }
        
        .admin-badge {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            font-size: 0.7rem;
            padding: 0.2rem 0.5rem;
            border-radius: 0.5rem;
            margin-left: 0.5rem;
        }
    </style>

    <!-- Custom CSS for specific page.  -->
    @stack('page-styles')
    @livewireStyles
</head>
<body>

    <div class="page">

        @include('layouts.body.header')

        @include('layouts.body.admin-navbar')

        <div class="page-wrapper">
            <div>
                @yield('content')
            </div>
        </div>

        @include('layouts.body.footer')

    </div>

    <!-- JS files -->
    <script src="{{ asset('dist/js/nexora.min.js') }}"></script>
    <script src="{{ asset('js/plugins/lottiefiles.js') }}"></script>

    <!-- Custom JS for specific page.  -->
    @stack('page-scripts')
    @livewireScripts
    
</body>
</html>