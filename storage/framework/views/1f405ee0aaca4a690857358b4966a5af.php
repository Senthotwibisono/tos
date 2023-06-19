<?php $__env->startSection('content'); ?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Vessel Schedule</h3>
                <p class="text-subtitle text-muted"></p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">Vessel Schedule</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
               
                    <a href="/planning/create-schedule" class="btn icon icon-left btn-warning"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg> Create Schedule</a>
            </div>
            <div class="card-body">
                <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
                    <thead>
                        <tr>
                            <th>Ves. Code</th>
                            <th>Ves. Id</th>
                            <th>Voy Out</th>
                            <th>Voy In</th>
                            <th>Ves. Name</th>
                            <th>Agent</th>
                            <th>Liner</th>
                            <th>Berth. Date</th>
                            <th>Dep. Date</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $__currentLoopData = $vessel_voyage; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $voyage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($voyage->ves_code); ?></td>                            
                            <td><?php echo e(str_pad($voyage->ves_id,4,'0', STR_PAD_LEFT)); ?></td>
                            <td><?php echo e($voyage->voy_out); ?></td>
                            <td><?php echo e($voyage->voy_in); ?></td>
                            <td><?php echo e($voyage->ves_name); ?></td>
                            <td><?php echo e($voyage->agent); ?></td>
                            <td><?php echo e($voyage->liner); ?></td>
                            <td><?php echo e($voyage->berthing_date); ?></td>
                            <td><?php echo e($voyage->deparature_date); ?></td>
                            <td>
                            <form action="/planning/delete_schedule=<?php echo e($voyage->ves_id); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn icon btn-danger"> <i class="bi bi-x"></i></button>
                            <a href="/planning/schedule_schedule=<?php echo e($voyage->ves_id); ?>" class="btn icon btn-primary"><i class="bi bi-pencil"></i></a>
                            </form>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>

    </section>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('partial.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Fdw Files\CTOS\dev\frontend\tos-dev-local\resources\views/planning/vessel/main.blade.php ENDPATH**/ ?>