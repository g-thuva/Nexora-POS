@extends('layouts.nexora')

@section('title', 'View Expense')

@section('content')
<div class="page-body">
    <div class="container-fluid">
        <x-alert />

        <!-- Page Header -->
        <div class="row mb-2">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="page-title mb-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-info" width="32" height="32" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M14 3v4a1 1 0 0 0 1 1h4"/>
                                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"/>
                                <line x1="9" y1="9" x2="10" y2="9"/>
                                <line x1="9" y1="13" x2="15" y2="13"/>
                                <line x1="9" y1="17" x2="15" y2="17"/>
                            </svg>
                            {{ __('Expense Details') }} #{{ $expense->id }}
                        </h1>
                        <p class="text-muted">View complete expense information</p>
                    </div>
                    <div class="btn-list">
                        <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-outline-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                                <path d="M16 5l3 3"/>
                            </svg>
                            Edit
                        </a>
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

        <div class="row">
            <!-- Main Expense Details -->
            <div class="col-lg-8">
                <!-- Expense Overview Card -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-primary" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <circle cx="12" cy="12" r="9"/>
                                <line x1="12" y1="8" x2="12.01" y2="8"/>
                                <polyline points="11 12 12 12 12 16 13 16"/>
                            </svg>
                            Expense Overview
                        </h3>
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-3">
                            <!-- Expense Type -->
                            <div class="col-md-6">
                                <div class="mb-0">
                                    <label class="form-label text-muted small mb-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2"/>
                                            <rect x="9" y="3" width="6" height="4" rx="2"/>
                                        </svg>
                                        Expense Type
                                    </label>
                                    <div class="h4 mb-0">
                                        <span class="badge bg-primary-lt text-primary">{{ $expense->type }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Amount -->
                            <div class="col-md-6">
                                <div class="mb-0">
                                    <label class="form-label text-muted small mb-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2"/>
                                            <path d="M12 3v3m0 12v3"/>
                                        </svg>
                                        Amount
                                    </label>
                                    <div class="h3 mb-0 text-danger">LKR {{ number_format($expense->amount, 2) }}</div>
                                </div>
                            </div>

                            <!-- Expense Date -->
                            <div class="col-md-6">
                                <div class="mb-0">
                                    <label class="form-label text-muted small mb-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <rect x="4" y="5" width="16" height="16" rx="2"/>
                                            <line x1="16" y1="3" x2="16" y2="7"/>
                                            <line x1="8" y1="3" x2="8" y2="7"/>
                                            <line x1="4" y1="11" x2="20" y2="11"/>
                                        </svg>
                                        Expense Date
                                    </label>
                                    <div class="fw-bold">{{ optional($expense->expense_date)->format('d M Y') ?? 'Not specified' }}</div>
                                </div>
                            </div>

                            <!-- Status Badge -->
                            <div class="col-md-6">
                                <div class="mb-0">
                                    <label class="form-label text-muted small mb-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M5 12l5 5l10 -10"/>
                                        </svg>
                                        Status
                                    </label>
                                    <div>
                                        <span class="badge bg-success">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M5 12l5 5l10 -10"/>
                                            </svg>
                                            Recorded
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Details Card -->
                @if($expense->details && !empty(array_filter($expense->details)))
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-success" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <polyline points="9 11 12 14 20 6"/>
                                <path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9"/>
                            </svg>
                            Specific Details
                        </h3>
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-2">
                            @foreach(array_filter($expense->details) as $key => $value)
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between align-items-start">
                                    <span class="text-muted small text-capitalize">{{ str_replace('_', ' ', $key) }}:</span>
                                    <span class="fw-bold small text-end ms-2">{{ $value }}</span>
                                </div>
                                @if(!$loop->last)
                                <hr class="my-2">
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Notes Card -->
                @if($expense->notes)
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-info" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="3" y1="19" x2="21" y2="19"/>
                                <polyline points="6 19 6 7 18 7 18 19"/>
                                <line x1="9" y1="10" x2="15" y2="10"/>
                                <line x1="9" y1="13" x2="15" y2="13"/>
                                <line x1="9" y1="16" x2="15" y2="16"/>
                            </svg>
                            Additional Notes
                        </h3>
                    </div>
                    <div class="card-body p-3">
                        <div class="text-muted" style="white-space: pre-wrap;">{{ $expense->notes }}</div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Metadata Card -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-warning" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <circle cx="12" cy="12" r="9"/>
                                <polyline points="12 7 12 12 15 15"/>
                            </svg>
                            Record Information
                        </h3>
                    </div>
                    <div class="card-body p-3">
                        <div class="mb-2">
                            <div class="text-muted small mb-1">Expense ID</div>
                            <div class="fw-bold">#{{ $expense->id }}</div>
                        </div>
                        <hr class="my-2">
                        <div class="mb-2">
                            <div class="text-muted small mb-1">Created At</div>
                            <div class="small">{{ optional($expense->created_at)->format('d M Y, h:i A') }}</div>
                        </div>
                        <div class="mb-2">
                            <div class="text-muted small mb-1">Last Updated</div>
                            <div class="small">{{ optional($expense->updated_at)->format('d M Y, h:i A') }}</div>
                        </div>
                        @if($expense->created_by)
                        <hr class="my-2">
                        <div class="mb-0">
                            <div class="text-muted small mb-1">Created By</div>
                            <div class="small">
                                <span class="avatar avatar-xs rounded me-1 bg-primary-lt">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <circle cx="12" cy="7" r="4"/>
                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/>
                                    </svg>
                                </span>
                                User #{{ $expense->created_by }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-success" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M11 7h-5a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-5"/>
                                <line x1="10" y1="14" x2="20" y2="4"/>
                                <polyline points="15 4 20 4 20 9"/>
                            </svg>
                            Quick Actions
                        </h3>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-grid gap-2">
                            <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                                    <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                                    <path d="M16 5l3 3"/>
                                </svg>
                                Edit Expense
                            </a>
                            <a href="{{ route('expenses.create') }}" class="btn btn-outline-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <line x1="12" y1="5" x2="12" y2="19"/>
                                    <line x1="5" y1="12" x2="19" y2="12"/>
                                </svg>
                                Add New Expense
                            </a>
                            <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M3 7v4a1 1 0 0 0 1 1h3"/>
                                    <path d="M7 7a2 2 0 1 1 2 2h-2z"/>
                                    <path d="M21 11v-4a1 1 0 0 0 -1 -1h-3"/>
                                    <path d="M17 7a2 2 0 1 0 -2 2h2z"/>
                                    <path d="M7 21v-4a1 1 0 0 1 1 -1h3"/>
                                    <path d="M7 17a2 2 0 1 0 2 -2h-2z"/>
                                    <path d="M21 17v4a1 1 0 0 1 -1 1h-3"/>
                                    <path d="M17 21a2 2 0 1 1 -2 -2h2z"/>
                                </svg>
                                All Expenses
                            </a>
                            <form action="{{ route('expenses.destroy', $expense) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this expense?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger w-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <line x1="4" y1="7" x2="20" y2="7"/>
                                        <line x1="10" y1="11" x2="10" y2="17"/>
                                        <line x1="14" y1="11" x2="14" y2="17"/>
                                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/>
                                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/>
                                    </svg>
                                    Delete Expense
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
