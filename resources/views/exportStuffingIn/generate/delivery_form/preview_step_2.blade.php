@extends ('partial.invoice.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
</div>
<div class="page content mb-5">
  <section class="row">
    <form action="/export/stuffing-in/add/storestep2" method="POST" enctype="multipart/form-data">
      @CSRF

      <div class="card">
        <div class="card-header">
          <!-- <h3>Delivery Form Data</h3> -->
          <h4>Step 2 Data Receiving Pranota</h4>
          <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-4">
              <div class="form-group">
                <label for="">Expired Date</label>
                <input type="text" class="form-control" placeholder="09/05/2023" value="<?= $ccdelivery->deliveryForm->exp_date ?>" readonly>
              </div>
            </div>
            <div class="col-4">
              <label for="">Customer</label>
              <div class="form-group">
                <input type="text" class="form-control" value="<?= $ccdelivery->deliveryForm->customer->customer_name ?>" readonly>
              </div>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-12">
              <h5>Information Shipping Agent</h5>
              <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
            </div>
            <div class="col-12 col-md-4">
              <div class="form-group">
                <label for="">Do Expired</label>
                <input type="text" class="form-control" placeholder="Do Expired" value="<?= $ccdelivery->deliveryForm->do_exp_date ?>" readonly>
              </div>
            </div>
            <div class="col-12 col-md-4">
              <div class="form-group">
                <label for="">RO Number</label>
                <input type="text" class="form-control" placeholder="Bill Of Loading Number" value="<?= $ccdelivery->deliveryForm->roNumber ?>" readonly>
              </div>
            </div>
            <div class="col-12 col-md-4">
              <div class="form-group">
                <label>Order Service</label>
                <input type="text" class="form-control" value="<?= $ccdelivery->deliveryForm->orderService ?>" readonly>
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
                    <th>Vessel Name</th>
                    <th>Size</th>
                    <th>Type</th>
                    <th>CTR Status</th>
                    <th>CTR Intern Status</th>
                    <th>Gross</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($ccdelivery->deliveryForm->containers as $data) { ?>
                    <tr>
                      <td><?= $data->container_no ?></td>
                      <td><?= $data->vessel_name ?></td>
                      <td><?= $data->ctr_size ?></td>
                      <td><?= $data->ctr_type ?></td>
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
              <a href="/export/stuffing-in" type="button" class="btn btn-info text-white">Kembali</a>
            </div>
          </div>
        </div>
      </div>
    </form>

  </section>
</div>

@endsection