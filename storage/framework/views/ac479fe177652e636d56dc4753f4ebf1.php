<?php $__env->startSection('content'); ?>


<section id='basic-vertical-layouts'>
    <div class="row match-height">
            <div class="col-md-12 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Role</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form form-vertical" method="post" action='/system/role_update=<?php echo e($roles->id); ?>'>
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="first-name-vertical">Role Name</label>
                                                <input type="text" id="first-name-vertical" class="form-control" name="name" placeholder="<?php echo e($roles->name); ?>">
                                            </div>
                                            <div class="form-group">
                                            <label for="first-name-vertical" class="text-left text-neutral"><h6>Guard Name</h6></label>
                                            <select class="form-select" id="basicSelect" name="guard_name" placeholder="<?php echo e($roles->guard_name); ?>">
                                            <option value="web">Web</option>
                                            <option value="api">Api</option>
                                        </select>
                                        </div>
                                        </div>
                                        <div class="col-12 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                                            <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('partial.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Fdw Files\CTOS\dev\frontend\tos-dev-local\resources\views/system/role/edit.blade.php ENDPATH**/ ?>