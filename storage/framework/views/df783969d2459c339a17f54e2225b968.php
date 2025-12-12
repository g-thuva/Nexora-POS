<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps([
    // TODO: WIP
]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps([
    // TODO: WIP
]); ?>
<?php foreach (array_filter(([
    // TODO: WIP
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible bg-white" role="alert">
        <h3 class="mb-1">Success</h3>
        <p><?php echo e(session('success')); ?></p>

        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
    </div>
<?php endif; ?>

<?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible bg-white" role="alert">
        <h3 class="mb-1">Oops...</h3>
        <p><?php echo e(session('error')); ?></p>

        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
    </div>
<?php endif; ?>

<?php if($errors->any()): ?>
    <div class="alert alert-danger alert-dismissible bg-white" role="alert">
        <h3 class="mb-1">Oops...</h3>
        <ul>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>

        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
    </div>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\New folder\NexoraLabs\resources\views/components/alert.blade.php ENDPATH**/ ?>