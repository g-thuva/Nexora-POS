@extends('layouts.nexora')

@section('content')
<div class="page-header d-print-none">
    <div class="container-fluid mb-3">
        <div class="row g-2 align-items-center mb-3">
            <div class="col">
                <h2 class="page-title">
                    {{ __('Edit Product') }}
                </h2>
            </div>
        </div>

        @include('partials._breadcrumbs', ['model' => $product])
    </div>
</div>

<div class="page-body">
    <div class="container-fluid">
        <div class="row row-cards">

            <div class="row">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">
                                {{ __('Product Image') }}
                            </h3>

                            <img class="img-account-profile mb-2" src="{{ asset('assets/img/products/default.webp') }}" alt="" id="image-preview" />
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                {{ __('Product Details') }}
                            </h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-borderless table-striped align-middle mb-0">
                                <tbody>
                                    <tr>
                                        <th class="text-muted" style="width: 160px;">Name</th>
                                        <td class="fw-bold">{{ $product->name }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Slug</th>
                                        <td class="text-secondary">{{ $product->slug }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Code</th>
                                        <td><span class="badge bg-light text-dark border">{{ $product->code }}</span></td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Barcode</th>
                                        <td>{!! $barcode !!}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Category</th>
                                        <td>
                                            @if($product->category)
                                                <a href="{{ route('categories.show', $product->category) }}" class="badge bg-blue-lt text-uppercase fw-normal">
                                                    {{ $product->category->name }}
                                                </a>
                                            @else
                                                <span class="badge bg-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Unit</th>
                                        <td>
                                            @if($product->unit)
                                                <a href="{{ route('units.show', $product->unit) }}" class="badge bg-green-lt text-uppercase fw-normal">
                                                    {{ $product->unit->short_code }}
                                                </a>
                                            @else
                                                <span class="badge bg-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Quantity</th>
                                        <td class="fw-bold">{{ $product->quantity }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Quantity Alert</th>
                                        <td><span class="badge bg-red-lt">{{ $product->quantity_alert }}</span></td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Buying Price</th>
                                        <td class="fw-bold text-danger">${{ number_format($product->buying_price, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Selling Price</th>
                                        <td class="fw-bold text-success">${{ number_format($product->selling_price, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Notes</th>
                                        <td class="text-secondary">{{ $product->notes }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer text-end">
                            <x-button.edit route="{{ route('products.edit', $product) }}">
                                {{ __('Edit') }}
                            </x-button.edit>

                            <x-button.back route="{{ route('products.index') }}">
                                {{ __('Cancel') }}
                            </x-button.back>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
