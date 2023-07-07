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
                <a onclick="createPaymentMethod()" type="button" class="btn btn-success">
                  Tambah Metode Pembayaran
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
          <h4 class="card-title">Billing Invoice Data Table</h4>
          <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
        </div>
        <div class="card-body">
          <div class="row mt-5">
            <div class="col-12">
              <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
                <thead>
                  <tr>
                    <th>Nama Penerima</th>
                    <th>Nama Bank</th>
                    <th>Kode Bank</th>
                    <th>Nomor Rekening</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($payments as $value) { ?>

                    <tr>
                      <td><?= $value->name ?></td>
                      <td><?= $value->bank ?></td>
                      <td><?= $value->bankCode ?></td>
                      <td><?= $value->bankNumber ?></td>
                      <td>
                        <span class="badge <?= $value->isActive == "1" ? "bg-success" : "bg-danger" ?> text-white"><?= $value->isActive == "1" ? "Default" : "non active" ?></span>
                      </td>
                      <td><a type="button" onclick="methodpaymentConfig(`<?= $value->id ?>`)" class="btn btn-sm btn-success"><i class="fa fa-pencil"></i></a></td>
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
<div class="modal fade text-left modal-borderless" id="editModalPayment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Metode Pembayaran</h5>
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
            <label for="">Nama Penerima</label>
            <input type="text" id="name" class="form-control" value="kosong">
          </div>
          <div class="form-group">
            <label for="">Nama bank</label>
            <input type="text" id="bankName" class="form-control" value="kosong">
          </div>
          <div class="form-group">
            <label for="">Kode Bank</label>
            <input type="text" id="bankCode" class="form-control" value="kosong">
          </div>
          <div class="form-group">
            <label for="">Nomor Rekening</label>
            <input type="text" id="bankNumber" class="form-control" value="kosong">
          </div>
          <!-- <div class="form-group">
            <label for="">Status</label>
            <select id="status" class="select form-control">
              <option value="" selected default> Pilih Salah Satu..</option>
              <option value="1">Active</option>
              <option value="2">Non-Active</option>
            </select>
          </div> -->

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
            <i class="bx bx-x d-block d-sm-none"></i>
            <span class="d-none d-sm-block">Cancel</span>
          </button>
          <button id="editSubmitPayment" type="button" class="btn btn-primary ml-1" data-bs-dismiss="modal">
            Submit
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- end of Edit Modal Single Data Table  -->

<!-- Create Modal Single Data Table  -->
<div class="modal fade text-left modal-borderless" id="createModalPayment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Metode Pembayaran</h5>
        <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
          <i data-feather="x"></i>
        </button>
      </div>
      <form action="#">
        <div class="modal-body" style="height:auto;">
          <div class="form-group">
            <label for="">Nama Penerima</label>
            <input type="text" id="paymentname" class="form-control" placeholder="Please Input..">
          </div>
          <div class="form-group">
            <label for="">Nama bank</label>
            <input type="text" id="paymentbankName" class="form-control" placeholder="Please Input..">
          </div>
          <div class="form-group">
            <label for="">Kode Bank</label>
            <input type="text" id="paymentbankCode" class="form-control" placeholder="Please Input..">
          </div>
          <div class="form-group">
            <label for="">Nomor Rekening</label>
            <input type="text" id="paymentbankNumber" class="form-control" placeholder="Please Input..">
          </div>
          <!-- <div class="form-group">
            <label for="">Status</label>
            <select id="status" class="select form-control">
              <option value="" selected default> Pilih Salah Satu..</option>
              <option value="1">Active</option>
              <option value="2">Non-Active</option>
            </select>
          </div> -->

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
            <i class="bx bx-x d-block d-sm-none"></i>
            <span class="d-none d-sm-block">Cancel</span>
          </button>
          <button id="createSubmitPayment" type="button" class="btn btn-primary ml-1" data-bs-dismiss="modal">
            Submit
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- end of Create Modal Single Data Table  -->

@endsection