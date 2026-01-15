@extends('layouts.nexora')

@section('title', 'All Expenses')

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
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-danger" width="32" height="32" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12"/>
                                <path d="M20 12v4h-4a2 2 0 0 1 0 -4h4"/>
                            </svg>
                            All Expenses
                        </h1>
                        <p class="text-muted">View all expense records organized by month</p>
                    </div>
                    <div class="btn-list">
                        <a href="{{ route('expenses.create') }}" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            New Expense
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
                            <div class="subheader">Total Expenses</div>
                        </div>
                        <div class="h1 mb-0">LKR {{ number_format($totalExpenses / 100, 2) }}</div>
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
                            <div class="subheader">Months with Data</div>
                        </div>
                        <div class="h1 mb-0">{{ count($expensesByMonth) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Average per Month</div>
                        </div>
                        <div class="h1 mb-0">LKR {{ count($expensesByMonth) > 0 ? number_format(($totalExpenses / 100) / count($expensesByMonth), 2) : '0.00' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Expenses by Month -->
        @forelse($expensesByMonth as $monthYear => $expenses)
            @php
                $monthTotal = $expenses->sum(function($exp) { return $exp->amount * 100; }) / 100;
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
                        <span class="badge bg-danger-lt text-danger fs-3">LKR {{ number_format($monthTotal, 2) }}</span>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-vcenter card-table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Details</th>
                                <th class="text-end">Amount</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($expenses as $expense)
                            <tr>
                                <td>
                                    <div class="text-muted small">{{ $expense->expense_date->format('d M Y') }}</div>
                                    <div class="text-muted" style="font-size: 11px;">{{ $expense->expense_date->format('l') }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-primary-lt">{{ $expense->type }}</span>
                                </td>
                                <td>
                                    @if($expense->details && !empty(array_filter($expense->details)))
                                        <div class="text-muted small">
                                            @foreach(array_slice(array_filter($expense->details), 0, 2) as $key => $value)
                                                <span class="badge bg-azure-lt me-1">{{ ucfirst(str_replace('_', ' ', $key)) }}: {{ $value }}</span>
                                            @endforeach
                                        </div>
                                    @elseif($expense->notes)
                                        <div class="text-muted small text-truncate" style="max-width: 250px;">{{ $expense->notes }}</div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="fw-bold text-danger">LKR {{ number_format($expense->amount, 2) }}</div>
                                </td>
                                <td class="text-center">
                                    <div class="btn-list">
                                        <a href="{{ route('expenses.show', $expense) }}" class="btn btn-sm btn-ghost-info" title="View">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <circle cx="12" cy="12" r="2"/>
                                                <path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7"/>
                                            </svg>
                                        </a>
                                        <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-sm btn-ghost-primary" title="Edit">
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
                                <td colspan="3" class="text-end fw-bold">Month Total:</td>
                                <td class="text-end fw-bold text-danger">LKR {{ number_format($monthTotal, 2) }}</td>
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
                    <h3>No expenses found</h3>
                    <p class="text-muted">Start by creating your first expense record</p>
                    <a href="{{ route('expenses.create') }}" class="btn btn-primary mt-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <line x1="12" y1="5" x2="12" y2="19"/>
                            <line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        Create First Expense
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
