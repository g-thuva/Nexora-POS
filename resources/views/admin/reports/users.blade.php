@extends('layouts.admin')

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xxl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        Admin Reports
                    </div>
                    <h2 class="page-title">
                        User Reports
                    </h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M9 11l-4 4l4 4m-4 -4h11a4 4 0 0 0 0 -8h-1" />
                            </svg>
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xxl">
            <div class="row row-deck row-cards">
                <!-- Summary Cards -->
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Total Users</div>
                            </div>
                            <div class="h1 mb-3">{{ $stats['total_users'] }}</div>
                            <div class="d-flex mb-2">
                                <div>All registered users in the system</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Administrators</div>
                            </div>
                            <div class="h1 mb-3">{{ $stats['admin_users'] }}</div>
                            <div class="d-flex mb-2">
                                <div>Users with admin privileges</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Shop Owners</div>
                            </div>
                            <div class="h1 mb-3">{{ $stats['shop_owner_users'] }}</div>
                            <div class="d-flex mb-2">
                                <div>Users managing shops</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Managers</div>
                            </div>
                            <div class="h1 mb-3">{{ $stats['manager_users'] }}</div>
                            <div class="d-flex mb-2">
                                <div>Users with manager role</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Distribution by Role -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">User Distribution by Role</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-vcenter card-table">
                                    <thead>
                                        <tr>
                                            <th>Role</th>
                                            <th>Count</th>
                                            <th>Percentage</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($usersByRole as $role)
                                            <tr>
                                                <td>
                                                    <span class="badge bg-{{ $role->role_color ?? 'secondary' }}">
                                                        {{ ucwords(str_replace('_', ' ', $role->role)) }}
                                                    </span>
                                                </td>
                                                <td>{{ $role->total }}</td>
                                                <td>
                                                    <div class="progress" style="width: 200px;">
                                                        <div class="progress-bar" style="width: {{ $role->percentage }}%"
                                                            role="progressbar">
                                                            {{ number_format($role->percentage, 1) }}%
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="text-muted">
                                                        Active: {{ $role->active_count }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent User Activity -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Recently Created Users</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-vcenter card-table">
                                    <thead>
                                        <tr>
                                            <th>User</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Shop</th>
                                            <th>Created</th>
                                            <th>Email Verified</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recentUsers as $user)
                                            <tr>
                                                <td>
                                                    <div class="d-flex py-1 align-items-center">
                                                        <span class="avatar me-2">{{ substr($user->name, 0, 2) }}</span>
                                                        <div class="flex-fill">
                                                            <div class="font-weight-medium">{{ $user->name }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    <span class="badge bg-blue-lt">
                                                        {{ ucwords(str_replace('_', ' ', $user->role)) }}
                                                    </span>
                                                </td>
                                                <td>{{ $user->shop->name ?? 'N/A' }}</td>
                                                <td>{{ $user->created_at->diffForHumans() }}</td>
                                                <td>
                                                    @if ($user->email_verified_at)
                                                        <span class="badge bg-success">Verified</span>
                                                    @else
                                                        <span class="badge bg-warning">Unverified</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
