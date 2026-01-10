@extends('layouts.nexora')

@section('title', 'Products Management')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-fluid">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        Inventory Management
                    </div>
                    <h2 class="page-title">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5"/>
                            <path d="M12 12l8 -4.5"/>
                            <path d="M12 12l0 9"/>
                            <path d="M12 12l-8 -4.5"/>
                        </svg>
                        Products Management
                    </h2>
                    <p class="text-muted">Manage products, track inventory, and monitor stock levels</p>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('products.create') }}" class="btn d-none d-sm-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M12 5l0 14"/>
                                <path d="M5 12l14 0"/>
                            </svg>
                            New Product
                        </a>
                        @if($categories->isNotEmpty())
                        <a href="{{ route('categories.index') }}" class="btn d-none d-sm-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M4 4h6v6h-6z"/>
                                <path d="M14 4h6v6h-6z"/>
                                <path d="M4 14h6v6h-6z"/>
                                <path d="M17 17m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"/>
                            </svg>
                            Categories
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">

        @if($products->isEmpty())
            <div class="container-fluid">
                <!-- Stats Cards Row - Empty State -->
                <div class="row g-3 mb-4">
                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-primary text-white avatar">0</span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">
                                            Total Products
                                        </div>
                                        <div class="text-muted">
                                            0 Items
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-success text-white avatar">0</span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">
                                            In Stock
                                        </div>
                                        <div class="text-muted">
                                            0 Items
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-warning text-white avatar">0</span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">
                                            Low Stock
                                        </div>
                                        <div class="text-muted">
                                            0 Items
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-info text-white avatar">0</span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">
                                            Categories
                                        </div>
                                        <div class="text-muted">
                                            0
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <x-empty title="No products found"
                    message="Try adjusting your search or filter to find what you're looking for."
                    button_label="{{ __('Add your first Product') }}" button_route="{{ route('products.create') }}" />
            </div>
        @else
            <div class="container-fluid">
                <!-- Stats Cards Row - Orders Style -->
                <div class="row g-3 mb-4">
                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-primary text-white avatar">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                height="24" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5"/>
                                                <path d="M12 12l8 -4.5"/>
                                                <path d="M12 12l0 9"/>
                                                <path d="M12 12l-8 -4.5"/>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">
                                            Total Products
                                        </div>
                                        <div class="text-muted">
                                            {{ $productCards['total_products'] ?? 0 }} Items
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-success text-white avatar">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                height="24" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M5 12l5 5l10 -10"/>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">
                                            In Stock
                                        </div>
                                        <div class="text-muted">
                                            {{ $productCards['in_stock'] ?? 0 }} Items
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-warning text-white avatar">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                height="24" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <circle cx="12" cy="12" r="9"/>
                                                <path d="M12 8v4"/>
                                                <path d="M12 16h.01"/>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">
                                            Low Stock
                                        </div>
                                        <div class="text-muted">
                                            {{ $productCards['low_stock'] ?? 0 }} Items
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-info text-white avatar">{{ $productCards['categories'] ?? 0 }}</span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">
                                            Categories
                                        </div>
                                        <div class="text-muted">
                                            {{ $productCards['categories'] ?? 0 }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <x-alert />

                <x-alert />

                @livewire('tables.product-table')
            </div>
        @endif
    </div>
</div>

@push('page-scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add any custom JavaScript for the products page
        console.log('Products page loaded');
    });
</script>
@endpush
@endsection
