@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Product Credit Summary</h3>

    <form class="row g-2 mb-3">
        <div class="col-auto"><input type="text" name="q" class="form-control" placeholder="Product name" value="{{ old('q', $q ?? '') }}"></div>
        <div class="col-auto"><button class="btn btn-primary">Search</button></div>
    </form>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Product</th>
                    <th class="text-end">Quantity Sold</th>
                    <th class="text-end">Total Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $r)
                <tr>
                    <td>{{ $r->product_id }}</td>
                    <td>{{ $r->product_name }}</td>
                    <td class="text-end">{{ number_format($r->total_quantity_sold ?? 0) }}</td>
                    <td class="text-end">{{ number_format(($r->total_amount ?? 0), 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
