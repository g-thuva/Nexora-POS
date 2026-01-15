@extends('layouts.nexora')

@section('title', 'Expenses Report')

@section('content')
<div class="page-header d-print-none" style="background: white; border-bottom: 1px solid #e9ecef; padding: 2rem 0;">
    <div class="container-fluid">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle text-muted" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">
                    Finance Reports
                </div>
                <h2 class="page-title" style="font-weight: 600; font-size: 2rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="32" height="32" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" style="display: inline;">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12"/>
                        <path d="M20 12v4h-4a2 2 0 0 1 0 -4h4"/>
                    </svg>
                    Monthly Expenses Report
                </h2>
                <p class="text-muted" style="font-size: 1rem; margin-top: 0.5rem;">Track and analyze monthly expenses across all categories</p>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('expenses.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <line x1="12" y1="5" x2="12" y2="19"/>
                            <line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        Create Expense
                    </a>
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
        <!-- Stats Cards -->
        <div class="row row-deck row-cards mb-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-danger text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12"/>
                                        <path d="M20 12v4h-4a2 2 0 0 1 0 -4h4"/>
                                    </svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="text-uppercase text-muted small font-weight-bold mb-1">Total Expenses</div>
                                <div class="h3 font-weight-bold mb-0">LKR {{ isset($rows) && count($rows) > 0 ? number_format(collect($rows)->sum(function($r) { return isset($r->total) ? ($r->total/100) : (isset($r->total_expenses) ? ($r->total_expenses/100) : 0); }), 2) : '0.00' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-warning text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <rect x="4" y="5" width="16" height="16" rx="2"/>
                                        <line x1="16" y1="3" x2="16" y2="7"/>
                                        <line x1="8" y1="3" x2="8" y2="7"/>
                                        <line x1="4" y1="11" x2="20" y2="11"/>
                                    </svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="text-uppercase text-muted small font-weight-bold mb-1">Avg Monthly</div>
                                <div class="h3 font-weight-bold mb-0">LKR {{ isset($rows) && count($rows) > 0 ? number_format(collect($rows)->avg(function($r) { return isset($r->total) ? ($r->total/100) : (isset($r->total_expenses) ? ($r->total_expenses/100) : 0); }), 2) : '0.00' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-info text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <polyline points="3 17 9 11 13 15 21 7"/>
                                        <polyline points="14 7 21 7 21 14"/>
                                    </svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="text-uppercase text-muted small font-weight-bold mb-1">Highest Month</div>
                                <div class="h4 font-weight-bold mb-0">
                            @php
                                $highest = isset($rows) && count($rows) > 0 ? collect($rows)->max(function($r) { return isset($r->total) ? ($r->total) : (isset($r->total_expenses) ? ($r->total_expenses) : 0); }) : null;
                                $highestMonth = null;
                                if($highest && isset($rows)) {
                                    foreach($rows as $r) {
                                        if((isset($r->total) ? $r->total : (isset($r->total_expenses) ? $r->total_expenses : 0)) == $highest) {
                                            $highestMonth = $r->month_name ?? $r->month_key ?? $r->month;
                                            break;
                                        }
                                    }
                                }
                            @endphp
                            {{ $highestMonth ?? 'N/A' }}
                        </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Year Selection Card -->
        <div class="row row-deck row-cards mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-white border-bottom">
                        <h3 class="card-title mb-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <rect x="4" y="5" width="16" height="16" rx="2"/>
                                <line x1="16" y1="3" x2="16" y2="7"/>
                                <line x1="8" y1="3" x2="8" y2="7"/>
                                <line x1="4" y1="11" x2="20" y2="11"/>
                            </svg>
                            Select Year
                        </h3>
                    </div>
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Year</label>
                                <select name="year" class="form-select" onchange="this.form.submit()" style="border-radius: 6px;">
                                    @for($y = now()->year; $y >= now()->year - 5; $y--)
                                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-8 d-flex align-items-end">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('reports.sales.finance.expenses', ['year' => $year - 1]) }}" class="btn btn-outline-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <polyline points="15,6 9,12 15,18"/>
                                        </svg>
                                        {{ $year - 1 }}
                                    </a>
                                    <a href="{{ route('reports.sales.finance.expenses', ['year' => now()->year]) }}" class="btn btn-primary">This Year</a>
                                    @if($year < now()->year)
                                    <a href="{{ route('reports.sales.finance.expenses', ['year' => $year + 1]) }}" class="btn btn-outline-primary">
                                        {{ $year + 1 }}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <polyline points="9,6 15,12 9,18"/>
                                        </svg>
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Expenses Data Card -->
        <div class="row row-deck row-cards">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title mb-0">Monthly Expenses for {{ $year }}</h3>
                        <div class="card-actions">
                            <span class="badge bg-danger">{{ count($rows) }} Months</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-vcenter">
                                <thead style="background: #f8f9fa;">
                                    <tr>
                                        <th style="border-top: none;"><strong>Month</strong></th>
                                        <th class="text-end" style="border-top: none;"><strong>Total Expenses</strong></th>
                                        <th class="text-end" style="border-top: none;"><strong>Percentage</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse($rows as $r)
                                    @php
                                        $total = isset($r->total) ? ($r->total/100) : (isset($r->total_expenses) ? ($r->total_expenses/100) : 0);
                                        $allTotal = collect($rows)->sum(function($row) { return isset($row->total) ? ($row->total/100) : (isset($row->total_expenses) ? ($row->total_expenses/100) : 0); });
                                        $percentage = $allTotal > 0 ? ($total / $allTotal) * 100 : 0;
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="avatar avatar-xs rounded me-2 bg-danger-lt text-danger fw-bold">{{ strtoupper(substr($r->month_name ?? $r->month_key ?? $r->month ?? 'N', 0, 1)) }}</span>
                                                <strong>{{ $r->month_name ?? $r->month_key ?? $r->month }}</strong>
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <span class="badge bg-danger-lt text-danger fw-bold">
                                                LKR {{ number_format($total, 2) }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex align-items-center justify-content-end">
                                                <div class="progress" style="width: 80px; height: 8px;">
                                                    <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <span class="text-muted ms-2 fw-bold" style="min-width: 45px;">{{ number_format($percentage, 1) }}%</span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-5">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-3 text-muted" width="64" height="64" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <circle cx="12" cy="12" r="9"/>
                                                <line x1="9" y1="10" x2="9.01" y2="10"/>
                                                <line x1="15" y1="10" x2="15.01" y2="10"/>
                                                <path d="M9.5 15.25a3.5 3.5 0 0 1 5 0"/>
                                            </svg>
                                            <div style="font-size: 1rem; color: #6b7280;">No expense data found for this year</div>
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
