@extends('layouts.nexora')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Profile
                </h2>
                <div class="text-muted mt-1">Manage your profile information and settings</div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <x-alert/>

        <div class="row row-cards">
            <!-- Profile Picture Card -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body text-center">
                        <img
                            class="rounded-circle mb-3 shadow-sm mx-auto d-block"
                            src="{{ $user->photo ? asset('storage/profile/'.$user->photo).'?t='.time() : asset('assets/img/demo/user-placeholder.svg') }}"
                            id="image-preview"
                            style="width: 150px; height: 150px; object-fit: cover; object-position: center; border: 4px solid #e5e7eb;"
                        />
                        <h3 class="mb-1">{{ $user->name }}</h3>
                        <div class="text-muted mb-2">{{ $user->getRoleDisplayName() }}</div>
                        <div class="mb-3">
                            <span class="badge bg-blue-lt">{{ $user->email }}</span>
                        </div>

                        <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data" id="photoForm">
                            @csrf
                            @method('patch')
                            <input type="hidden" name="photo_only" value="1">
                            <div class="mb-3">
                                <label for="image" class="btn btn-primary w-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
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
                                >
                                @error('photo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="form-hint text-center d-block mt-2">Max file size: 5MB</small>
                            </div>
                        </form>

                        @if($user->shop)
                        <div class="mt-4 pt-3 border-top">
                            <div class="text-muted small mb-1">Shop</div>
                            <div class="fw-bold">{{ $user->shop->name }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Profile Information Card -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Profile Information</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('user.profile.update') }}" method="POST">
                            @csrf
                            @method('patch')

                            <div class="row mb-3">
                                <div class="col-md-12">
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
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Username</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        value="{{ $user->username }}"
                                        readonly
                                        disabled
                                        style="background-color: #e9ecef; cursor: not-allowed;"
                                    >
                                    <small class="form-hint">Username cannot be changed</small>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Email Address</label>
                                    <input
                                        type="email"
                                        class="form-control"
                                        value="{{ $user->email }}"
                                        readonly
                                        disabled
                                        style="background-color: #e9ecef; cursor: not-allowed;"
                                    >
                                    @if($user->email_verified_at)
                                    <small class="form-hint text-success">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M5 12l5 5l10 -10" />
                                        </svg>
                                        Email verified - cannot be changed
                                    </small>
                                    @else
                                    <small class="form-hint">Email cannot be changed</small>
                                    @endif
                                </div>
                            </div>

                            <div class="card-footer text-end">
                                <button type="submit" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M5 12l5 5l10 -10" />
                                    </svg>
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Change Password Card -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Change Password</h3>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">For security purposes, please contact your administrator to change your password.</p>
                        <a href="{{ route('profile.settings') }}" class="btn btn-outline-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3" />
                                <circle cx="12" cy="11" r="1" />
                                <line x1="12" y1="12" x2="12" y2="14.5" />
                            </svg>
                            Account Settings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('page-scripts')
<script>
    function previewImage() {
        const image = document.querySelector('#image');
        const imgPreview = document.querySelector('#image-preview');

        imgPreview.style.display = 'block';

        const oFReader = new FileReader();
        oFReader.readAsDataURL(image.files[0]);

        oFReader.onload = function(oFREvent) {
            imgPreview.src = oFREvent.target.result;
        }
    }
</script>
@endpush
@endsection
