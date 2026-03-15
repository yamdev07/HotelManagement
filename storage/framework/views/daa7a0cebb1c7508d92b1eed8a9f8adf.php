<?php $__env->startComponent('mail::layout'); ?>

<?php $__env->slot('header'); ?>
<?php $__env->startComponent('mail::header', ['url' => config('app.url')]); ?>
<?php echo new \Illuminate\Support\EncodedHtmlString(config('app.name')); ?>

<?php echo $__env->renderComponent(); ?>
<?php $__env->endSlot(); ?>


<?php echo new \Illuminate\Support\EncodedHtmlString($slot); ?>



<?php if(isset($subcopy)): ?>
<?php $__env->slot('subcopy'); ?>
<?php $__env->startComponent('mail::subcopy'); ?>
<?php echo new \Illuminate\Support\EncodedHtmlString($subcopy); ?>

<?php echo $__env->renderComponent(); ?>
<?php $__env->endSlot(); ?>
<?php endif; ?>


<?php $__env->slot('footer'); ?>
<?php $__env->startComponent('mail::footer'); ?>
© <?php echo new \Illuminate\Support\EncodedHtmlString(date('Y')); ?> <?php echo new \Illuminate\Support\EncodedHtmlString(config('app.name')); ?>. <?php echo app('translator')->get('All rights reserved.'); ?>
<?php echo $__env->renderComponent(); ?>
<?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>
<?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/vendor/mail/html/message.blade.php ENDPATH**/ ?>