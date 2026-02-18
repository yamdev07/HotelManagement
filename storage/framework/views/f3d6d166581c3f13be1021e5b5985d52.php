<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Invoice</title>

    
    <link rel="icon" href="<?php echo e(asset('img/logo/sip.png')); ?>">

    <?php echo app('Illuminate\Foundation\Vite')('resources/sass/app.scss'); ?>
    <?php echo $__env->yieldContent('head'); ?>
</head>

<body>
    <main class="my-3">
        <?php echo $__env->yieldContent('content'); ?>

    </main>

    <?php echo $__env->yieldContent('footer'); ?>
    <?php echo app('Illuminate\Foundation\Vite')('resources/js/app.js'); ?>
</body>

</html>
<?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/template/invoicemaster.blade.php ENDPATH**/ ?>