@extends('layouts.admin')

@section('content')
<div class="page-header d-print-none">
    <div class="container-fluid">
        <div class="row g-2 align-items-center mb-3">
            <div class="col">
                <h2 class="page-title">
                    {{ __('Edit User') }}
                </h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-fluid">
        <x-alert/>

        <div class="row row-cards">
            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title text-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-bulb me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M3 12h1m8 -9v1m8 8h1m-15.4 -6.4l.7 .7m12.1 -.7l-.7 .7" />
                                        <path d="M9 16a5 5 0 1 1 6 0a3.5 3.5 0 0 0 -1 3a2 2 0 0 1 -4 0a3.5 3.5 0 0 0 -1 -3" />
                                        <path d="M9.7 17l4.6 0" />
                                    </svg>
                                    Quick Tips
                                </h3>

                                <div class="list-group list-group-flush mb-3">
                                    <div class="list-group-item px-0">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <span class="avatar bg-success text-white">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                                                </span>
                                            </div>
                                            <div class="col">
                                                <div class="text-truncate">
                                                    <strong>Update Role</strong>
                                                </div>
                                                <div class="text-muted small">Change permissions level</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="list-group-item px-0">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <span class="avatar bg-info text-white">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" /></svg>
                                                </span>
                                            </div>
                                            <div class="col">
                                                <div class="text-truncate">
                                                    <strong>Update Details</strong>
                                                </div>
                                                <div class="text-muted small">Keep info current</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="list-group-item px-0">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <span class="avatar bg-warning text-white">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 13a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-6z" /><path d="M11 16a1 1 0 1 0 2 0a1 1 0 0 0 -2 0" /><path d="M8 11v-4a4 4 0 1 1 8 0v4" /></svg>
                                                </span>
                                            </div>
                                            <div class="col">
                                                <div class="text-truncate">
                                                    <strong>Check Status</strong>
                                                </div>
                                                <div class="text-muted small">Email verification status</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="list-group-item px-0">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <span class="avatar bg-purple text-white">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l18 0" /><path d="M3 7v1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1h-18l2 -4h14l2 4" /><path d="M5 21l0 -10.15" /><path d="M19 21l0 -10.15" /><path d="M9 21v-4a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v4" /></svg>
                                                </span>
                                            </div>
                                            <div class="col">
                                                <div class="text-truncate">
                                                    <strong>Reassign Shop</strong>
                                                </div>
                                                <div class="text-muted small">Move to different location</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-info mb-0">
                                    <div class="d-flex">
                                        <div>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M12 9h.01" /><path d="M11 12h1v4h1" /></svg>
                                        </div>
                                        <div>
                                            <h4 class="alert-title">Remember!</h4>
                                            <div class="text-muted">Email and username must remain unique across all users. To change password, use "Send Password Reset" action.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <div>
                                    <h3 class="card-title">
                                        {{ __('Edit User Information') }}
                                    </h3>
                                    <div class="text-muted mt-1">
                                        <span class="avatar avatar-sm me-2">{{ substr($user->name, 0, 2) }}</span>
                                        <strong>{{ $user->name }}</strong> - {{ $user->email }}
                                    </div>
                                </div>

                                <div class="card-actions">
                                    <a href="{{ route('admin.users.index') }}" class="btn-action">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M18 6l-12 12"></path><path d="M6 6l12 12"></path></svg>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row row-cards">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label required">
                                                {{ __('Full Name') }}
                                            </label>
                                            <input type="text"
                                                   class="form-control @error('name') is-invalid @enderror"
                                                   name="name"
                                                   value="{{ old('name', $user->name) }}"
                                                   placeholder="John Doe"
                                                   required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label required">
                                                {{ __('Username') }}
                                            </label>
                                            <input type="text"
                                                   class="form-control @error('username') is-invalid @enderror"
                                                   name="username"
                                                   value="{{ old('username', $user->username) }}"
                                                   placeholder="johndoe"
                                                   required>
                                            @error('username')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-hint">Username must be unique and lowercase</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label required">
                                                {{ __('Email Address') }}
                                            </label>
                                            <input type="email"
                                                   class="form-control @error('email') is-invalid @enderror"
                                                   name="email"
                                                   value="{{ old('email', $user->email) }}"
                                                   placeholder="john@example.com"
                                                   required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label required">
                                                {{ __('User Role') }}
                                            </label>
                                            <select class="form-select @error('role') is-invalid @enderror"
                                                    name="role"
                                                    required
                                                    onchange="updateOwnershipWarning()">
                                                <option value="">Select Role</option>
                                                <option value="employee" {{ old('role', $user->role) == 'employee' ? 'selected' : '' }}>Employee</option>
                                                <option value="manager" {{ old('role', $user->role) == 'manager' ? 'selected' : '' }}>Manager</option>
                                                <option value="shop_owner" {{ old('role', $user->role) == 'shop_owner' ? 'selected' : '' }}>Shop Owner</option>
                                            </select>
                                            @error('role')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">
                                                {{ __('Shop Assignment') }}
                                                <span class="text-muted">(Optional)</span>
                                            </label>
                                            <select class="form-select @error('shop_id') is-invalid @enderror" name="shop_id">
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
                                            <small class="form-hint">Select the shop to assign this user to</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('Email Status') }}</label>
                                            <div class="d-flex align-items-center">
                                                @if($user->email_verified_at)
                                                    <span class="badge bg-success me-2">Verified</span>
                                                    <div class="text-muted small">{{ $user->email_verified_at->format('M d, Y') }}</div>
                                                @else
                                                    <span class="badge bg-warning me-2">Unverified</span>
                                                    <div class="text-muted small">Never verified</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('Account Created') }}</label>
                                            <div class="d-flex align-items-center">
                                                <div class="text-muted small">{{ $user->created_at->format('M d, Y') }}</div>
                                                <span class="ms-2 text-muted small">({{ $user->created_at->diffForHumans() }})</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="alert alert-warning" id="ownershipWarning" style="display: none;">
                                            <div class="d-flex">
                                                <div>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v4" /><path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" /><path d="M12 16h.01" /></svg>
                                                </div>
                                                <div>
                                                    <h4 class="alert-title">Important Notice!</h4>
                                                    <div class="text-muted">Making this user a <strong>Shop Owner</strong> will automatically demote the current owner to manager role.</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer text-end">
                                <div class="d-flex">
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-link me-auto">
                                        Cancel
                                    </a>
                                    <button type="submit" class="btn btn-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                        Update User
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
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
