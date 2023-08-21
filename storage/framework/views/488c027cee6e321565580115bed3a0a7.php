<!-- /tos/resources/views/layouts/base.blade.php -->
<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title>History Container</title>

    <!-- Styles -->
    <?php echo $__env->make('layouts.partials.styles', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</head>
<body>
    <div id="app">
        <?php echo $__env->make('layouts.partials.sidebar',['href' => route('dashboard'),'logo' => asset('logo/ICON2.png'), 'title' => "Menu"], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        
        <div id="main" class='layout-navbar'>
            <?php echo $__env->make('layouts.partials.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div id="main-content">

                <div class="page-heading">
                	<?php if (! empty(trim($__env->yieldContent('header')))): ?>
                    <div class="page-title">
                        <?php echo $__env->yieldContent('header'); ?>
                    </div>
					<?php endif; ?>
                    <?php echo $__env->yieldContent('content'); ?>
                </div>

                <?php echo $__env->make('partial.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <?php echo $__env->make('layouts.partials.scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

</body>
</html>
<?php /**PATH C:\xampp\htdocs\tos\resources\views/layouts/base.blade.php ENDPATH**/ ?>