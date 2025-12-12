@extends('layouts.nexora')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-fluid">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        Overview
                    </div>
                    <h2 class="page-title">
                        Dashboard
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-auto ms-auto d-print-none">
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('products.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                            <x-icon.plus/>
                            Add Product
                        </a>
                        <a href="{{ route('products.create') }}" class="btn btn-primary d-sm-none btn-icon" aria-label="Add new product" title="Add Product">
                            <x-icon.plus/>
                        </a>
                        <a href="{{ route('orders.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                            <x-icon.plus/>
                            New Order
                        </a>
                        <a href="{{ route('orders.create') }}" class="btn btn-primary d-sm-none btn-icon" aria-label="Create new order" title="New Order">
                            <x-icon.plus/>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-fluid">
            @if(isset($userType) && $userType === 'super_admin')
                <!-- Super Admin Dashboard -->
                <div class="row row-deck row-cards mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">System Overview</h3>
                            </div>
                            <div class="card-body">
                                <!-- Shop Sales Overview -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h4 class="mb-3">Shop Sales Overview</h4>
                                        <div class="row">
                                            @if(isset($shopsWithSales) && safe_count($shopsWithSales) > 0)
                                                @foreach($shopsWithSales as $shop)
                                                    <div class="col-sm-6 col-lg-4 col-xl-3 mb-3">
                                                        <div class="card card-sm border-primary">
                                                            <div class="card-body">
                                                                <div class="row align-items-center">
                                                                    <div class="col-auto">
                                                                        <span class="bg-primary text-white avatar">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                                                                <path d="M3 21h18"/>
                                                                                <path d="M5 21V7l8-4v18"/>
                                                                                <path d="M19 21V11l-6-4"/>
                                                                            </svg>
                                                                        </span>
                                                                    </div>
                                                                    <div class="col">
                                                                        <div class="font-weight-medium text-truncate" title="{{ $shop->name }}">{{ $shop->name }}</div>
                                                                        <div class="h3 m-0 {{ $shop->sales_total > 0 ? 'text-primary' : 'text-muted' }}">
                                                                            ${{ number_format($shop->sales_total, 2) }}
                                                                        </div>
                                                                        <div class="text-muted small">
                                                                            {{ $shop->completed_orders }} completed orders
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="col-12">
                                                    <div class="card card-sm border-warning">
                                                        <div class="card-body text-center">
                                                            <div class="text-muted">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon mb-2">
                                                                    <path d="M3 21h18"/>
                                                                    <path d="M5 21V7l8-4v18"/>
                                                                    <path d="M19 21V11l-6-4"/>
                                                                </svg>
                                                                <h4>No Shops Found</h4>
                                                                <p class="mb-0">Create your first shop to see sales data here.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- System Stats Row -->
                                <div class="row">
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="card card-sm border-primary">
                                            <div class="card-body">
                                                <div class="row align-items-center">
                                                    <div class="col-auto">
                                                        <span class="bg-primary text-white avatar">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                                                <path d="M3 21h18"/>
                                                                <path d="M5 21V7l8-4v18"/>
                                                                <path d="M19 21V11l-6-4"/>
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <div class="col">
                                                        <div class="font-weight-medium">{{ $totalShops ?? 0 }} Shops</div>
                                                        <div class="text-muted">Active stores</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="card card-sm border-success">
                                            <div class="card-body">
                                                <div class="row align-items-center">
                                                    <div class="col-auto">
                                                        <span class="bg-success text-white avatar">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                                                                <circle cx="9" cy="7" r="4"/>
                                                                <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                                                                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <div class="col">
                                                        <div class="font-weight-medium">{{ $totalUsers ?? 0 }} Users</div>
                                                        <div class="text-muted">System-wide</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="card card-sm border-info">
                                            <div class="card-body">
                                                <div class="row align-items-center">
                                                    <div class="col-auto">
                                                        <span class="bg-info text-white avatar">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                                                <path d="M7 16.5l-5 -3l5 -3l5 3v5.5l-5 3z"/>
                                                                <path d="M2 13.5v5.5l5 3"/>
                                                                <path d="M7 16.545l5 -3.03"/>
                                                                <path d="M17 16.5l-5 -3l5 -3l5 3v5.5l-5 3z"/>
                                                                <path d="M12 19l5 3"/>
                                                                <path d="M17 16.5l5 -3"/>
                                                                <path d="M12 13.5v-5.5l-5 -3l5 -3l5 3v5.5"/>
                                                                <path d="M7 5.03v5.455"/>
                                                                <path d="M12 8l5 -3"/>
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <div class="col">
                                                        <div class="font-weight-medium">{{ $products ?? 0 }} Products</div>
                                                        <div class="text-muted">{{ $categories ?? 0 }} categories</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="card card-sm border-warning">
                                            <div class="card-body">
                                                <div class="row align-items-center">
                                                    <div class="col-auto">
                                                        <span class="bg-warning text-white avatar">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                                                <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/>
                                                                <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/>
                                                                <path d="M17 17h-11v-14h-2"/>
                                                                <path d="M6 5l14 1l-1 7h-13"/>
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <div class="col">
                                                        <div class="font-weight-medium">{{ $orders ?? 0 }} Orders</div>
                                                        <div class="text-muted">{{ $completedOrders ?? 0 }} completed</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Super Admin Quick Actions -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-2">
                                        <circle cx="12" cy="12" r="3"/>
                                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1 1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                                    </svg>
                                    System Management
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <!-- Create New Shop -->
                                    <div class="col-sm-6 col-md-4 col-lg-3">
                                        <a href="{{ route('shops.create') }}" class="btn btn-primary w-100 btn-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon mb-2">
                                                <path d="M3 21h18"/>
                                                <path d="M5 21V7l8-4v18"/>
                                                <path d="M19 21V11l-6-4"/>
                                            </svg>
                                            <div>Create Shop</div>
                                            <small class="text-muted">Add new store</small>
                                        </a>
                                    </div>

                                    <!-- Manage Users -->
                                    <div class="col-sm-6 col-md-4 col-lg-3">
                                        <a href="{{ route('users.index') }}" class="btn btn-success w-100 btn-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon mb-2">
                                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                                <circle cx="9" cy="7" r="4"/>
                                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                            </svg>
                                            <div>Manage Users</div>
                                            <small class="text-muted">User accounts</small>
                                        </a>
                                    </div>

                                    <!-- Units & Warranties -->
                                    <div class="col-sm-6 col-md-4 col-lg-3">
                                        <a href="{{ route('units.index') }}" class="btn btn-info w-100 btn-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon mb-2">
                                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                                <path d="M9 12l2 2 4-4"/>
                                            </svg>
                                            <div>Units & Warranties</div>
                                            <small class="text-muted">Admin only</small>
                                        </a>
                                    </div>

                                    <!-- System Reports -->
                                    <div class="col-sm-6 col-md-4 col-lg-3">
                                        <a href="{{ route('reports.sales.index') }}" class="btn btn-orange w-100 btn-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon mb-2">
                                                <path d="M3 3v18h18"/>
                                                <path d="M18.7 8l-5-5"/>
                                                <path d="M7 12l5 5 5-5"/>
                                            </svg>
                                            <div>System Reports</div>
                                            <small class="text-muted">Analytics</small>
                                        </a>
                                    </div>

                                    <!-- All Products -->
                                    <div class="col-sm-6 col-md-4 col-lg-3">
                                        <a href="{{ route('products.index') }}" class="btn btn-purple w-100 btn-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon mb-2">
                                                <path d="M7 16.5l-5 -3l5 -3l5 3v5.5l-5 3z"/>
                                                <path d="M2 13.5v5.5l5 3"/>
                                                <path d="M7 16.545l5 -3.03"/>
                                                <path d="M17 16.5l-5 -3l5 -3l5 3v5.5l-5 3z"/>
                                                <path d="M12 19l5 3"/>
                                                <path d="M17 16.5l5 -3"/>
                                                <path d="M12 13.5v-5.5l-5 -3l5 -3l5 3v5.5"/>
                                                <path d="M7 5.03v5.455"/>
                                                <path d="M12 8l5 -3"/>
                                            </svg>
                                            <div>All Products</div>
                                            <small class="text-muted">System-wide</small>
                                        </a>
                                    </div>

                                    <!-- All Orders -->
                                    <div class="col-sm-6 col-md-4 col-lg-3">
                                        <a href="{{ route('orders.index') }}" class="btn btn-teal w-100 btn-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon mb-2">
                                                <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/>
                                                <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/>
                                                <path d="M17 17h-11v-14h-2"/>
                                                <path d="M6 5l14 1l-1 7h-13"/>
                                            </svg>
                                            <div>All Orders</div>
                                            <small class="text-muted">System-wide</small>
                                        </a>
                                    </div>

                                    <!-- Categories -->
                                    <div class="col-sm-6 col-md-4 col-lg-3">
                                        <a href="{{ route('categories.index') }}" class="btn btn-yellow w-100 btn-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon mb-2">
                                                <rect x="3" y="3" width="7" height="7"/>
                                                <rect x="14" y="3" width="7" height="7"/>
                                                <rect x="14" y="14" width="7" height="7"/>
                                                <rect x="3" y="14" width="7" height="7"/>
                                            </svg>
                                            <div>Categories</div>
                                            <small class="text-muted">Product groups</small>
                                        </a>
                                    </div>

                                    <!-- System Settings -->
                                    <div class="col-sm-6 col-md-4 col-lg-3">
                                        <a href="{{ route('profile.settings') }}" class="btn btn-dark w-100 btn-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon mb-2">
                                                <circle cx="12" cy="12" r="3"/>
                                                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1 1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                                            </svg>
                                            <div>System Settings</div>
                                            <small class="text-muted">Configuration</small>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif(isset($userType) && $userType === 'shop_user')
                <!-- Shop User Dashboard -->
                <div class="row row-deck row-cards mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{ $shopName ?? 'Shop' }} Overview</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6 col-lg-3">
                                        <a href="{{ route('products.index') }}" class="text-decoration-none">
                                            <div class="card card-sm border-primary">
                                                <div class="card-body">
                                                    <div class="row align-items-center">
                                                        <div class="col-auto">
                                                            <span class="bg-primary text-white avatar">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                                                    <path d="M7 16.5l-5 -3l5 -3l5 3v5.5l-5 3z"/>
                                                                    <path d="M2 13.5v5.5l5 3"/>
                                                                    <path d="M7 16.545l5 -3.03"/>
                                                                    <path d="M17 16.5l-5 -3l5 -3l5 3v5.5l-5 3z"/>
                                                                    <path d="M12 19l5 3"/>
                                                                    <path d="M17 16.5l5 -3"/>
                                                                    <path d="M12 13.5v-5.5l-5 -3l5 -3l5 3v5.5"/>
                                                                    <path d="M7 5.03v5.455"/>
                                                                    <path d="M12 8l5 -3"/>
                                                                </svg>
                                                            </span>
                                                        </div>
                                                        <div class="col">
                                                            <div class="font-weight-medium">{{ $products }} Products</div>
                                                            <div class="text-muted">{{ $categories }} categories</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-sm-6 col-lg-3">
                                        <a href="{{ route('orders.index') }}" class="text-decoration-none">
                                            <div class="card card-sm border-success">
                                                <div class="card-body">
                                                    <div class="row align-items-center">
                                                        <div class="col-auto">
                                                            <span class="bg-success text-white avatar">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                                                    <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/>
                                                                    <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/>
                                                                    <path d="M17 17h-11v-14h-2"/>
                                                                    <path d="M6 5l14 1l-1 7h-13"/>
                                                                </svg>
                                                            </span>
                                                        </div>
                                                        <div class="col">
                                                            <div class="font-weight-medium">{{ $orders }} Orders</div>
                                                            <div class="text-muted">{{ $completedOrders }} completed</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    @if(isset($lowStockProducts))
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="card card-sm border-warning">
                                            <div class="card-body">
                                                <div class="row align-items-center">
                                                    <div class="col-auto">
                                                        <span class="bg-warning text-white avatar">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                                                <path d="M12 9v4"/>
                                                                <path d="M12 17h.01"/>
                                                                <path d="M5 19h14a2 2 0 0 0 1.6-3.2L14 4a2 2 0 0 0-3.2 0L4.4 15.8A2 2 0 0 0 6 19z"/>
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <div class="col">
                                                        <div class="font-weight-medium">{{ $lowStockProducts }} Low Stock</div>
                                                        <div class="text-muted">Items need restocking</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Total Sales Card -->
                                    <div class="col-sm-6 col-lg-3">
                                        <a href="{{ route('orders.index') }}" class="text-decoration-none">
                                            <div class="card card-sm border-success">
                                                <div class="card-body">
                                                    <div class="row align-items-center">
                                                        <div class="col-auto">
                                                            <span class="bg-success text-white avatar">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                                                    <line x1="12" y1="1" x2="12" y2="23"/>
                                                                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                                                                </svg>
                                                            </span>
                                                        </div>
                                                        <div class="col">
                                                            <div class="font-weight-medium">${{ number_format($totalAllOrders ?? 0, 2) }}</div>
                                                            <div class="text-muted">{{ $shopName ?? 'This Shop' }} - Total Sales</div>
                                                            @if(isset($totalSales) && $totalSales > 0)
                                                                <div class="text-success small">
                                                                    ${{ number_format($totalSales, 2) }} completed only
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sales Analytics Section -->
                @if(isset($totalAllOrders) && $totalAllOrders > 0)
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{ $shopName ?? 'Shop' }} - Sales Performance</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="card card-sm border-primary">
                                            <div class="card-body">
                                                <div class="row align-items-center">
                                                    <div class="col-auto">
                                                        <span class="bg-primary text-white avatar">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                                                <line x1="12" y1="1" x2="12" y2="23"/>
                                                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <div class="col">
                                                        <div class="font-weight-medium">Shop Total Revenue</div>
                                                        <div class="h2 m-0 text-primary">${{ number_format($totalAllOrders, 2) }}</div>
                                                        <div class="text-muted">All {{ $shopName ?? 'shop' }} orders</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="card card-sm border-success">
                                            <div class="card-body">
                                                <div class="row align-items-center">
                                                    <div class="col-auto">
                                                        <span class="bg-success text-white avatar">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                                                <path d="M9 12l2 2 4-4"/>
                                                                <path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9c2.12 0 4.07.74 5.6 1.99"/>
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <div class="col">
                                                        <div class="font-weight-medium">Shop Completed Revenue</div>
                                                        <div class="h2 m-0 text-success">${{ number_format($totalSales ?? 0, 2) }}</div>
                                                        <div class="text-muted">{{ $shopName ?? 'Shop' }} completed orders only</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="card card-sm border-orange">
                                            <div class="card-body">
                                                <div class="row align-items-center">
                                                    <div class="col-auto">
                                                        <span class="bg-orange text-white avatar">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                                                <path d="M3 3v18h18"/>
                                                                <path d="M7 16l3-3 3 1 4-4"/>
                                                                <circle cx="7" cy="16" r="2"/>
                                                                <circle cx="10" cy="13" r="2"/>
                                                                <circle cx="17" cy="9" r="2"/>
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <div class="col">
                                                        <div class="font-weight-medium">Shop Average Order</div>
                                                        <div class="h3 m-0 text-orange">
                                                            ${{ $orders > 0 ? number_format($totalAllOrders / $orders, 2) : '0.00' }}
                                                        </div>
                                                        <div class="text-muted">{{ $shopName ?? 'Shop' }} average per order</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="card card-sm border-info">
                                            <div class="card-body">
                                                <div class="row align-items-center">
                                                    <div class="col-auto">
                                                        <span class="bg-info text-white avatar">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                                                <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/>
                                                                <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/>
                                                                <path d="M17 17h-11v-14h-2"/>
                                                                <path d="M6 5l14 1l-1 7h-13"/>
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <div class="col">
                                                        <div class="font-weight-medium">Shop Pending Revenue</div>
                                                        <div class="h3 m-0 text-info">${{ number_format(($totalAllOrders ?? 0) - ($totalSales ?? 0), 2) }}</div>
                                                        <div class="text-muted">{{ $shopName ?? 'Shop' }} pending orders</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Quick Actions Section -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-2">
                                        <circle cx="12" cy="12" r="3"/>
                                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1 1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                                    </svg>
                                    Quick Actions
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="row g-2 g-md-3">
                                    <!-- Add New Product -->
                                    <div class="col-6 col-sm-6 col-lg-3">
                                        <a href="{{ route('products.create') }}" class="text-decoration-none">
                                            <div class="card card-sm border-primary h-100">
                                                <div class="card-body d-flex align-items-center">
                                                    <div class="me-3">
                                                        <span class="bg-primary text-white avatar avatar-sm">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                                                <path d="M12 5v14"/>
                                                                <path d="M5 12h14"/>
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <div class="flex-fill min-width-0">
                                                        <div class="font-weight-medium text-truncate">Add Product</div>
                                                        <div class="text-muted small d-none d-sm-block">Create new item</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <!-- Create Order -->
                                    <div class="col-6 col-sm-6 col-lg-3">
                                        <a href="{{ route('orders.create') }}" class="text-decoration-none">
                                            <div class="card card-sm border-success h-100">
                                                <div class="card-body d-flex align-items-center">
                                                    <div class="me-3">
                                                        <span class="bg-success text-white avatar avatar-sm">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                                                <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/>
                                                                <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/>
                                                                <path d="M17 17h-11v-14h-2"/>
                                                                <path d="M6 5l14 1l-1 7h-13"/>
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <div class="flex-fill min-width-0">
                                                        <div class="font-weight-medium text-truncate">New Order</div>
                                                        <div class="text-muted small d-none d-sm-block">Create sale</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <!-- POS System -->
                                    <div class="col-6 col-sm-6 col-lg-3">
                                        <a href="{{ route('orders.create') }}" class="text-decoration-none">
                                            <div class="card card-sm border-info h-100">
                                                <div class="card-body d-flex align-items-center">
                                                    <div class="me-3">
                                                        <span class="bg-info text-white avatar avatar-sm">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                                                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                                                                <line x1="8" y1="21" x2="16" y2="21"/>
                                                                <line x1="12" y1="17" x2="12" y2="21"/>
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <div class="flex-fill min-width-0">
                                                        <div class="font-weight-medium text-truncate">POS System</div>
                                                        <div class="text-muted small d-none d-sm-block">Point of sale</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <!-- View Sales -->
                                    <div class="col-6 col-sm-6 col-lg-3">
                                        <a href="{{ route('orders.index') }}" class="text-decoration-none">
                                            <div class="card card-sm border-orange h-100">
                                                <div class="card-body d-flex align-items-center">
                                                    <div class="me-3">
                                                        <span class="bg-orange text-white avatar avatar-sm">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                                                <line x1="12" y1="1" x2="12" y2="23"/>
                                                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <div class="flex-fill min-width-0">
                                                        <div class="font-weight-medium text-truncate">View Sales</div>
                                                        <div class="text-muted small d-none d-sm-block">Order history</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    @if(Auth::user()->hasInventoryAccess())
                                    <!-- Manage Inventory -->
                                    <div class="col-6 col-sm-6 col-lg-3">
                                        <a href="{{ route('products.index') }}" class="text-decoration-none">
                                            <div class="card card-sm border-purple h-100">
                                                <div class="card-body d-flex align-items-center">
                                                    <div class="me-3">
                                                        <span class="bg-purple text-white avatar avatar-sm">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                                                <path d="M7 16.5l-5 -3l5 -3l5 3v5.5l-5 3z"/>
                                                                <path d="M2 13.5v5.5l5 3"/>
                                                                <path d="M7 16.545l5 -3.03"/>
                                                                <path d="M17 16.5l-5 -3l5 -3l5 3v5.5l-5 3z"/>
                                                                <path d="M12 19l5 3"/>
                                                                <path d="M17 16.5l5 -3"/>
                                                                <path d="M12 13.5v-5.5l-5 -3l5 -3l5 3v5.5"/>
                                                                <path d="M7 5.03v5.455"/>
                                                                <path d="M12 8l5 -3"/>
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <div class="flex-fill min-width-0">
                                                        <div class="font-weight-medium text-truncate">Inventory</div>
                                                        <div class="text-muted small d-none d-sm-block">Manage products</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    @endif

                                    <!-- Customers -->
                                    <div class="col-sm-6 col-lg-3">
                                        <a href="{{ route('customers.index') }}" class="text-decoration-none">
                                            <div class="card card-sm border-teal">
                                                <div class="card-body">
                                                    <div class="row align-items-center">
                                                        <div class="col-auto">
                                                            <span class="bg-teal text-white avatar">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                                                    <circle cx="12" cy="7" r="4"/>
                                                                </svg>
                                                            </span>
                                                        </div>
                                                        <div class="col">
                                                            <div class="font-weight-medium">Customers</div>
                                                            <div class="text-muted">Client list</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <!-- Reports -->
                                    <div class="col-sm-6 col-lg-3">
                                        <a href="{{ route('reports.sales.index') }}" class="text-decoration-none">
                                            <div class="card card-sm border-dark">
                                                <div class="card-body">
                                                    <div class="row align-items-center">
                                                        <div class="col-auto">
                                                            <span class="bg-dark text-white avatar">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                                                    <path d="M3 3v18h18"/>
                                                                    <path d="M18.7 8l-5-5"/>
                                                                    <path d="M7 12l5 5 5-5"/>
                                                                </svg>
                                                            </span>
                                                        </div>
                                                        <div class="col">
                                                            <div class="font-weight-medium">Reports</div>
                                                            <div class="text-muted">Analytics</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <!-- Settings -->
                                    <div class="col-sm-6 col-lg-3">
                                        <a href="{{ route('profile.settings') }}" class="text-decoration-none">
                                            <div class="card card-sm border-secondary">
                                                <div class="card-body">
                                                    <div class="row align-items-center">
                                                        <div class="col-auto">
                                                            <span class="bg-secondary text-white avatar">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                                                    <circle cx="12" cy="12" r="3"/>
                                                                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1 1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                                                                </svg>
                                                            </span>
                                                        </div>
                                                        <div class="col">
                                                            <div class="font-weight-medium">Settings</div>
                                                            <div class="text-muted">Configuration</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Default Dashboard -->
                <div class="row row-deck row-cards">
                    <div class="col-12">
                        <div class="row row-cards">
                            <div class="col-sm-6 col-lg-3">
                                <a href="{{ route('products.index') }}" class="text-decoration-none">
                                <div class="card card-sm">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                        <span class="bg-primary text-white avatar">
                                          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-nexora icon-nexora-packages" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 16.5l-5 -3l5 -3l5 3v5.5l-5 3z" /><path d="M2 13.5v5.5l5 3" /><path d="M7 16.545l5 -3.03" /><path d="M17 16.5l-5 -3l5 -3l5 3v5.5l-5 3z" /><path d="M12 19l5 3" /><path d="M17 16.5l5 -3" /><path d="M12 13.5v-5.5l-5 -3l5 -3l5 3v5.5" /><path d="M7 5.03v5.455" /><path d="M12 8l5 -3" /></svg>
                                        </span>
                                            </div>
                                            <div class="col">
                                                <div class="font-weight-medium">
                                                    {{ $products ?? 0 }} Products
                                                </div>
                                                <div class="text-muted">
                                                    {{ $categories ?? 0 }} categories
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <a href="{{ route('orders.index') }}" class="text-decoration-none">
                                <div class="card card-sm">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                        <span class="bg-green text-white avatar">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M17 17h-11v-14h-2" /><path d="M6 5l14 1l-1 7h-13" /></svg>
                                        </span>
                                            </div>
                                            <div class="col">
                                                <div class="font-weight-medium">
                                                    {{ $orders ?? 0 }} Orders
                                                </div>
                                                <div class="text-muted">
                                                    {{ $completedOrders ?? 0 }} {{ __('completed') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Default User Quick Actions -->
                <div class="row row-deck row-cards mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h3 class="card-title mb-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-2">
                                        <circle cx="12" cy="12" r="3"/>
                                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1 1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                                    </svg>
                                    Quick Actions
                                </h3>
                                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#defaultQuickActionsContent" aria-expanded="true" aria-controls="defaultQuickActionsContent" id="defaultQuickActionsToggle">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon" id="defaultCollapseIcon">
                                        <polyline points="6,9 12,15 18,9"></polyline>
                                    </svg>
                                    <span id="defaultToggleText">Collapse</span>
                                </button>
                            </div>
                            <div class="collapse show" id="defaultQuickActionsContent">
                                <div class="card-body">
                                    <div class="row g-3">
                                    <!-- Add New Product -->
                                    <div class="col-sm-6 col-md-4 col-lg-3">
                                        <a href="{{ route('products.create') }}" class="btn btn-primary w-100 btn-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon mb-2">
                                                <path d="M12 5v14"/>
                                                <path d="M5 12h14"/>
                                            </svg>
                                            <div>Add Product</div>
                                            <small class="text-muted">Create new item</small>
                                        </a>
                                    </div>

                                    <!-- Create Order -->
                                    <div class="col-sm-6 col-md-4 col-lg-3">
                                        <a href="{{ route('orders.create') }}" class="btn btn-success w-100 btn-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon mb-2">
                                                <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/>
                                                <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/>
                                                <path d="M17 17h-11v-14h-2"/>
                                                <path d="M6 5l14 1l-1 7h-13"/>
                                            </svg>
                                            <div>New Order</div>
                                            <small class="text-muted">Create sale</small>
                                        </a>
                                    </div>

                                    <!-- View Products -->
                                    <div class="col-sm-6 col-md-4 col-lg-3">
                                        <a href="{{ route('products.index') }}" class="btn btn-info w-100 btn-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon mb-2">
                                                <path d="M7 16.5l-5 -3l5 -3l5 3v5.5l-5 3z"/>
                                                <path d="M2 13.5v5.5l5 3"/>
                                                <path d="M7 16.545l5 -3.03"/>
                                                <path d="M17 16.5l-5 -3l5 -3l5 3v5.5l-5 3z"/>
                                                <path d="M12 19l5 3"/>
                                                <path d="M17 16.5l5 -3"/>
                                                <path d="M12 13.5v-5.5l-5 -3l5 -3l5 3v5.5"/>
                                                <path d="M7 5.03v5.455"/>
                                                <path d="M12 8l5 -3"/>
                                            </svg>
                                            <div>View Products</div>
                                            <small class="text-muted">Inventory list</small>
                                        </a>
                                    </div>

                                    <!-- View Orders -->
                                    <div class="col-sm-6 col-md-4 col-lg-3">
                                        <a href="{{ route('orders.index') }}" class="btn btn-orange w-100 btn-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon mb-2">
                                                <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/>
                                                <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/>
                                                <path d="M17 17h-11v-14h-2"/>
                                                <path d="M6 5l14 1l-1 7h-13"/>
                                            </svg>
                                            <div>View Orders</div>
                                            <small class="text-muted">Sales history</small>
                                        </a>
                                    </div>

                                    <!-- POS System -->
                                    <div class="col-sm-6 col-md-4 col-lg-3">
                                        <a href="{{ route('orders.create') }}" class="btn btn-purple w-100 btn-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon mb-2">
                                                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                                                <line x1="8" y1="21" x2="16" y2="21"/>
                                                <line x1="12" y1="17" x2="12" y2="21"/>
                                            </svg>
                                            <div>POS System</div>
                                            <small class="text-muted">Point of sale</small>
                                        </a>
                                    </div>

                                    <!-- Customers -->
                                    <div class="col-sm-6 col-md-4 col-lg-3">
                                        <a href="{{ route('customers.index') }}" class="btn btn-teal w-100 btn-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon mb-2">
                                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                                <circle cx="12" cy="7" r="4"/>
                                            </svg>
                                            <div>Customers</div>
                                            <small class="text-muted">Client database</small>
                                        </a>
                                    </div>

                                    <!-- Reports -->
                                    <div class="col-sm-6 col-md-4 col-lg-3">
                                        <a href="{{ route('reports.sales.index') }}" class="btn btn-yellow w-100 btn-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon mb-2">
                                                <path d="M3 3v18h18"/>
                                                <path d="M18.7 8l-5-5"/>
                                                <path d="M7 12l5 5 5-5"/>
                                            </svg>
                                            <div>Reports</div>
                                            <small class="text-muted">View analytics</small>
                                        </a>
                                    </div>

                                    <!-- Settings -->
                                    <div class="col-sm-6 col-md-4 col-lg-3">
                                        <a href="{{ route('profile.settings') }}" class="btn btn-secondary w-100 btn-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon mb-2">
                                                <circle cx="12" cy="12" r="3"/>
                                                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1 1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                                            </svg>
                                            <div>Settings</div>
                                            <small class="text-muted">Preferences</small>
                                        </a>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif


            </div>
        </div>
    </div>
@endsection

@push('page-libraries')
    <script src="{{ asset('dist/libs/apexcharts/dist/apexcharts.min.js') }}" defer></script>
    <script src="{{ asset('dist/libs/jsvectormap/dist/js/jsvectormap.min.js') }}" defer></script>
    <script src="{{ asset('dist/libs/jsvectormap/dist/maps/world.js') }}" defer></script>
    <script src="{{ asset('dist/libs/jsvectormap/dist/maps/world-merc.js') }}" defer></script>
@endpush

@pushonce('page-scripts')
    <script>
        // @formatter:off
        document.addEventListener("DOMContentLoaded", function () {
            window.ApexCharts && (new ApexCharts(document.getElementById('chart-revenue-bg'), {
                chart: {
                    type: "area",
                    fontFamily: 'inherit',
                    height: 40.0,
                    sparkline: {
                        enabled: true
                    },
                    animations: {
                        enabled: false
                    },
                },
                dataLabels: {
                    enabled: false,
                },
                fill: {
                    opacity: .16,
                    type: 'solid'
                },
                stroke: {
                    width: 2,
                    lineCap: "round",
                    curve: "smooth",
                },
                series: [{
                    name: "Profits",
                    data: [37, 35, 44, 28, 36, 24, 65, 31, 37, 39, 62, 51, 35, 41, 35, 27, 93, 53, 61, 27, 54, 43, 19, 46, 39, 62, 51, 35, 41, 67]
                }],
                tooltip: {
                    theme: 'dark'
                },
                grid: {
                    strokeDashArray: 4,
                },
                xaxis: {
                    labels: {
                        padding: 0,
                    },
                    tooltip: {
                        enabled: false
                    },
                    axisBorder: {
                        show: false,
                    },
                    type: 'datetime',
                },
                yaxis: {
                    labels: {
                        padding: 4
                    },
                },
                labels: [
                    '2020-06-20', '2020-06-21', '2020-06-22', '2020-06-23', '2020-06-24', '2020-06-25', '2020-06-26', '2020-06-27', '2020-06-28', '2020-06-29', '2020-06-30', '2020-07-01', '2020-07-02', '2020-07-03', '2020-07-04', '2020-07-05', '2020-07-06', '2020-07-07', '2020-07-08', '2020-07-09', '2020-07-10', '2020-07-11', '2020-07-12', '2020-07-13', '2020-07-14', '2020-07-15', '2020-07-16', '2020-07-17', '2020-07-18', '2020-07-19'
                ],
                colors: [tabler.getColor("primary")],
                legend: {
                    show: false,
                },
            })).render();
        });
        // @formatter:on
    </script>
    <script>
        // @formatter:off
        document.addEventListener("DOMContentLoaded", function () {
            window.ApexCharts && (new ApexCharts(document.getElementById('chart-new-clients'), {
                chart: {
                    type: "line",
                    fontFamily: 'inherit',
                    height: 40.0,
                    sparkline: {
                        enabled: true
                    },
                    animations: {
                        enabled: false
                    },
                },
                fill: {
                    opacity: 1,
                },
                stroke: {
                    width: [2, 1],
                    dashArray: [0, 3],
                    lineCap: "round",
                    curve: "smooth",
                },
                series: [{
                    name: "May",
                    data: [37, 35, 44, 28, 36, 24, 65, 31, 37, 39, 62, 51, 35, 41, 35, 27, 93, 53, 61, 27, 54, 43, 4, 46, 39, 62, 51, 35, 41, 67]
                },{
                    name: "April",
                    data: [93, 54, 51, 24, 35, 35, 31, 67, 19, 43, 28, 36, 62, 61, 27, 39, 35, 41, 27, 35, 51, 46, 62, 37, 44, 53, 41, 65, 39, 37]
                }],
                tooltip: {
                    theme: 'dark'
                },
                grid: {
                    strokeDashArray: 4,
                },
                xaxis: {
                    labels: {
                        padding: 0,
                    },
                    tooltip: {
                        enabled: false
                    },
                    type: 'datetime',
                },
                yaxis: {
                    labels: {
                        padding: 4
                    },
                },
                labels: [
                    '2020-06-20', '2020-06-21', '2020-06-22', '2020-06-23', '2020-06-24', '2020-06-25', '2020-06-26', '2020-06-27', '2020-06-28', '2020-06-29', '2020-06-30', '2020-07-01', '2020-07-02', '2020-07-03', '2020-07-04', '2020-07-05', '2020-07-06', '2020-07-07', '2020-07-08', '2020-07-09', '2020-07-10', '2020-07-11', '2020-07-12', '2020-07-13', '2020-07-14', '2020-07-15', '2020-07-16', '2020-07-17', '2020-07-18', '2020-07-19'
                ],
                colors: [tabler.getColor("primary"), tabler.getColor("gray-600")],
                legend: {
                    show: false,
                },
            })).render();
        });
        // @formatter:on
    </script>
    <script>
        // @formatter:off
        document.addEventListener("DOMContentLoaded", function () {
            window.ApexCharts && (new ApexCharts(document.getElementById('chart-active-users'), {
                chart: {
                    type: "bar",
                    fontFamily: 'inherit',
                    height: 40.0,
                    sparkline: {
                        enabled: true
                    },
                    animations: {
                        enabled: false
                    },
                },
                plotOptions: {
                    bar: {
                        columnWidth: '50%',
                    }
                },
                dataLabels: {
                    enabled: false,
                },
                fill: {
                    opacity: 1,
                },
                series: [{
                    name: "Profits",
                    data: [37, 35, 44, 28, 36, 24, 65, 31, 37, 39, 62, 51, 35, 41, 35, 27, 93, 53, 61, 27, 54, 43, 19, 46, 39, 62, 51, 35, 41, 67]
                }],
                tooltip: {
                    theme: 'dark'
                },
                grid: {
                    strokeDashArray: 4,
                },
                xaxis: {
                    labels: {
                        padding: 0,
                    },
                    tooltip: {
                        enabled: false
                    },
                    axisBorder: {
                        show: false,
                    },
                    type: 'datetime',
                },
                yaxis: {
                    labels: {
                        padding: 4
                    },
                },
                labels: [
                    '2020-06-20', '2020-06-21', '2020-06-22', '2020-06-23', '2020-06-24', '2020-06-25', '2020-06-26', '2020-06-27', '2020-06-28', '2020-06-29', '2020-06-30', '2020-07-01', '2020-07-02', '2020-07-03', '2020-07-04', '2020-07-05', '2020-07-06', '2020-07-07', '2020-07-08', '2020-07-09', '2020-07-10', '2020-07-11', '2020-07-12', '2020-07-13', '2020-07-14', '2020-07-15', '2020-07-16', '2020-07-17', '2020-07-18', '2020-07-19'
                ],
                colors: [tabler.getColor("primary")],
                legend: {
                    show: false,
                },
            })).render();
        });
        // @formatter:on
    </script>

    <!-- Quick Actions Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Function to handle Quick Actions toggle
            function setupQuickActionsToggle(toggleId, contentId, iconId, textId, storageKey) {
                const toggle = document.getElementById(toggleId);
                const content = document.getElementById(contentId);
                const icon = document.getElementById(iconId);
                const text = document.getElementById(textId);

                if (toggle && content && icon && text) {
                    // Handle toggle button click
                    toggle.addEventListener('click', function() {
                        const isCollapsed = !content.classList.contains('show');

                        if (isCollapsed) {
                            // Currently collapsed, so expand
                            icon.innerHTML = '<polyline points="6,9 12,15 18,9"></polyline>';
                            text.textContent = 'Collapse';
                            localStorage.setItem(storageKey, 'true');
                        } else {
                            // Currently expanded, so collapse
                            icon.innerHTML = '<polyline points="9,18 15,12 9,6"></polyline>';
                            text.textContent = 'Expand';
                            localStorage.setItem(storageKey, 'false');
                        }
                    });

                    // Restore state from localStorage
                    const savedState = localStorage.getItem(storageKey);
                    if (savedState === 'false') {
                        content.classList.remove('show');
                        icon.innerHTML = '<polyline points="9,18 15,12 9,6"></polyline>';
                        text.textContent = 'Expand';
                        toggle.setAttribute('aria-expanded', 'false');
                    }

                    // Listen for Bootstrap collapse events to sync the button state
                    content.addEventListener('shown.bs.collapse', function() {
                        icon.innerHTML = '<polyline points="6,9 12,15 18,9"></polyline>';
                        text.textContent = 'Collapse';
                        toggle.setAttribute('aria-expanded', 'true');
                    });

                    content.addEventListener('hidden.bs.collapse', function() {
                        icon.innerHTML = '<polyline points="9,18 15,12 9,6"></polyline>';
                        text.textContent = 'Expand';
                        toggle.setAttribute('aria-expanded', 'false');
                    });
                }
            }

            // Setup for admin Quick Actions
            setupQuickActionsToggle('quickActionsToggle', 'quickActionsContent', 'collapseIcon', 'toggleText', 'quickActionsExpanded');

            // Setup for default user Quick Actions
            setupQuickActionsToggle('defaultQuickActionsToggle', 'defaultQuickActionsContent', 'defaultCollapseIcon', 'defaultToggleText', 'defaultQuickActionsExpanded');
        });
    </script>
@endpushonce
