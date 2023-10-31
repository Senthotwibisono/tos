@extends ('partial.invoice.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Masukan Data untuk form Delivery</p>
</div>
<div class="page-content mb-5">
  <section class="row">
    <form action="/receiving/form/storeForm" method="POST" id="formSubmit" enctype="multipart/form-data">
      @CSRF
      <input type="hidden" id="exp_date" value="receiving">
      <input type="hidden" id="do_exp_date" value="receiving">
      <input type="hidden" id="boln" value="receiving">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-12">
              <h5>Customer Information</h5>
              <p>Masukan Data Customer</p>
            </div>
            <div class="col-6">
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
            <div class="col-6">
              <div class="form-group">
                <label for="">NPWP</label>
                <input required type="text" class="form-control" id="npwp" name="npwp" placeholder="Pilih Customer Dahulu!.." readonly>
              </div>
            </div>
            <!-- <div class="col-4">
              <div class="form-group">
                <label for="">Expired Date</label>
                <input required name="exp_date" id="exp_date" type="date" class="form-control flatpickr-range mb-3" placeholder="09/05/2023" id="expired">
              </div>
            </div> -->
            <div class="col-12">
              <div class="form-group">
                <label for="">Address</label>
                <input required type="text" class="form-control" id="address" name="address" placeholder="Pilih Customer Dahulu!.." readonly>
                <!-- <textarea class="form-control" id="address" name="address" cols="10" rows="4"></textarea> -->
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label>Order Service</label>
                <select name="order_service" class="form-select" required id="orderService">
                  <option value="" default selected disabled>Pilih Salah Satu..</option>
                  <option value="lolofull">LOLO FULL KAPAL SANDAR ICON (2 Invoice)</option>
                  <option value="lolofull1inv">LOLO FULL KAPAL SANDAR ICON(1 Invoice)</option>
                  <option value="lolomt">LOLO MT (1 INVOICE)</option>
                  <option value="jpbicon">JPB EX-TRUCK/ STUFFING MUATAN KAPAL ICON</option>
                  <option value="jpbluar">JPB EX-TRUCK/ STUFFING MUATAN KAPAL LUAR </option>
                  <option value="ernahandling1inv">HANDLING CHARGE ERNA VIA KAPAL ICON (INVOICE ICL) 1 INVOICE</option>
                  <option value="ernahandling2inv">HANDLING CHARGE ERNA VIA KAPAL ICON (INVOICE ERNA) 2 INVOICE </option>
                  <option value="ernahandlingluar">HANDLING CHARGE ERNA VIA KAPAL LUAR (INVOICE ERNA) 2 INVOICE</option>
                  <option value="sp2dry">MUAT DRY SP2</option>
                  <option value="sppsdry">MUAT DRY SPPS</option>
                </select>
              </div>
            </div>
          </div>
          <div class="row mt-5">
            <div class="col-12">
              <h5>Information Shipping Agent</h5>
              <p>Masukan Informasi Agen Shipping</p>
            </div>
            <div class="col-12">
              <div class="row">
                <div class="col-6" id="bookingInput">
                  <div class="form-group">
                    <label for="">Booking Number</label>
                    <select name="booking" id="booking" class="js-example-basic-multiple form-control" style="height: 150%;">
                      <option value="" disabled selected>Pilih Salah Satu</option>
                      <?php foreach ($booking as $data) { ?>
                        <option value="<?= $data->booking_no ?>" data-id="<?= $data->booking_no ?>"><?= $data->booking_no ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="col-6" id="RoInput" style="display: none !important;">
                  <div class="form-group">
                    <label for="">RO Number</label>
                    <select name="roNumber" id="roNumber" class="js-example-basic-multiple form-control" style="height: 150%;">
                      <option value="" disabled selected>Pilih Salah Satu</option>
                      <?php foreach ($ro as $data) { ?>
                        <option value="<?= $data->roNumber ?>" data-id="<?= $data->roNumber ?>"><?= $data->roNumber ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="col-2">
                  <div class="form-group" id="ctrInput">
                    <label for="">Ctr Count</label>
                    <input readonly placeholder="Pilih Order Service Dahulu!" name="ctr" type="text" id="ctr" class="form-control" required placeholder="Ctr Count">
                  </div>
                </div>
                <div class="col-2">
                  <div class="form-group" id="podInput">
                    <label for="">Pod</label>
                    <input readonly placeholder="Pilih Order Service Dahulu!" name="pod" type="text" id="pod" class="form-control" required placeholder="pod">
                  </div>
                </div>
                <div class="col-2">
                  <div class="form-group" id="fpodInput">
                    <label for="">Fpod</label>
                    <input readonly placeholder="Pilih Order Service Dahulu!" name="fpod" type="text" id="fpod" class="form-control" required placeholder="fpod">
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 mt-2">
              <div class="form-group" id="vesselSelect" style="display: none !important;">
                <label for="">Vessel</label>
                <select required name="vessel" id="vessel" class="js-example-basic-multiple form-control" style="height: 150%;">
                  <option value="" disabled selected>Pilih Salah Satu</option>
                  <?php foreach ($vessel as $data) { ?>
                    <option value="<?= $data->ves_name ?>" data-id="<?= $data->ves_id ?>"><?= $data->ves_name ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-2" id="vesselBN">
              <label for="">Vessel</label>
              <input readonly placeholder="Pilih Order Service Dahulu!" type="text" name="vessel" id="vesselBNInput" class="form-control" placeholder="Vessel" required>

            </div>
            <div class="col-2">
              <div class="form-group">
                <label>Voyage</label>
                <input readonly placeholder="Pilih Order Service Dahulu!" required type="text" id="voyage" name="voyage" class="form-control" placeholder="Voyage..">
              </div>
            </div>

            <div class="col-2">
              <div class="form-group">
                <label>Vessel Code</label>
                <input readonly placeholder="Pilih Order Service Dahulu!" required type="text" id="vesselcode" name="vesselcode" class="form-control" placeholder="Vessel Code..">
              </div>
            </div>

            <div class="col-2">
              <div class="form-group">
                <label>Closing Time</label>
                <input readonly placeholder="Pilih Order Service Dahulu!" required name="closingtime" id="closing" type="date" class="form-control flatpickr-range mb-3" placeholder="09/05/2023" id="closingtime">
              </div>
            </div>

            <input readonly placeholder="Pilih Order Service Dahulu!" type="hidden" name="exp_time" value="12:00">

            <div class="col-2">
              <div class="form-group">
                <label>Arrival Date</label>
                <input readonly placeholder="Pilih Order Service Dahulu!" required name="arrival" id="arrival" type="date" class="form-control flatpickr-range mb-3" placeholder="09/05/2023" id="arrival">
              </div>
            </div>

            <div class="col-2">
              <div class="form-group">
                <label>Departure Date</label>
                <input readonly placeholder="Pilih Order Service Dahulu!" required name="departure" type="date" class="form-control flatpickr-range mb-3" placeholder="09/05/2023" id="departure">
              </div>
            </div>
          </div>
          <div class="row mt-5">
            <div class="col-12">
              <h5>Add Container</h5>
              <p>Pilihlah Booking Number Terlebih Dahulu, lalu Pilih No Container</p>
            </div>
            <div class="col-12" id="selector">
              <label for="">Container Number</label>
              <select name="container[]" id="containerSelector" class="js-example-basic-multiple form-control" style="height: 150%;" multiple="multiple">
                <option disabled value="">Pilih Salah Satu</option>
                <!-- <?php foreach ($container as $data) { ?>
                  <?php if ($data->ctr_intern_status == "03") { ?>
                    <option value="<?= $data->id ?>"><?= $data->container_no ?></option>
                  <?php } ?>
                <?php } ?> -->
              </select>

            </div>
            <div class="col-12" id="selectorView" style="display: none !important;">
              <select name="" id="containerSelectorView" disabled class="js-example-basic-multiple form-control" style="height: 150%;" multiple="multiple">
                <option disabled value="">Pilih Salah Satu</option>
              </select>
            </div>
          </div>
          <div class="row mt-5">
            <div class="col-12">
              <h5>Beacukai Information</h5>
              <p>Please Select Domestic Service first</p>
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
                  <a onclick="checkBeacukaiImport();" class="btn btn-primary" type="button" id="beacukaicheck"><i class="fa fa-magnifying-glass"></i> Check</a>
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
          </div>
          <div class="row mt-5">
            <div class="col-12 text-right">
              <a type="submit" onclick="beacukaiCheckValueExport();" class="btn btn-success">Submit</a>
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