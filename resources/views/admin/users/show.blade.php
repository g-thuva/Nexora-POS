@extends('layouts.admin')

@section('content')
<div class="page-header d-print-none">
    <div class="container-fluid">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Admin Panel</div>
                <h2 class="page-title">{{ $user->name }}</h2>
            </div>
            <div class="col-12 col-md-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                            <path d="M16 5l3 3"/>
                        </svg>
                        Edit User
                    </a>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Back to Users</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-fluid">
        <!-- Profile / Actions / Stats -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row align-items-center gap-4">
                    <div class="text-center" style="min-width:160px;">
                        <span class="avatar avatar-xl mb-3" style="font-size:2.5rem; background:#f3f3f3; color:#222;">{{ strtoupper(Str::substr($user->name, 0, 2)) }}</span>
                        <h3 class="m-0 mb-1">{{ $user->name }}</h3>
                        <div class="text-muted mb-1">{{ $user->email }}</div>
                        <div class="mb-2">
                            @if($user->username)
                                <span class="badge bg-light text-dark">@{{ $user->username }}</span>
                            @else
                                <span class="badge bg-light text-dark">No Username</span>
                            @endif
                        </div>
                        <div class="mb-2">
                            @if($user->role === 'admin')
                                <span class="badge bg-red">Super Admin</span>
                            @elseif($user->role === 'shop_owner')
                                <span class="badge bg-blue">Shop Owner</span>
                            @elseif($user->role === 'manager')
                                <span class="badge bg-green">Manager</span>
                            @elseif($user->role === 'employee')
                                <span class="badge bg-yellow">Employee</span>
                            @elseif($user->role === 'suspended')
                                <span class="badge bg-gray">Suspended</span>
                            @else
                                <span class="badge bg-gray">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</span>
                            @endif
                        </div>
                        @if($user->shop)
                            <div class="mb-2">
                                <span class="badge bg-primary">Shop: {{ $user->shop->name }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex-fill w-100">
                        <div class="row g-2 mb-3">
                            <div class="col-6 col-md-3">
                                <div class="card text-center">
                                    <div class="card-body p-2">
                                        <div class="text-muted small">Pending Orders</div>
                                        <div class="h4 m-0">{{ $stats['pending_orders'] ?? 0 }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="card text-center">
                                    <div class="card-body p-2">
                                        <div class="text-muted small">Completed</div>
                                        <div class="h4 m-0">{{ $stats['completed_orders'] ?? 0 }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            @if(!$user->email_verified_at)
                                <button class="btn btn-success btn-sm" onclick="verifyUserEmail({{ $user->id }})">Verify Email</button>
                            @else
                                <button class="btn btn-warning btn-sm" onclick="unverifyUserEmail({{ $user->id }})">Unverify</button>
                            @endif
                            <button class="btn btn-info btn-sm" onclick="sendPasswordReset({{ $user->id }})">Reset Password</button>
                            @if($user->role !== 'admin' && $user->id !== auth()->id())
                                <button class="btn btn-warning btn-sm" onclick="toggleUserAccess({{ $user->id }})">Suspend</button>
                                <button class="btn btn-danger btn-sm" onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')">Delete</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Details -->
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">User Details</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter mb-0">
                    <tbody>
                                <tr>
                                    <td class="w-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="m0 0h24v24H0z" fill="none"/>
                                            <circle cx="12" cy="12" r="3"/>
                                            <path d="M12 1v6m0 6v6"/>
                                            <path d="M21 12h-6m-6 0H3"/>
                                        </svg>
                                    </td>
                                    <td class="font-weight-medium">Full Name</td>
                                    <td class="text-muted">{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <td class="w-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="m0 0h24v24H0z" fill="none"/>
                                            <rect x="3" y="5" width="18" height="14" rx="2"/>
                                            <polyline points="3,7 12,13 21,7"/>
                                        </svg>
                                    </td>
                                    <td class="font-weight-medium">Email</td>
                                    <td class="text-muted">
                                        {{ $user->email }}
                                        @if($user->email_verified_at)
                                            <span class="badge bg-success ms-2">Verified</span>
                                        @else
                                            <span class="badge bg-warning ms-2">Unverified</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($user->username)
                                <tr>
                                    <td class="w-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="m0 0h24v24H0z" fill="none"/>
                                            <circle cx="12" cy="12" r="3"/>
                                            <path d="M12 1v6m0 6v6"/>
                                            <path d="M21 12h-6m-6 0H3"/>
                                        </svg>
                                    </td>
                                    <td class="font-weight-medium">Username</td>
                                    <td class="text-muted">@{{ $user->username }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td class="w-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="m0 0h24v24H0z" fill="none"/>
                                            <circle cx="9" cy="7" r="4"/>
                                            <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/>
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                            <path d="M21 21v-2a4 4 0 0 0 -3 -3.85"/>
                                        </svg>
                                    </td>
                                    <td class="font-weight-medium">Role</td>
                                    <td class="text-muted">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</td>
                                </tr>
                                <tr>
                                    <td class="w-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M3 21l18 0"/>
                                            <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16"/>
                                            <path d="M9 9l0 4"/>
                                            <path d="M12 7l0 6"/>
                                            <path d="M15 11l0 2"/>
                                        </svg>
                                    </td>
                                    <td class="font-weight-medium">Shop Assignment</td>
                                    <td class="text-muted">
                                        @if($user->shop)
                                            <div class="d-flex align-items-center">
                                                <span class="avatar avatar-xs me-2">{{ substr($user->shop->name, 0, 2) }}</span>
                                                <div>
                                                    <div class="font-weight-medium">{{ $user->shop->name }}</div>
                                                    <div class="text-muted small">{{ $user->shop->email }}</div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">No shop assigned</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="w-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="m0 0h24v24H0z" fill="none"/>
                                            <rect x="4" y="5" width="16" height="16" rx="2"/>
                                            <line x1="16" y1="3" x2="16" y2="7"/>
                                            <line x1="8" y1="3" x2="8" y2="7"/>
                                            <rect x="8" y="11" width="2" height="2"/>
                                            <rect x="10" y="13" width="2" height="2"/>
                                            <rect x="12" y="11" width="2" height="2"/>
                                        </svg>
                                    </td>
                                    <td class="font-weight-medium">Created</td>
                                    <td class="text-muted">
                                        {{ $user->created_at->format('M d, Y h:i A') }}
                                        <div class="small text-muted">{{ $user->created_at->diffForHumans() }}</div>
                                    </td>
                                </tr>
                                @if($user->email_verified_at)
                                <tr>
                                    <td class="w-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-green" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="9"/>
                                            <path d="m9 12l2 2l4 -4"/>
                                        </svg>
                                    </td>
                                    <td class="font-weight-medium">Email Verified</td>
                                    <td class="text-muted">
                                        {{ $user->email_verified_at->format('M d, Y h:i A') }}
                                        <div class="small text-muted">{{ $user->email_verified_at->diffForHumans() }}</div>
                                    </td>
                                </tr>
                                @endif
                                @if($user->last_login_at)
                                <tr>
                                    <td class="w-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="m0 0h24v24H0z" fill="none"/>
                                            <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2"/>
                                            <path d="M20 12h-13l3 -3m0 6l-3 -3"/>
                                        </svg>
                                    </td>
                                    <td class="font-weight-medium">Last Login</td>
                                    <td class="text-muted">
                                        {{ $user->last_login_at->format('M d, Y h:i A') }}
                                        <div class="small text-muted">{{ $user->last_login_at->diffForHumans() }}</div>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
        </div>

        <!-- Recent Orders -->
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">Recent Orders</h3>
                <div class="card-actions">
                    <span class="badge bg-blue">Last 10 Orders</span>
                </div>
            </div>
            @if(($stats['total_orders'] ?? 0) > 0)
                <div class="table-responsive">
                    <table class="table card-table table-vcenter">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th class="w-1">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->orders as $order)
                                <tr>
                                    <td>#{{ $order->id }}</td>
                                    <td>
                                        <div>{{ optional($order->order_date)->format('M d, Y') }}</div>
                                        <div class="text-muted small">{{ optional($order->order_date)->format('h:i A') }}</div>
                                    </td>
                                    <td>
                                        <div class="font-weight-medium">LKR {{ number_format($order->total ?? $order->total_amount ?? 0, 2) }}</div>
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'processing' => 'info',
                                                'completed' => 'success',
                                                'cancelled' => 'danger',
                                                'refunded' => 'secondary',
                                                0 => 'warning',
                                                1 => 'success',
                                            ];
                                            $label = is_numeric($order->status ?? $order->order_status)
                                                ? ((($order->status ?? $order->order_status) == 1) ? 'completed' : 'pending')
                                                : ($order->status ?? 'unknown');
                                            $color = $statusColors[$order->status ?? $order->order_status ?? 'pending'] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $color }}">{{ ucfirst($label) }}</span>
                                    </td>
                                    <td>
                                        @php $payment = $order->payment_type ?? $order->payment_method ?? 'N/A'; @endphp
                                        <span class="badge bg-gray-lt">{{ ucfirst($payment) }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-ghost-primary">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="card-body">
                    <div class="empty">
                        <div class="empty-img">
                            <img src="{{ asset('static/illustrations/undraw_printing_invoices_5r4r.svg') }}" height="128" alt="">
                        </div>
                        <p class="empty-title">No orders found</p>
                        <p class="empty-subtitle text-muted">This user hasn't placed any orders yet.</p>
                    </div>
                </div>
            @endif
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
