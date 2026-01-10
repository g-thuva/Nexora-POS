@extends('layouts.admin')

@section('content')
<div class="page-header d-print-none">
    <div class="container-fluid">
        <div class="row g-2 align-items-center mb-3">
            <div class="col">
                <div class="page-pretitle">Admin Panel</div>
                <h2 class="page-title">Suspended Users</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-ghost-dark">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <circle cx="12" cy="7" r="4"/>
                            <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/>
                        </svg>
                        All Users
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-white d-none d-sm-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M5 12l-2 0l9 -9l9 9l-2 0"/>
                            <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7"/>
                            <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6"/>
                        </svg>
                        Back to Dashboard
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
                            <path d="m12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"/>
                            <path d="m9 12l2 2l4 -4"/>
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
                <div class="card" style="min-height: 600px; overflow: visible;">
                    <div class="card-header">
                        <h3 class="card-title">All Suspended Users</h3>
                        <div class="card-actions">
                            <span class="badge bg-red">{{ $users->total() }} Suspended</span>
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
                                    <input type="text" class="form-control form-control-sm" aria-label="Search users" placeholder="Search suspended users..." onkeyup="searchUsers(this.value)">
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(safe_count($users) > 0)
                        <div class="table-responsive" style="padding-bottom: 250px;">
                            <table class="table card-table table-vcenter text-nowrap datatable">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Role</th>
                                        <th>Shop</th>
                                        <th>Suspended At</th>
                                        <th>Suspension Reason</th>
                                        <th>Suspended By</th>
                                        <th>Ends At</th>
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
                                                @elseif($user->role === 'shop_owner')
                                                    <span class="badge bg-blue">Shop Owner</span>
                                                @elseif($user->role === 'manager')
                                                    <span class="badge bg-green">Manager</span>
                                                @elseif($user->role === 'employee')
                                                    <span class="badge bg-yellow">Employee</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($user->role) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->shop)
                                                    <div class="d-flex align-items-center">
                                                        <span class="avatar avatar-sm me-2 bg-blue-lt">{{ substr($user->shop->name, 0, 2) }}</span>
                                                        <div>
                                                            <div>{{ $user->shop->name }}</div>
                                                            <div class="text-muted small">{{ $user->shop->email }}</div>
                                                        </div>
                                                    </div>
                                                @elseif($user->ownedShop)
                                                    <div class="d-flex align-items-center">
                                                        <span class="avatar avatar-sm me-2 bg-purple-lt">{{ substr($user->ownedShop->name, 0, 2) }}</span>
                                                        <div>
                                                            <div>{{ $user->ownedShop->name }}</div>
                                                            <div class="text-muted small"><strong>Owner</strong></div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="text-muted">No Shop</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="text-muted">
                                                    @if($user->suspended_at)
                                                        {{ $user->suspended_at->diffForHumans() }}
                                                        <div class="small">{{ $user->suspended_at->format('M d, Y h:i A') }}</div>
                                                    @else
                                                        -
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-truncate" style="max-width: 200px;" title="{{ $user->suspension_reason }}">
                                                    {{ $user->suspension_reason ?? 'No reason provided' }}
                                                </div>
                                                @if($user->suspension_type)
                                                    <span class="badge bg-danger-lt mt-1">{{ ucfirst(str_replace('_', ' ', $user->suspension_type)) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->suspendedBy)
                                                    <div class="text-muted">
                                                        <div>{{ $user->suspendedBy->name }}</div>
                                                        <div class="small">{{ $user->suspendedBy->email }}</div>
                                                    </div>
                                                @else
                                                    <span class="text-muted">System</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="text-muted">
                                                    @if($user->suspension_ends_at)
                                                        {{ $user->suspension_ends_at->format('M d, Y') }}
                                                        <div class="small">{{ $user->suspension_ends_at->diffForHumans() }}</div>
                                                    @elseif($user->suspension_type === 'lifetime')
                                                        <span class="badge bg-danger">Permanent</span>
                                                    @else
                                                        <span class="text-muted">Manual Review</span>
                                                    @endif
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
                                                            <div class="dropdown-divider"></div>
                                                            <a class="dropdown-item text-green" href="{{ route('admin.users.suspend', $user) }}">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path stroke="none" d="m0 0h24v24H0z" fill="none"/>
                                                                    <path d="M5 13a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-6z" />
                                                                    <path d="M11 16a1 1 0 1 0 2 0a1 1 0 0 0 -2 0" />
                                                                    <path d="M8 11v-5a4 4 0 0 1 8 0" />
                                                                </svg>
                                                                Unsuspend User
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
                            {{ $users->links() }}
                        </div>
                    @else
                        <div class="empty">
                            <div class="empty-img">
                                <img src="{{ asset('static/illustrations/undraw_happy_announcement_ac67.svg') }}" height="128" alt="">
                            </div>
                            <p class="empty-title">No suspended users</p>
                            <p class="empty-subtitle text-muted">
                                Great news! There are currently no suspended users in the system.
                            </p>
                            <div class="empty-action">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <circle cx="12" cy="7" r="4"/>
                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/>
                                    </svg>
                                    View All Users
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function changePerPage(value) {
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', value);
        window.location.href = url.toString();
    }

    function searchUsers(query) {
        const rows = document.querySelectorAll('.user-row');
        const searchQuery = query.toLowerCase();

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if (text.includes(searchQuery)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
@endsection
