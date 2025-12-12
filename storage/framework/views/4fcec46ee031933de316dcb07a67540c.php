<?php
if (! isset($scrollTo)) {
    $scrollTo = 'body';
}

$scrollIntoViewJsSnippet = ($scrollTo !== false)
    ? <<<JS
       (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
    JS
    : '';
?>

<div>
    <!--[if BLOCK]><![endif]--><?php if($paginator->hasPages()): ?>
        <nav>
            <ul class="pagination m-0 ms-auto">
                
                <!--[if BLOCK]><![endif]--><?php if($paginator->onFirstPage()): ?>
                <li class="page-item disabled" aria-disabled="true" aria-label="<?php echo app('translator')->get('pagination.previous'); ?>">
                    <span class="page-link" aria-hidden="true">
                        <?php if (isset($component)) { $__componentOriginal9a1c354c8e4026e3d2e857ac047139e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9a1c354c8e4026e3d2e857ac047139e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon.chevron-left','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('icon.chevron-left'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9a1c354c8e4026e3d2e857ac047139e2)): ?>
<?php $attributes = $__attributesOriginal9a1c354c8e4026e3d2e857ac047139e2; ?>
<?php unset($__attributesOriginal9a1c354c8e4026e3d2e857ac047139e2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9a1c354c8e4026e3d2e857ac047139e2)): ?>
<?php $component = $__componentOriginal9a1c354c8e4026e3d2e857ac047139e2; ?>
<?php unset($__componentOriginal9a1c354c8e4026e3d2e857ac047139e2); ?>
<?php endif; ?>
                        prev
                    </span>
                </li>
                <?php else: ?>
                    <li class="page-item">
                        <button type="button" dusk="previousPage<?php echo e($paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName()); ?>" class="page-link" wire:click="previousPage('<?php echo e($paginator->getPageName()); ?>')" x-on:click="<?php echo e($scrollIntoViewJsSnippet); ?>" wire:loading.attr="disabled" rel="prev" aria-label="<?php echo app('translator')->get('pagination.previous'); ?>">
                            <?php if (isset($component)) { $__componentOriginal9a1c354c8e4026e3d2e857ac047139e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9a1c354c8e4026e3d2e857ac047139e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon.chevron-left','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('icon.chevron-left'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9a1c354c8e4026e3d2e857ac047139e2)): ?>
<?php $attributes = $__attributesOriginal9a1c354c8e4026e3d2e857ac047139e2; ?>
<?php unset($__attributesOriginal9a1c354c8e4026e3d2e857ac047139e2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9a1c354c8e4026e3d2e857ac047139e2)): ?>
<?php $component = $__componentOriginal9a1c354c8e4026e3d2e857ac047139e2; ?>
<?php unset($__componentOriginal9a1c354c8e4026e3d2e857ac047139e2); ?>
<?php endif; ?>
                            prev
                        </button>
                    </li>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    
                    <!--[if BLOCK]><![endif]--><?php if(is_string($element)): ?>
                        <li class="page-item disabled" aria-disabled="true">
                            <span class="page-link"><?php echo e($element); ?></span>
                        </li>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                    
                    <!--[if BLOCK]><![endif]--><?php if(is_array($element)): ?>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <!--[if BLOCK]><![endif]--><?php if($page == $paginator->currentPage()): ?>
                                <li class="page-item active" wire:key="paginator-<?php echo e($paginator->getPageName()); ?>-page-<?php echo e($page); ?>" aria-current="page"><span class="page-link"><?php echo e($page); ?></span></li>
                            <?php else: ?>
                                <li class="page-item" wire:key="paginator-<?php echo e($paginator->getPageName()); ?>-page-<?php echo e($page); ?>"><button type="button" class="page-link" wire:click="gotoPage(<?php echo e($page); ?>, '<?php echo e($paginator->getPageName()); ?>')" x-on:click="<?php echo e($scrollIntoViewJsSnippet); ?>"><?php echo e($page); ?></button></li>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

                
                <!--[if BLOCK]><![endif]--><?php if($paginator->hasMorePages()): ?>
                    <li class="page-item">
                        <button type="button"
                                dusk="nextPage<?php echo e($paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName()); ?>"
                                class="page-link" wire:click="nextPage('<?php echo e($paginator->getPageName()); ?>')" x-on:click="<?php echo e($scrollIntoViewJsSnippet); ?>"
                                wire:loading.attr="disabled"
                                rel="next" aria-label="<?php echo app('translator')->get('pagination.next'); ?>">
                            next
                            <?php if (isset($component)) { $__componentOriginal33421e83187810905409acff94940f43 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal33421e83187810905409acff94940f43 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon.chevron-right','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('icon.chevron-right'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal33421e83187810905409acff94940f43)): ?>
<?php $attributes = $__attributesOriginal33421e83187810905409acff94940f43; ?>
<?php unset($__attributesOriginal33421e83187810905409acff94940f43); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal33421e83187810905409acff94940f43)): ?>
<?php $component = $__componentOriginal33421e83187810905409acff94940f43; ?>
<?php unset($__componentOriginal33421e83187810905409acff94940f43); ?>
<?php endif; ?>
                        </button>
                    </li>
                <?php else: ?>
                    <button type="button" class="page-link disabled">
                        next
                        <?php if (isset($component)) { $__componentOriginal33421e83187810905409acff94940f43 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal33421e83187810905409acff94940f43 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon.chevron-right','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('icon.chevron-right'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal33421e83187810905409acff94940f43)): ?>
<?php $attributes = $__attributesOriginal33421e83187810905409acff94940f43; ?>
<?php unset($__attributesOriginal33421e83187810905409acff94940f43); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal33421e83187810905409acff94940f43)): ?>
<?php $component = $__componentOriginal33421e83187810905409acff94940f43; ?>
<?php unset($__componentOriginal33421e83187810905409acff94940f43); ?>
<?php endif; ?>
                    </button>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </ul>
        </nav>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>
<?php /**PATH C:\xampp\htdocs\New folder\NexoraLabs\resources\views/vendor/livewire/bootstrap.blade.php ENDPATH**/ ?>