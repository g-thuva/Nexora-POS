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
                                            <button type="button" class="btn btn-sm" title="Add Stock" data-bs-toggle="modal" data-bs-target="#addStockModal" onclick="window.setProductForStock('{{ $product->slug }}', '{{ $product->name }}', {{ $product->quantity }})">
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
</div>
