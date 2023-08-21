@extends ('partial.invoice.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
</div>
<div class="page-content mb-5">
  <section class="row">
    <form action="/invoice/mastertarif/store" method="POST" id="formSubmit" enctype="multipart/form-data">
      @CSRF
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Tambah Data Master Tarif</h3>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-6">

              <div class="form-group">
                <label for="">Lokasi Sandar</label>
                <input required type="text" name="lokasi_sandar" class="form-control" placeholder="Lokasi_Sandar">
              </div>
              <div class="form-group">
                <label for="">Type</label>
                <input required type="text" name="type" class="form-control" placeholder="Type">
              </div>
              <div class="form-group">
                <label for="">Size</label>
                <input required type="text" name="size" class="form-control" placeholder="Size">
              </div>
              <div class="form-group">
                <label for="">Status</label>
                <input required type="text" name="status" class="form-control" placeholder="Status">
              </div>
              <div class="form-group">
                <label for="">Masa 1</label>
                <input required type="text" name="masa1" class="form-control" placeholder="Masa_1">
              </div>
              <div class="form-group">
                <label for="">Masa 2</label>
                <input required type="text" name="masa2" class="form-control" placeholder="Masa_2">
              </div>
              <div class="form-group">
                <label for="">Masa 3</label>
                <input required type="text" name="masa3" class="form-control" placeholder="Masa_3">
              </div>
              <div class="form-group">
                <label for="">Masa 4</label>
                <input required type="text" name="masa4" class="form-control" placeholder="Masa_4">
              </div>
              <div class="form-group">
                <label for="">Lift On</label>
                <input required type="text" name="lift_on" class="form-control" placeholder="Lift_On">
              </div>

            </div>
            <div class="col-6">
              <div class="form-group">
                <label for="">Lift Off</label>
                <input required type="text" name="lift_off" class="form-control" placeholder="Lift_Off">
              </div>
              <div class="form-group">
                <label for="">Pass Truck</label>
                <input required type="text" name="pass_truck" class="form-control" placeholder="Pass_Truck">
              </div>
              <div class="form-group">
                <label for="">Gate Pass Admin</label>
                <input required type="text" name="gate_pass_admin" class="form-control" placeholder="Gate_Pass_Admin">
              </div>
              <div class="form-group">
                <label for="">Cost Recovery</label>
                <input required type="text" name="cost_recovery" class="form-control" placeholder="Cost_Recovery">
              </div>
              <div class="form-group">
                <label for="">Surcharge</label>
                <input required type="text" name="surcharge" class="form-control" placeholder="Surcharge">
              </div>
              <div class="form-group">
                <label for="">Packet PLP</label>
                <input required type="text" name="packet_plp" class="form-control" placeholder="Packet_PLP">
              </div>
              <div class="form-group">
                <label for="">Behandle</label>
                <input required type="text" name="behandle" class="form-control" placeholder="Behandle">
              </div>
              <div class="form-group">
                <label for="">Recooling</label>
                <input required type="text" name="recooling" class="form-control" placeholder="Recooling">
              </div>
              <div class="form-group">
                <label for="">Monitoring</label>
                <input required type="text" name="monitoring" class="form-control" placeholder="Monitoring">
              </div>
              <div class="form-group">
                <label for="">Administrasi</label>
                <input required type="text" name="administrasi" class="form-control" placeholder="Administrasi">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                <i class="bx bx-x d-block d-sm-none"></i>
                <span class="d-none d-sm-block">Cancel</span>
              </button>
              <button type="submit" class="btn btn-primary ml-1">
                Submit
              </button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </section>
</div>
<!-- update test  -->
@endsection