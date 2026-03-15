<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <ul class="progress-indicator m-4">
                    <li
                        class="<?php echo e(Route::currentRouteName() == 'transaction.reservation.createIdentity' ? 'completed' : ''); ?> <?php echo e(Route::currentRouteName() == 'transaction.reservation.pickFromCustomer' ? 'completed' : ''); ?> <?php echo e(Route::currentRouteName() == 'transaction.reservation.viewCountPerson' ? 'completed' : ''); ?> <?php echo e(Route::currentRouteName() == 'transaction.reservation.chooseRoom' ? 'completed' : ''); ?> <?php echo e(Route::currentRouteName() == 'transaction.reservation.confirmation' ? 'completed' : ''); ?> <?php echo e(Route::currentRouteName() == 'transaction.reservation.payDownPayment' ? 'completed' : ''); ?>">
                        <span class="bubble"></span> Identity Card
                    </li>
                    <li
                        class="<?php echo e(Route::currentRouteName() == 'transaction.reservation.viewCountPerson' ? 'completed' : ''); ?> <?php echo e(Route::currentRouteName() == 'transaction.reservation.chooseRoom' ? 'completed' : ''); ?> <?php echo e(Route::currentRouteName() == 'transaction.reservation.confirmation' ? 'completed' : ''); ?> <?php echo e(Route::currentRouteName() == 'transaction.reservation.payDownPayment' ? 'completed' : ''); ?>">
                        <span class="bubble"></span> How many person?
                    </li>
                    <li
                        class="<?php echo e(Route::currentRouteName() == 'transaction.reservation.chooseRoom' ? 'completed' : ''); ?> <?php echo e(Route::currentRouteName() == 'transaction.reservation.confirmation' ? 'completed' : ''); ?> <?php echo e(Route::currentRouteName() == 'transaction.reservation.payDownPayment' ? 'completed' : ''); ?>">
                        <span class="bubble"></span> Pick a room
                    </li>
                    <li
                        class="<?php echo e(Route::currentRouteName() == 'transaction.reservation.confirmation' ? 'completed' : ''); ?> <?php echo e(Route::currentRouteName() == 'transaction.reservation.payDownPayment' ? 'completed' : ''); ?>">
                        <span class="bubble"></span> Confirmation
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/transaction/reservation/progressbar.blade.php ENDPATH**/ ?>