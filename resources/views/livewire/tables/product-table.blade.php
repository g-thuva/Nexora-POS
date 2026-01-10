<div>
    <!-- Filters and Search Section -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <!-- Search -->
                <div class="col-md-4">
                    <div class="input-icon">
                        <span class="input-icon-addon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-search">
                                <path stroke="none" d="m0 0h24v24H0z" fill="none"/>
                                <path d="m15 15l6 6"/>
                                <path d="c-3.866 0-7-3.134-7-7s3.134-7 7-7s7 3.134 7 7s-3.134 7-7 7z"/>
                            </svg>
                        </span>
                        <input type="text"
                               wire:model.live.debounce.300ms="search"
                               class="form-control"
                               placeholder="Search products..."
                               value="{{ $search }}">
                    </div>
                </div>

                <!-- Category Filter -->
                <div class="col-md-2">
                    <select wire:model.live="categoryFilter" class="form-select">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Unit Filter -->
                <div class="col-md-2">
                    <select wire:model.live="unitFilter" class="form-select">
                        <option value="">All Units</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Stock Filter -->
                <div class="col-md-2">
                    <select wire:model.live="stockFilter" class="form-select">
                        <option value="all">All Stock</option>
                        <option value="in_stock">In Stock</option>
                        <option value="low_stock">Low Stock</option>
                        <option value="out_of_stock">Out of Stock</option>
                    </select>
                </div>

                <!-- Clear Filters Button -->
                <div class="col-md-2">
                    <button type="button"
                            wire:click="clearFilters"
                            class="btn btn-outline-secondary w-100"
                            title="Clear Filters">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                            <path d="M3 6h18"/>
                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                            <line x1="10" x2="10" y1="11" y2="17"/>
                            <line x1="14" x2="14" y1="11" y2="17"/>
                        </svg>
                        Clear Filters
                    </button>
                </div>
            </div>

            <!-- Results Summary -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <small class="text-muted">
                        Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products
                    </small>
                </div>
                <div class="col-md-6 text-end">
                    <div class="d-inline-block">
                        <select wire:model.live="perPage" class="form-select form-select-sm" style="width: auto;">
                            <option value="6">6 per page</option>
                            <option value="12">12 per page</option>
                            <option value="24">24 per page</option>
                            <option value="48">48 per page</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Spinner -->
    <x-spinner.loading-spinner/>

    @if(safe_count($products) > 0)
        <!-- Table View -->
            <div class="card">
                <div class="table-responsive">
                    <table wire:loading.class="opacity-50" class="table table-vcenter card-table">
                        <thead>
                            <tr>
                                <th class="w-1">No.</th>
                                <th>
                                    <a wire:click.prevent="sortBy('name')" href="#" role="button" class="text-decoration-none">
                                        Name
                                        @if($sortField === 'name')
                                            @if($sortAsc)
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                                    <path d="m7 15 5 5 5-5"/>
                                                    <path d="m7 9 5-5 5 5"/>
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                                    <path d="m17 14-5 5-5-5"/>
                                                </svg>
                                            @endif
                                        @endif
                                    </a>
                                </th>
                                <th>Category</th>
                                <th>Unit</th>
                                <th class="text-center">Stock</th>
                                <th class="text-end">Price</th>
                                <th class="w-1">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td class="text-muted">
                                        {{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}
                                    </td>
                                    <td>
                                        <div style="display: block;">
                                            <div class="fw-bold" style="line-height:1.2;">{{ $product->name }}</div>
                                            <div class="text-muted" style="font-size: 11px; line-height:1.4; margin-top: 2px;">
                                                {{ $product->code ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-blue-lt">{{ optional($product->category)->name ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-green-lt">{{ optional($product->unit)->name ?? '-' }}</span>
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $stockClass = 'text-success';
                                            if ($product->quantity <= 0) {
                                                $stockClass = 'text-danger fw-bold';
                                            } elseif ($product->quantity <= $product->quantity_alert) {
                                                $stockClass = 'text-warning fw-bold';
                                            }
                                        @endphp
                                        <span class="{{ $stockClass }}">{{ number_format($product->quantity) }}</span>
                                        <div class="text-muted small">Alert: {{ $product->quantity_alert }}</div>
                                    </td>
                                    <td class="text-end">
                                        <div class="fw-bold text-success">LKR {{ number_format($product->selling_price, 2) }}</div>
                                        @if($product->buying_price > 0)
                                            <div class="text-muted small">Cost: LKR {{ number_format($product->buying_price, 2) }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-list flex-nowrap">
                                            <button type="button" class="btn btn-sm" title="Add Stock" data-bs-toggle="modal" data-bs-target="#addStockModal" onclick="setProductForStock('{{ $product->slug }}', '{{ $product->name }}', {{ $product->quantity }})">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                                            </button>
                                            <a href="{{ route('products.show', $product) }}" class="btn btn-white btn-sm" title="View">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><circle cx="12" cy="12" r="2" /><path d="M22 12c-2.667 4.667-6 7-10 7s-7.333-2.333-10-7c2.667-4.667 6-7 10-7s7.333 2.333 10 7" /></svg>
                                            </a>
                                            <a href="{{ route('products.edit', $product) }}" class="btn btn-sm" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M7 7h-1a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2-2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0-2.97-2.97l-8.415 8.385v3h3l8.385-8.415z" /><path d="M16 5l3 3" /></svg>
                                            </a>
                                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm" title="Delete" onclick="return confirm('Are you sure you want to delete this product?')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Load More Button for Table View -->
            @if($products->hasMorePages())
                <div class="d-flex justify-content-center my-4">
                    <button wire:click="loadMore" class="btn btn-outline-primary">
                        Load More
                    </button>
                </div>
            @endif

        <!-- Pagination -->
        @if($products->hasPages())
            <div class="d-flex align-items-center justify-content-between mt-4">
                <div class="text-muted">
                    Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} products
                </div>
                <div>
                    {{ $products->links() }}
                </div>
            </div>
        @endif

    @else
        <!-- Empty State -->
        <div wire:loading.class="opacity-50" class="card">
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="icon text-muted">
                        <path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"/>
                        <path d="m9 12 2 2 4-4"/>
                    </svg>
                </div>
                <h3 class="text-muted">No products found</h3>
                <p class="text-muted">
                    @if($search || $categoryFilter || $unitFilter || $stockFilter !== 'all')
                        No products match your current filters.
                    @else
                        Get started by creating your first product.
                    @endif
                </p>
                <div class="mt-4">
                    @if($search || $categoryFilter || $unitFilter || $stockFilter !== 'all')
                        <button wire:click="clearFilters" class="btn btn-outline-primary me-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1">
                                <path d="M3 6h18"/>
                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                                <line x1="10" x2="10" y1="11" y2="17"/>
                                <line x1="14" x2="14" y1="11" y2="17"/>
                            </svg>
                            Clear Filters
                        </button>
                    @endif
                    <a href="{{ route('products.create') }}" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1">
                            <path d="M5 12h14"/>
                            <path d="M12 5v14"/>
                        </svg>
                        Create Product
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Add Stock Modal -->
    <div wire:ignore.self class="modal fade" id="addStockModal" tabindex="-1" aria-labelledby="addStockModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" wire:ignore>
                <div class="modal-header">
                    <h5 class="modal-title" id="addStockModalLabel">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M12 5l0 14"/>
                            <path d="M5 12l14 0"/>
                        </svg>
                        Add Stock
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addStockForm" method="POST" action="#">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="modalProductName" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Current Stock</label>
                            <input type="text" class="form-control" id="modalCurrentStock" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required">Add Quantity</label>
                            <input type="number" class="form-control" name="add_quantity" id="addQuantity" min="1" step="1" required>
                            <small class="form-hint">Enter the quantity to add to current stock</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notes (Optional)</label>
                            <textarea class="form-control" name="notes" rows="2" placeholder="Reason for stock adjustment..."></textarea>
                        </div>
                        <div class="alert alert-info mb-0">
                            <div class="d-flex">
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <circle cx="12" cy="12" r="9"/>
                                        <line x1="12" y1="8" x2="12" y2="12"/>
                                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="alert-title">New stock will be:</h4>
                                    <div class="text-muted" id="newStockPreview">-</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <polyline points="9 11 12 14 20 6"/>
                                <path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9"/>
                            </svg>
                            Update Stock
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('page-scripts')
    <script>
        let currentStock = 0;
        let productSlug = null;

        function setProductForStock(slug, name, stock) {
            productSlug = slug;
            currentStock = stock;
            document.getElementById('modalProductName').value = name;
            document.getElementById('modalCurrentStock').value = stock;
            document.getElementById('addQuantity').value = '';
            document.getElementById('newStockPreview').textContent = '-';

            // Update form action using product slug (not ID)
            const formAction = `{{ url('products') }}/${slug}/add-stock`;
            document.getElementById('addStockForm').action = formAction;
            console.log('Set form action to:', formAction);
            console.log('Product slug:', slug);
        }

        // Update preview when quantity changes
        document.addEventListener('DOMContentLoaded', function() {
            setupAddStockHandlers();
        });

        // Setup handlers that work with Livewire updates
        function setupAddStockHandlers() {
            const addQuantityInput = document.getElementById('addQuantity');
            if (addQuantityInput) {
                addQuantityInput.addEventListener('input', updateStockPreview);
            }

            const addStockForm = document.getElementById('addStockForm');
            if (addStockForm) {
                // Remove existing listener if any
                addStockForm.removeEventListener('submit', handleStockSubmit);
                addStockForm.addEventListener('submit', handleStockSubmit);
            }

            const modal = document.getElementById('addStockModal');
            if (modal) {
                modal.addEventListener('hidden.bs.modal', resetStockForm);
            }
        }

        function updateStockPreview() {
            const addQty = parseInt(this.value) || 0;
            const newStock = currentStock + addQty;
            document.getElementById('newStockPreview').textContent = `Current: ${currentStock} + Add: ${addQty} = New Total: ${newStock}`;
        }

        function resetStockForm() {
            document.getElementById('addStockForm').reset();
            document.getElementById('newStockPreview').textContent = '-';
        }

        function handleStockSubmit(e) {
            e.preventDefault();

            const form = this;
            console.log('Form action:', form.action);
            console.log('Form method:', form.method);

            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;

            // Log FormData contents for debugging
            console.log('FormData contents:');
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }

            // Disable submit button
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Updating...';

            console.log('Submitting to:', form.action);

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response ok:', response.ok);
                console.log('Response headers:', response.headers);
                console.log('Content-Type:', response.headers.get('content-type'));

                if (!response.ok) {
                    return response.text().then(text => {
                        console.error('Error response body:', text);
                        throw new Error(`HTTP error! status: ${response.status}, body: ${text.substring(0, 200)}`);
                    });
                }

                // Check if response is JSON
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return response.json();
                } else {
                    // Server returned HTML - likely a redirect or error page
                    return response.text().then(text => {
                        console.log('Received HTML response:', text.substring(0, 500));
                        // Since we got 200 OK, consider it success and reload
                        if (response.status === 200) {
                            // Close modal properly without relying on Bootstrap JS
                            const modalElement = document.getElementById('addStockModal');
                            if (modalElement) {
                                modalElement.classList.remove('show');
                                modalElement.style.display = 'none';
                                modalElement.setAttribute('aria-hidden', 'true');
                                modalElement.removeAttribute('aria-modal');
                            }
                            // Remove backdrop if it exists
                            document.querySelectorAll('.modal-backdrop').forEach(backdrop => backdrop.remove());
                            document.body.classList.remove('modal-open');
                            document.body.style.removeProperty('overflow');
                            document.body.style.removeProperty('padding-right');

                            // Return success object to continue the chain
                            return { success: true, message: 'Stock updated successfully!', reload: true };
                        } else {
                            throw new Error('Server returned HTML instead of JSON');
                        }
                    });
                }
            })
            .then(data => {
                // Check if we need to reload (from HTML response handler)
                if (data && data.reload) {
                    window.location.reload();
                    return;
                }

                if (data && data.success) {
                    // Close modal properly without relying on Bootstrap JS
                    const modalElement = document.getElementById('addStockModal');
                    if (modalElement) {
                        modalElement.classList.remove('show');
                        modalElement.style.display = 'none';
                        modalElement.setAttribute('aria-hidden', 'true');
                        modalElement.removeAttribute('aria-modal');
                    }
                    // Remove backdrop if it exists
                    document.querySelectorAll('.modal-backdrop').forEach(backdrop => backdrop.remove());
                    document.body.classList.remove('modal-open');
                    document.body.style.removeProperty('overflow');
                    document.body.style.removeProperty('padding-right');

                    // Reload page immediately to show updated stock
                    window.location.reload();
                } else if (data.errors) {
                    // Show validation errors
                    let errorMsg = 'Validation errors:\n';
                    Object.values(data.errors).forEach(err => {
                        errorMsg += '- ' + err + '\n';
                    });
                    alert(errorMsg);
                }
            })
            .catch(error => {
                console.error('Error details:', error);
                // Only show error if modal is still open (meaning it wasn't a success)
                const modalElement = document.getElementById('addStockModal');
                if (modalElement && modalElement.classList.contains('show')) {
                    alert('An error occurred while updating stock. Please check console for details.');
                }
            })
            .finally(() => {
                // Re-enable submit button only if modal is still open
                const modalElement = document.getElementById('addStockModal');
                if (modalElement && modalElement.classList.contains('show')) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                }
            });
        }

        // Re-initialize handlers after Livewire updates
        document.addEventListener('livewire:load', function() {
            setupAddStockHandlers();
        });

        // Re-initialize after each Livewire update (check if Livewire is available)
        if (typeof Livewire !== 'undefined') {
            Livewire.hook('message.processed', (message, component) => {
                setupAddStockHandlers();
            });
        }
    </script>
    @endpush
</div>
