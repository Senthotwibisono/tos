@extends ('partial.invoice.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>

</div>
<div class="page-content">
  <section class="row">
    <div class="col-12 text-center">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">
            Export Stuffing Dalam Form Data Management
          </h4>
          <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12">
              <div class="btn-group mb-3" role="group" aria-label="Basic example">
                <a href="/export/stuffing-in/add/step1" type="button" class="btn btn-success">
                  Tambah Form Export Stuffing Dalam
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </section>

  <section class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Export Stuffing Dalam Form Data Table</h4>
          <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
        </div>
        <div class="card-body">
          <div class="row mt-5">
            <div class="col-12">
              <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Customer</th>
                    <th>Booking Number</th>
                    <th>Order Service</th>
                    <th>Expired Date</th>
                    <th>Created At</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $i = 1;
                  foreach ($deliveries as $data) { ?>
                    <?php if ($data->orderService == "stuffingIn") { ?>

                      <tr>
                        <td><?= $i ?></td>
                        <td><?= $data->customer->customer_name ?></td>
                        <td><?= $data->booking_no ?></td>
                        <td><?= $data->orderService ?></td>
                        <td><?= DateFormat($data->exp_date) ?></td>
                        <td><?= DateTimeFormat($data->createdAt) ?></td>
                        <td>
                          <?php if ($data->hasInvoice != null) { ?>
                            <span class="badge bg-success text-white">Has Invoice</span>
                          <?php } else { ?>
                            <span class="badge bg-warning text-white">Draft</span>
                          <?php } ?>
                        </td>
                        <td>
                          <?php if ($data->hasInvoice != null) { ?>
                            <button disabled type="button" class="btn btn-sm btn-success"><i class="fa fa-file"></i></button>
                          <?php } else { ?>
                            <a href="/invoice/add/update_step1?id=<?= $data->id ?>" type="button" class="btn btn-sm btn-info"><i class="fa fa-pencil"></i></a>
                          <?php } ?>
                        </td>
                      </tr>
                    <?php } ?>

                  <?php
                    $i++;
                  } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

@include('invoice.modal.modal')


@endsection