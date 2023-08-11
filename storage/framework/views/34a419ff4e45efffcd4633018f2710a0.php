


<?php $__env->startSection('content'); ?>

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>

</div>

<div class="page-content">
  <section class="row">
    <div class="col-12 text-center">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            <?= $title ?>
          </h3>
          <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12">
              <div class="btn-group mb-3" role="group" aria-label="Basic example">
                <a href="/coparn/create" type="button" class="btn btn-success">
                  Upload Coparn Document With File
                </a>
                <a href="/coparn/singlecreate" type="button" class="btn btn-primary">
                  Create Single Coparn Document
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('partial.invoice.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Fdw File Storage 1\CTOS\dev\frontend\tos-dev-local\resources\views/coparn/dashboard.blade.php ENDPATH**/ ?>