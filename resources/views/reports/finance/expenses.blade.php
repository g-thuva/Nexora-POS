@extends('layouts.nexora')

@section('content')
<div class="page-header">
    <div class="container-fluid">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">{{ __('Expenses Summary') }}</h2>
                <p class="page-subtitle text-muted">{{ __('Monthly expenses summary (uses view v_monthly_expenses_summary)') }}</p>
            </div>
            <div class="col-auto ms-auto">
                <form method="GET" class="row g-2">
                    <div class="col-auto">
                        <select name="year" class="form-select">
                            @for($y = now()->year; $y >= now()->year - 5; $y--)
                                <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-primary">Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>Total Expenses</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($rows as $r)
                            <tr>
                                <td>{{ $r->month_name ?? $r->month_key ?? $r->month }}</td>
                                <td>{{ isset($r->total) ? number_format(($r->total/100), 2) : (isset($r->total_expenses) ? number_format(($r->total_expenses/100),2) : '0.00') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2">No data</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
