<?php $__env->startSection('title', 'Products Management'); ?>

<?php $__env->startSection('content'); ?>
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
                        <a href="<?php echo e(route('products.create')); ?>" class="btn btn-primary d-none d-sm-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M12 5l0 14"/>
                                <path d="M5 12l14 0"/>
                            </svg>
                            New Product
                        </a>
                        <?php if($categories->isNotEmpty()): ?>
                        <a href="<?php echo e(route('categories.index')); ?>" class="btn btn-success d-none d-sm-inline-block">
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
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">

        <?php if($products->isEmpty()): ?>
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

                <?php if (isset($component)) { $__componentOriginal4f22a152e0729cd34293e65bd200d933 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4f22a152e0729cd34293e65bd200d933 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.empty','data' => ['title' => 'No products found','message' => 'Try adjusting your search or filter to find what you\'re looking for.','buttonLabel' => ''.e(__('Add your first Product')).'','buttonRoute' => ''.e(route('products.create')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('empty'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'No products found','message' => 'Try adjusting your search or filter to find what you\'re looking for.','button_label' => ''.e(__('Add your first Product')).'','button_route' => ''.e(route('products.create')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4f22a152e0729cd34293e65bd200d933)): ?>
<?php $attributes = $__attributesOriginal4f22a152e0729cd34293e65bd200d933; ?>
<?php unset($__attributesOriginal4f22a152e0729cd34293e65bd200d933); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4f22a152e0729cd34293e65bd200d933)): ?>
<?php $component = $__componentOriginal4f22a152e0729cd34293e65bd200d933; ?>
<?php unset($__componentOriginal4f22a152e0729cd34293e65bd200d933); ?>
<?php endif; ?>
            </div>
        <?php else: ?>
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
                                            <?php echo e($productCards['total_products'] ?? 0); ?> Items
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
                                            <?php echo e($productCards['in_stock'] ?? 0); ?> Items
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
                                            <?php echo e($productCards['low_stock'] ?? 0); ?> Items
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
                                        <span class="bg-info text-white avatar"><?php echo e($productCards['categories'] ?? 0); ?></span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">
                                            Categories
                                        </div>
                                        <div class="text-muted">
                                            <?php echo e($productCards['categories'] ?? 0); ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if (isset($component)) { $__componentOriginal5194778a3a7b899dcee5619d0610f5cf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5194778a3a7b899dcee5619d0610f5cf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.alert','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5194778a3a7b899dcee5619d0610f5cf)): ?>
<?php $attributes = $__attributesOriginal5194778a3a7b899dcee5619d0610f5cf; ?>
<?php unset($__attributesOriginal5194778a3a7b899dcee5619d0610f5cf); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5194778a3a7b899dcee5619d0610f5cf)): ?>
<?php $component = $__componentOriginal5194778a3a7b899dcee5619d0610f5cf; ?>
<?php unset($__componentOriginal5194778a3a7b899dcee5619d0610f5cf); ?>
<?php endif; ?>

                <?php if (isset($component)) { $__componentOriginal5194778a3a7b899dcee5619d0610f5cf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5194778a3a7b899dcee5619d0610f5cf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.alert','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5194778a3a7b899dcee5619d0610f5cf)): ?>
<?php $attributes = $__attributesOriginal5194778a3a7b899dcee5619d0610f5cf; ?>
<?php unset($__attributesOriginal5194778a3a7b899dcee5619d0610f5cf); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5194778a3a7b899dcee5619d0610f5cf)): ?>
<?php $component = $__componentOriginal5194778a3a7b899dcee5619d0610f5cf; ?>
<?php unset($__componentOriginal5194778a3a7b899dcee5619d0610f5cf); ?>
<?php endif; ?>

                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('tables.product-table');

$__html = app('livewire')->mount($__name, $__params, 'lw-1539226535-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php $__env->startPush('page-scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add any custom JavaScript for the products page
        console.log('Products page loaded');
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.nexora', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\New folder\NexoraLabs\resources\views/products/index.blade.php ENDPATH**/ ?>