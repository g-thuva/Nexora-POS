@extends('layouts.app')

@section('content')
    <div class="page-wrapper">
        <div class="container-fluid py-4">

            <div class="page-header d-print-none mb-3">
                <div class="container-fluid">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <div class="page-pretitle">FINANCE</div>
                            <h2 class="page-title">Edit Expense #{{ $expense->id }}</h2>
                            <p class="text-muted small">Update expense details and notes.</p>
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

            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            @if(session('status'))
                                <div class="alert alert-success">{{ session('status') }}</div>
                            @endif

                            <form method="POST" action="{{ route('expenses.update', $expense) }}">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="type" class="form-label">Type</label>
                                    <input type="text" name="type" id="type" class="form-control" value="{{ old('type', $expense->type) }}">
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="amount" class="form-label">Amount</label>
                                        <input type="number" step="0.01" name="amount" id="amount" class="form-control" required value="{{ number_format($expense->amount, 2) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="expense_date" class="form-label">Date</label>
                                        <input type="date" name="expense_date" id="expense_date" class="form-control" value="{{ $expense->expense_date?->format('Y-m-d') }}">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea name="notes" id="notes" class="form-control">{{ old('notes', $expense->notes) }}</textarea>
                                </div>

                                <div class="d-flex">
                                    <button class="btn btn-primary">Save</button>
                                    <a href="{{ route('expenses.create') }}" class="btn btn-secondary ms-2">New Expense</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-3">Recent Expenses</h6>
                            @php $recentExp = \App\Models\Expense::latest()->limit(8)->get(); @endphp
                            @if($recentExp->isNotEmpty())
                                <div class="list-group">
                                    @foreach($recentExp as $e)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="small text-muted">#{{ $e->id }} — {{ $e->expense_date?->format('Y-m-d') }}</div>
                                                <div>{{ \Illuminate\Support\Str::limit($e->notes, 60) }}</div>
                                            </div>
                                            <div>
                                                <a href="{{ route('expenses.edit', $e) }}" class="btn btn-sm btn-outline-secondary">View</a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-muted small">No recent expenses</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
