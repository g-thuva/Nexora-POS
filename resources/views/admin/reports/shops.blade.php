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
                        Shop Reports
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
                                <div class="subheader">Total Shops</div>
                            </div>
                            <div class="h1 mb-3">{{ $stats['total_shops'] }}</div>
                            <div class="d-flex mb-2">
                                <div>All registered shops</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Active Shops</div>
                            </div>
                            <div class="h1 mb-3 text-green">{{ $stats['active_shops'] }}</div>
                            <div class="d-flex mb-2">
                                <div>Currently operational</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Suspended Shops</div>
                            </div>
                            <div class="h1 mb-3 text-red">{{ $stats['suspended_shops'] }}</div>
                            <div class="d-flex mb-2">
                                <div>Currently suspended</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Overdue Shops</div>
                            </div>
                            <div class="h1 mb-3 text-orange">{{ $stats['overdue_shops'] }}</div>
                            <div class="d-flex mb-2">
                                <div>Subscription expired</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shop Status Distribution -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Shop Status Distribution</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="table-responsive">
                                        <table class="table table-vcenter card-table">
                                            <thead>
                                                <tr>
                                                    <th>Status</th>
                                                    <th>Count</th>
                                                    <th>Percentage</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($shopsByStatus as $status)
                                                    <tr>
                                                        <td>
                                                            <span
                                                                class="badge bg-{{ $status->status === 'active' ? 'success' : ($status->status === 'suspended' ? 'danger' : 'warning') }}">
                                                                {{ ucfirst($status->status) }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $status->total }}</td>
                                                        <td>
                                                            <div class="progress" style="width: 150px;">
                                                                <div class="progress-bar" style="width: {{ $status->percentage }}%"
                                                                    role="progressbar">
                                                                    {{ number_format($status->percentage, 1) }}%
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card-title mb-3">Revenue Status</div>
                                    <div class="mb-3">
                                        <div class="text-muted">Total Active Subscription Value</div>
                                        <div class="h2">LKR {{ number_format($stats['active_subscription_value'], 2) }}</div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="text-muted">Average Subscription per Shop</div>
                                        <div class="h3">LKR {{ number_format($stats['avg_subscription'], 2) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shop Performance -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Top Performing Shops</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-vcenter card-table">
                                    <thead>
                                        <tr>
                                            <th>Shop Name</th>
                                            <th>Owner</th>
                                            <th>Status</th>
                                            <th>Users</th>
                                            <th>Products</th>
                                            <th>Subscription End</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($topShops as $shop)
                                            <tr>
                                                <td>
                                                    <div class="d-flex py-1 align-items-center">
                                                        <div class="flex-fill">
                                                            <div class="font-weight-medium">{{ $shop->name }}</div>
                                                            <div class="text-muted">{{ $shop->address }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $shop->owner->name ?? 'N/A' }}</td>
                                                <td>
                                                    @if ($shop->subscription_status === 'active' && $shop->is_active)
                                                        <span class="badge bg-success">Active</span>
                                                    @elseif ($shop->subscription_status === 'suspended')
                                                        <span class="badge bg-danger">Suspended</span>
                                                    @else
                                                        <span class="badge bg-warning">Overdue</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-blue">{{ $shop->users_count }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-purple">{{ $shop->products_count }}</span>
                                                </td>
                                                <td>
                                                    @if ($shop->subscription_end_date)
                                                        <span
                                                            class="text-{{ $shop->subscription_end_date < now() ? 'danger' : 'muted' }}">
                                                            {{ $shop->subscription_end_date->format('Y-m-d') }}
                                                        </span>
                                                    @else
                                                        N/A
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

                <!-- Recent Activity -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Recently Created Shops</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-vcenter card-table">
                                    <thead>
                                        <tr>
                                            <th>Shop Name</th>
                                            <th>Owner</th>
                                            <th>Contact</th>
                                            <th>Created</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recentShops as $shop)
                                            <tr>
                                                <td>{{ $shop->name }}</td>
                                                <td>{{ $shop->owner->name ?? 'N/A' }}</td>
                                                <td>{{ $shop->phone }}</td>
                                                <td>{{ $shop->created_at->diffForHumans() }}</td>
                                                <td>
                                                    @if ($shop->is_active)
                                                        <span class="badge bg-success">Active</span>
                                                    @else
                                                        <span class="badge bg-danger">Inactive</span>
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
