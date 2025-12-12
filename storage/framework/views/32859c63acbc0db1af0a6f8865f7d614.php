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
                               value="<?php echo e($search); ?>">
                    </div>
                </div>

                <!-- Category Filter -->
                <div class="col-md-2">
                    <select wire:model.live="categoryFilter" class="form-select">
                        <option value="">All Categories</option>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </select>
                </div>

                <!-- Unit Filter -->
                <div class="col-md-2">
                    <select wire:model.live="unitFilter" class="form-select">
                        <option value="">All Units</option>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($unit->id); ?>"><?php echo e($unit->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
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

                <!-- View Toggle and Actions -->
                <div class="col-md-2">
                    <div class="btn-group w-100" role="group">
                        <button type="button"
                                wire:click="toggleViewType"
                                class="btn btn-outline-primary btn-sm"
                                title="<?php echo e($viewType === 'cards' ? 'Switch to Table View' : 'Switch to Card View'); ?>">
                            <!--[if BLOCK]><![endif]--><?php if($viewType === 'cards'): ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <rect width="7" height="7" x="3" y="3" rx="1"/>
                                    <rect width="7" height="7" x="14" y="3" rx="1"/>
                                    <rect width="7" height="7" x="14" y="14" rx="1"/>
                                    <rect width="7" height="7" x="3" y="14" rx="1"/>
                                </svg>
                            <?php else: ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <line x1="8" x2="21" y1="6" y2="6"/>
                                    <line x1="8" x2="21" y1="12" y2="12"/>
                                    <line x1="8" x2="21" y1="18" y2="18"/>
                                    <line x1="3" x2="3.01" y1="6" y2="6"/>
                                    <line x1="3" x2="3.01" y1="12" y2="12"/>
                                    <line x1="3" x2="3.01" y1="18" y2="18"/>
                                </svg>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </button>
                        <button type="button"
                                wire:click="clearFilters"
                                class="btn btn-outline-secondary btn-sm"
                                title="Clear Filters">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                <path d="M3 6h18"/>
                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                                <line x1="10" x2="10" y1="11" y2="17"/>
                                <line x1="14" x2="14" y1="11" y2="17"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Results Summary -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <small class="text-muted">
                        Showing <?php echo e($products->firstItem() ?? 0); ?> to <?php echo e($products->lastItem() ?? 0); ?> of <?php echo e($products->total()); ?> products
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
    <?php if (isset($component)) { $__componentOriginal3ecbb299d928ab1b0c1204ffec825529 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3ecbb299d928ab1b0c1204ffec825529 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.spinner.loading-spinner','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('spinner.loading-spinner'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3ecbb299d928ab1b0c1204ffec825529)): ?>
<?php $attributes = $__attributesOriginal3ecbb299d928ab1b0c1204ffec825529; ?>
<?php unset($__attributesOriginal3ecbb299d928ab1b0c1204ffec825529); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3ecbb299d928ab1b0c1204ffec825529)): ?>
<?php $component = $__componentOriginal3ecbb299d928ab1b0c1204ffec825529; ?>
<?php unset($__componentOriginal3ecbb299d928ab1b0c1204ffec825529); ?>
<?php endif; ?>

    <!--[if BLOCK]><![endif]--><?php if(safe_count($products) > 0): ?>
        <!--[if BLOCK]><![endif]--><?php if($viewType === 'cards'): ?>
            <!-- Card View -->
            <div wire:loading.class="opacity-50" class="row g-3">
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <!-- Product Header -->
                                <div class="d-flex align-items-start justify-content-between mb-3">
                                    <div class="flex-grow-1">
                                        <h4 class="card-title mb-1 text-truncate"><?php echo e($product->name); ?></h4>
                                        <div class="text-muted small"><?php echo e($product->code); ?></div>
                                               <?php echo e(optional($product->category)->name ?? '-'); ?>

                                    <!-- Stock Status Badge -->
                                    <?php
                                        $stockClass = 'bg-success';
                                        $stockText = 'In Stock';
                                        if ($product->quantity <= 0) {
                                            $stockClass = 'bg-danger';
                                            $stockText = 'Out of Stock';
                                        } elseif ($product->quantity <= $product->quantity_alert) {
                                            $stockClass = 'bg-warning';
                                            $stockText = 'Low Stock';
                                        }
                                    ?>
                                    <span class="badge <?php echo e($stockClass); ?> badge-sm"><?php echo e($stockText); ?></span>
                                </div>

                                <!-- Product Details -->
                                <div class="mb-3">
                                    <div class="row g-2 text-sm">
                                        <div class="col-6">
                                            <div class="text-muted">Category:</div>
                                            <div class="fw-medium"><?php echo e(optional($product->category)->name ?? '-'); ?></div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-muted">Unit:</div>
                                            <div class="fw-medium"><?php echo e($product->unit->name); ?></div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-muted">Quantity:</div>
                                            <div class="fw-medium"><?php echo e(number_format($product->quantity)); ?></div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-muted">Selling Price:</div>
                                            <div class="fw-medium text-success">$<?php echo e(number_format($product->selling_price, 2)); ?></div>
                                        </div>
                                        <!--[if BLOCK]><![endif]--><?php if($product->warranty): ?>
                                        <div class="col-12">
                                            <div class="text-muted">Warranty:</div>
                                            <div class="fw-medium"><?php echo e($product->warranty->name); ?></div>
                                        </div>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                </div>

                                <!-- Progress Bar for Stock Level -->
                                <!--[if BLOCK]><![endif]--><?php if($product->quantity > 0): ?>
                                    <?php
                                        $stockPercentage = min(100, ($product->quantity / ($product->quantity_alert * 2)) * 100);
                                        $progressClass = $stockPercentage > 50 ? 'bg-success' : ($stockPercentage > 25 ? 'bg-warning' : 'bg-danger');
                                    ?>
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between text-sm mb-1">
                                            <span class="text-muted">Stock Level</span>
                                            <span class="fw-medium"><?php echo e($product->quantity); ?>/<?php echo e($product->quantity_alert * 2); ?></span>
                                        </div>
                                        <div class="progress" style="height: 4px;">
                                            <div class="progress-bar <?php echo e($progressClass); ?>" role="progressbar" style="width: <?php echo e($stockPercentage); ?>%"></div>
                                        </div>
                                    </div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                                <!-- Action Buttons -->
                                <div class="btn-group w-100" role="group">
                                    <a href="<?php echo e(route('products.show', $product)); ?>" class="btn btn-outline-primary btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1">
                                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                        View
                                    </a>
                                    <a href="<?php echo e(route('products.edit', $product)); ?>" class="btn btn-outline-success btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1">
                                            <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/>
                                        </svg>
                                        Edit
                                    </a>
                                    <form action="<?php echo e(route('products.destroy', $product)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1">
                                                <path d="M3 6h18"/>
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                                                <line x1="10" x2="10" y1="11" y2="17"/>
                                                <line x1="14" x2="14" y1="11" y2="17"/>
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
            <!-- Load More Button for Card View -->
            <!--[if BLOCK]><![endif]--><?php if($products->hasMorePages()): ?>
                <div class="d-flex justify-content-center my-4">
                    <button wire:click="loadMore" class="btn btn-outline-primary">
                        Load More
                    </button>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        <?php else: ?>
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
                                        <!--[if BLOCK]><![endif]--><?php if($sortField === 'name'): ?>
                                            <!--[if BLOCK]><![endif]--><?php if($sortAsc): ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                                    <path d="m7 15 5 5 5-5"/>
                                                    <path d="m7 9 5-5 5 5"/>
                                                </svg>
                                            <?php else: ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                                    <path d="m17 14-5 5-5-5"/>
                                                </svg>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
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
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="text-muted">
                                        <?php echo e(($products->currentPage() - 1) * $products->perPage() + $loop->iteration); ?>

                                    </td>
                                    <td>
                                        <div style="display: block;">
                                            <div class="fw-bold" style="line-height:1.2;"><?php echo e($product->name); ?></div>
                                            <div class="text-muted" style="font-size: 12px; line-height:1;"><?php echo e($product->code); ?></div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-blue-lt"><?php echo e(optional($product->category)->name ?? '-'); ?></span>
                                    </td>
                                    <td>
                                        <span class="badge bg-green-lt"><?php echo e(optional($product->unit)->name ?? '-'); ?></span>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                            $stockClass = 'text-success';
                                            if ($product->quantity <= 0) {
                                                $stockClass = 'text-danger fw-bold';
                                            } elseif ($product->quantity <= $product->quantity_alert) {
                                                $stockClass = 'text-warning fw-bold';
                                            }
                                        ?>
                                        <span class="<?php echo e($stockClass); ?>"><?php echo e(number_format($product->quantity)); ?></span>
                                        <div class="text-muted small">Alert: <?php echo e($product->quantity_alert); ?></div>
                                    </td>
                                    <td class="text-end">
                                        <div class="fw-bold text-success">$<?php echo e(number_format($product->selling_price, 2)); ?></div>
                                        <!--[if BLOCK]><![endif]--><?php if($product->buying_price > 0): ?>
                                            <div class="text-muted small">Cost: $<?php echo e(number_format($product->buying_price, 2)); ?></div>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </td>
                                    <td>
                                        <div class="btn-list flex-nowrap">
                                            <button type="button" class="btn btn-success btn-sm" title="Add Stock" data-bs-toggle="modal" data-bs-target="#addStockModal" onclick="setProductForStock('<?php echo e($product->slug); ?>', '<?php echo e($product->name); ?>', <?php echo e($product->quantity); ?>)">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                                            </button>
                                            <a href="<?php echo e(route('products.show', $product)); ?>" class="btn btn-white btn-sm" title="View">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><circle cx="12" cy="12" r="2" /><path d="M22 12c-2.667 4.667-6 7-10 7s-7.333-2.333-10-7c2.667-4.667 6-7 10-7s7.333 2.333 10 7" /></svg>
                                            </a>
                                            <a href="<?php echo e(route('products.edit', $product)); ?>" class="btn btn-warning btn-sm" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M7 7h-1a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2-2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0-2.97-2.97l-8.415 8.385v3h3l8.385-8.415z" /><path d="M16 5l3 3" /></svg>
                                            </a>
                                            <form action="<?php echo e(route('products.destroy', $product)); ?>" method="POST" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Are you sure you want to delete this product?')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Load More Button for Table View -->
            <!--[if BLOCK]><![endif]--><?php if($products->hasMorePages()): ?>
                <div class="d-flex justify-content-center my-4">
                    <button wire:click="loadMore" class="btn btn-outline-primary">
                        Load More
                    </button>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <!-- Pagination -->
        <!--[if BLOCK]><![endif]--><?php if($products->hasPages()): ?>
            <div class="d-flex align-items-center justify-content-between mt-4">
                <div class="text-muted">
                    Showing <?php echo e($products->firstItem()); ?> to <?php echo e($products->lastItem()); ?> of <?php echo e($products->total()); ?> products
                </div>
                <div>
                    <?php echo e($products->links()); ?>

                </div>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <?php else: ?>
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
                    <!--[if BLOCK]><![endif]--><?php if($search || $categoryFilter || $unitFilter || $stockFilter !== 'all'): ?>
                        No products match your current filters.
                    <?php else: ?>
                        Get started by creating your first product.
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </p>
                <div class="mt-4">
                    <!--[if BLOCK]><![endif]--><?php if($search || $categoryFilter || $unitFilter || $stockFilter !== 'all'): ?>
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
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    <a href="<?php echo e(route('products.create')); ?>" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1">
                            <path d="M5 12h14"/>
                            <path d="M12 5v14"/>
                        </svg>
                        Create Product
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

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
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
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
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">
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

    <?php $__env->startPush('page-scripts'); ?>
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
            const formAction = `<?php echo e(url('products')); ?>/${slug}/add-stock`;
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
    <?php $__env->stopPush(); ?>
</div>
<?php /**PATH C:\xampp\htdocs\New folder\NexoraLabs\resources\views/livewire/tables/product-table.blade.php ENDPATH**/ ?>