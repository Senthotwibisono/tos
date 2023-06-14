


<?php $__env->startSection('content'); ?>

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
</div>
<div class="page content mb-5">
  <section class="row">
    <div class="card">
      <div class="card-header">
        <!-- <h3>Delivery Form Data</h3> -->
        <h4>Step 2 Data Delivery Review</h4>
        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-4">
            <div class="form-group">
              <label for="">Expired Date</label>
              <input type="text" class="form-control" placeholder="09/05/2023" value="09/05/2023" readonly>
            </div>
          </div>
          <div class="col-2">
            <label for="">Hour</label>
            <input type="text" class="form-control" placeholder="Select Hour" value="01" readonly>
          </div>
          <div class="col-2">
            <label for="">Minute</label>
            <input type="text" class="form-control" placeholder="Select Minute" value="30" readonly>
          </div>
          <div class="col-4">
            <label for="">Customer</label>
            <div class="form-group">
              <input type="text" class="form-control" value="Square" readonly>
            </div>
          </div>
        </div>
        <div class="row mt-5">
          <div class="col-12">
            <h5>Information Shipping Agent</h5>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
          </div>
          <div class="col-12 col-md-4">
            <div class="form-group">
              <label for="">Do Number</label>
              <input type="text" class="form-control" placeholder="Do Number" value="JKTC123123" readonly>
            </div>
          </div>
          <div class="col-12 col-md-4">
            <div class="form-group">
              <label for="">Do Expired</label>
              <input type="text" class="form-control" placeholder="Do Expired" value="20/05/2023" readonly>
            </div>
          </div>
          <div class="col-12 col-md-4">
            <div class="form-group">
              <label for="">Bill of Loading Number</label>
              <input type="text" class="form-control" placeholder="Bill Of Loading Number" value="RICCY767812Q" readonly>
            </div>
          </div>
        </div>
        <div class="row mt-5">
          <div class="col-12">
            <h5>Add Container</h5>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
          </div>
          <div class="col-12">
            <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
              <thead>
                <tr>
                  <th>Container No</th>
                  <th>Size</th>
                  <th>Type</th>
                  <th>Status</th>
                  <th>Gross</th>
                  <th>POD</th>
                  <th>OH</th>
                  <th>OL</th>
                  <th>IMO</th>
                </tr>
              </thead>
              <tbody>
                <?php for ($i = 0; $i < 20; $i++) { ?>
                  <tr>
                    <td>1</td>
                    <td>Vessel 1</td>
                    <td>Customer 1</td>
                    <td>Service 1</td>
                    <td><span class="badge bg-danger text-white">Not Paid</span></td>
                    <td><a type="button" class="btn btn-sm btn-warning text-white"><i class="fa fa-file"></i></a></td>
                    <td><a type="button" class="btn btn-sm btn-primary text-white disabled"><i class="fa fa-file"></i></a></td>
                    <td><a type="button" class="btn btn-sm btn-info text-white"><i class="fa fa-file"></i></a></td>
                    <td><a href="" type="button" class="btn btn-sm btn-success"><i class="fa fa-file"></i></a></td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
        <div class="row mt-5">
          <div class="col-12 text-right">
            <a href="/invoice/add/step3" class="btn btn btn-success">Submit</a>
            <a href="/invoice/add/step1" class="btn btn btn-warning">Edit</a>
            <a href="" class="btn btn btn-secondary">Cancel</a>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('partial.invoice.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Fdw Files\CTOS\dev\frontend\tos-dev-local\resources\views/invoice/add_step_2.blade.php ENDPATH**/ ?>