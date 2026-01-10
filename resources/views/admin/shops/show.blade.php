
@extends('layouts.admin')

@section('content')
<div class="page-header d-print-none">
    <div class="container-fluid">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Admin Panel / <a href="{{ route('admin.shops.index') }}">Shops</a>
                </div>
                <h2 class="page-title">
                    {{ $shop->name }}
                </h2>
            </div>
            <div class="col-12 col-md-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('admin.shops.index') }}" class="btn btn-ghost-dark">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="m0 0h24v24H0z" fill="none"/>
                            <polyline points="15 6 9 12 15 18"/>
                        </svg>
                        Back to Shops
                    </a>
                    <a href="{{ route('admin.shops.edit', $shop) }}" class="btn btn-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="m0 0h24v24H0z" fill="none"/>
                            <path d="m7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                            <path d="m20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                            <path d="m16 5l3 3"/>
                        </svg>
                        Edit Shop
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

        <div class="row row-deck row-cards">
            <!-- Shop Information -->
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Shop Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="datagrid">
                            <div class="datagrid-item">
                                <div class="datagrid-title">Name</div>
                                <div class="datagrid-content">{{ $shop->name }}</div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Email</div>
                                <div class="datagrid-content">
                                    <a href="mailto:{{ $shop->email }}">{{ $shop->email }}</a>
                                </div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Phone</div>
                                <div class="datagrid-content">{{ $shop->phone ?: 'Not provided' }}</div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Address</div>
                                <div class="datagrid-content">{{ $shop->address ?: 'Not provided' }}</div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Status</div>
                                <div class="datagrid-content">
                                    @if(isset($shop->is_active) && $shop->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Created</div>
                                <div class="datagrid-content">{{ $shop->created_at->format('M d, Y h:i A') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shop Owner -->
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Shop Owner</h3>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <span class="avatar avatar-lg me-3">{{ substr($shop->owner->name, 0, 2) }}</span>
                            <div>
                                <h4 class="m-0">{{ $shop->owner->name }}</h4>
                                <div class="text-muted">{{ $shop->owner->email }}</div>
                                <div class="text-muted">
                                    <small>{{ $shop->owner->username }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="datagrid">
                            <div class="datagrid-item">
                                <div class="datagrid-title">Role</div>
                                <div class="datagrid-content">
                                    <span class="badge bg-blue">{{ ucfirst(str_replace('_', ' ', $shop->owner->role)) }}</span>
                                </div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Joined</div>
                                <div class="datagrid-content">{{ $shop->owner->created_at->format('M d, Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subscription Information -->
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Subscription</h3>
                    </div>
                    <div class="card-body">
                        <div class="datagrid">
                            <div class="datagrid-item">
                                <div class="datagrid-title">Status</div>
                                <div class="datagrid-content">
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
                                    @else
                                        <span class="badge bg-gray">Not Set</span>
                                    @endif
                                </div>
                            </div>
                            @if(isset($shop->subscription_end_date))
                            <div class="datagrid-item">
                                <div class="datagrid-title">End Date</div>
                                <div class="datagrid-content">
                                    {{ \Carbon\Carbon::parse($shop->subscription_end_date)->format('M d, Y') }}
                                    @if(\Carbon\Carbon::parse($shop->subscription_end_date)->isPast())
                                        <span class="badge bg-red ms-2">Expired</span>
                                    @elseif(\Carbon\Carbon::parse($shop->subscription_end_date)->diffInDays() <= 7)
                                        <span class="badge bg-yellow ms-2">Expires Soon</span>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shop Statistics -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Shop Statistics</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6 col-lg-3">
                                <div class="card card-sm">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <span class="bg-primary text-white avatar">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="m0 0h24v24H0z" fill="none"/>
                                                        <circle cx="12" cy="7" r="4"/>
                                                        <path d="m6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="col">
                                                <div class="font-weight-medium">
                                                    {{ $shop->users_count ?? 0 }}
                                                </div>
                                                <div class="text-muted">Users</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="card card-sm">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <span class="bg-success text-white avatar">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="m0 0h24v24H0z" fill="none"/>
                                                        <rect x="4" y="4" width="16" height="16" rx="2"/>
                                                        <path d="m9 12l2 2l4 -4"/>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="col">
                                                <div class="font-weight-medium">
                                                    {{ $shop->products_count ?? 0 }}
                                                </div>
                                                <div class="text-muted">Products</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="card card-sm">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <span class="bg-info text-white avatar">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="m0 0h24v24H0z" fill="none"/>
                                                        <circle cx="12" cy="12" r="3"/>
                                                        <path d="m12 1v6m0 6v6"/>
                                                        <path d="m21 12h-6m-6 0h-6"/>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="col">
                                                <div class="font-weight-medium">
                                                    {{ $shop->customers_count ?? 0 }}
                                                </div>
                                                <div class="text-muted">Customers</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="card card-sm">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <span class="bg-warning text-white avatar">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="m0 0h24v24H0z" fill="none"/>
                                                        <rect x="4" y="4" width="16" height="16" rx="2"/>
                                                        <path d="m9 9h6v6h-6z"/>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="col">
                                                <div class="font-weight-medium">
                                                    {{ $shop->orders_count ?? 0 }}
                                                </div>
                                                <div class="text-muted">Orders</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Users -->
            @if(safe_count($shopUsers) > 0)
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Users ({{ $shop->users_count ?? safe_count($shopUsers) }})</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @foreach($shopUsers->take(5) as $user)
                                <div class="list-group-item">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span class="avatar">{{ substr($user->name, 0, 2) }}</span>
                                        </div>
                                        <div class="col text-truncate">
                                            <span class="text-body d-block">{{ $user->name }}</span>
                                            <small class="d-block text-muted text-truncate mt-n1">
                                                {{ $user->email }} • {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                            </small>
                                        </div>
                                        <div class="col-auto">
                                            <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @if(($shop->users_count ?? 0) > 5)
                        <div class="card-footer">
                            <div class="text-center">
                                <small class="text-muted">and {{ max(0, ($shop->users_count ?? 0) - 5) }} more users...</small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Recent Products -->
            @if(safe_count($recentProducts) > 0)
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Recent Products ({{ $shop->products_count ?? safe_count($recentProducts) }})</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @foreach($recentProducts->take(5) as $product)
                                <div class="list-group-item">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            @if($product->photo)
                                                <span class="avatar" style="background-image: url({{ Storage::url($product->photo) }})"></span>
                                            @else
                                                <span class="avatar">{{ substr($product->name, 0, 2) }}</span>
                                            @endif
                                        </div>
                                        <div class="col text-truncate">
                                            <span class="text-body d-block">{{ $product->name }}</span>
                                            <small class="d-block text-muted text-truncate mt-n1">
                                                Code: {{ $product->code }} • Stock: {{ $product->quantity }}
                                            </small>
                                        </div>
                                        <div class="col-auto">
                                            <span class="badge bg-blue">LKR {{ number_format($product->selling_price, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @if(($shop->products_count ?? 0) > 5)
                        <div class="card-footer">
                            <div class="text-center">
                                <small class="text-muted">and {{ max(0, ($shop->products_count ?? 0) - 5) }} more products...</small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
