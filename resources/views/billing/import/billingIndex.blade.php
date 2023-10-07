@extends ('partial.invoice.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Management Data Billing Delivery</p>

</div>
<div class="page-content">
  <!-- <section class="row">
    <div class="col-12 text-center">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">
            Data Management
          </h4>
          <p>Pilihan Pembayaran untuk Delivery</p>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12">
              <div class="btn-group mb-3" role="group" aria-label="Basic example">
                <a href="/invoice/delivery" type="button" class="btn btn-success">
                  Delivery Form
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section> -->

  <section class="row">
    <div class="col-12 mb-3">
      <a href="/delivery/form" type="button" class="btn btn-primary">
        <i class="fa fa-folder"></i>
        Delivery Form
      </a>
    </div>
  </section>

  <?php
  $menuArr = [
    "SP2 Kapal Sandar icon (MT Balik IKS)",
    "SP2 Kapal Sandar icon (MKB)",
    "SP2 Kapal Sandar icon (MT Balik Pelindo)",
    "SPPS",
    "SPPS (Relokasi Pelindo - ICON)",
    "SP2 (Relokasi Pelindo - ICON)",
  ];
  $orderServiceArr = [
    "sp2iks",
    "sp2mkb",
    "sp2pelindo",
    "spps",
    "sppsrelokasipelindo",
    "sp2icon",
  ];

  ?>
  <?php
  $i = 1;
  $k = 0;
  foreach ($menuArr as $menu) { ?>
    <section class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Tabel Data Billing Receiving <?= $menu ?></h4>
            <p>Rekap Data Billing</p>
          </div>
          <div class="card-body">
            <form action="/invoice/export" method="POST" enctype="multipart/form-data">
              @CSRF
              <div class="row">

                <div class="col-4">
                  <div class="form-group">
                    <label>Pick Start Date Range</label>
                    <input name="start" type="date" class="form-control flatpickr-range mb-1" placeholder="09/05/2023" id="expired">
                  </div>
                </div>
                <div class="col-4">
                  <div class="form-group">
                    <label>Pick End Date Range</label>
                    <input name="end" type="date" class="form-control flatpickr-range mb-1" placeholder="09/05/2023" id="expired">
                  </div>
                </div>
                <div class="col-4 mt-4">
                  <button class="btn btn-primary" type="submit"><i class=" fa fa-file"></i> Export Active Invoice to Excel</button>
                </div>
              </div>
            </form>

            <div class="row">

              <div class="col-12">
                <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table<?= $i ?>">
                  <thead>
                    <tr>
                      <th>Proforma No</th>
                      <th>Customer</th>
                      <th>Container No</th>
                      <th>Order Service</th>
                      <th>Tipe Invoice</th>
                      <th>Dibuat Pada</th>
                      <th>Status</th>
                      <th>Piutang</th>
                      <th>Pranota</th>
                      <th>Invoice</th>
                      <th>Job</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($invoices as $value) { ?>
                      <?php if ($value->orderService == $orderServiceArr[$k]) { ?>
                        <tr>
                          <td><?= $value->proformaId ?></td>
                          <!-- <td>Vessel Name</td> -->
                          <td><?= $value->deliveryForm->customer->customer_name ?></td>
                          <td><?= $value->containerDetail->{'Container Number'} ?></td>
                          <td><?= $value->orderService ?></td>
                          <td><?= $value->billingName ?></td>
                          <!-- <td>Service Name</td> -->
                          <td><?= DateTimeFormat($value->createdAt) ?></td>
                          <td>
                            <?php if ($value->isPaid == 0) { ?>
                              <span class="badge bg-danger text-white">Not Paid</span>
                            <?php } else { ?>
                              <span class="badge bg-success text-white">Paid</span>
                            <?php } ?>
                          </td>
                          <td>
                            <?php if ($value->isPiutang == 0) { ?>
                              <span class="badge bg-danger text-white">Not Piutang</span>
                            <?php } else { ?>
                              <span class="badge bg-warning text-white">Piutang</span>
                            <?php } ?>
                          </td>
                          <td>
                            <a type="button" href="/delivery/billing/pranota?id=<?= $value->id ?>" target="_blank" class="btn btn-sm btn-warning text-white"><i class="fa fa-file"></i></a>
                          </td>
                          <td>
                            <?php if ($value->isPiutang == 1 && $value->isPaid == 1) { ?>
                              <a type="button" href="/delivery/billing/invoice?id=<?= $value->id ?>" target="_blank" class="btn btn-sm btn-primary text-white"><i class="fa fa-dollar"></i></a>
                            <?php } else if ($value->isPiutang == 1 && $value->isPaid == 0) { ?>
                              <a type="button" href="/delivery/billing/invoice?id=<?= $value->id ?>" target="_blank" class="btn btn-sm btn-primary text-white"><i class="fa fa-dollar"></i></a>
                            <?php } else if ($value->isPiutang == 0 && $value->isPaid == 1) { ?>
                              <a type="button" href="/delivery/billing/invoice?id=<?= $value->id ?>" target="_blank" class="btn btn-sm btn-primary text-white"><i class="fa fa-dollar"></i></a>
                            <?php } else if ($value->isPiutang == 0 && $value->isPaid == 0) { ?>
                              <a type="button" class="btn btn-sm btn-primary text-white disabled"><i class="fa fa-dollar"></i></a>
                            <?php } ?>
                          </td>

                          <td>
                            <?php if ($value->isPiutang == 1 && $value->isPaid == 1) { ?>
                              <a type="button" href="/delivery/billing/job?id=<?= $value->id ?>" target="_blank" class="btn btn-sm btn-info text-white"><i class="fa fa-ship"></i></a>
                            <?php } else if ($value->isPiutang == 1 && $value->isPaid == 0) { ?>
                              <a type="button" href="/delivery/billing/job?id=<?= $value->id ?>" target="_blank" class="btn btn-sm btn-info text-white"><i class="fa fa-ship"></i></a>
                            <?php } else if ($value->isPiutang == 0 && $value->isPaid == 1) { ?>
                              <a type="button" href="/delivery/billing/job?id=<?= $value->id ?>" target="_blank" class="btn btn-sm btn-info text-white"><i class="fa fa-ship"></i></a>
                            <?php } else if ($value->isPiutang == 0 && $value->isPaid == 0) { ?>
                              <a type="button" class="btn btn-sm btn-info text-white disabled"><i class="fa fa-ship"></i></a>
                            <?php } ?>

                          </td>
                          <td><a type="button" onclick="paidConfigv2(`<?= $value->id ?>`)" class="btn btn-sm btn-success"><i class="fa fa-cogs"></i></a></td>

                        </tr>
                      <?php } ?>

                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  <?php
    $i++;
    $k++;
  } ?>




</div>

<!-- Edit Modal Single Data Table  -->
<div class="modal fade text-left modal-borderless" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Verify Payment</h5>
        <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
          <i data-feather="x"></i>
        </button>
      </div>
      <form action="#">
        <div class="modal-body" style="height:auto;">
          <div class="form-group">
            <label>Id</label>
            <input type="text" id="input_id" disabled value="kosong" class="form-control">
          </div>
          <div class="form-group">
            <label for="">Customer</label>
            <input type="text" id="customer" class="form-control" disabled value="kosong">
          </div>
          <div class="form-group">
            <label>Status Pembayaran</label>
            <p>
              <span id="isPaid" class="badge text-white"></span>
            </p>
          </div>
          <div class="form-group">
            <label>Status Piutang</label>
            <p>
              <span id="isPiutang" class="badge text-white"></span>
            </p>
          </div>


        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
            <i class="bx bx-x d-block d-sm-none"></i>
            <span class="d-none d-sm-block">Cancel</span>
          </button>
          <button id="verifyPayment" type="button" class="btn btn-primary ml-1" data-bs-dismiss="modal">
            Verify This Payment
          </button>
          <button id="verifyPiutang" type="button" class="btn btn-warning ml-1" data-bs-dismiss="modal">
            Piutang This Invoices
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- end of Edit Modal Single Data Table  -->




@endsection