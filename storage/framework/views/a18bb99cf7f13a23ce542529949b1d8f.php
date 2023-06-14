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
          <form id="customer_form" action="/invoice/customer/store" method="POST" enctype="multipart/form-data">
            <!-- <form id="customer_form" action="<?php echo e(route('customer.store')); ?>" method="post" enctype="multipart/form-data"> -->
            <?php echo csrf_field(); ?>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="">Customer Name</label>
                  <input name="cust_name" type="text" class="form-control" placeholder="Customer Name" required>
                </div>
                <div class="form-group">
                  <label for="">Customer Code</label>
                  <input name="cust_code" type="text" class="form-control" placeholder="Customer Code" required>
                </div>
                <div class="form-group">
                  <label for="">Phone Number</label>
                  <input name="cust_phone" type="text" class="form-control" name="telphone" placeholder="0878377728" required />
                </div>
                <div class="form-group">
                  <label for="">Fax Code</label>
                  <input name="cust_fax" type="text" class="form-control" placeholder="13610" required>
                </div>
                <div class="form-group">
                  <label for="">NPWP</label>
                  <input name="cust_npwp" type="text" class="form-control" placeholder="6673009219991" required>
                </div>
                <div class="form-group">
                  <label for="">Address</label>
                  <textarea required name="cust_address" class="form-control" placeholder="Enter the address here" id="" cols="10" rows="3"></textarea>
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
<?php echo $__env->make('partial.invoice.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\CTOS\Dev\Frontend\tos-dev-local\resources\views/invoice/customer/customeradd.blade.php ENDPATH**/ ?>