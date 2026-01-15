@extends('layouts.nexora')

@section('title', 'All Returns')

@section('content')
<div class="page-body">
    <div class="container-fluid">
        <x-alert />

        <!-- Page Header -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="page-title mb-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-warning" width="32" height="32" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M3 12l3 3l3 -3l-3 -3z"/>
                                <path d="M21 12l-3 3l-3 -3l3 -3z"/>
                                <path d="M12 3l3 3l-3 3l-3 -3z"/>
                                <path d="M12 21l3 -3l-3 -3l-3 3z"/>
                                <path d="M12 12m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/>
                            </svg>
                            All Returns
                        </h1>
                        <p class="text-muted">View all product return records organized by month</p>
                    </div>
                    <div class="btn-list">
                        <a href="{{ route('returns.create') }}" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            New Return
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Stats -->
        <div class="row row-cards mb-3">
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Total Returns Value</div>
                        </div>
                        <div class="h1 mb-0">LKR {{ number_format($totalReturns, 2) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Total Records</div>
                        </div>
                        <div class="h1 mb-0">{{ $totalRecords }}</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Items Returned</div>
                        </div>
                        <div class="h1 mb-0">{{ $totalItems }}</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Average per Return</div>
                        </div>
                        <div class="h1 mb-0">LKR {{ $totalRecords > 0 ? number_format($totalReturns / $totalRecords, 2) : '0.00' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Returns by Month -->
        @forelse($returnsByMonth as $monthYear => $returns)
            @php
                $monthTotal = $returns->sum('total');
                $monthItems = $returns->sum(function($return) {
                    return $return->items->sum('quantity');
                });
            @endphp
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-primary" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <rect x="4" y="5" width="16" height="16" rx="2"/>
                            <line x1="16" y1="3" x2="16" y2="7"/>
                            <line x1="8" y1="3" x2="8" y2="7"/>
                            <line x1="4" y1="11" x2="20" y2="11"/>
                        </svg>
                        {{ $monthYear }}
                    </h3>
                    <div class="ms-auto">
                        <span class="badge bg-warning-lt text-warning fs-3">LKR {{ number_format($monthTotal, 2) }}</span>
                        <span class="badge bg-info-lt text-info fs-3 ms-2">{{ $monthItems }} Items</span>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-vcenter card-table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Products</th>
                                <th class="text-center">Items</th>
                                <th class="text-end">Amount</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($returns as $return)
                            <tr>
                                <td>
                                    <div class="text-muted small">{{ $return->return_date ? $return->return_date->format('d M Y') : 'N/A' }}</div>
                                    @if($return->return_date)
                                    <div class="text-muted" style="font-size: 11px;">{{ $return->return_date->format('l') }}</div>
                                    @endif
                                </td>
                                <td>
                                    @if($return->customer)
                                        <div>{{ $return->customer->name }}</div>
                                        @if($return->customer->phone)
                                        <div class="text-muted small">{{ $return->customer->phone }}</div>
                                        @endif
                                    @else
                                        <span class="text-muted">Walk-in Customer</span>
                                    @endif
                                </td>
                                <td>
                                    @if($return->items->count() > 0)
                                        <div class="text-muted small">
                                            @foreach($return->items->take(2) as $item)
                                                <div class="mb-1">
                                                    <span class="badge bg-azure-lt me-1">{{ $item->product->name ?? 'Unknown' }}</span>
                                                    <span class="text-muted">× {{ $item->quantity }}</span>
                                                </div>
                                            @endforeach
                                            @if($return->items->count() > 2)
                                                <div class="text-muted">+{{ $return->items->count() - 2 }} more</div>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">No items</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info">{{ $return->items->sum('quantity') }}</span>
                                </td>
                                <td class="text-end">
                                    <div class="fw-bold text-warning">LKR {{ number_format($return->total, 2) }}</div>
                                </td>
                                <td class="text-center">
                                    <div class="btn-list">
                                        <a href="{{ route('returns.show', $return) }}" class="btn btn-sm btn-ghost-info" title="View">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <circle cx="12" cy="12" r="2"/>
                                                <path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7"/>
                                            </svg>
                                        </a>
                                        <a href="{{ route('returns.edit', $return) }}" class="btn btn-sm btn-ghost-primary" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                                                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                                                <path d="M16 5l3 3"/>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-end fw-bold">Month Total:</td>
                                <td class="text-end fw-bold text-warning">LKR {{ number_format($monthTotal, 2) }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        @empty
            <div class="card">
                <div class="card-body text-center py-5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-3 text-muted" width="64" height="64" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <circle cx="12" cy="12" r="9"/>
                        <line x1="9" y1="10" x2="9.01" y2="10"/>
                        <line x1="15" y1="10" x2="15.01" y2="10"/>
                        <path d="M9.5 15.25a3.5 3.5 0 0 1 5 0"/>
                    </svg>
                    <h3>No returns found</h3>
                    <p class="text-muted">Start by recording your first product return</p>
                    <a href="{{ route('returns.create') }}" class="btn btn-primary mt-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <line x1="12" y1="5" x2="12" y2="19"/>
                            <line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        Create First Return
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
