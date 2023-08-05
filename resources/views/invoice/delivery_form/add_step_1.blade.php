@extends ('partial.invoice.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
</div>
<div class="page-content mb-5">
  <section class="row">
    <form action="/invoice/add/storestep1" method="POST" enctype="multipart/form-data">
      @CSRF
      <div class="card">
        <div class="card-header">
          <!-- <h3>Delivery Form Data</h3> -->
          <h4>Step 1 Tambah Data Delivery</h4>
          <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-4">
              <div class="form-group">
                <label for="">Expired Date</label>
                <input name="exp_date" type="date" class="form-control flatpickr-range mb-3" placeholder="09/05/2023" id="expired">
              </div>
            </div>
            <div class="col-4">
              <label for="">Expired Time</label>
              <input name="exp_time" type="text" class="form-control flatpickr-range mb-3" placeholder="12.00 PM" id="hour">

            </div>
            <div class="col-4">
              <label for="">Customer</label>
              <div class="form-group">
                <select name="customer" class="js-example-basic-single form-control">
                  <option selected disabled default value="">Pilih Salah Satu</option>
                  <?php foreach ($customer as $data) { ?>
                    <option value="<?= $data->id ?>"><?= $data->customer_name ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
          </div>
          <div class="row mt-5">
            <div class="col-12">
              <h5>Information Shipping Agent</h5>
              <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
            </div>
            <div class="col-12">
              <div class="row">
                <div class="col-6">
                  <div class="btn-group mb-3">
                    <a id="manual" class="btn btn-primary" type="button">Manual Do Checking</a>
                  </div>
                  <div class="btn-group mb-3">
                    <a id="auto" class="btn btn-info ml-3" type="button">Automatic Do Checking</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-md-4" style="display: none !important;" id="do_manual">
              <div class="form-group">
                <label for="">Do Number</label>
                <input name="do_number" type="text" class="form-control" placeholder="Do Number">
              </div>
            </div>
            <div class="col-12 col-md-4" id="do_auto">
              <div class="form-group">
                <label for="">Do Number Auto</label>
                <!-- <div class="input-group mb-3">
                  <input name="do_number" type="text" class="form-control" placeholder="Do Number">
                  <button class="btn btn-primary"><i class="fa fa-search"></i></button>
                </div> -->
                <select name="do_number_auto" id="do_number_auto" class="js-example-basic-multiple form-control" style="height: 150%;">
                  <option value="" disabled selected>Pilih Salah Satu</option>
                  <?php foreach ($do as $data) { ?>
                    <option value="<?= $data->do_no ?>" data-id="<?= $data->do_no ?>"><?= $data->do_no ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-12 col-md-4">
              <div class="form-group">
                <label for="">Do Expired</label>
                <input name="do_exp_date" required type="date" class="form-control flatpickr-range mb-3" placeholder="09/05/2023" id="doexpired">
              </div>
            </div>
            <div class="col-12 col-md-4">
              <div class="form-group">
                <label for="">Bill of Loading Number</label>
                <input name="boln" required type="text" class="form-control" placeholder="Bill Of Loading Number">
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label>Order Service</label>
                <select name="order_service" class="form-select" required id="basicSelect">
                  <option value="" default disabled selected>Pilih Salah Satu..</option>
                  <option value="sp2">SP2</option>
                  <option value="spps">SPPS</option>
                </select>
              </div>
            </div>
          </div>
          <div class="row mt-5">
            <div class="col-12">
              <h5>Add Container</h5>
              <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
            </div>
            <div class="col-12">
              <label for="">Container Number</label>
              <select name="container[]" id="containerSelector" class="js-example-basic-multiple form-control" style="height: 150%;" multiple="multiple">
                <option disabled value="">Pilih Salah Satu</option>
                <?php foreach ($container as $data) { ?>
                  <?php if ($data->ctr_intern_status == "03") { ?>
                    <option value="<?= $data->id ?>"><?= $data->container_no ?></option>
                  <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="row mt-5">
            <div class="col-12">
              <h5>Beacukai Information</h5>
              <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
            </div>
            <div class="col-12 col-md-6">
              <div class="form-group">
                <label class="mb-2" for="">Document Number <span class="badge bg-warning">Maximum 6 Characters </span></label>
                <div class="input-group mb-3">
                  <input type="text" class="form-control" name="documentNumber" placeholder="" aria-label="Example text with button addon" aria-describedby="button-addon1">
                  <button class="btn btn-primary" type="button" id="button-addon1"><i class="fa fa-magnifying-glass"></i> Check</button>
                </div>
              </div>
            </div>
            <div class="col-12 col-md-3">
              <div class="form-group">
                <label for="">Document Type</label>
                <input class="form-control" type="text" name="documentType">
              </div>
            </div>
            <div class="col-12 col-md-3">
              <div class="form-group">
                <label for="">Document Date</label>
                <input class="form-control" type="text" name="documentDate">
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
@endsection