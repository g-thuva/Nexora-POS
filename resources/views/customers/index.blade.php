@extends('layouts.nexora')

@section('title', 'Customer Management')

@section('content')
<div class="page-header d-print-none">
    <div class="container-fluid">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Customer Relations
                </div>
                <h2 class="page-title">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24"
                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                        <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                    </svg>
                    Customer Management
                </h2>
                <p class="text-muted">Manage customer information, track purchase history, and maintain customer relationships</p>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('customers.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg>
                        Add Customer
                    </a>
                    <a href="{{ route('customers.create') }}" class="btn btn-primary d-sm-none btn-icon" aria-label="Add customer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M12 5l0 14"/>
                            <path d="M5 12l14 0"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    @if($customers->isEmpty())
    <div class="container-fluid">
        <!-- Customer Statistics - Empty State -->
        <div class="row mb-4">
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Total Customers</div>
                        </div>
                        <div class="h2 mb-0">0</div>
                        <div class="text-muted small">Active accounts</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">New Today</div>
                        </div>
                        <div class="h2 mb-0 text-success">0</div>
                        <div class="text-muted small">Registered today</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">This Month</div>
                        </div>
                        <div class="h2 mb-0 text-info">0</div>
                        <div class="text-muted small">New customers</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Total Purchases</div>
                        </div>
                        <div class="h2 mb-0 text-primary">LKR 0.00</div>
                        <div class="text-muted small">Revenue generated</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Management Table - Empty State -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Customer List</h3>
                    </div>
                    <div class="card-body">
                        <div class="empty">
                            <div class="empty-img">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="128" height="128"
                                    viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <circle cx="9" cy="7" r="4" />
                                    <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                    <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                                </svg>
                            </div>
                            <p class="empty-title">No customers found</p>
                            <p class="empty-subtitle text-muted">
                                Create your first customer account to start managing customer relationships and track purchase history.
                            </p>
                            <div class="empty-action">
                                <a href="{{ route('customers.create') }}" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 5l0 14" />
                                        <path d="M5 12l14 0" />
                                    </svg>
                                    Add Your First Customer
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="container-fluid">
        <x-alert/>

        <!-- Customer Statistics -->
        <div class="row mb-4">
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Total Customers</div>
                        </div>
                        <div class="h2 mb-0">{{ $customers->total() ?? safe_count($customers) }}</div>
                        <div class="text-muted small">Active accounts</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">New Today</div>
                        </div>
                        <div class="h2 mb-0 text-success">{{ $new_today_count ?? 0 }}</div>
                        <div class="text-muted small">Registered today</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">This Month</div>
                        </div>
                        <div class="h2 mb-0 text-info">{{ $new_this_month_count ?? 0 }}</div>
                        <div class="text-muted small">New customers</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Total Purchases</div>
                        </div>
                        <div class="h2 mb-0 text-primary">LKR {{ number_format(($total_purchases_cents ?? 0) / 100, 2) }}</div>
                        <div class="text-muted small">Revenue generated</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Management Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Customer List</h3>
                    </div>
                    <div class="card-body">
                        @livewire('tables.customer-table')
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
