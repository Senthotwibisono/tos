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
        <h4>Step 1 Tambah Data Delivery</h4>
        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-4">
            <div class="form-group">
              <label for="">Expired Date</label> <input type="date" class="form-control flatpickr-range mb-3" placeholder="09/05/2023" id="expired">
            </div>
          </div>
          <div class="col-4">
            <label for="">Expired Time</label> <input type="text" class="form-control flatpickr-range mb-3" placeholder="12.00 PM" id="hour">

          </div>
          <div class="col-4">
            <label for="">Customer</label>
            <div class="form-group">
              <select class="js-example-basic-single form-control">
                <option selected default value="">Pilih Salah Satu</option>
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
          <div class="col-12 col-md-4">
            <div class="form-group">
              <label for="">Do Number</label>
              <input required type="text" class="form-control" placeholder="Do Number">
            </div>
          </div>
          <div class="col-12 col-md-4">
            <div class="form-group">
              <label for="">Do Expired</label>
              <input required type="date" class="form-control flatpickr-range mb-3" placeholder="09/05/2023" id="doexpired">
            </div>
          </div>
          <div class="col-12 col-md-4">
            <div class="form-group">
              <label for="">Bill of Loading Number</label>
              <input required type="text" class="form-control" placeholder="Bill Of Loading Number">
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
            <select class="js-example-basic-multiple form-control" multiple="multiple">
              <option selected default value="">Pilih Salah Satu</option>
              <?php foreach ($customer as $data) { ?>
                <option value="<?= $data->id ?>"><?= $data->customer_name ?></option>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="row mt-5">
          <div class="col-12 text-right">
            <a href="/invoice/add/step2" class="btn btn-success">Submit</a>
            <a href="" class="btn btn-secondary">Cancel</a>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<!-- update test  -->
@endsection