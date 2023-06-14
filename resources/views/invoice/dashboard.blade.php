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
            Data Management
          </h4>
          <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12">
              <div class="btn-group mb-3" role="group" aria-label="Basic example">
                <a href="/invoice/delivery" type="button" class="btn btn-success">
                  Data Delivery
                </a>
                <a href=""></a>
                <a href="/invoice/container" type="button" class="btn btn-primary">
                  Data Container
                </a>
                <a href="/invoice/customer" type="button" class="btn btn-info">
                  Data Customer
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
          <h4 class="card-title">Invoice Data Table</h4>
          <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
        </div>
        <div class="card-body">
          <!-- <div class="row">
            <div class="col-12 col-md-4">
              <div class="form-group">
                <label for="">Performa No</label>
                <input type="text" class="form-control" placeholder="Performa No">
              </div>
            </div>
            <div class="col-12 col-md-4">
              <div class="form-group">
                <label for=""> From Date</label>
                <input type="text" class="form-control" placeholder="Start">
              </div>
            </div>
            <div class="col-12 col-md-3">
              <div class="form-group">
                <label for="">To Date</label>
                <input type="text" class="form-control" placeholder="To">
              </div>
            </div>
            <div class="col m-auto">
              <div class="align-items-center mt-2">
                <a href="" class="btn block btn-primary"><i class="fa fa-search"></i></a>
              </div>
            </div>

          </div> -->
          <div class="row mt-5">
            <div class="col-12">
              <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
                <thead>
                  <tr>
                    <th>Performa No</th>
                    <!-- <th>Vessel</th> -->
                    <th>Customer</th>
                    <!-- <th>Service</th> -->
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
                    <tr>
                      <td><?= $value->performaId ?></td>
                      <!-- <td>Vessel Name</td> -->
                      <td><?= $value->data6->customer ?></td>
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
                        <a type="button" href="/invoice/pranota?id=<?= $value->id ?>" target="_blank" class="btn btn-sm btn-warning text-white"><i class="fa fa-file"></i></a>
                      </td>
                      <td>
                        <?php if ($value->isPiutang == 1 && $value->isPaid == 1) { ?>
                          <a type="button" href="/invoice/paidinvoice?id=<?= $value->id ?>" target="_blank" class="btn btn-sm btn-primary text-white"><i class="fa fa-file"></i></a>
                        <?php } else if ($value->isPiutang == 1 && $value->isPaid == 0) { ?>
                          <a type="button" href="/invoice/paidinvoice?id=<?= $value->id ?>" target="_blank" class="btn btn-sm btn-primary text-white"><i class="fa fa-file"></i></a>
                        <?php } else if ($value->isPiutang == 0 && $value->isPaid == 1) { ?>
                          <a type="button" href="/invoice/paidinvoice?id=<?= $value->id ?>" target="_blank" class="btn btn-sm btn-primary text-white"><i class="fa fa-file"></i></a>
                        <?php } else if ($value->isPiutang == 0 && $value->isPaid == 0) { ?>
                          <a type="button" class="btn btn-sm btn-primary text-white disabled"><i class="fa fa-file"></i></a>
                        <?php } ?>
                      </td>

                      <td>
                        <?php if ($value->isPiutang == 1 && $value->isPaid == 1) { ?>
                          <a type="button" href="/invoice/job?id=<?= $value->id ?>" target="_blank" class="btn btn-sm btn-info text-white"><i class="fa fa-file"></i></a>
                        <?php } else if ($value->isPiutang == 1 && $value->isPaid == 0) { ?>
                          <a type="button" href="/invoice/job?id=<?= $value->id ?>" target="_blank" class="btn btn-sm btn-info text-white"><i class="fa fa-file"></i></a>
                        <?php } else if ($value->isPiutang == 0 && $value->isPaid == 1) { ?>
                          <a type="button" href="/invoice/job?id=<?= $value->id ?>" target="_blank" class="btn btn-sm btn-info text-white"><i class="fa fa-file"></i></a>
                        <?php } else if ($value->isPiutang == 0 && $value->isPaid == 0) { ?>
                          <a type="button" class="btn btn-sm btn-info text-white disabled"><i class="fa fa-file"></i></a>
                        <?php } ?>

                      </td>
                      <td><a type="button" onclick="paidConfig(`<?= $value->id ?>`)" class="btn btn-sm btn-success"><i class="fa fa-pencil"></i></a></td>

                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
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
            Piutang This Invoice
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- end of Edit Modal Single Data Table  -->

@endsection