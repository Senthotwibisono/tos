@extends ('partial.invoice.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
</div>
<div class="page-content mb-5">
  <section class="row">
    <form action="/export/stuffing-in/generate/storestep1" id="formSubmit" method="POST" enctype="multipart/form-data">
      @CSRF
      <!-- <input type="hidden" name="order_service" value="stuffingIn"> -->
      <div class="card">
        <div class="card-header">
          <!-- <h3>Delivery Form Data</h3> -->
          <h4>Step 1 Tambah Data Export Stuffing Dalam</h4>
          <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-4">
              <label for="">Customer</label>
              <div class="form-group">
                <select required name="customer" id="customer" class="js-example-basic-single form-control">
                  <option selected disabled default value="">Pilih Salah Satu</option>
                  <?php foreach ($customer as $data) { ?>
                    <option value="<?= $data->id ?>" data-id="<?= $data->id ?>"><?= $data->customer_name ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-4">
              <div class="form-group">
                <label for="">NPWP</label>
                <input required type="text" class="form-control" id="npwp" name="npwp" placeholder="Npwp">
              </div>
            </div>
            <div class="col-4">
              <div class="form-group">
                <label for="">Address</label>
                <input required type="text" class="form-control" id="address" name="address" placeholder="address">
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
                    <div class="form-group">
                      <label for="">RO Number</label>
                      <input readonly id="roNumberSingle" type="text" value="<?= $container[0]->roNumber ?>" name="roNumber" class="form-control" required>
                    </div>
                  </div>
                  <!-- <div class="col-2">
                    <div class="form-group">
                      <label for="">Ctr Count</label>
                      <input name="ctr" type="text" id="ctr" class="form-control" required placeholder="Ctr Count">
                    </div>
                  </div>
                  <div class="col-2">
                    <div class="form-group">
                      <label for="">Pod</label>
                      <input name="pod" type="text" id="pod" class="form-control" required placeholder="pod">
                    </div>
                  </div>
                  <div class="col-2">
                    <div class="form-group">
                      <label for="">Fpod</label>
                      <input name="fpod" type="text" id="fpod" class="form-control" required placeholder="fpod">
                    </div>
                  </div> -->
                </div>
              </div>
              <div class="col-2 mt-2">
                <div class="form-group">
                  <label for="">Vessel</label>
                  <input readonly value="<?= $vessel->ves_name ?>" type="text" id="vesselBN" name="vessel" class="form-control" placeholder="Vessel" required>
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label>Voyage</label>
                  <input readonly value="<?= $vessel->voy_out ?>" required type="text" id="voyage" name="voyage" class="form-control" placeholder="Voyage..">
                </div>
              </div>

              <div class="col-2">
                <div class="form-group">
                  <label>Vessel Code</label>
                  <input readonly value="<?= $vessel->ves_code ?>" required type="text" id="vesselcode" name="vesselcode" class="form-control" placeholder="Vessel Code..">
                </div>
              </div>

              <div class="col-2">
                <div class="form-group">
                  <label>Closing Time</label>
                  <input readonly value="<?= DateOnly($vessel->clossing_date) ?>" required name="closingtime" id="closing" type="text" class="form-control flatpickr-range mb-3" placeholder="09/05/2023" id="closingtime">
                </div>
              </div>

              <input type="hidden" name="exp_time" value="12:00">

              <div class="col-2">
                <div class="form-group">
                  <label>Arrival Date</label>
                  <input required readonly value="<?= DateOnly($vessel->arrival_date) ?>" name="arrival" id="arrival" type="text" class="form-control flatpickr-range mb-3" placeholder="09/05/2023" id="arrival">
                </div>
              </div>

              <div class="col-2">
                <div class="form-group">
                  <label>Departure Date</label>
                  <input required readonly value="<?= DateOnly($vessel->deparature_date) ?>" name="departure" id="departure" type="text" class="form-control flatpickr-range mb-3" placeholder="09/05/2023" id="departure">
                </div>
              </div>
            </div>

            <!-- <div class="col-12 col-md-4">
              <div class="form-group">
                <label for="">Do Expired</label>
                <input name="do_exp_date" id="do_exp_date" required type="date" class="form-control flatpickr-range mb-3" placeholder="09/05/2023" id="doexpired">
              </div>
            </div>
            <div class="col-12 col-md-4">
              <div class="form-group">
                <label for="">Bill of Loading Number</label>
                <input name="boln" id="boln" required type="text" class="form-control" placeholder="Bill Of Loading Number">
              </div>
            </div> -->
          </div>
          <div class="row mt-5">
            <div class="col-12">
              <h5>Add Container</h5>
              <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
            </div>
            <div class="col-12" id="selector">
              <label for="">Container Number</label>
              <select name="container[]" id="containerSelector" class="js-example-basic-multiple form-control" style="height: 150%;" multiple="multiple">
                <option disabled value="">Pilih Salah Satu RO Number Dahulu!</option>
                <?php foreach ($container as $data) { ?>
                  <option selected value="<?= $data->id ?>"><?= $data->container_no ?></option>

                <?php } ?>
              </select>

            </div>
            <div class="col-12" id="selectorView" style="display: none !important;">
              <select name="" id="containerSelectorView" disabled class="js-example-basic-multiple form-control" style="height: 150%;" multiple="multiple">
                <option disabled value="">Pilih Salah Satu</option>
              </select>
            </div>
          </div>
          <!-- <div class="row mt-5">
            <div class="col-12">
              <h5>Beacukai Information</h5>
              <p>Please Select Domestic Service first.</p>
            </div>
            <div class="col-6">
              <div class="btn-group">
                <a id="domestic" style="opacity:50%;" type="button" class="btn btn-primary text-white">Domestic Form</a>
              </div>
              <div class="btn-group">
                <a id="nondomestic" type="button" class="btn btn-info text-white">Non-Domestic Form</a>
              </div>
            </div>
          </div>
          <div class="row mt-3" id="beacukaiForm">
            <div class="col-12 col-md-6">
              <div class="form-group">
                <label class="mb-2" for="">Document Number <span class="badge bg-warning">Maximum 6 Characters </span></label>
                <div class="input-group mb-3">
                  <input placeholder="396956/KPU.01/2021" type="text" class="form-control" name="documentNumber" id="documentNumber" placeholder="" aria-label="Example text with button addon" aria-describedby="button-addon1">
                  <a onclick="checkBeacukaiExport();" class="btn btn-primary" type="button" id="beacukaicheck"><i class="fa fa-magnifying-glass"></i> Check</a>
                </div>
              </div>
            </div>
            <div class="col-12 col-md-3">
              <div class="form-group">
                <label for="">Document Type</label>
                <input readonly placeholder="Please Fill Document Number First.." class="form-control" type="text" name="documentType" id="documentType">
              </div>
            </div>
            <div class="col-12 col-md-3">
              <div class="form-group">
                <label for="">Document Date</label>
                <input readonly class="form-control" placeholder="Please Fill Document Number First.." type="text" name="documentDate" id="documentDate">
                <input type="hidden" id="beacukaiChecking" value="false">
              </div>
            </div>
          </div> -->
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