@extends('layouts.nexora')

@section('title', 'Returns Report')

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
                        <path d="M3 12l3 3l3 -3l-3 -3z"/>
                        <path d="M21 12l-3 3l-3 -3l3 -3z"/>
                        <path d="M12 3l3 3l-3 3l-3 -3z"/>
                        <path d="M12 21l3 -3l-3 -3l-3 3z"/>
                        <path d="M12 12m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/>
                    </svg>
                    Returns Report
                </h2>
                <p class="text-muted">Monitor product return rates and identify high-return items</p>
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
        <!-- Filter Card -->
        <div class="row row-deck row-cards mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M5.5 5h13a1 1 0 0 1 .5 1.5l-5 5.5l0 7l-4 -3l0 -4l-5 -5.5a1 1 0 0 1 .5 -1.5"/>
                            </svg>
                            Filter Returns
                        </h3>
                    </div>
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <div class="col-md-5">
                                <label class="form-label">Product Name</label>
                                <input type="text" name="product" class="form-control" placeholder="Search by product name" value="{{ $filters['product'] ?? '' }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Minimum Return Rate (%)</label>
                                <input type="number" step="0.01" name="min_rate" class="form-control" placeholder="e.g., 5.00" value="{{ $filters['min_rate'] ?? '' }}">
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <circle cx="10" cy="10" r="7"/>
                                        <line x1="21" y1="21" x2="15" y2="15"/>
                                    </svg>
                                    Apply Filter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Returns Data Card -->
        <div class="row row-deck row-cards">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Product Return Rates</h3>
                        <div class="card-actions">
                            <span class="badge bg-blue">{{ count($results) }} Products</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-vcenter table-hover">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th class="text-end">Total Sold</th>
                                        <th class="text-end">Total Returns</th>
                                        <th class="text-end">Return Rate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse($results as $r)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm me-2" style="background-color: {{ '#' . substr(md5($r->product_name ?? 'N/A'), 0, 6) }};">
                                                    <span class="text-white">{{ strtoupper(substr($r->product_name ?? 'N', 0, 1)) }}</span>
                                                </div>
                                                <strong>{{ $r->product_name ?? ($r->name ?? '—') }}</strong>
                                            </div>
                                        </td>
                                        <td class="text-end"><span class="badge bg-blue-lt">{{ number_format($r->total_sold ?? 0) }}</span></td>
                                        <td class="text-end"><span class="badge bg-red-lt">{{ number_format($r->total_returns ?? 0) }}</span></td>
                                        <td class="text-end">
                                            @php
                                                $rate = isset($r->return_rate) ? ($r->return_rate * 100) : 0;
                                                $badgeClass = $rate > 10 ? 'bg-red' : ($rate > 5 ? 'bg-yellow' : 'bg-green');
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">{{ number_format($rate, 2) }}%</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-muted icon-lg" width="48" height="48" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <circle cx="12" cy="12" r="9"/>
                                                <line x1="9" y1="10" x2="9.01" y2="10"/>
                                                <line x1="15" y1="10" x2="15.01" y2="10"/>
                                                <path d="M9.5 15.25a3.5 3.5 0 0 1 5 0"/>
                                            </svg>
                                            <div>No return data found</div>
                                        </td>
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
