@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Customer Credit Summary</h3>

    <form class="row g-2 mb-3">
        <div class="col-auto"><input type="text" name="q" class="form-control" placeholder="Customer name" value="{{ old('q', $q ?? '') }}"></div>
        <div class="col-auto"><button class="btn btn-primary">Search</button></div>
    </form>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Customer ID</th>
                    <th>Customer</th>
                    <th class="text-end">Total Credit</th>
                    <th class="text-end">Total Due</th>
                    <th>Last Sale</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $r)
                <tr>
                    <td>{{ $r->customer_id }}</td>
                    <td>{{ $r->customer_name }}</td>
                    <td class="text-end">{{ number_format(($r->total_credit_cents ?? 0)/100, 2) }}</td>
                    <td class="text-end">{{ number_format(($r->total_due_cents ?? 0)/100, 2) }}</td>
                    <td>{{ optional(\Carbon\Carbon::parse($r->last_sale_date))->format('Y-m-d') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
