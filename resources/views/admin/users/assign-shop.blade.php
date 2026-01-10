@extends('layouts.admin')

@section('content')
<div class="page-header d-print-none">
    <div class="container-fluid">
        <div class="row g-2 align-items-center mb-3">
            <div class="col">
                <div class="page-pretitle">Admin Panel</div>
                <h2 class="page-title">Assign User to Shop</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-ghost-dark">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M5 12l14 0"/>
                            <path d="M5 12l6 6"/>
                            <path d="M5 12l6 -6"/>
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
        <div class="row g-4">
            <!-- Main Form Column -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-primary" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M3 21l18 0"/>
                                <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16"/>
                                <path d="M9 9l0 4"/>
                                <path d="M12 7l0 6"/>
                                <path d="M15 11l0 2"/>
                            </svg>
                            Shop Assignment
                        </h3>
                    </div>

                    <div class="card-body">
                        <!-- User Information Card -->
                        <div class="card mb-4 bg-blue-lt">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <span class="avatar avatar-lg me-3 bg-primary text-white">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </span>
                                    <div class="flex-fill">
                                        <div class="h2 mb-1">{{ $user->name }}</div>
                                        <div class="text-muted">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z"/>
                                                <path d="M3 7l9 6l9 -6"/>
                                            </svg>
                                            {{ $user->email }}
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="badge bg-blue mb-1">{{ str_replace('_', ' ', ucfirst($user->role)) }}</div><br>
                                        @if($user->shop_id)
                                            <div class="badge bg-green">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                    <path d="M3 21l18 0"/>
                                                    <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16"/>
                                                </svg>
                                                {{ $user->shop->name ?? 'Unknown Shop' }}
                                            </div>
                                        @else
                                            <div class="badge bg-red">No Shop Assigned</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Assignment Form -->
                        <form action="{{ route('admin.users.update-shop-assignment', $user) }}" method="POST" id="assignmentForm">
                            @csrf
                            <input type="hidden" name="confirm_transfer" id="confirmTransferInput" value="0">

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label required">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M3 21l18 0"/>
                                            <path d="M3 7v1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1h-18l2 -4h14l2 4"/>
                                            <path d="M5 21l0 -10.15"/>
                                            <path d="M19 21l0 -10.15"/>
                                            <path d="M9 21v-4a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v4"/>
                                        </svg>
                                        Select Shop
                                    </label>
                                    <select class="form-select @error('shop_id') is-invalid @enderror"
                                            name="shop_id"
                                            id="shopSelect"
                                            required>
                                        <option value="">Choose a shop...</option>
                                        @foreach($shops as $shop)
                                            <option value="{{ $shop->id }}"
                                                    data-owner-id="{{ $shop->owner_id }}"
                                                    data-owner-name="{{ $shop->owner ? $shop->owner->name : '' }}"
                                                    {{ old('shop_id', $user->shop_id) == $shop->id ? 'selected' : '' }}>
                                                {{ $shop->name }}
                                                @if($shop->owner_id)
                                                    (Owner: {{ $shop->owner->name }})
                                                @else
                                                    (No Owner)
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('shop_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-hint">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"/>
                                            <path d="M12 9h.01"/>
                                            <path d="M11 12h1v4h1"/>
                                        </svg>
                                        Choose which shop this user will be associated with
                                    </small>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label required">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"/>
                                            <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/>
                                        </svg>
                                        User Role
                                    </label>
                                    <select class="form-select @error('role') is-invalid @enderror"
                                            name="role"
                                            id="roleSelect"
                                            required>
                                        <option value="employee" {{ old('role', $user->role) == 'employee' ? 'selected' : '' }}>
                                            Employee
                                        </option>
                                        <option value="manager" {{ old('role', $user->role) == 'manager' ? 'selected' : '' }}>
                                            Manager
                                        </option>
                                        <option value="shop_owner" {{ old('role', $user->role) == 'shop_owner' ? 'selected' : '' }}>
                                            Shop Owner
                                        </option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-hint">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"/>
                                            <path d="M12 9h.01"/>
                                            <path d="M11 12h1v4h1"/>
                                        </svg>
                                        Determines the user's access level and permissions
                                    </small>
                                </div>
                            </div>

                            <!-- Warning Alert -->
                            <div class="alert alert-warning" id="ownershipWarning" style="display: none;">
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
                                        <h4 class="alert-title">⚠️ Ownership Transfer Warning!</h4>
                                        <div class="text-secondary" id="ownershipWarningMessage">
                                            This shop already has an owner. Making <strong>{{ $user->name }}</strong> the <span class="badge badge-sm bg-orange">Shop Owner</span> will automatically demote the current owner <strong id="currentOwnerName"></strong> to <span class="badge badge-sm bg-blue">Manager</span> role.
                                            <br><br>
                                            <strong>⚠️ Only ONE owner is allowed per shop!</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Current Owner Alert (when shop has owner but role is not shop_owner) -->
                            <div class="alert alert-info" id="shopOwnerInfo" style="display: none;">
                                <div class="d-flex">
                                    <div>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"/>
                                            <path d="M12 9h.01"/>
                                            <path d="M11 12h1v4h1"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="alert-title">Shop Owner Information</h4>
                                        <div class="text-secondary" id="shopOwnerInfoMessage">
                                            This shop currently has an owner: <strong id="currentOwnerNameInfo"></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-footer">
                                <button type="submit" class="btn btn-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M5 12l5 5l10 -10"/>
                                    </svg>
                                    Update Shop Assignment
                                </button>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-link">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tips Sidebar -->
            <div class="col-lg-4">
                <!-- Role Descriptions -->
                <div class="card mb-3">
                    <div class="card-status-top bg-blue"></div>
                    <div class="card-header">
                        <h3 class="card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-blue" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"/>
                                <path d="M12 9h.01"/>
                                <path d="M11 12h1v4h1"/>
                            </svg>
                            Role Descriptions
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-orange me-2">Shop Owner</span>
                                <small class="text-muted">Highest Access</small>
                            </div>
                            <p class="text-secondary mb-0">
                                Full control over shop operations, can manage all staff, inventory, sales, and financial reports. Only one per shop.
                            </p>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-blue me-2">Manager</span>
                                <small class="text-muted">Advanced Access</small>
                            </div>
                            <p class="text-secondary mb-0">
                                Can manage daily operations, staff supervision, and access most reports. Cannot change ownership or critical settings.
                            </p>
                        </div>
                        <hr>
                        <div>
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-green me-2">Employee</span>
                                <small class="text-muted">Basic Access</small>
                            </div>
                            <p class="text-secondary mb-0">
                                Basic access to daily operations like POS, order processing, and viewing inventory. Limited report access.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Important Notes -->
                <div class="card mb-3">
                    <div class="card-status-top bg-yellow"></div>
                    <div class="card-header">
                        <h3 class="card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-yellow" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M12 9v4"/>
                                <path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z"/>
                                <path d="M12 16h.01"/>
                            </svg>
                            Important Notes
                        </h3>
                    </div>
                    <div class="list-group list-group-flush">
                        <div class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="text-yellow">•</span>
                                </div>
                                <div class="col">
                                    <small>Users can only be assigned to <strong>one shop</strong> at a time</small>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="text-yellow">•</span>
                                </div>
                                <div class="col">
                                    <small>Each shop can have <strong>only one owner</strong></small>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="text-yellow">•</span>
                                </div>
                                <div class="col">
                                    <small>Changing shop will <strong>reassign all user data</strong> to the new shop</small>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="text-yellow">•</span>
                                </div>
                                <div class="col">
                                    <small>Admin users <strong>cannot be assigned</strong> to shops</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                @if($user->shop_id)
                <div class="card">
                    <div class="card-status-top bg-green"></div>
                    <div class="card-header">
                        <h3 class="card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-green" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M7 10h3v-3l-3.5 -3.5a6 6 0 0 1 8 8l6 6a2 2 0 0 1 -3 3l-6-6a6 6 0 0 1 -8 -8l3.5 3.5"/>
                            </svg>
                            Current Assignment
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <span class="text-muted">Shop:</span>
                            <strong>{{ $user->shop->name ?? 'Unknown' }}</strong>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted">Role:</span>
                            <span class="badge bg-blue">{{ str_replace('_', ' ', ucfirst($user->role)) }}</span>
                        </div>
                        <div>
                            <span class="text-muted">Status:</span>
                            <span class="badge bg-green">Active</span>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('roleSelect');
        const shopSelect = document.getElementById('shopSelect');
        const ownershipWarning = document.getElementById('ownershipWarning');
        const shopOwnerInfo = document.getElementById('shopOwnerInfo');
        const currentUserId = {{ $user->id }};

        function updateWarnings() {
            const selectedRole = roleSelect.value;
            const selectedShop = shopSelect.options[shopSelect.selectedIndex];
            const shopOwnerId = selectedShop ? selectedShop.getAttribute('data-owner-id') : null;
            const shopOwnerName = selectedShop ? selectedShop.getAttribute('data-owner-name') : null;

            // Hide both warnings initially
            ownershipWarning.style.display = 'none';
            shopOwnerInfo.style.display = 'none';

            // If a shop is selected and it has an owner
            if (shopOwnerId && shopOwnerName) {
                // If trying to make this user a shop owner AND shop already has a different owner
                if (selectedRole === 'shop_owner' && shopOwnerId != currentUserId) {
                    // Show critical warning about ownership transfer
                    document.getElementById('currentOwnerName').textContent = shopOwnerName;
                    ownershipWarning.style.display = 'block';
                } else if (selectedRole !== 'shop_owner') {
                    // Just show info that shop has an owner
                    document.getElementById('currentOwnerNameInfo').textContent = shopOwnerName;
                    shopOwnerInfo.style.display = 'block';
                }
            }
        }

        // Add event listeners
        roleSelect.addEventListener('change', updateWarnings);
        shopSelect.addEventListener('change', updateWarnings);

        // Check on page load
        updateWarnings();

        // Add confirmation before form submit when there's ownership transfer
        document.querySelector('form').addEventListener('submit', function(e) {
            const selectedRole = roleSelect.value;
            const selectedShop = shopSelect.options[shopSelect.selectedIndex];
            const shopOwnerId = selectedShop ? selectedShop.getAttribute('data-owner-id') : null;
            const shopOwnerName = selectedShop ? selectedShop.getAttribute('data-owner-name') : null;
            const confirmTransferInput = document.getElementById('confirmTransferInput');

            // Reset confirmation flag
            confirmTransferInput.value = '0';

            // If trying to assign shop owner role to a shop that already has a different owner
            if (selectedRole === 'shop_owner' && shopOwnerId && shopOwnerId != currentUserId) {
                e.preventDefault(); // Prevent form submission first

                const confirmed = confirm(
                    `⚠️ WARNING: Shop Ownership Transfer\n\n` +
                    `This shop already has an owner: ${shopOwnerName}\n\n` +
                    `Making {{ $user->name }} the Shop Owner will:\n` +
                    `• Demote ${shopOwnerName} from Shop Owner to Manager\n` +
                    `• Transfer full shop control to {{ $user->name }}\n\n` +
                    `❗ Only ONE owner is allowed per shop!\n\n` +
                    `Are you sure you want to proceed with this ownership transfer?`
                );

                if (confirmed) {
                    // Set confirmation flag and submit form
                    confirmTransferInput.value = '1';
                    this.submit();
                } else {
                    // User cancelled
                    return false;
                }
            }
            // If no ownership conflict, allow normal submission
        });
    });
</script>
@endpush
@endsection
