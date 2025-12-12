
<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('app.name')); ?></title>
    <link rel="icon" type="image/svg+xml" href="<?php echo e(asset('favicon.svg')); ?>" />
    <link rel="alternate icon" type="image/x-icon" href="<?php echo e(asset('favicon.ico')); ?>" />

    <!-- CSS files -->
    <link href="<?php echo e(asset('dist/css/nexora.min.css')); ?>" rel="stylesheet"/>
    <link href="<?php echo e(asset('css/custom-colors.css')); ?>" rel="stylesheet"/>
    <link href="<?php echo e(asset('css/mobile-responsive.css')); ?>" rel="stylesheet"/>
    <style>
        @import url('https://rsms.me/inter/inter.css');
        :root {
            --nexora-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }
        body {
            font-feature-settings: "cv03", "cv04", "cv11";
        }

        /* Brand styling */
        .navbar-brand-text {
            color: #1f2937 !important;
            text-decoration: none !important;
        }

        .navbar-brand:hover .navbar-brand-text {
            color: #1f2937 !important;
            text-decoration: none !important;
        }
    </style>

    <!-- Custom CSS for specific page.  -->
    <?php echo $__env->yieldPushContent('page-styles'); ?>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

</head>
    <body>

        <div class="page">

            <?php echo $__env->make('layouts.body.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <?php echo $__env->make('layouts.body.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <div class="page-wrapper">
                <div>
                    <?php echo $__env->yieldContent('content'); ?>
                </div>

                <?php echo $__env->make('layouts.body.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </div>

        <!-- Nexora Core -->
        <script src="<?php echo e(asset('dist/js/nexora.min.js')); ?>" defer></script>
        
        <?php echo $__env->yieldPushContent('page-scripts'); ?>

        <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

    </body>
</html>
<?php /**PATH C:\xampp\htdocs\New folder\NexoraLabs\resources\views/layouts/nexora.blade.php ENDPATH**/ ?>