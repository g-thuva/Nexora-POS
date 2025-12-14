
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
                    Manage All Shops
                </h2>
            </div>
            <div class="col-12 col-md-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-ghost-dark">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="m0 0h24v24H0z" fill="none"/>
                            <polyline points="5 12 3 12 12 3 21 12 19 12"/>
                            <path d="m5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7"/>
                            <path d="m9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6"/>
                        </svg>
                        Back to Dashboard
                    </a>
                    <a href="{{ route('admin.shops.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Create New Shop
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
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">All Shops in System</h3>
                        <div class="card-actions">
                            <span class="badge bg-blue">{{ $shops->total() }} Total Shops</span>
                        </div>
                    </div>
                    <div class="card-body border-bottom py-3">
                        <div class="d-flex">
                            <div class="text-muted">
                                Show
                                <div class="mx-2 d-inline-block">
                                    <select class="form-select form-select-sm">
                                        <option value="15" {{ request('per_page') == 15 ? 'selected' : '' }}>15</option>
                                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                    </select>
                                </div>
                                entries
                            </div>
                            <div class="ms-auto text-muted">
                                Search:
                                <div class="ms-2 d-inline-block">
                                    <input type="text" class="form-control form-control-sm" aria-label="Search shops" placeholder="Search shops...">
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(safe_count($shops) > 0)
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter text-nowrap datatable">
                                <thead>
                                    <tr>
                                        <th class="w-1">
                                            <input class="form-check-input m-0 align-middle" type="checkbox" aria-label="Select all shops">
                                        </th>
                                        <th>Shop Details</th>
                                        <th>Owner</th>
                                        <th>Subscription</th>
                                        <th>Status</th>
                                        <th>Stats</th>
                                        <th>Created</th>
                                        <th class="w-1">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($shops as $shop)
                                        <tr>
                                            <td>
                                                <input class="form-check-input m-0 align-middle" type="checkbox" aria-label="Select shop">
                                            </td>
                                            <td>
                                                <div class="d-flex py-1 align-items-center">
                                                    <span class="avatar me-2" style="background-image: url({{ asset('static/avatars/shop-default.png') }})"></span>
                                                    <div class="flex-fill">
                                                        <div class="font-weight-medium">{{ $shop->name }}</div>
                                                        <div class="text-muted">
                                                            <a href="mailto:{{ $shop->email }}" class="text-reset">{{ $shop->email }}</a>
                                                        </div>
                                                        <div class="text-muted">
                                                            <small>{{ $shop->phone }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex py-1 align-items-center">
                                                    <span class="avatar avatar-sm me-2">{{ substr($shop->owner->name, 0, 2) }}</span>
                                                    <div class="flex-fill">
                                                        <div class="font-weight-medium">{{ $shop->owner->name }}</div>
                                                        <div class="text-muted">
                                                            <a href="mailto:{{ $shop->owner->email }}" class="text-reset">{{ $shop->owner->email }}</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if(isset($shop->subscription_status))
                                                    @if($shop->subscription_status === 'active')
                                                        <span class="badge bg-green">Active</span>
                                                    @elseif($shop->subscription_status === 'expired')
                                                        <span class="badge bg-red">Expired</span>
                                                    @elseif($shop->subscription_status === 'trial')
                                                        <span class="badge bg-yellow">Trial</span>
                                                    @else
                                                        <span class="badge bg-gray">{{ ucfirst($shop->subscription_status) }}</span>
                                                    @endif
                                                    @if(isset($shop->subscription_end_date))
                                                        <div class="text-muted">
                                                            <small>Expires: {{ \Carbon\Carbon::parse($shop->subscription_end_date)->format('M d, Y') }}</small>
                                                        </div>
                                                    @endif
                                                @else
                                                    <span class="badge bg-gray">Not Set</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($shop->is_active) && $shop->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="text-muted">
                                                    <div>{{ $shop->users_count }} Users</div>
                                                    <div>{{ $shop->products_count }} Products</div>
                                                    <div>{{ $shop->orders_count }} Orders</div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-muted">
                                                    {{ $shop->created_at->format('M d, Y') }}
                                                    <div class="small">{{ $shop->created_at->diffForHumans() }}</div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-list flex-nowrap">
                                                    <div class="dropdown">
                                                        <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">
                                                            Actions
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <a class="dropdown-item" href="{{ route('admin.shops.show', $shop) }}">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path stroke="none" d="m0 0h24v24H0z" fill="none"/>
                                                                    <circle cx="12" cy="12" r="2"/>
                                                                    <path d="m12 1c.835 0 1.52 .205 2.05 .623a3.441 3.441 0 0 1 1.343 3.146c.096 .443 -.071 .884 -.334 1.317l-2.059 3.914l-2.059 -3.914c-.263 -.433 -.43 -.874 -.334 -1.317a3.441 3.441 0 0 1 1.343 -3.146c.53 -.418 1.215 -.623 2.05 -.623z"/>
                                                                </svg>
                                                                View Details
                                                            </a>
                                                            <a class="dropdown-item" href="{{ route('admin.shops.edit', $shop) }}">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path stroke="none" d="m0 0h24v24H0z" fill="none"/>
                                                                    <path d="m7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                                                                    <path d="m20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                                                                    <path d="m16 5l3 3"/>
                                                                </svg>
                                                                Edit Shop
                                                            </a>
                                                            <a class="dropdown-item" href="#" onclick="manageShopUsers({{ $shop->id }}, '{{ $shop->name }}')">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path stroke="none" d="m0 0h24v24H0z" fill="none"/>
                                                                    <circle cx="9" cy="7" r="4"/>
                                                                    <path d="m3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/>
                                                                    <path d="m16 3.13a4 4 0 0 1 0 7.75"/>
                                                                    <path d="m21 21v-2a4 4 0 0 0 -3 -3.85"/>
                                                                </svg>
                                                                Manage Users
                                                            </a>
                                                            <div class="dropdown-divider"></div>
                                                            @if(isset($shop->is_active) && $shop->is_active)
                                                                <a class="dropdown-item text-yellow" href="#" onclick="toggleShopStatus({{ $shop->id }}, false)">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                        <path stroke="none" d="m0 0h24v24H0z" fill="none"/>
                                                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                                                        <line x1="9" y1="9" x2="15" y2="15"/>
                                                                        <line x1="15" y1="9" x2="9" y2="15"/>
                                                                    </svg>
                                                                    Deactivate
                                                                </a>
                                                            @else
                                                                <a class="dropdown-item text-green" href="#" onclick="toggleShopStatus({{ $shop->id }}, true)">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                        <path stroke="none" d="m0 0h24v24H0z" fill="none"/>
                                                                        <circle cx="12" cy="12" r="9"/>
                                                                        <path d="m9 12l2 2l4 -4"/>
                                                                    </svg>
                                                                    Activate
                                                                </a>
                                                            @endif
                                                            <div class="dropdown-divider"></div>
                                                            <a class="dropdown-item text-red" href="#" onclick="deleteShop({{ $shop->id }}, '{{ $shop->name }}')">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path stroke="none" d="m0 0h24v24H0z" fill="none"/>
                                                                    <line x1="4" y1="7" x2="20" y2="7"/>
                                                                    <line x1="10" y1="11" x2="10" y2="17"/>
                                                                    <line x1="14" y1="11" x2="14" y2="17"/>
                                                                    <path d="m5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/>
                                                                    <path d="m9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/>
                                                                </svg>
                                                                Delete Shop
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer d-flex align-items-center">
                            {{ $shops->links() }}
                        </div>
                    @else
                        <div class="empty">
                            <div class="empty-img">
                                <img src="{{ asset('static/illustrations/undraw_printing_invoices_5r4r.svg') }}" height="128" alt="">
                            </div>
                            <p class="empty-title">No shops found</p>
                            <p class="empty-subtitle text-muted">
                                No shops have been created yet. Create the first shop to get started.
                            </p>
                            <div class="empty-action">
                                <a href="{{ route('admin.shops.create') }}" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                    Create your first shop
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Shop Modal -->
<div class="modal modal-blur fade" id="deleteShopModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-status bg-danger"></div>
            <div class="modal-body text-center py-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="m0 0h24v24H0z" fill="none"/>
                    <path d="m12 9v2m0 4v.01"/>
                    <path d="m5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75"/>
                </svg>
                <h3>Are you sure?</h3>
                <div class="text-muted">Do you really want to delete shop "<span id="shopNameToDelete"></span>"? This action cannot be undone.</div>
            </div>
            <div class="modal-footer">
                <div class="w-100">
                    <div class="row">
                        <div class="col">
                            <button type="button" class="btn w-100" data-bs-dismiss="modal">Cancel</button>
                        </div>
                        <div class="col">
                            <form id="deleteShopForm" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">Delete Shop</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Manage Users Modal -->
<div class="modal modal-blur fade user-management-modal" id="manageUsersModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Manage Users - <span id="shopNameInModal"></span></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Existing Users in Shop -->
                <div class="mb-4">
                    <h5>Current Users</h5>
                    <div id="currentUsersList" class="list-group">
                        <div class="text-center py-3">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add New User -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Assign User to Shop</h5>
                    </div>
                    <div class="card-body">
                        <form id="assignUserForm">
                            <input type="hidden" id="targetShopId" name="shop_id">

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="userSelect" class="form-label">Select User</label>
                                    <select class="form-select" id="userSelect" name="user_id" required>
                                        <option value="">Choose a user to assign...</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="userRole" class="form-label">Role</label>
                                    <select class="form-select" id="userRole" name="role" required>
                                        <option value="">Select Role</option>
                                        <option value="shop_owner">Shop Owner</option>
                                        <option value="manager">Manager</option>
                                        <option value="employee">Employee</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch mt-4">
                                        <input class="form-check-input" type="checkbox" id="makeOwner" name="make_owner">
                                        <label class="form-check-label" for="makeOwner">
                                            Transfer shop ownership
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="m0 0h24v24H0z" fill="none"/>
                                        <line x1="12" y1="5" x2="12" y2="19"/>
                                        <line x1="5" y1="12" x2="19" y2="12"/>
                                    </svg>
                                    Assign User
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.user-management-modal .list-group-item {
    border: 1px solid #e6e7e9;
    margin-bottom: 0.5rem;
    border-radius: 0.375rem;
}

.user-management-modal .avatar {
    text-transform: uppercase;
    font-weight: 600;
}

.role-badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

.loading-overlay {
    background: rgba(255, 255, 255, 0.8);
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
@endpush

@push('scripts')
<script>
function deleteShop(shopId, shopName) {
    document.getElementById('shopNameToDelete').textContent = shopName;
    document.getElementById('deleteShopForm').action = '/shops/' + shopId;

    var deleteModal = new bootstrap.Modal(document.getElementById('deleteShopModal'));
    deleteModal.show();
}

function toggleShopStatus(shopId, status) {
    if(confirm('Are you sure you want to ' + (status ? 'activate' : 'deactivate') + ' this shop?')) {
        // You can implement AJAX call here to toggle status
        // For now, we'll use a simple form submission
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '/admin/shops/' + shopId + '/toggle-status';

        var csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfToken);

        var statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = status ? '1' : '0';
        form.appendChild(statusInput);

        document.body.appendChild(form);
        form.submit();
    }
}

function manageShopUsers(shopId, shopName) {
    console.log('manageShopUsers called with:', shopId, shopName);

    document.getElementById('shopNameInModal').textContent = shopName;
    document.getElementById('targetShopId').value = shopId;

    // Load current users
    loadShopUsers(shopId);

    // Load available users
    loadAvailableUsers();

    var manageModal = new bootstrap.Modal(document.getElementById('manageUsersModal'));
    manageModal.show();
}

function loadShopUsers(shopId) {
    const currentUsersList = document.getElementById('currentUsersList');
    currentUsersList.innerHTML = '<div class="text-center py-3"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>';

    fetch(`/admin/shops/${shopId}/users`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                let html = '';
                if (data.users.length > 0) {
                    data.users.forEach(user => {
                        const roleColor = getRoleColor(user.role);
                        html += `
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <span class="avatar avatar-sm me-2">${user.name.substring(0, 2)}</span>
                                    <div>
                                        <div class="fw-medium">${user.name}</div>
                                        <div class="text-muted small">${user.email}</div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="badge ${roleColor} me-2">${user.role.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())}</span>
                                    ${user.role !== 'shop_owner' ? `<button class="btn btn-sm btn-outline-danger" onclick="removeUserFromShop(${user.id}, '${user.name}', ${shopId})">Remove</button>` : ''}
                                </div>
                            </div>`;
                    });
                } else {
                    html = '<div class="text-center py-4 text-muted">No users assigned to this shop</div>';
                }
                currentUsersList.innerHTML = html;
            } else {
                currentUsersList.innerHTML = '<div class="alert alert-danger">Error loading users</div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (error.message.includes('401')) {
                currentUsersList.innerHTML = '<div class="alert alert-warning">Session expired. Please refresh the page and log in again.</div>';
            } else if (error.message.includes('403')) {
                currentUsersList.innerHTML = '<div class="alert alert-danger">Access denied. Admin privileges required.</div>';
            } else {
                currentUsersList.innerHTML = '<div class="alert alert-danger">Error loading users. Please try again.</div>';
            }
        });
}

function loadAvailableUsers() {
    const userSelect = document.getElementById('userSelect');

    fetch('/admin/available-users', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                userSelect.innerHTML = '<option value="">Choose a user to assign...</option>';
                data.users.forEach(user => {
                    userSelect.innerHTML += `<option value="${user.id}">${user.name} (${user.email}) - ${user.role.replace('_', ' ')}</option>`;
                });
            }
        })
        .catch(error => {
            console.error('Error loading available users:', error);
            const userSelect = document.getElementById('userSelect');
            userSelect.innerHTML = '<option value="">Error loading users - please refresh</option>';
        });
}

function getRoleColor(role) {
    switch(role) {
        case 'admin': return 'bg-red';
        case 'admin': return 'bg-purple';
        case 'shop_owner': return 'bg-blue';
        case 'manager': return 'bg-green';
        case 'employee': return 'bg-yellow';
        default: return 'bg-gray';
    }
}

function removeUserFromShop(userId, userName, shopId) {
    if (confirm(`Are you sure you want to remove ${userName} from this shop?`)) {
        fetch(`/admin/shops/${shopId}/users/${userId}/remove`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadShopUsers(shopId);
                loadAvailableUsers();
                alert('User removed successfully');
            } else {
                alert(data.message || 'Error removing user');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error removing user');
        });
    }
}

// Handle assign user form submission
document.addEventListener('DOMContentLoaded', function() {
    const assignUserForm = document.getElementById('assignUserForm');
    if (assignUserForm) {
        assignUserForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const shopId = formData.get('shop_id');

            fetch(`/admin/shops/${shopId}/users/assign`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    user_id: formData.get('user_id'),
                    role: formData.get('role'),
                    make_owner: formData.get('make_owner') === 'on'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadShopUsers(shopId);
                    loadAvailableUsers();
                    assignUserForm.reset();
                    alert('User assigned successfully');
                } else {
                    alert(data.message || 'Error assigning user');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error assigning user');
            });
        });
    }
});
</script>
@endpush
