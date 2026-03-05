
<?php $__env->startSection('title', 'Count Person'); ?>
<?php $__env->startSection('head'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('style/css/progress-indication.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('transaction.reservation.progressbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <div class="container mt-3">
        <div class="row justify-content-md-center">
            <div class="col-md-8 mt-2">
                <div class="card shadow-sm border">
                    <div class="card-body p-3">
                        <div class="card">
                            <div class="card-body">
                                <form class="row g-3" method="GET"
                                    action="<?php echo e(route('transaction.reservation.chooseRoom', ['customer' => $customer->id])); ?>">
                                    <div class="col-md-12">
                                        <label for="count_person" class="form-label">
                                            How many person?
                                        </label>
                                        <input type="text" class="form-control <?php $__errorArgs = ['count_person'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            id="count_person" name="count_person" value="<?php echo e(old('count_person')); ?>">
                                        <?php $__errorArgs = ['count_person'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="text-danger mt-1">
                                                <?php echo e($message); ?>

                                            </div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        <label for="check_in" class="form-label">
                                            From
                                        </label>
                                        <input type="date" class="form-control <?php $__errorArgs = ['check_in'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="check_in" name="check_in" value="<?php echo e(old('check_in')); ?>">
                                        <?php $__errorArgs = ['check_in'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="text-danger mt-1">
                                                <?php echo e($message); ?>

                                            </div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        <label for="check_out" class="form-label">
                                            Until
                                        </label>
                                        <input type="date" class="form-control <?php $__errorArgs = ['check_out'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="check_out" name="check_out" value="<?php echo e(old('check_out')); ?>">
                                        <?php $__errorArgs = ['check_out'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="text-danger mt-1">
                                                <?php echo e($message); ?>

                                            </div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn myBtn shadow-sm border float-end">Next</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mt-2">
                <div class="card shadow-sm">
                    <img src="<?php echo e($customer->user->getAvatar()); ?>"
                        style="border-top-right-radius: 0.5rem; border-top-left-radius: 0.5rem">
                    <div class="card-body">
                        <table>
                            <tr>
                                <td style="text-align: center; width:50px">
                                    <span>
                                        <i class="fas <?php echo e($customer->gender == 'Male' ? 'fa-male' : 'fa-female'); ?>">
                                        </i>
                                    </span>
                                </td>
                                <td>
                                    <?php echo e($customer->name); ?>

                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center; ">
                                    <span>
                                        <i class="fas fa-user-md"></i>
                                    </span>
                                </td>
                                <td><?php echo e($customer->job); ?></td>
                            </tr>
                            <tr>
                                <td style="text-align: center; ">
                                    <span>
                                        <i class="fas fa-birthday-cake"></i>
                                    </span>
                                </td>
                                <td>
                                    <?php echo e($customer->birthdate); ?>

                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center; ">
                                    <span>
                                        <i class="fas fa-map-marker-alt"></i>
                                    </span>
                                </td>
                                <td>
                                    <?php echo e($customer->address); ?>

                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/transaction/reservation/viewCountPerson.blade.php ENDPATH**/ ?>