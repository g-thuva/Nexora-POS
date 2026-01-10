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
                    <a href="{{ route('dashboard') }}" class="btn btn-ghost-dark">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="m0 0h24v24H0z" fill="none"/>
                            <polyline points="5 12 3 12 12 3 21 12 19 12"/>
                            <path d="m5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7"/>
                            <path d="m9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6"/>
                        </svg>
                        Back to Dashboard
                    </a>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-white d-none d-sm-inline-block">
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
                        <div class="table-responsive" style="padding-bottom: 180px;">
                            <table class="table card-table table-vcenter text-nowrap datatable">
                                <thead>
                                    <tr>
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
                                                @if($user->is_suspended)
                                                    <span class="badge bg-danger" title="{{ $user->suspension_reason }}">Suspended</span>
                                                @else
                                                    <span class="badge bg-success">Active</span>
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
                                            <td class="text-end">
                                                <div class="btn-list flex-nowrap justify-content-end">
                                                    <div class="dropdown" style="position: relative;">
                                                        <button class="btn dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="true" data-bs-display="static">
                                                            Actions
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-end" style="min-width: 200px; position: absolute; right: 0;">
                                                            <a class="dropdown-item" href="{{ route('admin.users.show', $user) }}">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path stroke="none" d="m0 0h24v24H0z" fill="none"/>
                                                                    <circle cx="12" cy="12" r="2"/>
                                                                    <path d="m12 1c.835 0 1.52 .205 2.05 .623a3.441 3.441 0 0 1 1.343 3.146c.096 .443 -.071 .884 -.334 1.317l-2.059 3.914l-2.059 -3.914c-.263 -.433 -.43 -.874 -.334 -1.317a3.441 3.441 0 0 1 1.343 -3.146c.53 -.418 1.215 -.623 2.05 -.623z"/>
                                                                </svg>
                                                                View Profile
                                                            </a>
                                                            <a class="dropdown-item" href="{{ route('admin.users.edit', $user) }}">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path stroke="none" d="m0 0h24v24H0z" fill="none"/>
                                                                    <path d="m7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                                                                    <path d="m20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                                                                    <path d="m16 5l3 3"/>
                                                                </svg>
                                                                Edit User
                                                            </a>
                                                            @if($user->role !== 'admin')
                                                                <a class="dropdown-item text-blue" href="{{ route('admin.users.assign-shop', $user) }}">
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
                                                            @if($user->role !== 'admin' && $user->id !== auth()->id())
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item {{ $user->is_suspended ? 'text-green' : 'text-yellow' }}" href="{{ route('admin.users.suspend', $user) }}">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                        <path stroke="none" d="m0 0h24v24H0z" fill="none"/>
                                                                        @if($user->is_suspended)
                                                                            <path d="M5 13a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-6z" />
                                                                            <path d="M11 16a1 1 0 1 0 2 0a1 1 0 0 0 -2 0" />
                                                                            <path d="M8 11v-5a4 4 0 0 1 8 0" />
                                                                        @else
                                                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                                                            <line x1="9" y1="9" x2="15" y2="15"/>
                                                                            <line x1="15" y1="9" x2="9" y2="15"/>
                                                                        @endif
                                                                    </svg>
                                                                    {{ $user->is_suspended ? 'Unsuspend User' : 'Suspend User' }}
                                                                </a>
                                                                <a class="dropdown-item text-red" href="{{ route('admin.users.delete.page', $user) }}">
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
                                <a href="{{ route('admin.users.create') }}" class="btn btn-white">
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
                <h5 class="modal-title">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-primary" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M3 21l18 0"/>
                        <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16"/>
                        <path d="M9 9l0 4"/>
                        <path d="M12 7l0 6"/>
                        <path d="M15 11l0 2"/>
                    </svg>
                    Assign User to Shop
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="shopAssignmentForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="form-label fw-bold">User Information</label>
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <span class="avatar avatar-md me-3 bg-primary text-white" id="userAvatar">U</span>
                                    <div>
                                        <div class="h3 mb-1" id="userNameDisplay">User Name</div>
                                        <div class="text-muted">
                                            <span class="badge bg-blue-lt" id="currentRoleDisplay">Role</span>
                                            <span class="ms-2 badge bg-green-lt" id="currentShopDisplay">Shop</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M3 21l18 0"/>
                                        <path d="M3 7v1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1h-18l2 -4h14l2 4"/>
                                        <path d="M5 21l0 -10.15"/>
                                        <path d="M19 21l0 -10.15"/>
                                        <path d="M9 21v-4a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v4"/>
                                    </svg>
                                    Shop Assignment
                                </label>
                                <select class="form-select" name="shop_id" id="shopSelect" required>
                                    <option value="">Select Shop</option>
                                    @foreach($shops ?? [] as $shop)
                                        <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                                    @endforeach
                                </select>
                                <small class="form-hint">Choose which shop this user belongs to</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"/>
                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/>
                                    </svg>
                                    User Role
                                </label>
                                <select class="form-select" name="role" id="roleSelect" required>
                                    <option value="employee">Employee</option>
                                    <option value="manager">Manager</option>
                                    <option value="shop_owner">Shop Owner</option>
                                </select>
                                <small class="form-hint">Determines user's access level</small>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-warning alert-dismissible" id="ownershipWarning" style="display: none;">
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
                                <h4 class="alert-title">Ownership Transfer Warning!</h4>
                                <div class="text-muted">Making this user a <strong>Shop Owner</strong> will automatically demote the current shop owner to <strong>Manager</strong> role.</div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
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
                                <h4 class="alert-title">Quick Tip</h4>
                                <div class="text-muted">
                                    <strong>Shop Owner:</strong> Full control over shop operations<br>
                                    <strong>Manager:</strong> Can manage staff and operations<br>
                                    <strong>Employee:</strong> Basic access to daily operations
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M5 12l5 5l10 -10"/>
                        </svg>
                        Update Assignment
                    </button>
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
                                <button type="submit" class="btn btn-white w-100">Delete User</button>
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

function unsuspendUser(userId, userName) {
    if(confirm('Are you sure you want to unsuspend ' + userName + '? They will be able to log in immediately.')) {
        fetch('/admin/users/' + userId + '/unsuspend', {
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
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                showAlert('error', data.message || 'An error occurred');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'An error occurred while unsuspending user');
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
    const userNameDisplay = document.getElementById('userNameDisplay');
    const userAvatar = document.getElementById('userAvatar');
    const shopSelect = document.getElementById('shopSelect');
    const roleSelect = document.getElementById('roleSelect');
    const currentRoleDisplay = document.getElementById('currentRoleDisplay');
    const currentShopDisplay = document.getElementById('currentShopDisplay');

    // Update user info display
    userNameDisplay.textContent = userName;
    userAvatar.textContent = userName.charAt(0).toUpperCase();

    // Update current status badges
    if (currentRole) {
        const roleFormatted = currentRole.replace('_', ' ').toUpperCase();
        currentRoleDisplay.textContent = roleFormatted;
    } else {
        currentRoleDisplay.textContent = 'NO ROLE';
    }

    if (currentShopId) {
        const currentShopOption = shopSelect.querySelector(`option[value="${currentShopId}"]`);
        currentShopDisplay.textContent = currentShopOption ? currentShopOption.textContent : 'No Shop';
        currentShopDisplay.className = 'ms-2 badge bg-green-lt';
    } else {
        currentShopDisplay.textContent = 'No Shop Assigned';
        currentShopDisplay.className = 'ms-2 badge bg-red-lt';
    }

    // Set current values in form
    shopSelect.value = currentShopId || '';
    roleSelect.value = currentRole || 'employee';

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
