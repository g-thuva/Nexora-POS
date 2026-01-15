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
                                        <span class="bg-teal text-white avatar">LKR</span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">
                                            Stock Value
                                        </div>
                                        <div class="text-muted">
                                            LKR 0.00
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
                                        <span class="bg-teal text-white avatar">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                height="24" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M12 3c-1.657 0 -3 .895 -3 2c0 1.105 1.343 2 3 2s3 .895 3 2c0 1.105 -1.343 2 -3 2s-3 .895 -3 2c0 1.105 1.343 2 3 2s3 .895 3 2c0 1.105 -1.343 2 -3 2"/>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">
                                            In-Stock Value
                                        </div>
                                        <div class="text-muted">
                                            LKR {{ number_format($productCards['stock_value'] ?? 0, 2) }}
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

    <!-- Add Stock Modal - Outside Livewire Component -->
    <div class="modal modal-blur fade" id="addStockModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-status bg-success"></div>
                <div class="modal-header">
                    <h5 class="modal-title">Add Stock</h5>
                </div>
                <form method="POST" id="addStockForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label" for="modalProductName">Product Name</label>
                            <input type="text" class="form-control" id="modalProductName" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="modalCurrentStock">Current Stock</label>
                            <input type="number" class="form-control" id="modalCurrentStock" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="addQuantity">Add Quantity</label>
                            <input type="number" class="form-control" id="addQuantity" min="1" step="1" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label" for="newStockPreview">New Stock</label>
                            <input type="number" class="form-control" id="newStockPreview" readonly>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-success">Add Stock</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('page-scripts')
<script>
    let productSlug = '';
    let currentStock = 0;

    // Set product for stock modal
    window.setProductForStock = function(slug, name, stock) {
        console.log('Setting product for stock modal:', { slug, name, stock });
        productSlug = slug;
        currentStock = stock;

        // Update modal fields
        document.getElementById('modalProductName').value = name;
        document.getElementById('modalCurrentStock').value = stock;
        document.getElementById('addQuantity').value = '';
        document.getElementById('newStockPreview').value = '';

        // Set form action
        document.getElementById('addStockForm').action = `/products/${slug}/add-stock`;
    };

    // Update new stock preview when quantity changes
    document.addEventListener('DOMContentLoaded', function() {
        const addQuantityInput = document.getElementById('addQuantity');
        const newStockPreview = document.getElementById('newStockPreview');

        if (addQuantityInput) {
            addQuantityInput.addEventListener('input', function() {
                const addQty = parseInt(this.value) || 0;
                newStockPreview.value = currentStock + addQty;
            });
        }

        // Handle form submission
        const addStockForm = document.getElementById('addStockForm');
        if (addStockForm) {
            addStockForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const addQuantity = document.getElementById('addQuantity').value;
                const csrfToken = document.querySelector('input[name="_token"]')?.value ||
                                 document.querySelector('meta[name="csrf-token"]')?.content;

                if (!productSlug) {
                    alert('No product selected. Please click "Add Stock" button again.');
                    return;
                }

                if (!addQuantity || addQuantity <= 0) {
                    alert('Please enter a valid quantity.');
                    return;
                }

                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Updating...';

                console.log('Submitting stock update:', {
                    action: this.action,
                    addQuantity: addQuantity,
                    productSlug: productSlug
                });

                fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ add_quantity: parseInt(addQuantity) })
                })
                .then(async response => {
                    const text = await response.text();
                    console.log('Response status:', response.status, 'Body:', text.substring(0, 500));

                    let data = { success: false };
                    try {
                        data = text ? JSON.parse(text) : { success: false, message: 'Empty response' };
                    } catch(e) {
                        console.error('JSON parse error:', e);
                        data = { success: false, message: 'Invalid server response' };
                    }

                    return { status: response.status, ok: response.ok, data: data };
                })
                .then(({ status, ok, data }) => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;

                    console.log('Final response:', { status, ok, data });

                    if (data.success) {
                        // Close modal - handle case where bootstrap might not be defined
                        const modalElement = document.getElementById('addStockModal');
                        try {
                            if (modalElement) {
                                if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                                    const modalInstance = bootstrap.Modal.getInstance(modalElement);
                                    if (modalInstance) {
                                        modalInstance.hide();
                                    }
                                } else {
                                    // Fallback: manually hide modal
                                    modalElement.classList.remove('show');
                                    modalElement.style.display = 'none';
                                    const backdrop = document.querySelector('.modal-backdrop');
                                    if (backdrop) backdrop.remove();
                                }
                            }
                        } catch(e) {
                            console.error('Error closing modal:', e);
                            // Try manual close anyway
                            if (modalElement) {
                                modalElement.classList.remove('show');
                                modalElement.style.display = 'none';
                                const backdrop = document.querySelector('.modal-backdrop');
                                if (backdrop) backdrop.remove();
                            }
                        }

                        // Show success message
                        alert(data.message || 'Stock added successfully!');

                        // Refresh page to update table
                        setTimeout(() => {
                            location.reload();
                        }, 800);
                    } else {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                        alert('Error: ' + (data.message || 'Failed to add stock'));
                    }
                })
                .catch(error => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                    console.error('Fetch error:', error);
                    alert('An error occurred: ' + error.message);
                });
            });
        }
    });
</script>
@endpush
@endsection
