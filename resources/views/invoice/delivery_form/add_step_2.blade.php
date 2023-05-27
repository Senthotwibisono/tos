@extends ('partial.invoice.main')


@section('content')

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
              <input type="text" class="form-control" placeholder="09/05/2023" value="<?= $singleform->exp_date ?>" readonly>
            </div>
          </div>
          <div class="col-2">
            <label for="">Hour</label>
            <input type="text" class="form-control" placeholder="Select Hour" value="<?= $singleform->time ?>" readonly>
          </div>
          <div class="col-4">
            <label for="">Customer</label>
            <div class="form-group">
              <input type="text" class="form-control" value="<?= $singleform->customer->customer_name  ?>" readonly>
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
              <input type="text" class="form-control" placeholder="Do Number" value="<?= $singleform->do_number ?>" readonly>
            </div>
          </div>
          <div class="col-12 col-md-4">
            <div class="form-group">
              <label for="">Do Expired</label>
              <input type="text" class="form-control" placeholder="Do Expired" value="<?= $singleform->do_exp_date ?>" readonly>
            </div>
          </div>
          <div class="col-12 col-md-4">
            <div class="form-group">
              <label for="">Bill of Loading Number</label>
              <input type="text" class="form-control" placeholder="Bill Of Loading Number" value="<?= $singleform->boln ?>" readonly>
            </div>
          </div>
        </div>
        <div class="row mt-5">
          <div class="col-12">
            <h5>Selected Container</h5>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
          </div>
          <div class="col-12">
            <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
              <thead>
                <tr>
                  <th>Container No</th>
                  <th>Container Name</th>
                  <th>Size</th>
                  <th>Type</th>
                  <th>CTR Status</th>
                  <th>CTR Intern Status</th>
                  <th>Gross</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($singleform->containers as $data) { ?>
                  <tr>

                    <td><?= $data->container_no ?></td>
                    <td><?= $data->container_name ?></td>
                    <td><?= $data->size ?></td>
                    <td><?= $data->type ?></td>
                    <td><?= $data->ctr_status ?></td>
                    <td><?= $data->ctr_intern_status ?></td>
                    <td><?= $data->gross ?></td>
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

@endsection