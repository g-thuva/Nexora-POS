@extends('layouts.nexora')

@section('title', 'Customer Credit Report')

@section('content')
<div class="page-header d-print-none">
    <div class="container-fluid">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Finance Reports
                </div>
                <h2 class="page-title">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"/>
                        <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        <path d="M21 21v-2a4 4 0 0 0 -3 -3.85"/>
                    </svg>
                    Customer Credit Summary
                </h2>
                <p class="text-muted">Track customer credit balances and payment history</p>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('reports.sales.index') }}" class="btn btn-outline-secondary d-none d-sm-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M9 11l-4 4l4 4m-4 -4h11a4 4 0 0 0 0 -8h-1"/>
                        </svg>
                        Back to Reports
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-fluid">
        <!-- Search Card -->
        <div class="row row-deck row-cards mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <circle cx="10" cy="10" r="7"/>
                                <line x1="21" y1="21" x2="15" y2="15"/>
                            </svg>
                            Search Customers
                        </h3>
                    </div>
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label">Customer Name</label>
                                <input type="text" name="q" class="form-control" placeholder="Search by customer name" value="{{ old('q', $q ?? '') }}">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <circle cx="10" cy="10" r="7"/>
                                        <line x1="21" y1="21" x2="15" y2="15"/>
                                    </svg>
                                    Search
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customers Data Card -->
        <div class="row row-deck row-cards">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Customer Credit Summary</h3>
                        <div class="card-actions">
                            <span class="badge bg-blue">{{ count($rows) }} Customers</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-vcenter table-hover">
                                <thead>
                                    <tr>
                                        <th>Customer ID</th>
                                        <th>Customer Name</th>
                                        <th class="text-end">Total Credit</th>
                                        <th class="text-end">Total Due</th>
                                        <th>Last Sale Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($rows as $r)
                                    <tr>
                                        <td><span class="badge bg-blue-lt">{{ $r->customer_id }}</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm me-2" style="background-color: {{ '#' . substr(md5($r->customer_name), 0, 6) }};">
                                                    <span class="text-white">{{ strtoupper(substr($r->customer_name, 0, 1)) }}</span>
                                                </div>
                                                <strong>{{ $r->customer_name }}</strong>
                                            </div>
                                        </td>
                                        <td class="text-end"><span class="text-primary fw-bold">LKR {{ number_format(($r->total_credit_cents ?? 0)/100, 2) }}</span></td>
                                        <td class="text-end">
                                            @php
                                                $due = ($r->total_due_cents ?? 0)/100;
                                                $badgeClass = $due > 0 ? 'bg-red' : 'bg-green';
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">LKR {{ number_format($due, 2) }}</span>
                                        </td>
                                        <td>
                                            @if($r->last_sale_date)
                                                <span class="text-muted">{{ \Carbon\Carbon::parse($r->last_sale_date)->format('M d, Y') }}</span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">No customers found</td>
                                    </tr>
                                    @endforelse
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
