<?php $__env->startSection('content'); ?>

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>

</div>
<div class="page-content">

  <section class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <form id="customer_form" action="/invoice/container/store" method="POST" enctype="multipart/form-data">
            <!-- <form id="customer_form" action="<?php echo e(route('customer.store')); ?>" method="post" enctype="multipart/form-data"> -->
            <?php echo csrf_field(); ?>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="">Container Name</label>
                  <input value="" class="form-control" type="text" name="container_name" placeholder="MV. Baltic Strait" required>
                </div>
                <div class="form-group">
                  <label for="">Container No</label>
                  <input value="" class="form-control" type="text" name="container_no" placeholder="AAGP3345" required>
                </div>
                <div class="form-group">
                  <label for="">CTR Status</label>
                  <input value="" class="form-control" type="text" name="ctr_status" placeholder="FCL" required>
                </div>
                <div class="form-group">
                  <label for="">CTR Intern Status</label>
                  <select name="ctr_intern_status" class="form-control" id="" required>
                    <option selected disabled default value="">Pilih Salah Satu</option>
                    <option value="01">01</option>
                    <option value="02">02</option>
                    <option value="03">03</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="">Size</label>
                  <input value="" class="form-control" type="text" name="size" placeholder="22">
                </div>
                <div class="form-group">
                  <label for="">type</label>
                  <input value="" class="form-control" type="text" name="type" placeholder="mtp">
                </div>
                <div class="form-group">
                  <label for="">Gross</label>
                  <input value="" class="form-control" type="text" name="gross" placeholder="3000.00">
                </div>


              </div>
            </div>
            <div class="row mt-5">
              <div class="col-12 text-right">
                <button type="submit" class="btn btn-success">Submit</button>
                <a type="button" onclick="canceladdCustomer();" class="btn btn-secondary">Cancel</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</div>

<?php echo $__env->make('invoice.modal.modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('partial.invoice.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Fdw Files\CTOS\dev\frontend\tos-dev-local\resources\views/invoice/container/containeradd.blade.php ENDPATH**/ ?>