<style>
    @media screen and (max-width: 400px) {
        li.page-item {

            display: none;
        }

        .page-item:first-child,
        .page-item:nth-child(2),
        .page-item:nth-last-child(2),
        .page-item:last-child,
        .page-item.active,
        .page-item.disabled {

            display: block;
        }
    }

</style>
<?php if($paginator->hasPages()): ?>
    <ul class="pagination">
        <!-- Prevoius Page Link -->
        <?php if($paginator->onFirstPage()): ?>
            <li class="page-item disabled"><a class="page-link"><span>&laquo;</span></a></li>
        <?php else: ?>
            <li class="page-item"><a href="<?php echo e($paginator->previousPageUrl()); ?>" class="page-link"
                    rel="prev">&laquo;</a></li>
        <?php endif; ?>

        <!-- Pagination Elements Here -->
        <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <!-- Make three dots -->
            <?php if(is_string($element)): ?>
                <li class="page-item disabled"><a class="page-link"><span><?php echo e($element); ?></span></a></li>
            <?php endif; ?>

            <!-- Links Array Here -->
            <?php if(is_array($element)): ?>
                <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <?php if($page == $paginator->currentPage()): ?>
                        <li class="page-item active"><a class="page-link"><span><?php echo e($page); ?></span></a></li>
                    <?php else: ?>
                        <li class="page-item"><a href="<?php echo e($url); ?>" class="page-link"><?php echo e($page); ?></a>
                        </li>
                    <?php endif; ?>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <!-- Next Page Link -->
        <?php if($paginator->hasMorePages()): ?>
            <li class="page-item"><a href="<?php echo e($paginator->nextPageUrl()); ?>" class="page-link"><span>&raquo;</span></a>
            </li>
        <?php else: ?>
            <li class="page-item disabled"><a class="page-link"><span>&raquo;</span></a></li>
        <?php endif; ?>
    </ul>

<?php endif; ?>
<?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/template/paginationlinks.blade.php ENDPATH**/ ?>