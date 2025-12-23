@extends('layouts.admin')

@section('content')
<div class="page-header d-print-none">
    <div class="container-fluid">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Admin Dashboard
                </h2>
                <div class="text-muted mt-1">Complete system control - manage users, shops, and all operations</div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-fluid">

        <!-- Statistics Cards -->
        <div class="row row-deck row-cards">
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Total Shops</div>
                        </div>
                        <div class="h1 mb-3">{{ $stats['total_shops'] }}</div>
                        <div class="d-flex mb-2">
                            <div class="flex-fill">
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-primary" style="width: 100%" role="progressbar"></div>
                                </div>
                            </div>
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
                            <div class="flex-fill">
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-green" style="width: {{ $stats['total_shops'] > 0 ? ($stats['active_shops'] / $stats['total_shops']) * 100 : 0 }}%" role="progressbar"></div>
                                </div>
                            </div>
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
                            <div class="flex-fill">
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-red" style="width: {{ $stats['total_shops'] > 0 ? ($stats['suspended_shops'] / $stats['total_shops']) * 100 : 0 }}%" role="progressbar"></div>
                                </div>
                            </div>
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
                            <div class="flex-fill">
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-orange" style="width: {{ $stats['total_shops'] > 0 ? ($stats['overdue_shops'] / $stats['total_shops']) * 100 : 0 }}%" role="progressbar"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Cards -->
        <div class="row row-deck row-cards mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Quick Actions</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <a href="{{ route('admin.shops.index') }}" class="btn btn-primary w-100 mb-2">
                                    <svg class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M3 21l18 0"></path>
                                        <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16"></path>
                                        <path d="M9 9l0 4"></path>
                                        <path d="M12 7l0 6"></path>
                                        <path d="M15 11l0 2"></path>
                                    </svg>
                                    Manage Shops
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-info w-100 mb-2">
                                    <svg class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                        <path d="M21 21v-2a4 4 0 0 0 -3 -3.85"></path>
                                    </svg>
                                    Manage Users
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- Overdue Shops Alert -->
    @if(safe_count($overdueShops) > 0)
        <div class="row row-deck row-cards mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-red-lt">
                        <h3 class="card-title text-red">
                            <svg class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M12 9v2m0 4v.01"></path>
                                <path d="M5.07 19H19a2 2 0 0 0 1.84 -2.75L13.41 4a2 2 0 0 0 -3.82 0L2.07 16.25a2 2 0 0 0 1.84 2.75"></path>
                            </svg>
                            Overdue Shops Requiring Attention
                        </h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>Shop Name</th>
                                    <th>Owner</th>
                                    <th>Subscription End</th>
                                    <th>Days Overdue</th>
                                    <th>Monthly Fee</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($overdueShops as $shop)
                                <tr>
                                    <td>
                                        <div class="d-flex py-1 align-items-center">
                                            <div class="flex-fill">
                                                <div class="font-weight-medium">{{ $shop->name }}</div>
                                                <div class="text-muted">{{ $shop->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        {{ $shop->owner->name ?? 'No Owner' }}
                                    </td>
                                    <td>
                                        <span class="text-red">{{ $shop->subscription_end_date->format('M d, Y') }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-red">{{ $shop->subscription_end_date->diffInDays(now()) }} days</span>
                                    </td>
                                    <td>
                                        LKR {{ number_format($shop->monthly_fee, 2) }}
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.shops.show', $shop) }}" class="btn btn-sm btn-primary">
                                            Manage
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection
