
@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 mt-4">
    <x-alert/>
    <div class="row">
        <!-- Sidebar/Profile Card -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0 text-center p-4">
                <img
                    class="rounded-circle mx-auto mb-3 shadow"
                    src="{{ $user->photo ? asset('storage/profile/'.$user->photo) : asset('assets/img/demo/user-placeholder.svg') }}"
                    id="image-preview"
                    style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #e5e7eb;"
                />
                <h4 class="mb-0">{{ $user->name }}</h4>
                <div class="text-muted mb-2">{{ $user->getRoleDisplayName() }}</div>
                <div class="mb-2">
                    <span class="badge bg-primary-lt text-primary">{{ $user->email }}</span>
                </div>
                <div class="mb-3">
                    <a href="{{ route('profile.settings') }}" class="btn btn-outline-secondary btn-sm">Account Settings</a>
                </div>
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('patch')
                    <div class="mb-2">
                        <input class="form-control @error('photo') is-invalid @enderror" type="file"  id="image" name="photo" accept="image/*" onchange="previewImage();">
                        @error('photo')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm w-100">Update Photo</button>
                </form>
            </div>
        </div>
        <!-- Main Profile Form -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom-0 pb-0">
                    <h3 class="card-title mb-0">Profile Information</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('patch')
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username', $user->username) }}" required>
                            @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-success">Save Changes</button>
                            <a href="{{ route('dashboard') }}" class="btn btn-link">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page-scripts')
    <script src="{{ asset('assets/js/img-preview.js') }}"></script>
@endpush
