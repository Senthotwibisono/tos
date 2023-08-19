<?php $__env->startSection('content'); ?>

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
</div>

<div class="page-content">

  <section class="row">
    <form action="/do/store" method="POST" enctype="multipart/form-data">
      <?php echo csrf_field(); ?>
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Import Do Online</h3>
            <p>Please Upload your file by drag n drop or click the area</p>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-6">
                <input id="storedo" type="file" name="storedo" class="dropify" data-height="270" data-max-file-size="3M" data-allowed-file-extensions="xls" />
              </div>
            </div>
            <div class="row mt-3">
              <div class="col-2">
                <button type="submit" class="btn btn-primary text-white">Submit</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </section>

</div>

<?php $__env->stopSection(); ?>

<script>
  console.log("masuk");
</script>
<?php echo $__env->make('partial.invoice.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\tos\resources\views/do/create.blade.php ENDPATH**/ ?>