@extends ('partial.invoice.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Masukan Data untuk form Delivery</p>
</div>
<div class="page-content mb-5">
  <section class="row">
    <form action="/delivery/form/storeForm" method="POST" id="formSubmit" enctype="multipart/form-data">
      @CSRF
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-12">
              <h5>Customer Information</h5>
              <p>Masukan Data Customer</p>
            </div>
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
                <input required type="text" class="form-control" id="npwp" name="npwp" placeholder="Pilih Customer Dahulu!.." readonly>
              </div>
            </div>
            <div class="col-4">
              <div class="form-group">
                <label for="">Expired Date</label>
                <input required name="exp_date" id="exp_date" type="date" class="form-control flatpickr-range mb-3" placeholder="09/05/2023" id="expired">
              </div>
            </div>
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
                  <option value="sp2iks">SP2 Kapal Sandar icon (MT Balik IKS)</option>
                  <option value="sp2mkb">SP2 Kapal Sandar icon (MKB)</option>
                  <option value="sp2pelindo">SP2 Kapal Sandar icon (MT Balik Pelindo)</option>
                  <option value="spps">SPPS</option>
                  <option value="sppsrelokasipelindo">SPPS (Relokasi Pelindo - ICON)</option>
                  <option value="sp2icon">SP2 (Relokasi Pelindo - ICON)</option>
                  <option value="mtiks">MT Keluar IKS</option>
                </select>
              </div>
            </div>
          </div>
          <div class="row mt-5" id="do_fill">
            <div class="col-12">
              <h5>Information Shipping Agent</h5>
              <p>Masukan Data Shipping dan Pilih Metode Do Checking</p>
            </div>

            <?php
            if ($user == "2") { ?>
              <div class="col-12 col-md-4" id="do_manual">
                <div class="form-group">
                  <label for="">Do Number</label>
                  <div class="input-group mb-3">
                    <input name="do_number" id="do_number_type" type="text" class="form-control" placeholder="DO910934">
                    <a onclick="checkDoNumber();" class="btn btn-primary" type="button" id="doNumberCheck"><i class="fa fa-magnifying-glass"></i> Check</a>
                  </div>
                </div>
              </div>
            <?php } else { ?>
              <div class="col-12">
                <div class="row">
                  <div class="col-6">
                    <div class="btn-group mb-3">
                      <a id="manual" style="opacity: 50% !important;" class="btn btn-primary" type="button">Manual Do Checking</a>
                    </div>
                    <div class="btn-group mb-3">
                      <a id="auto" class="btn btn-info ml-3" type="button">Automatic Do Checking</a>
                    </div>
                  </div>
                </div>
              </div>
              <!-- <div class="col-12 col-md-4" style="display: none !important;" id="do_manual">
                <div class="form-group">
                  <label for="">Do Number</label>
                  <input name="do_number" type="text" class="form-control" placeholder="Do Number">
                </div>
              </div> -->
              <div class="col-12 col-md-4" id="do_manual" style="display: none !important;">
                <div class="form-group">
                  <label for="">Do Number</label>
                  <div class="input-group mb-3">
                    <input name="do_number" id="do_number_type" type="text" class="form-control" placeholder="DO910934">
                    <a onclick="checkDoNumber();" class="btn btn-primary" type="button" id="doNumberCheck"><i class="fa fa-magnifying-glass"></i> Check</a>
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-4" id="do_auto">
                <div class="form-group">
                  <label for="">Do Number Auto</label>
                  <select required name="do_number_auto" id="do_number_auto" class="js-example-basic-multiple form-control" style="height: 150%;">
                    <option value="" disabled selected>Pilih Salah Satu</option>
                    <?php foreach ($do as $data) { ?>
                      <option value="<?= $data->do_no ?>" data-id="<?= $data->do_no ?>"><?= $data->do_no ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>

            <?php } ?>
            <div class="col-12 col-md-4">
              <div class="form-group">
                <label for="">Do Expired</label>
                <input name="do_exp_date" id="do_exp_date" required readonly type="date" class="form-control flatpickr-range mb-3" placeholder="09/05/2023">
              </div>
            </div>
            <div class="col-12 col-md-4">
              <div class="form-group">
                <label for="">Bill of Loading Number</label>
                <input name="boln" id="boln" required readonly type="text" class="form-control" placeholder="Bill Of Loading Number">
              </div>
            </div>

          </div>
          <div class="row mt-5" id="mt_fill" style="display: none !important;">
            <div class="col-12">
              <h5>Information Shipping Agent</h5>
              <p>Masukan Data Shipping dan Pilih Metode Do Checking</p>
            </div>
            <div class="col-4" id="vesselBN">
              <div class="form-group">
                <label for="">Vessel</label>
                <input type="text" id="vesselBNInput" name="vessel" class="form-control" placeholder="Vessel" required>
              </div>
            </div>
            <div class="col-4">
              <div class="form-group">
                <label>Voyage</label>
                <input required type="text" id="voyage" name="voyage" class="form-control" placeholder="Voyage..">
              </div>
            </div>
            <div class="col-4">
              <div class="form-group">
                <label>Vessel Code</label>
                <input required type="text" id="vesselcode" name="vesselcode" class="form-control" placeholder="Vessel Code..">
              </div>
            </div>
          </div>
          <div class="row mt-5">
            <div class="col-12">
              <h5>Add Container</h5>
              <p>Masukan Nomor Container</p>
            </div>
            <div class="col-12" id="selector">
              <label for="">Container Number</label>
              <select name="container[]" id="containerSelector" class="js-example-basic-multiple form-control" style="height: 150%;" multiple="multiple">
                <option disabled value="">Pilih Salah Satu</option>
                <?php foreach ($container as $data) { ?>
                  <?php if ($data->ctr_intern_status == "03" && $data->isChoosen == "0") { ?>
                    <option value="<?= $data->id ?>"><?= $data->container_no ?></option>
                  <?php } ?>
                <?php } ?>
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
              <a type="submit" onclick="beacukaiCheckValue();" class="btn btn-success">Submit</a>
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