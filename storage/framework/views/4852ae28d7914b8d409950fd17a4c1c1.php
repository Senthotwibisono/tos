<?php $__env->startSection('content'); ?>

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
</div>
<div class="page content mb-5">
  <section class="row">
    <form action="/invoice/add/extend/storestep1" method="POST" enctype="multipart/form-data">
      <?php echo csrf_field(); ?>
      <div class="card">
        <div class="card-header">
          <!-- <h3>Delivery Form Data</h3> -->
          <h4>Step 1 Pilih Delivery Form Untuk Extend</h4>
          <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label for="">Proforma Number</label>
                <select name="form" class="js-example-basic-single form-control">
                  <option value="" selected default disabled>Pilih Salah Satu..</option>
                  <?php foreach ($invoices as $invoice) { ?>
                    <?php if ($invoice->orderService == "sp2" && $invoice->isExtended != "1") { ?>
                      <?php if ($invoice->isPaid == "1" || $invoice->isPiutang == "1") { ?>
                        <option value="<?= $invoice->data6->deliveryid ?>"><?= $invoice->performaId ?></option>
                      <?php } ?>
                    <?php } ?>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label for="">Extended Expired Date</label>
                <input name="extended_exp_date" type="date" class="form-control flatpickr-range mb-3" placeholder="09/05/2023" id="expired">
              </div>
            </div>
          </div>
          <div class="row mt-5">
            <div class="col-12 text-right">
              <button type="submit" class="btn btn-success">Submit</button>
              <a type="button" onclick="canceladdCustomer();" class="btn btn-secondary">Cancel</a>
            </div>
          </div>
        </div>
      </div>
    </form>
  </section>
</div>
<!-- update test  -->
<?php $__env->stopSection(); ?>
<?php echo $__env->make('partial.invoice.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Fdw File Storage 1\CTOS\dev\frontend\tos-dev-local\resources\views/invoice/delivery_form/extend/add_step_1.blade.php ENDPATH**/ ?>