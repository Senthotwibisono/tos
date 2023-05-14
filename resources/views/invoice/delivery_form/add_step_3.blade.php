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
        <h4>Step 3 Data Delivery Pranota</h4>
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
        <div class="row mt-3">
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

        <div class="row mt-3">
          <div class="col-12">
            <h5>Add Container</h5>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
          </div>
          <div class="col-12">
            <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
              <thead>
                <tr>
                  <th>Journal Code</th>
                  <th>Description</th>
                  <th>Size</th>
                  <th>Type</th>
                  <th>Status</th>
                  <th>Boxes</th>
                  <th>Shift</th>
                  <th>DG</th>
                  <th>Rate</th>
                  <th>Amount</th>
                </tr>
              </thead>
              <tbody>
                <?php for ($i = 1; $i < 20; $i++) { ?>
                  <tr>
                    <td><?= $i ?></td>
                    <td>Data</td>
                    <td>Data</td>
                    <td>Data</td>
                    <td>Data</td>
                    <td>Data</td>
                    <td>Data</td>
                    <td>Data</td>
                    <td>Data</td>
                    <td>Data</td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>

        <div class="row mt-3">
          <style>
          </style>
          <div class="col-12">
            <div class="card" style="border-radius:15px !important;background-color:#435ebe !important;">
              <div class="card-body">
                <div class="row text-white p-3">
                  <div class="col-6">
                    <h4 class="text-white bold">TOTAL AMOUNT</h4>
                    <h5 class="text-white">Grand Total :</h5>
                    <h5 class="text-white">Tax 10% :</h5>
                    <h5 class="text-white">Administration :</h5>
                  </div>
                  <div class="col-6" style="text-align:right;">
                    <h4 class="text-white bold">INVOICE AMOUNT</h4>
                    <h4 class="bold" style="color:#f64e60;">IDR 50.000.000,00 ~</h4>
                    <h5 class="text-white">Taxes Included</h5>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row mt-5">
          <div class="col-12 text-right">
            <a href="/invoice" class="btn btn btn-success">Submit</a>
            <a href="/invoice/add/step1" class="btn btn btn-warning">Edit</a>
            <a href="/invoice" class="btn btn btn-secondary">Cancel</a>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

@endsection