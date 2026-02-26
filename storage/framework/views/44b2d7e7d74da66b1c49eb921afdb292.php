<?php $__env->startSection('title', 'Notification'); ?>
<?php $__env->startSection('head'); ?>
<link rel="stylesheet" href="<?php echo e(asset('style/css/notification.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="page">
        <div class="timelines">
            <div class="timeline__group">
                <span class="timeline__year time" aria-hidden="true">Unread</span>
                <div class="timeline__cards">
                    <?php $__empty_1 = true; $__currentLoopData = auth()->user()->unreadNotifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="timeline__card card">
                            <header class="card__header">
                                <time class="time">
                                    <span class="time__day"><?php echo e(Helper::dateFormatTimeNoYear($notification->created_at)); ?></span>
                                </time>
                            </header>
                            <div class="card__content">
                                <p><?php echo e($notification->data['message']); ?></p>
                                <a class="btn btn-sm shadow-sm myBtn border rounded" href="<?php echo e($notification->data['url']); ?>"> See Detail</a>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="timeline__card card">
                        <div class="card__content">
                            <p>There's no new unread notification</p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="timeline__group">
                <span class="timeline__year time" aria-hidden="true">Read</span>
                <div class="timeline__cards">
                    <?php $__empty_1 = true; $__currentLoopData = auth()->user()->readNotifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="timeline__card card">
                            <header class="card__header">
                                <time class="time">
                                    <span class="time__day"><?php echo e(Helper::dateFormatTimeNoYear($notification->created_at)); ?></span>
                                </time>
                            </header>
                            <div class="card__content">
                                <p><?php echo e($notification->data['message']); ?></p>
                                <a class="btn btn-sm shadow-sm myBtn border rounded mt-2 float-end" href="<?php echo e($notification->data['url']); ?>"> See Detail</a>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="timeline__card card">
                        <div class="card__content">
                            <p>There's no notification</p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/notification/index.blade.php ENDPATH**/ ?>