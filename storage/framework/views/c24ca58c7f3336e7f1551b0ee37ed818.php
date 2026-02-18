<?php $__env->startSection('title', 'Room Status'); ?>
<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <!-- Add Status Button -->
        <div class="row mb-4">
            <div class="col-12">
                <button id="add-button" type="button" class="add-item-btn">
                    <i class="fas fa-plus"></i>
                    Add New Status
                </button>
            </div>
        </div>

        <!-- Table Container -->
        <div class="professional-table-container">
            <!-- Table Header -->
            <div class="table-header">
                <h4><i class="fas fa-toggle-on me-2"></i>Room Status Management</h4>
                <p>Manage room availability statuses and their codes</p>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table id="roomstatus-table" class="professional-table table" style="width: 100%;">
                    <thead>
                        <tr>
                            <th scope="col">
                                <i class="fas fa-hashtag me-1"></i>#
                            </th>
                            <th scope="col">
                                <i class="fas fa-tag me-1"></i>Name
                            </th>
                            <th scope="col">
                                <i class="fas fa-code me-1"></i>Code
                            </th>
                            <th scope="col">
                                <i class="fas fa-info-circle me-1"></i>Information
                            </th>
                            <th scope="col">
                                <i class="fas fa-cog me-1"></i>Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- DataTable will populate this -->
                    </tbody>
                </table>
            </div>

            <!-- Table Footer -->
            <div class="table-footer">
                <h3><i class="fas fa-toggle-on me-2"></i>Room Status</h3>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/roomstatus/index.blade.php ENDPATH**/ ?>