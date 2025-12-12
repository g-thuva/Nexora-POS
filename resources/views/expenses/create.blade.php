@extends('layouts.app')

@section('content')
    <div class="page-wrapper">
        <div class="container-fluid py-4">

            <div class="page-header d-print-none mb-3">
                <div class="container-fluid">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <div class="page-pretitle">FINANCE</div>
                            <h2 class="page-title">Expenses Management</h2>
                            <p class="text-muted small">Record operating expenses and track spending by type.</p>
                        </div>
                        <div class="col-auto ms-auto d-print-none">
                            <div class="btn-list">
                                <a href="{{ route('expenses.create') }}" class="btn btn-outline-primary">New Expense</a>
                                <a href="{{ url('/expenses') }}" class="btn btn-primary ms-2">Expense List</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card p-3">
                        <div class="text-muted small">TOTAL EXPENSES</div>
                        <div class="h5">LKR {{ number_format( (isset($totalExpenses) ? $totalExpenses/100 : 0), 2) }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-3">
                        <div class="text-muted small">THIS MONTH</div>
                        <div class="h5">LKR {{ number_format( (isset($monthTotal) ? $monthTotal/100 : 0), 2) }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-3">
                        <div class="text-muted small">LAST 7 DAYS</div>
                        <div class="h5">LKR {{ number_format( (isset($weekTotal) ? $weekTotal/100 : 0), 2) }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-3">
                        <div class="text-muted small">TYPES</div>
                        <div class="h5">{{ isset($typesCount) ? $typesCount : 0 }}</div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="card mb-3">
                        <div class="card-body">
                            <form id="expense-form" method="POST" action="{{ route('expenses.store') }}">
                                @csrf

                                <div class="row mb-3">
                                    <div class="col-md-8">
                                        <label for="type" class="form-label">Type</label>
                                        <input type="text" name="type" id="type" class="form-control" placeholder="e.g. Utilities, Rent">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="amount" class="form-label">Amount</label>
                                        <input type="number" step="0.01" name="amount" id="amount" class="form-control" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="expense_date" class="form-label">Date</label>
                                        <input type="date" name="expense_date" id="expense_date" class="form-control" value="{{ now()->toDateString() }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="notes" class="form-label">Notes</label>
                                        <input type="text" name="notes" id="notes" class="form-control">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-secondary me-2" type="reset">Reset</button>
                                    <button class="btn btn-primary" type="submit">Save Expense</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Recent Expenses</h5>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Type</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Notes</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($expenses ?? [] as $exp)
                                            <tr>
                                                <td>{{ $exp->id }}</td>
                                                <td>{{ $exp->type }}</td>
                                                <td>{{ optional($exp->expense_date)->format('d/m/Y') }}</td>
                                                <td>LKR {{ number_format($exp->amount, 2) }}</td>
                                                <td>{{ \Illuminate\Support\Str::limit($exp->notes, 50) }}</td>
                                                <td>
                                                    <a href="{{ route('expenses.edit', $exp) }}" class="btn btn-sm btn-outline-primary">View</a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="6" class="text-center">No expenses recorded yet.</td></tr>
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
