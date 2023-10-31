@extends ('partial.invoice.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Masukan Data untuk form Extend Delivery</p>
</div>
<div class="page-content mb-5">
  <section class="row">
    <form action="/delivery/form/extend/storeForm" method="POST" id="formSubmit" enctype="multipart/form-data">
      @CSRF
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">
            Choose Delivery Form to Extends it Active To Date
          </h4>
          <div class="card-body">
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label for="">Choose Proforma Number</label>
                  <select name="invoiceID" class="form-control js-example-basic-multiple" id="proformaNumber" required>
                    <option value="" default disabled selected>Pilih Salah Satu</option>
                    <?php foreach ($invoice as $data) { ?>
                      <?php if ($data->isExtended == "0") { ?>
                        <option data-id="<?= $data->id ?>" value="<?= $data->deliveryForm->id ?>"><?= $data->proformaId ?></option>
                      <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label for="">Extended Expired Date</label>
                  <input name="extended_exp_date" type="date" class="form-control flatpickr-range mb-3" placeholder="09/05/2023" required>
                </div>
              </div>
            </div>
            <div class="row">
              <h4 class="card-title">Data Delivery Form</h4>
              <div class="col-4">
                <div class="form-group">
                  <label for="">Proforma Number</label>
                  <input placeholder="Pilih Nomor Proforma Terlebih Dahulu.." class="form-control" type="text" id="proforma" readonly>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="">Customer Name</label>
                  <input placeholder="Pilih Nomor Proforma Terlebih Dahulu.." class="form-control" type="text" id="customer_name" readonly>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="">Customer NPWP</label>
                  <input placeholder="Pilih Nomor Proforma Terlebih Dahulu.." class="form-control" type="text" id="customer_npwp" readonly>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="">Order Service</label>
                  <input placeholder="Pilih Nomor Proforma Terlebih Dahulu.." class="form-control" type="text" id="orderService" readonly>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="">Active To</label>
                  <input placeholder="Pilih Nomor Proforma Terlebih Dahulu.." class="form-control" type="text" id="active_to" readonly>
                </div>
              </div>
            </div>
            <div class="row mt-3">
              <h4 class="card-title">Data Container</h4>
              <div class="col-3">
                <div class="form-group">
                  <label for="">Container Number</label>
                  <input placeholder="Pilih Nomor Proforma Terlebih Dahulu.." type="text" readonly class="form-control" id="containerNo">
                </div>
              </div>
              <div class="col-3">
                <div class="form-group">
                  <label for="">Container Type</label>
                  <input placeholder="Pilih Nomor Proforma Terlebih Dahulu.." type="text" readonly class="form-control" id="containerType">
                </div>
              </div>
              <div class="col-3">
                <div class="form-group">
                  <label for="">Container Size</label>
                  <input placeholder="Pilih Nomor Proforma Terlebih Dahulu.." type="text" readonly class="form-control" id="containerSize">
                </div>
              </div>
              <div class="col-3">
                <div class="form-group">
                  <label for="">Container Status</label>
                  <input placeholder="Pilih Nomor Proforma Terlebih Dahulu.." type="text" readonly class="form-control" id="containerStatus">
                </div>
              </div>
            </div>
            <div class="row mt-5">
              <div class="col-12 text-right">
                <button class="btn btn-success text-white" type="submit"><i class="fa fa-check"></i> Submit</button>
                <a type="button" onclick="canceladdCustomer();" class="btn btn-secondary">Cancel</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </section>
</div>
<!-- update test  -->
@endsection