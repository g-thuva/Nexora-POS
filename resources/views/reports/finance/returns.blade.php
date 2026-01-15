@extends('layouts.nexora')

@section('title', 'Returns Report')

@section('content')
<div class="page-header d-print-none" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding-top: 2rem; padding-bottom: 2rem; margin-bottom: 2rem;">
    <div class="container-fluid">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle" style="color: rgba(255,255,255,0.8);">Finance Reports</div>
                <h2 class="page-title" style="color: white; font-weight: 700;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="28" height="28" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M3 12l3 3l3 -3l-3 -3z"/>
                        <path d="M21 12l-3 3l-3 -3l3 -3z"/>
                        <path d="M12 3l3 3l-3 3l-3 -3z"/>
                        <path d="M12 21l3 -3l-3 -3l-3 3z"/>
                        <path d="M12 12m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/>
                    </svg>
                    Returns Report
                </h2>
                <p style="color: rgba(255,255,255,0.8); margin-top: 0.5rem; font-size: 0.95rem;">Monitor product return rates and identify high-return items</p>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('reports.sales.index') }}" class="btn" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white;">
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
        <!-- Stats Cards -->
        <div class="row row-deck row-cards mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body" style="color: white;">
                        <div class="text-uppercase text-white-50 text-sm font-weight-bold mb-2">Total Products with Returns</div>
                        <div class="h3 font-weight-bold mb-0">{{ count($results) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <div class="card-body" style="color: white;">
                        <div class="text-uppercase text-white-50 text-sm font-weight-bold mb-2">Avg Return Rate</div>
                        <div class="h3 font-weight-bold mb-0">{{ $results->isEmpty() ? '0.00%' : number_format(($results->avg('return_rate') ?? 0) * 100, 2) . '%' }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <div class="card-body" style="color: white;">
                        <div class="text-uppercase text-white-50 text-sm font-weight-bold mb-2">Highest Return Rate</div>
                        <div class="h3 font-weight-bold mb-0">{{ $results->isEmpty() ? '0.00%' : number_format(($results->max('return_rate') ?? 0) * 100, 2) . '%' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="row row-deck row-cards mb-3">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h3 class="card-title mb-0">
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
                                <label class="form-label fw-bold">Product Name</label>
                                <input type="text" name="product" class="form-control" placeholder="Search by product name" value="{{ $filters['product'] ?? '' }}" style="border-radius: 6px;">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Minimum Return Rate (%)</label>
                                <input type="number" step="0.01" name="min_rate" class="form-control" placeholder="e.g., 5.00" value="{{ $filters['min_rate'] ?? '' }}" style="border-radius: 6px;">
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 6px;">
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
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h3 class="card-title mb-0">Product Return Rates</h3>
                        <div class="card-actions">
                            <span class="badge" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">{{ count($results) }} Products</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-vcenter">
                                <thead style="background: #f8f9fa;">
                                    <tr>
                                        <th style="border-top: none;"><strong>Product Name</strong></th>
                                        <th class="text-end" style="border-top: none;"><strong>Total Sold</strong></th>
                                        <th class="text-end" style="border-top: none;"><strong>Total Returns</strong></th>
                                        <th class="text-end" style="border-top: none;"><strong>Return Rate</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse($results as $r)
                                    <tr style="border-bottom: 1px solid #e9ecef; transition: background 0.2s;" onmouseover="this.style.backgroundColor='#f8f9fa'" onmouseout="this.style.backgroundColor=''">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm me-2 rounded-circle" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                                    {{ strtoupper(substr($r->product_name ?? 'N', 0, 1)) }}
                                                </div>
                                                <strong style="color: #1a202c;">{{ $r->product_name ?? ($r->name ?? '—') }}</strong>
                                            </div>
                                        </td>
                                        <td class="text-end"><span class="badge" style="background: #e7f1ff; color: #0052cc; border-radius: 6px;">{{ number_format($r->total_sold ?? 0) }}</span></td>
                                        <td class="text-end"><span class="badge" style="background: #ffe7e7; color: #cc0000; border-radius: 6px;">{{ number_format($r->total_returned ?? $r->total_returns ?? 0) }}</span></td>
                                        <td class="text-end">
                                            @php
                                                $rate = isset($r->return_rate) ? ($r->return_rate) : 0;
                                                if(is_object($r) && isset($r->total_returned) && isset($r->total_sold) && $r->total_sold > 0) {
                                                    $rate = ($r->total_returned / $r->total_sold) * 100;
                                                }
                                                $badgeColor = $rate > 10 ? '#dc3545' : ($rate > 5 ? '#ffc107' : '#28a745');
                                                $badgeBg = $rate > 10 ? '#ffe5e8' : ($rate > 5 ? '#fff8e6' : '#e6f7ed');
                                            @endphp
                                            <span class="badge" style="background: {{ $badgeBg }}; color: {{ $badgeColor }}; border-radius: 6px; font-weight: 600;">{{ number_format($rate, 2) }}%</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-5">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-3 text-muted" width="64" height="64" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <circle cx="12" cy="12" r="9"/>
                                                <line x1="9" y1="10" x2="9.01" y2="10"/>
                                                <line x1="15" y1="10" x2="15.01" y2="10"/>
                                                <path d="M9.5 15.25a3.5 3.5 0 0 1 5 0"/>
                                            </svg>
                                            <div style="font-size: 1rem; color: #6b7280;">No return data found</div>
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
