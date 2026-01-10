<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Account Suspended - {{ config('app.name') }}</title>
    <link href="{{ asset('dist/css/tabler.min.css') }}" rel="stylesheet"/>
    <style>
        @import url('https://rsms.me/inter/inter.css');
        :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }
        body {
            font-feature-settings: "cv03", "cv04", "cv11";
        }
    </style>
</head>
<body class="d-flex flex-column">
    <div class="page page-center">
        <div class="container container-tight py-4">
            <div class="text-center mb-4">
                <a href="/" class="navbar-brand navbar-brand-autodark">
                    <h1>{{ config('app.name', 'NexoraLabs') }}</h1>
                </a>
            </div>

            <div class="card card-md">
                <div class="card-status-top bg-danger"></div>
                <div class="card-body text-center py-5">
                    <!-- Suspension Icon -->
                    <div class="text-danger mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-lock-access" width="100" height="100" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M4 8v-2a2 2 0 0 1 2 -2h2"/>
                            <path d="M4 16v2a2 2 0 0 0 2 2h2"/>
                            <path d="M16 4h2a2 2 0 0 1 2 2v2"/>
                            <path d="M16 20h2a2 2 0 0 0 2 -2v-2"/>
                            <path d="M8 11m0 1a1 1 0 0 1 1 -1h6a1 1 0 0 1 1 1v3a1 1 0 0 1 -1 1h-6a1 1 0 0 1 -1 -1z"/>
                            <path d="M10 11v-2a2 2 0 1 1 4 0v2"/>
                        </svg>
                    </div>

                    <!-- Title -->
                    <h1 class="text-danger mb-3">Account Suspended</h1>

                    <!-- User Info -->
                    <div class="mb-4">
                        <div class="avatar avatar-xl mb-3" style="font-size: 2rem;">
                            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}
                        </div>
                        <div class="h3">{{ Auth::user()->name ?? 'User' }}</div>
                        <div class="text-muted">{{ Auth::user()->email ?? '' }}</div>
                    </div>

                    <!-- Suspension Details -->
                    <div class="alert alert-danger text-start mb-4">
                        <div class="d-flex">
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M12 9v4"/>
                                    <path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z"/>
                                    <path d="M12 16h.01"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="alert-title">Reason for Suspension:</h4>
                                <div class="text-secondary">
                                    {{ $suspensionReason ?? 'Your account has been suspended by an administrator.' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Suspension Type Information -->
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <div class="text-muted mb-1">Suspension Type</div>
                                    <div class="h3 mb-0 text-capitalize">
                                        {{ str_replace('_', ' ', $suspensionType ?? 'N/A') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <div class="text-muted mb-1">Suspended On</div>
                                    <div class="h3 mb-0">
                                        {{ $suspendedAt ? $suspendedAt->format('M d, Y') : 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($suspensionEndsAt)
                    <div class="alert alert-info mb-4">
                        <div class="d-flex">
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"/>
                                    <path d="M12 7v5l3 3"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="alert-title">Suspension Ends:</h4>
                                <div class="text-secondary">
                                    <strong>{{ $suspensionEndsAt->format('F d, Y \a\t H:i') }}</strong>
                                    <br>
                                    <small>({{ $suspensionEndsAt->diffForHumans() }})</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    @elseif($suspensionType === 'lifetime')
                    <div class="alert alert-danger mb-4">
                        <strong>Permanent Ban:</strong> This suspension is permanent and requires administrator intervention to be lifted.
                    </div>
                    @elseif($suspensionType === 'until_payment')
                    <div class="alert alert-warning mb-4">
                        <strong>Payment Required:</strong> Your account will be reactivated once payment is received. Please contact support.
                    </div>
                    @elseif($suspensionType === 'manual')
                    <div class="alert alert-warning mb-4">
                        <strong>Manual Review:</strong> Your account is under review. An administrator will evaluate your case.
                    </div>
                    @endif

                    <!-- Contact Information -->
                    <div class="text-muted mb-4">
                        <p class="mb-2">If you believe this is an error or would like to appeal this suspension, please contact support.</p>
                        <small>Support Email: <strong>support@nexoralabs.com</strong></small>
                    </div>

                    <!-- Logout Button -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-lg w-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2"/>
                                <path d="M9 12h12l-3 -3"/>
                                <path d="M18 15l3 -3"/>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>

            <div class="text-center text-muted mt-3">
                <small>© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</small>
            </div>
        </div>
    </div>

    <script src="{{ asset('dist/js/tabler.min.js') }}"></script>
</body>
</html>
