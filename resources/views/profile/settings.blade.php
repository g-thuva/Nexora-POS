@extends('layouts.nexora')

@section('content')
<div class="container-fluid px-4 mt-4">

    <nav class="nav nav-borders">
        <a class="nav-link ms-0" href="{{ route('profile.edit') }}">Profile</a>
        <a class="nav-link active" href="{{ route('profile.settings') }}">Settings</a>
    </nav>

    <hr class="mt-0 mb-5" />

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon alert-icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10"/></svg>
        <div>
            {{ session('success') }}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <div>
                        <h3 class="card-title">
                            {{ __('Change Password') }}
                        </h3>
                    </div>
                </div>

                <x-form action="{{ route('password.update') }}" method="PUT">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="current_password" class="form-label required">Current Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="current_password" name="current_password" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"/>
                                        <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label required">New Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"/>
                                        <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label required">Confirm Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"/>
                                        <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-end">
                        <x-button type="submit">{{ __('Save') }}</x-button>
                    </div>
                </x-form>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4" style="opacity: 0.6;">
                <div class="card-header">
                    Two-Factor Authentication
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        Two-factor authentication is currently unavailable. This feature is managed by your administrator.
                    </p>
                    <form>
                        <div class="form-check">
                            <input class="form-check-input" id="twoFactorOn" type="radio" name="twoFactor" disabled />
                            <label class="form-check-label text-muted" for="twoFactorOn">On</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" id="twoFactorOff" type="radio" name="twoFactor" checked="" disabled />
                            <label class="form-check-label text-muted" for="twoFactorOff">Off</label>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mb-4" style="opacity: 0.6; pointer-events: none;">
                <div class="card-header">
                    Delete Account
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        Account deletion is disabled. Please contact your administrator for account removal.
                    </p>
                    <button type="button" class="btn btn-secondary" disabled>
                        Account Deletion Disabled
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(fieldId) {
    const input = document.getElementById(fieldId);
    const button = input.nextElementSibling;
    const icon = button.querySelector('svg');

    if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = `
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M10.585 10.587a2 2 0 0 0 2.829 2.828"/>
            <path d="M16.681 16.673a8.717 8.717 0 0 1 -4.681 1.327c-3.6 0 -6.6 -2 -9 -6c1.272 -2.12 2.712 -3.678 4.32 -4.674m2.86 -1.146a9.055 9.055 0 0 1 1.82 -.18c3.6 0 6.6 2 9 6c-.666 1.11 -1.379 2.067 -2.138 2.87"/>
            <path d="M3 3l18 18"/>
        `;
    } else {
        input.type = 'password';
        icon.innerHTML = `
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"/>
            <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"/>
        `;
    }
}
</script>
@endsection
