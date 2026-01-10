@extends('layouts.nexora')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-fluid">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        Shop Management
                    </div>
                    <h2 class="page-title">
                        {{ $shop->name }}
                    </h2>
                    <div class="text-muted mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                            <path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z" />
                        </svg>
                        {{ $shop->address }} •
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" />
                        </svg>
                        {{ $shop->phone }} •
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" />
                            <path d="M3 7l9 6l9 -6" />
                        </svg>
                        {{ $shop->email }}
                    </div>
                </div>
                <!-- Page title actions -->
                <div class="col-auto ms-auto d-print-none">
                    <div class="d-flex gap-2 flex-wrap">
                        @if(auth()->user()->role === 'shop_owner' && $shop->owner_id === auth()->id())
                            <a href="{{ route('admin.shops.edit', $shop) }}" class="btn d-none d-sm-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                    <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                    <path d="M16 5l3 3" />
                                </svg>
                                Edit Shop
                            </a>
                            <a href="{{ route('admin.shops.edit', $shop) }}" class="btn d-sm-none btn-icon" aria-label="Edit shop" title="Edit Shop">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                    <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                    <path d="M16 5l3 3" />
                                </svg>
                            </a>
                        @endif
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-primary d-none d-sm-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M9 14l-4 -4l4 -4" />
                                <path d="M5 10h11a4 4 0 1 1 0 8h-1" />
                            </svg>
                            Back to Dashboard
                        </a>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-primary d-sm-none btn-icon" aria-label="Back to dashboard" title="Back to Dashboard">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M9 14l-4 -4l4 -4" />
                                <path d="M5 10h11a4 4 0 1 1 0 8h-1" />
            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-fluid">
            <x-alert />

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h3>{{ $shop->users_count ?? 0 }}</h3>
                                    <p class="mb-0">Team Members</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-users fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h3>{{ $shop->products_count ?? 0 }}</h3>
                                    <p class="mb-0">Products</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-box fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h3>{{ $shopKpis->total_orders ?? ($shop->orders_count ?? 0) }}</h3>
                                    <p class="mb-0">Orders</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-shopping-cart fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h3>LKR {{ number_format(($shopKpis->total_amount ?? 0) / 100, 2) }}</h3>
                                    <p class="mb-0">Total Sales</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-dollar-sign fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Shop Details -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Shop Information</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Owner:</th>
                                    <td>{{ $shop->owner->name }}</td>
                                </tr>
                                <tr>
                                    <th>Created:</th>
                                    <td>{{ $shop->created_at->format('F d, Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="badge bg-success">Active</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Team Members -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5>Team Members</h5>
                            @if(auth()->user()->role === 'shop_owner' && $shop->owner_id === auth()->id())
                                <a href="{{ route('users.create', ['shop_id' => $shop->id]) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus"></i> Add Member
                                </a>
                            @endif
                        </div>
                        <div class="card-body">
                            @if(safe_count($shopUsers) > 0)
                                <div class="list-group list-group-flush">
                                        @foreach($shopUsers as $user)
                                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                            <div>
                                                <strong>{{ $user->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $user->email }}</small>
                                            </div>
                                            <div>
                                                    <span class="badge bg-{{ $user->role === 'shop_owner' ? 'primary' : ($user->role === 'manager' ? 'success' : 'info') }}">
                                                        {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                                    </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted text-center py-3">No team members assigned to this shop yet.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            @if(safe_count($recentOrders) > 0)
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5>Recent Orders</h5>
                                <a href="{{ route('orders.index', ['shop_id' => $shop->id]) }}" class="btn btn-sm btn-outline-primary">
                                    View All Orders
                                </a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Invoice #</th>
                                                <th>Customer</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach(($recentOrders ?? collect()) as $order)
                                                <tr>
                                                    <td>{{ $order->invoice_no }}</td>
                                                    <td>{{ $order->customer->name }}</td>
                                                    <td>{{ $order->order_date->format('M d, Y') }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $order->order_status->value === 'completed' ? 'success' : 'warning' }}">
                                                            {{ ucfirst($order->order_status->value) }}
                                                        </span>
                                                    </td>
                                                    <td>LKR {{ number_format($order->total, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
