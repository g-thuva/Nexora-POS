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
                            class="rounded-circle mb-3 shadow-sm"
                            src="{{ $user->photo ? asset('storage/profile/'.$user->photo) : asset('assets/img/demo/user-placeholder.svg') }}"
                            id="image-preview"
                            style="width: 150px; height: 150px; object-fit: cover; border: 4px solid #e5e7eb;"
                        />
                        <h3 class="mb-1">{{ $user->name }}</h3>
                        <div class="text-muted mb-2">{{ $user->getRoleDisplayName() }}</div>
                        <div class="mb-3">
                            <span class="badge bg-blue-lt">{{ $user->email }}</span>
                        </div>
                        
                        <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('patch')
                            <div class="mb-3">
                                <label for="image" class="form-label">Update Profile Photo</label>
                                <input 
                                    class="form-control @error('photo') is-invalid @enderror" 
                                    type="file"  
                                    id="image" 
                                    name="photo" 
                                    accept="image/*" 
                                    onchange="previewImage();"
                                >
                                @error('photo')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                    <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                    <path d="M16 5l3 3" />
                                </svg>
                                Update Photo
                            </button>
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
                                    <label for="username" class="form-label required">Username</label>
                                    <input 
                                        type="text" 
                                        class="form-control @error('username') is-invalid @enderror" 
                                        id="username" 
                                        name="username" 
                                        value="{{ old('username', $user->username) }}"
                                        required
                                    >
                                    @error('username')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                    <small class="form-hint">Must be 4-25 characters, alphanumeric and dashes only</small>
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label required">Email Address</label>
                                    <input 
                                        type="email" 
                                        class="form-control @error('email') is-invalid @enderror" 
                                        id="email" 
                                        name="email" 
                                        value="{{ old('email', $user->email) }}"
                                        required
                                    >
                                    @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                    @if($user->email_verified_at)
                                    <small class="form-hint text-success">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M5 12l5 5l10 -10" />
                                        </svg>
                                        Verified
                                    </small>
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
