@extends('layouts.admin')

@section('content')
<div class="page-header d-print-none">
    <div class="container-fluid">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Admin Panel
                </div>
                <h2 class="page-title">
                    Create New User
                </h2>
            </div>
            <div class="col-12 col-md-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-ghost-dark">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="m0 0h24v24H0z" fill="none"/>
                            <path d="M5 12l-2 0l9 -9l9 9l-2 0"/>
                            <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7"/>
                            <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6"/>
                        </svg>
                        Back to Users
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                <div class="d-flex">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="m0 0h24v24H0z" fill="none"/>
                            <path d="m5 12l5 5l10 -10"/>
                        </svg>
                    </div>
                    <div>{{ session('success') }}</div>
                </div>
                <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible" role="alert">
                <div class="d-flex">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="m0 0h24v24H0z" fill="none"/>
                            <path d="m12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"/>
                            <path d="m9 12l2 2l4 -4"/>
                        </svg>
                    </div>
                    <div>{{ session('error') }}</div>
                </div>
                <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
        @endif

        <div class="row row-deck row-cards">
            <div class="col-12">
                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">User Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Full Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               name="name" value="{{ old('name') }}" placeholder="Enter full name" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Username</label>
                                        <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                               name="username" value="{{ old('username') }}" placeholder="Enter username" required>
                                        @error('username')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Username must be unique</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Email Address</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                               name="email" value="{{ old('email') }}" placeholder="Enter email address" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">User Role</label>
                                        <select class="form-select @error('role') is-invalid @enderror" name="role" required onchange="updateOwnershipWarning()">
                                            <option value="">Select Role</option>
                                            <option value="employee" {{ old('role') == 'employee' ? 'selected' : '' }}>Employee</option>
                                            <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                                            <option value="shop_owner" {{ old('role') == 'shop_owner' ? 'selected' : '' }}>Shop Owner</option>
                                        </select>
                                        @error('role')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Password</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                               name="password" placeholder="Enter password" required>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Password must be at least 8 characters</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Confirm Password</label>
                                        <input type="password" class="form-control" 
                                               name="password_confirmation" placeholder="Confirm password" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Shop Assignment</label>
                                        <select class="form-select @error('shop_id') is-invalid @enderror" name="shop_id">
                                            <option value="">No Shop (Unassigned)</option>
                                            @foreach($shops as $shop)
                                                <option value="{{ $shop->id }}" {{ old('shop_id') == $shop->id ? 'selected' : '' }}>
                                                    {{ $shop->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('shop_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Select the shop to assign this user to</div>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-info" id="ownershipWarning" style="display: none;">
                                <strong>Note:</strong> Making this user a shop owner will demote the current owner to manager.
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <div class="d-flex">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-ghost-dark me-auto">Cancel</a>
                                <button type="submit" class="btn btn-primary">Create User</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page-scripts')
<script>
function updateOwnershipWarning() {
    const roleSelect = document.querySelector('select[name="role"]');
    const warningDiv = document.getElementById('ownershipWarning');
    
    if (roleSelect.value === 'shop_owner') {
        warningDiv.style.display = 'block';
    } else {
        warningDiv.style.display = 'none';
    }
}

// Initialize warning on page load
document.addEventListener('DOMContentLoaded', function() {
    updateOwnershipWarning();
});
</script>
@endpush