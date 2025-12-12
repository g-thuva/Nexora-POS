@extends('layouts.auth')

@section('content')
<div class="card card-md">
    <div class="card-body">
        <h2 class="h2 text-center mb-4">
            Login to your account
        </h2>

        <!-- Error Alert Banner -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible" role="alert">
                <div class="d-flex">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <circle cx="12" cy="12" r="9"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="alert-title">Login Failed!</h4>
                        <div class="text-muted">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Session Status Alert -->
        @if (session('status'))
            <div class="alert alert-success alert-dismissible" role="alert">
                <div class="d-flex">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M5 12l5 5l10 -10"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="alert-title">Success!</h4>
                        <div class="text-muted">{{ session('status') }}</div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Custom error messages for better UX -->
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible" role="alert">
                <div class="d-flex">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <circle cx="12" cy="12" r="9"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="alert-title">Authentication Error!</h4>
                        <div class="text-muted">{{ session('error') }}</div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" autocomplete="off">
            @csrf

            <x-input name="email" :value="old('email')" placeholder="your@email.com" required="true"/>

            <x-input type="password" name="password" placeholder="Your password" required="true"/>

            <div class="mb-2">
                <label for="remember" class="form-check">
                    <input type="checkbox" id="remember" name="remember" class="form-check-input"/>
                    <span class="form-check-label">Remember me on this device</span>
                </label>
            </div>

            <div class="form-footer">
                <x-button type="submit" class="w-100">
                    {{ __('Sign in') }}
                </x-button>
            </div>
        </form>
    </div>
</div>

<div class="text-center mt-3 text-gray-600">
    <p>Don't have an account yet?
        <a href="{{ route('register') }}" class="text-blue-500 hover:text-blue-700 focus:outline-none focus:underline" tabindex="-1">
            Sign up
        </a>
    </p>

    <p class="mt-2">
        <a href="{{ route('password.request') }}" class="text-sm text-gray-500 hover:text-gray-700 focus:outline-none focus:underline">
            I forgot my password
        </a>
    </p>
</div>

@endsection

@push('page-scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide success alerts after 5 seconds
    const successAlerts = document.querySelectorAll('.alert-success');
    successAlerts.forEach(function(alert) {
        setTimeout(function() {
            if (alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    });

    // Add form submission feedback
    const loginForm = document.querySelector('form[action*="login"]');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Signing in...';
                
                // Re-enable after 10 seconds in case of network issues
                setTimeout(function() {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = 'Sign in';
                    }
                }, 10000);
            }
        });
    }

    // Focus on email field if there are validation errors
    @if ($errors->any())
        const emailField = document.querySelector('input[name="email"]');
        if (emailField) {
            emailField.focus();
        }
    @endif
});
</script>
@endpush
