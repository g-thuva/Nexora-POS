<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>{{ config('app.name') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}" />
    <link rel="alternate icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
    <!-- CSS files -->
    <link href="{{ asset('dist/css/nexora.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('css/custom-colors.css') }}" rel="stylesheet"/>
    <style>
        @import url('https://rsms.me/inter/inter.css');
        :root {
            --nexora-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }
        body {
            font-feature-settings: "cv03", "cv04", "cv11";
            color: #000 !important;
        }

        /* Ensure all text is black for better visibility */
        * {
            color: #000 !important;
        }

        /* Exception for colored elements */
        .text-blue-500, .text-blue-700, .btn-primary {
            color: #3b82f6 !important;
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
    </style>

    <!-- Custom CSS for specific page.  -->
    @stack('page-styles')

    <style>
        /* Enhanced Alert Styling */
        .alert {
            border-radius: 8px;
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .alert-danger {
            background-color: #fef2f2;
            color: #dc2626 !important;
            border-left: 4px solid #dc2626;
        }

        .alert-success {
            background-color: #f0fdf4;
            color: #16a34a !important;
            border-left: 4px solid #16a34a;
        }

        .alert-title {
            color: inherit !important;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .alert .text-muted {
            color: inherit !important;
            opacity: 0.8;
        }

        .alert-icon {
            color: inherit !important;
        }

        .btn-close {
            color: inherit !important;
        }

        /* Login Form Enhancements */
        .card {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border: none;
            border-radius: 12px;
        }

        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
        }

        /* Preloader Styles */
        #preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #ffffff;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            transition: opacity 0.3s ease-out;
        }

        #preloader.fade-out {
            opacity: 0;
            pointer-events: none;
        }

        .preloader-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #f3f4f6;
            border-top: 4px solid #3b82f6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .preloader-text {
            margin-top: 20px;
            color: #6b7280;
            font-size: 14px;
            font-weight: 500;
        }

        /* Prevent content from showing during load */
        body.loading .page {
            opacity: 0;
        }
    </style>
</head>

<body class="d-flex flex-column loading">
    <!-- Preloader -->
    <div id="preloader">
        <div class="preloader-spinner"></div>
        <div class="preloader-text">Loading...</div>
    </div>
    <div class="page page-center">
        <div class="container container-tight py-4">
            <div class="text-center mb-4">
                <a href="{{ url('/') }}" class="navbar-brand navbar-brand-autodark d-flex align-items-center justify-content-center">
                    <span class="navbar-brand-text fs-3 fw-bold text-dark">NexoraLabs</span>
                </a>
            </div>

            <!-- BEGIN: Content -->
            @yield('content')
            <!-- END: Content -->
        </div>
    </div>

    <!-- Preloader Script -->
    <script>
        // Hide preloader when page is fully loaded
        window.addEventListener('load', function() {
            const preloader = document.getElementById('preloader');
            const body = document.body;

            // Add fade-out class
            preloader.classList.add('fade-out');
            body.classList.remove('loading');

            // Remove preloader from DOM after transition
            setTimeout(function() {
                preloader.style.display = 'none';
            }, 300);
        });

        // Fallback: Hide preloader after 2 seconds (faster for auth pages)
        setTimeout(function() {
            const preloader = document.getElementById('preloader');
            if (preloader && !preloader.classList.contains('fade-out')) {
                preloader.classList.add('fade-out');
                document.body.classList.remove('loading');
                setTimeout(function() {
                    preloader.style.display = 'none';
                }, 300);
            }
        }, 2000);
    </script>

    <!-- Libs JS -->
    <script src="{{ asset('dist/js/nexora.min.js') }}" defer></script>

    <!-- Custom JS for specific page.  -->
    @stack('page-scripts')
</body>
</html>
