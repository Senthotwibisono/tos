@extends ('partial.invoice.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
</div>

<div class="page-content">

  <section class="row">
    <form action="/coparn/singlestore" method="POST" enctype="multipart/form-data">
      @CSRF
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Shipment Information</h3>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-4">
                  <div class="form-group">
                    <label>Vessel Name</label>
                    <select required name="vessel" id="vessel" class="js-example-basic-multiple form-control" style="height: 150%;">
                      <option value="" disabled selected>Pilih Salah Satu</option>
                      <?php foreach ($vessel as $data) { ?>
                        <option value="<?= $data->ves_name ?>" data-id="<?= $data->ves_id ?>"><?= $data->ves_name ?></option>
                      <?php } ?>
                    </select>
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

                <div class="col-4">
                  <div class="form-group">
                    <label>Closing Time</label>
                    <input required name="closingtime" id="closing" type="date" class="form-control flatpickr-range mb-3" placeholder="09/05/2023" id="closingtime">
                  </div>
                </div>

                <div class="col-4">
                  <div class="form-group">
                    <label>Arival Date</label>
                    <input required name="arrival" id="arrival" type="date" class="form-control flatpickr-range mb-3" placeholder="09/05/2023" id="arrival">
                  </div>
                </div>

                <div class="col-4">
                  <div class="form-group">
                    <label>Departure Date</label>
                    <input required name="departure" id="departure" type="date" class="form-control flatpickr-range mb-3" placeholder="09/05/2023" id="departure">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Entry Document</h3>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <label>Booking Number</label>
                    <input required type="text" name="booking" class="form-control" placeholder="Booking Number">
                  </div>
                </div>
                <div class="col-3">
                  <div class="form-group">
                    <label>Container Operator</label>
                    <input required type="text" name="contoperator" class="form-control" placeholder="Container Operator">
                  </div>
                </div>
                <div class="col-3">
                  <div class="form-group">
                    <label>Owner</label>
                    <input required type="text" name="owner" class="form-control" placeholder="Owner">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <!-- <h3 class="card-title">Temperature, Over Height, Over Weight, Over Length ,IMO</h3> -->
              <h3 class="card-title">Entry Container</h3>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-3">
                  <div class="form-group">
                    <label>Container Number</label>
                    <input required type="text" name="connumber" class="form-control" placeholder="Container Number">
                  </div>
                </div>
                <div class="col-3">
                  <div class="form-group">
                    <label>ISO</label>
                    <input required type="text" name="iso" class="form-control" placeholder="ISO">
                  </div>
                </div>
                <div class="col-3">
                  <div class="form-group">
                    <label>Size</label>
                    <input required type="text" name="size" class="form-control" placeholder="Size">
                  </div>
                </div>
                <div class="col-3">
                  <div class="form-group">
                    <label>Type</label>
                    <input required type="text" name="type" class="form-control" placeholder="Type">
                  </div>
                </div>
                <div class="col-3">
                  <div class="form-group">
                    <label>Status</label>
                    <select required name="status" id="status" class="js-example-basic-multiple form-control" style="height: 150%;">
                      <option value="" disabled selected>Pilih Salah Satu</option>
                      <option value="full">Full</option>
                      <option value="empty">Empty</option>
                    </select>
                  </div>
                </div>
                <div class="col-3">
                  <div class="form-group">
                    <label>Gross</label>
                    <input required type="text" name="gross" class="form-control" placeholder="Gross">
                  </div>
                </div>
                <div class="col-3">
                  <div class="form-group">
                    <label>Pod</label>
                    <input required type="text" name="pod" class="form-control" placeholder="Pod">
                  </div>
                </div>
                <div class="col-3">
                  <div class="form-group">
                    <label>Fpod</label>
                    <input required type="text" name="fpod" class="form-control" placeholder="Fpod">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Temperature, Over Height, Over Weight, Over Length ,IMO</h3>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <label>Temperature</label>
                    <input required type="text" name="temperature" class="form-control" placeholder="Temperature">
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label>Over Height</label>
                    <input required type="text" name="overh" class="form-control" placeholder="Over Height">
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label>Over Weight</label>
                    <input required type="text" name="overw" class="form-control" placeholder="Over Weight">
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label>Over Length</label>
                    <input required type="text" name="overl" class="form-control" placeholder="Over Length">
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label>IMO</label>
                    <input required type="text" name="imo" class="form-control" placeholder="IMO">
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label>UN Number</label>
                    <input required type="text" name="unnumber" class="form-control" placeholder="UN Number">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12">
          <button type="submit" class="btn btn-success">Kirim</button>
          <button class="btn btn-warning" onclick="history.back();">Batal</button>
        </div>
      </div>

    </form>
  </section>

</div>

@endsection

<script>
  console.log("masuk");
</script>