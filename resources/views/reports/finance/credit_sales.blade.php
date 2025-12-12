@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Credit Sales Report</h3>

    <form class="row g-2 mb-3">
        <div class="col-auto"><input type="date" name="start" class="form-control" value="{{ old('start', $filters['start'] ?? '') }}"></div>
        <div class="col-auto"><input type="date" name="end" class="form-control" value="{{ old('end', $filters['end'] ?? '') }}"></div>
        <div class="col-auto"><input type="text" name="customer" class="form-control" placeholder="Customer" value="{{ old('customer', $filters['customer'] ?? '') }}"></div>
        <div class="col-auto"><button class="btn btn-primary">Filter</button></div>
    </form>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Sale ID</th>
                    <th>Shop</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th class="text-end">Total</th>
                    <th class="text-end">Due</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $r)
                <tr>
                    <td>{{ $r->credit_sale_id }}</td>
                    <td>{{ $r->shop_id }}</td>
                    <td>{{ $r->customer_name ?? 'N/A' }}</td>
                    <td>{{ optional(
                        \Carbon\Carbon::parse($r->sale_date))->format('Y-m-d') }}</td>
                    <td class="text-end">{{ number_format(($r->total_cents ?? 0)/100, 2) }}</td>
                    <td class="text-end">{{ number_format(($r->due_cents ?? 0)/100, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
