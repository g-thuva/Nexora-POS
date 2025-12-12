@extends('layouts.nexora')

@section('content')
<div class="page-header">
    <div class="container-fluid">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">{{ __('Returns Summary') }}</h2>
                <p class="page-subtitle text-muted">{{ __('Return rates per product (uses database view v_return_rates)') }}</p>
            </div>
            <div class="col-auto ms-auto">
                <form method="GET" class="row g-2">
                    <div class="col-auto">
                        <input type="text" name="product" class="form-control" placeholder="Product name" value="{{ $filters['product'] ?? '' }}">
                    </div>
                    <div class="col-auto">
                        <input type="number" step="0.01" name="min_rate" class="form-control" placeholder="Min return rate" value="{{ $filters['min_rate'] ?? '' }}">
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
                                <th>Product</th>
                                <th>Total Sold</th>
                                <th>Total Returns</th>
                                <th>Return Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($results as $r)
                            <tr>
                                <td>{{ $r->product_name ?? ($r->name ?? '—') }}</td>
                                <td>{{ $r->total_sold ?? 0 }}</td>
                                <td>{{ $r->total_returns ?? 0 }}</td>
                                <td>{{ isset($r->return_rate) ? number_format($r->return_rate * 100, 2) . '%' : 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4">No results</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
