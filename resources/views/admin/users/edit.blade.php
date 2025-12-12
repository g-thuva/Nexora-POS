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
                    Edit User: {{ $user->name }}
                </h2>
            </div>
            <div class="col-12 col-md-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-ghost-dark">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="m0 0h24v24H0z" fill="none"/>
                            <circle cx="12" cy="12" r="2"/>
                            <path d="m12 1c.835 0 1.52 .205 2.05 .623a3.441 3.441 0 0 1 1.343 3.146c.096 .443 -.071 .884 -.334 1.317l-2.059 3.914l-2.059 -3.914c-.263 -.433 -.43 -.874 -.334 -1.317a3.441 3.441 0 0 1 1.343 -3.146c.53 -.418 1.215 -.623 2.05 -.623z"/>
                        </svg>
                        View Profile
                    </a>
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
                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf
                    @method('PUT')
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Edit User Information</h3>
                            <div class="card-actions">
                                <div class="d-flex align-items-center">
                                    <span class="avatar me-2">{{ substr($user->name, 0, 2) }}</span>
                                    <div>
                                        <div class="font-weight-medium">{{ $user->name }}</div>
                                        <div class="text-muted">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Full Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                               name="name" value="{{ old('name', $user->name) }}" placeholder="Enter full name" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Email Address</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                               name="email" value="{{ old('email', $user->email) }}" placeholder="Enter email address" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">User Role</label>
                                        <select class="form-select @error('role') is-invalid @enderror" name="role" required onchange="updateOwnershipWarning()">
                                            @if($user->isSuperAdmin())
                                                <option value="super_admin" selected>Super Admin</option>
                                            @else
                                                <option value="employee" {{ old('role', $user->role) == 'employee' ? 'selected' : '' }}>Employee</option>
                                                <option value="manager" {{ old('role', $user->role) == 'manager' ? 'selected' : '' }}>Manager</option>
                                                <option value="shop_owner" {{ old('role', $user->role) == 'shop_owner' ? 'selected' : '' }}>Shop Owner</option>
                                                <option value="suspended" {{ old('role', $user->role) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                            @endif
                                        </select>
                                        @error('role')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        @if($user->isSuperAdmin())
                                            <div class="form-text text-muted">Super Admin role cannot be changed</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Shop Assignment</label>
                                        <select class="form-select @error('shop_id') is-invalid @enderror" name="shop_id" {{ $user->isSuperAdmin() ? 'disabled' : '' }}>
                                            <option value="">No Shop (Unassigned)</option>
                                            @foreach($shops as $shop)
                                                <option value="{{ $shop->id }}" {{ old('shop_id', $user->shop_id) == $shop->id ? 'selected' : '' }}>
                                                    {{ $shop->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('shop_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Select the shop to assign this user to</div>
                                        @if($user->isSuperAdmin())
                                            <div class="form-text text-muted">Super Admin accounts are not assigned to shops</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Account Status</label>
                                        <div class="d-flex align-items-center">
                                            @if($user->email_verified_at)
                                                <span class="badge bg-success me-2">Email Verified</span>
                                                <div class="text-muted">{{ $user->email_verified_at->format('M d, Y') }}</div>
                                            @else
                                                <span class="badge bg-warning me-2">Email Unverified</span>
                                                <div class="text-muted">Never verified</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Account Created</label>
                                        <div class="d-flex align-items-center">
                                            <div class="text-muted">{{ $user->created_at->format('M d, Y h:i A') }}</div>
                                            <span class="ms-2 text-muted">({{ $user->created_at->diffForHumans() }})</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-info" id="ownershipWarning" style="display: none;">
                                <strong>Note:</strong> Making this user a shop owner will demote the current owner to manager.
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <div class="d-flex">
                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-ghost-dark me-auto">Cancel</a>
                                <button type="submit" class="btn btn-primary">Update User</button>
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
