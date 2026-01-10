
@extends('layouts.nexora')

@section('content')
<!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-fluid">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Account</div>
                <h2 class="page-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-2">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"/>
                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/>
                    </svg>
                    Profile Settings
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('dashboard') }}" class="btn btn-white btn-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M9 14l-4 -4l4 -4"/>
                            <path d="M5 10h11a4 4 0 1 1 0 8h-1"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Page body -->
<div class="page-body">
    <div class="container-fluid">
        <x-alert/>

        <div class="row row-deck row-cards">
            <!-- Profile Picture Card -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <div class="position-relative d-inline-block">
                                <img
                                    class="avatar avatar-xl rounded-circle shadow"
                                    src="{{ $user->photo ? asset('storage/profile/'.$user->photo).'?t='.time() : asset('assets/img/demo/user-placeholder.svg') }}"
                                    id="image-preview"
                                    style="width: 120px; height: 120px; object-fit: cover;"
                                />
                                <span class="badge bg-success position-absolute" style="bottom: 10px; right: 10px; width: 12px; height: 12px; padding: 0; border: 2px solid white; border-radius: 50%;"></span>
                            </div>
                        </div>
                        <h3 class="m-0">{{ $user->name }}</h3>
                        <div class="text-muted mb-3">{{ $user->username }}</div>
                        <span class="badge bg-primary-lt mb-3">{{ $user->getRoleDisplayName() }}</span>

                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="photoForm">
                            @csrf
                            @method('patch')
                            <input type="hidden" name="photo_only" value="1">

                            <div class="mb-3">
                                <label for="image" class="btn btn-white w-100 cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-2">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M15 8h.01"/>
                                        <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z"/>
                                        <path d="M3.5 15.5l4.5 -4.5c.928 -.893 2.072 -.893 3 0l5 5"/>
                                        <path d="M14 14l1 -1c.928 -.893 2.072 -.893 3 0l2.5 2.5"/>
                                    </svg>
                                    Choose Photo
                                </label>
                                <input
                                    class="form-control d-none @error('photo') is-invalid @enderror"
                                    type="file"
                                    id="image"
                                    name="photo"
                                    accept="image/*"
                                    onchange="previewImage(); this.form.submit();"
                                />
                                @error('photo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="text-muted small">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M12 9v4"/>
                                    <path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z"/>
                                    <path d="M12 16h.01"/>
                                </svg>
                                Max file size: 5MB
                            </div>
                        </form>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col">
                                <div class="text-truncate">
                                    <strong>Email</strong>
                                </div>
                                <div class="text-muted small text-truncate">{{ $user->email }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats Card -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Account Information</h3>
                    </div>
                    <div class="list-group list-group-flush">
                        <div class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="avatar avatar-sm rounded" style="background-color: #206bc4;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-white">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"/>
                                            <path d="M12 7v5l3 3"/>
                                        </svg>
                                    </span>
                                </div>
                                <div class="col text-truncate">
                                    <div class="text-reset d-block">Member Since</div>
                                    <div class="text-muted small">{{ $user->created_at->format('M d, Y') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="avatar avatar-sm rounded" style="background-color: #2fb344;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-white">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"/>
                                            <path d="M9 12l2 2l4 -4"/>
                                        </svg>
                                    </span>
                                </div>
                                <div class="col text-truncate">
                                    <div class="text-reset d-block">Status</div>
                                    <div class="text-muted small">
                                        @if($user->email_verified_at)
                                            <span class="badge bg-success-lt">Verified</span>
                                        @else
                                            <span class="badge bg-warning-lt">Unverified</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Form Card -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-2">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                                <path d="M16 5l3 3"/>
                            </svg>
                            Personal Information
                        </h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            @method('patch')

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label required">Full Name</label>
                                    <input
                                        type="text"
                                        class="form-control @error('name') is-invalid @enderror"
                                        id="name"
                                        name="name"
                                        value="{{ old('name', $user->name) }}"
                                        placeholder="Enter your full name"
                                        required
                                    >
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="username" class="form-label required">Username</label>
                                    <div class="input-group">
                                        <span class="input-group-text">@</span>
                                        <input
                                            type="text"
                                            class="form-control @error('username') is-invalid @enderror"
                                            id="username"
                                            name="username"
                                            value="{{ old('username', $user->username) }}"
                                            placeholder="username"
                                            required
                                        >
                                        @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-hint">4-25 characters, letters, numbers, dashes and underscores only</div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input
                                    type="email"
                                    class="form-control"
                                    id="email"
                                    value="{{ $user->email }}"
                                    readonly
                                    disabled
                                    tabindex="-1"
                                    style="background-color: #e9ecef; cursor: not-allowed; opacity: 0.7; pointer-events: none;"
                                >
                                <div class="form-hint">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M12 9v4"/>
                                        <path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z"/>
                                        <path d="M12 16h.01"/>
                                    </svg>
                                    Email address cannot be changed for security reasons.
                                </div>
                            </div>

                            <div class="card mt-4">
                                <div class="card-status-top bg-blue"></div>
                                <div class="card-body">
                                    <h4 class="mb-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-2">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"/>
                                            <path d="M12 9h.01"/>
                                            <path d="M11 12h1v4h1"/>
                                        </svg>
                                        Profile Information
                                    </h4>
                                    <p class="text-muted mb-2">You can update your name and username. Your email address is locked for security reasons.</p>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2"/>
                                        <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/>
                                        <path d="M14 4l0 4l-6 0l0 -4"/>
                                    </svg>
                                    Save Changes
                                </button>
                                <a href="{{ route('dashboard') }}" class="btn btn-link">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Password Change Card -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-2">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M5 13a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-6z"/>
                                <path d="M11 16a1 1 0 1 0 2 0a1 1 0 0 0 -2 0"/>
                                <path d="M8 11v-4a4 4 0 1 1 8 0v4"/>
                            </svg>
                            Change Password
                        </h3>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Want to change your password?</p>
                        <a href="{{ route('profile.settings') }}" class="btn btn-outline-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z"/>
                                <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"/>
                            </svg>
                            Go to Account Settings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page-scripts')
    <script src="{{ asset('assets/js/img-preview.js') }}"></script>
@endpush
