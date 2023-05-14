<?php $__env->startSection('content'); ?>


<section id='basic-vertical-layouts'>
    <div class="row match-height">
            <div class="col-md-12 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Add Role</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form form-vertical" method="post" action='/system/user_store'>
                                <?php echo csrf_field(); ?>
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="first-name-vertical">Name</label>
                                                <input type="text" id="first-name-vertical" class="form-control" name="name" placeholder="Name" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="first-name-vertical">E-mail</label>
                                                <input type="email" id="first-name-vertical" class="form-control" name="email" placeholder="Email" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="first-name-vertical">Password</label>
                                                <input type="password" id="first-name-vertical" class="form-control" name="password" placeholder="Password" required>
                                            </div>
                                            <div class="form-group">
                                            <label for="first-name-vertical" class="text-left text-neutral"><h6>Role</h6></label>
                                            <select class="form-select" id="basicSelect" name="role">
                                                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($role->name); ?>"><?php echo e($role->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php echo $__env->make('partial.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Fdw Files\CTOS\dev\frontend\tos-dev-local\resources\views/system/user/create.blade.php ENDPATH**/ ?>