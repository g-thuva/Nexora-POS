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
                    Manage All Users
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
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Create New User
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
                        <h3 class="card-title">All Users in System</h3>
                        <div class="card-actions">
                            <span class="badge bg-blue">{{ $users->total() }} Total Users</span>
                        </div>
                    </div>
                    <div class="card-body border-bottom py-3">
                        <div class="d-flex">
                            <div class="text-muted">
                                Show
                                <div class="mx-2 d-inline-block">
                                    <select class="form-select form-select-sm" onchange="changePerPage(this.value)">
                                        <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                    </select>
                                </div>
                                entries
                            </div>
                            <div class="ms-auto text-muted">
                                Search:
                                <div class="ms-2 d-inline-block">
                                    <input type="text" class="form-control form-control-sm" aria-label="Search users" placeholder="Search users..." onkeyup="searchUsers(this.value)">
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(safe_count($users) > 0)
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter text-nowrap datatable">
                                <thead>
                                    <tr>
                                        <th class="w-1">
                                            <input class="form-check-input m-0 align-middle" type="checkbox" aria-label="Select all users">
                                        </th>
                                        <th>User</th>
                                        <th>Role</th>
                                        <th>Shop</th>
                                        <th>Status</th>
                                        <th>Last Login</th>
                                        <th>Created</th>
                                        <th class="w-1">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="usersTableBody">
                                    @foreach($users as $user)
                                        <tr data-user-id="{{ $user->id }}" class="user-row">
                                            <td>
                                                <input class="form-check-input m-0 align-middle" type="checkbox" aria-label="Select user" value="{{ $user->id }}">
                                            </td>
                                            <td>
                                                <div class="d-flex py-1 align-items-center">
                                                    <span class="avatar me-2">{{ substr($user->name, 0, 2) }}</span>
                                                    <div class="flex-fill">
                                                        <div class="font-weight-medium">{{ $user->name }}</div>
                                                        <div class="text-muted">
                                                            <a href="mailto:{{ $user->email }}" class="text-reset">{{ $user->email }}</a>
                                                        </div>
                                                        <div class="text-muted">
                                                            <small>{{ $user->username }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($user->role === 'admin')
                                                    <span class="badge bg-red">Super Admin</span>
                                                @elseif($user->role === 'admin')
                                                    <span class="badge bg-purple">Admin</span>
                                                @elseif($user->role === 'shop_owner')
                                                    <span class="badge bg-blue">Shop Owner</span>
                                                @elseif($user->role === 'manager')
                                                    <span class="badge bg-green">Manager</span>
                                                @elseif($user->role === 'employee')
                                                    <span class="badge bg-yellow">Employee</span>
                                                @else
                                                    <span class="badge bg-gray">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->shop)
                                                    <div class="d-flex align-items-center">
                                                        <span class="avatar avatar-xs me-2">{{ substr($user->shop->name, 0, 2) }}</span>
                                                        <div>
                                                            <div class="font-weight-medium">{{ $user->shop->name }}</div>
                                                            <div class="text-muted">
                                                                <small>{{ $user->shop->email }}</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="text-muted">No Shop</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->email_verified_at)
                                                    <span class="badge bg-success">Verified</span>
                                                @else
                                                    <span class="badge bg-warning">Unverified</span>
                                                @endif
                                                @if($user->created_at->diffInDays() <= 7)
                                                    <span class="badge bg-info ms-1">New</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="text-muted">
                                                    @if($user->last_login_at)
                                                        {{ $user->last_login_at->diffForHumans() }}
                                                        <div class="small">{{ $user->last_login_at->format('M d, Y h:i A') }}</div>
                                                    @else
                                                        Never
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-muted">
                                                    {{ $user->created_at->format('M d, Y') }}
                                                    <div class="small">{{ $user->created_at->diffForHumans() }}</div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-list flex-nowrap">
                                                    <div class="dropdown">
                                                        <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">
                                                            Actions
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <a class="dropdown-item" href="{{ route('admin.users.show', $user) }}">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path stroke="none" d="m0 0h24v24H0z" fill="none"/>
                                                                    <circle cx="12" cy="12" r="2"/>
                                                                    <path d="m12 1c.835 0 1.52 .205 2.05 .623a3.441 3.441 0 0 1 1.343 3.146c.096 .443 -.071 .884 -.334 1.317l-2.059 3.914l-2.059 -3.914c-.263 -.433 -.43 -.874 -.334 -1.317a3.441 3.441 0 0 1 1.343 -3.146c.53 -.418 1.215 -.623 2.05 -.623z"/>
                                                                </svg>
                                                                View Profile
                                                            </a>
                                                            <a class="dropdown-item" href="{{ route('users.edit', $user) }}">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path stroke="none" d="m0 0h24v24H0z" fill="none"/>
                                                                    <path d="m7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                                                                    <path d="m20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                                                                    <path d="m16 5l3 3"/>
                                                                </svg>
                                                                Edit User
                                                            </a>
                                                            <div class="dropdown-divider"></div>
                                                            <div class="text-muted small px-3 py-2">Super Admin Actions</div>
                                                            @if(!$user->email_verified_at)
                                                                <a class="dropdown-item text-green" href="#" onclick="verifyUserEmail({{ $user->id }})">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                        <circle cx="12" cy="12" r="9"/>
                                                                        <path d="m9 12l2 2l4 -4"/>
                                                                    </svg>
                                                                    Verify Email
                                                                </a>
                                                            @else
                                                                <a class="dropdown-item text-orange" href="#" onclick="unverifyUserEmail({{ $user->id }})">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                        <circle cx="12" cy="12" r="9"/>
                                                                        <path d="m15 9l-6 6"/>
                                                                        <path d="m9 9l6 6"/>
                                                                    </svg>
                                                                    Unverify Email
                                                                </a>
                                                            @endif
                                                            @if($user->role !== 'admin')
                                                                <a class="dropdown-item text-blue" href="#" onclick="openShopAssignmentModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->role }}', {{ $user->shop_id ?? 'null' }})">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                        <path d="M3 21l18 0"/>
                                                                        <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16"/>
                                                                        <path d="M9 9l0 4"/>
                                                                        <path d="M12 7l0 6"/>
                                                                        <path d="M15 11l0 2"/>
                                                                    </svg>
                                                                    Assign to Shop
                                                                </a>
                                                            @endif
                                                            <a class="dropdown-item text-blue" href="#" onclick="sendPasswordReset({{ $user->id }})">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path stroke="none" d="m0 0h24v24H0z" fill="none"/>
                                                                    <rect x="3" y="11" width="18" height="10" rx="2" ry="2"/>
                                                                    <circle cx="12" cy="16" r="1"/>
                                                                    <path d="m7 11v-4a5 5 0 0 1 10 0v4"/>
                                                                </svg>
                                                                Reset Password
                                                            </a>
                                                            @if($user->role !== 'admin' && $user->id !== auth()->id())
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item text-yellow" href="#" onclick="toggleUserAccess({{ $user->id }})">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                        <path stroke="none" d="m0 0h24v24H0z" fill="none"/>
                                                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                                                        <line x1="9" y1="9" x2="15" y2="15"/>
                                                                        <line x1="15" y1="9" x2="9" y2="15"/>
                                                                    </svg>
                                                                    Suspend Account
                                                                </a>
                                                                <a class="dropdown-item text-red" href="#" onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                        <path stroke="none" d="m0 0h24v24H0z" fill="none"/>
                                                                        <line x1="4" y1="7" x2="20" y2="7"/>
                                                                        <line x1="10" y1="11" x2="10" y2="17"/>
                                                                        <line x1="14" y1="11" x2="14" y2="17"/>
                                                                        <path d="m5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/>
                                                                        <path d="m9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/>
                                                                    </svg>
                                                                    Delete User
                                                                </a>
                                                            @endif
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
                            {{ $users->links() }}
                        </div>
                    @else
                        <div class="empty">
                            <div class="empty-img">
                                <img src="{{ asset('static/illustrations/undraw_printing_invoices_5r4r.svg') }}" height="128" alt="">
                            </div>
                            <p class="empty-title">No users found</p>
                            <p class="empty-subtitle text-muted">
                                No users have been created yet. Create the first user to get started.
                            </p>
                            <div class="empty-action">
                                <a href="{{ route('users.create') }}" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                    Create your first user
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Shop Assignment Modal -->
<div class="modal modal-blur fade" id="shopAssignmentModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Shop Assignment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="shopAssignmentForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">User</label>
                        <div class="d-flex align-items-center">
                            <span class="avatar avatar-sm me-2" id="userAvatar">U</span>
                            <div>
                                <div class="font-weight-medium" id="userNameDisplay">User Name</div>
                                <div class="text-muted small">Current Assignment</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Shop Assignment</label>
                        <select class="form-select" name="shop_id" id="shopSelect">
                            <option value="">No Shop (Unassigned)</option>
                            @foreach($shops ?? [] as $shop)
                                <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                            @endforeach
                        </select>
                        <div class="form-text">Select the shop to assign this user to</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">User Role</label>
                        <select class="form-select" name="role" id="roleSelect">
                            <option value="employee">Employee</option>
                            <option value="manager">Manager</option>
                            <option value="shop_owner">Shop Owner</option>
                        </select>
                        <div class="form-text">The user's role determines their access level</div>
                    </div>

                    <div class="alert alert-info" id="ownershipWarning" style="display: none;">
                        <strong>Note:</strong> Making this user a shop owner will demote the current owner to manager.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Assignment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete User Modal -->
<div class="modal modal-blur fade" id="deleteUserModal" tabindex="-1" role="dialog" aria-hidden="true">
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
                <div class="text-muted">Do you really want to delete user "<span id="userNameToDelete"></span>"? This action cannot be undone.</div>
            </div>
            <div class="modal-footer">
                <div class="w-100">
                    <div class="row">
                        <div class="col">
                            <button type="button" class="btn w-100" data-bs-dismiss="modal">Cancel</button>
                        </div>
                        <div class="col">
                            <form id="deleteUserForm" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">Delete User</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('page-scripts')
<script>
function deleteUser(userId, userName) {
    document.getElementById('userNameToDelete').textContent = userName;
    document.getElementById('deleteUserForm').action = '/admin/users/' + userId;

    var deleteModal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
    deleteModal.show();
}

function toggleUserAccess(userId) {
    if(confirm('Are you sure you want to toggle access for this user?')) {
        fetch('/admin/users/' + userId + '/toggle-access', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                location.reload();
            } else {
                alert(data.message || 'An error occurred');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating user access');
        });
    }
}

function verifyUserEmail(userId) {
    if(confirm('Are you sure you want to verify this user\'s email?')) {
        fetch('/admin/users/' + userId + '/verify-email', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                showAlert('success', data.message);
                location.reload();
            } else {
                showAlert('error', data.message || 'An error occurred');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'An error occurred while verifying user email');
        });
    }
}

function unverifyUserEmail(userId) {
    if(confirm('Are you sure you want to unverify this user\'s email? They will need to verify it again.')) {
        fetch('/admin/users/' + userId + '/unverify-email', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                showAlert('success', data.message);
                location.reload();
            } else {
                showAlert('error', data.message || 'An error occurred');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'An error occurred while unverifying user email');
        });
    }
}

function sendPasswordReset(userId) {
    if(confirm('Are you sure you want to send a password reset email to this user?')) {
        fetch('/admin/users/' + userId + '/send-password-reset', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                showAlert('success', data.message);
            } else {
                showAlert('error', data.message || 'An error occurred');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'An error occurred while sending password reset');
        });
    }
}

function changePerPage(value) {
    const url = new URL(window.location);
    url.searchParams.set('per_page', value);
    window.location.href = url.toString();
}

function searchUsers(query) {
    const rows = document.querySelectorAll('.user-row');
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(query.toLowerCase())) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function openShopAssignmentModal(userId, userName, currentRole, currentShopId) {
    document.getElementById('userNameDisplay').textContent = userName;
    document.getElementById('userAvatar').textContent = userName.substr(0, 2);

    // Set current values
    document.getElementById('shopSelect').value = currentShopId || '';
    document.getElementById('roleSelect').value = currentRole;

    // Set form action
    document.getElementById('shopAssignmentForm').setAttribute('data-user-id', userId);

    // Show/hide ownership warning
    updateOwnershipWarning();

    var modal = new bootstrap.Modal(document.getElementById('shopAssignmentModal'));
    modal.show();
}

function updateOwnershipWarning() {
    const roleSelect = document.getElementById('roleSelect');
    const warningDiv = document.getElementById('ownershipWarning');

    if (roleSelect.value === 'shop_owner') {
        warningDiv.style.display = 'block';
    } else {
        warningDiv.style.display = 'none';
    }
}

// Handle shop assignment form submission
document.addEventListener('DOMContentLoaded', function() {
    const shopAssignmentForm = document.getElementById('shopAssignmentForm');
    const roleSelect = document.getElementById('roleSelect');

    // Update warning when role changes
    roleSelect.addEventListener('change', updateOwnershipWarning);

    shopAssignmentForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const userId = this.getAttribute('data-user-id');
        const formData = new FormData(this);

        fetch('/admin/users/' + userId + '/update-shop-assignment', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                showAlert('success', data.message);
                bootstrap.Modal.getInstance(document.getElementById('shopAssignmentModal')).hide();
                location.reload();
            } else {
                showAlert('error', data.message || 'An error occurred');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'An error occurred while updating shop assignment');
        });
    });
});

function showAlert(type, message) {
    // Create alert element
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const iconPath = type === 'success'
        ? 'M5 12l5 5l10 -10'
        : 'M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0';

    const alertHTML = `
        <div class="alert ${alertClass} alert-dismissible" role="alert">
            <div class="d-flex">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="${iconPath}"/>
                    </svg>
                </div>
                <div>${message}</div>
            </div>
            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
        </div>
    `;

    // Insert alert at the top of the page body
    const pageBody = document.querySelector('.page-body .container-fluid');
    pageBody.insertAdjacentHTML('afterbegin', alertHTML);
}
</script>
@endpush
