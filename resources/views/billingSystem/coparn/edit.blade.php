@extends ('partial.invoice.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>

</div>

<div class="page-content">
  <section class="row">
    <div class="col-12 text-center">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            <?= $title ?>
          </h3>
        </div>
      </div>
    </div>
  </section>
  <section class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
           Form Edit Coparn
          </h3>
        
        </div>
        <div class="card-body">
            
        <form action="{{ route('updateCoparn')}}" method="POST" enctype="multipart/form-data">
          @CSRF
          <div class="row">
            <div class="col-6">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Shipment Information</h3>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-6">
                      <div class="form-group">
                        <label>Vessel Name</label>
                        <select name="ves_id" id="vesselCoparn" class="js-example-basic-multiple form-control" style="height: 150%;">
                          <option value="" disabled selected>Pilih Salah Satu</option>
                          @foreach ($vessel as $data)
                            <option value="{{$data->ves_id}}" {{$cont->ves_id == $data->ves_id ? 'selected' : ''}}>{{$data->ves_name}}--{{$data->voy_out}}</option>
                          @endforeach
                          <option value="PELINDO">Relokasi Pelindo</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="form-group">
                        <label>Voyage</label>
                        <input type="text" id="voyage" name="voyage" class="form-control" placeholder="Voyage.." readonly value="{{$cont->Kapal->voy_out ?? ' ' }}">
                      </div>
                    </div>

                    <div class="col-6">
                      <div class="form-group">
                        <label>Vessel Code</label>
                        <input type="text" id="vesselcode" name="vesselcode" class="form-control" placeholder="Vessel Code.." readonly value="{{$cont->Kapal->ves_code ?? ' ' }}">
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="form-group">
                        <label>Vessel Name</label>
                        <input type="text" id="vesName" name="vesName" class="form-control" placeholder="Vessel ID.." readonly value="{{$cont->Kapal->ves_name ?? ' ' }}">
                      </div>
                    </div>

                    <div class="col-6">
                      <div class="form-group">
                        <label>Closing Time</label>
                        <input name="closingtime" id="closing" required type="date-time" class="form-control flatpickr-range mb-3" placeholder="09/05/2023" id="closingtime" readonly value="{{$cont->Kapal->clossing_date ?? ' ' }}">
                      </div>
                    </div>

                    <div class="col-6">
                      <div class="form-group">
                        <label>Arival Date</label>
                        <input name="arrival" id="arrival" required type="date-time" class="form-control flatpickr-range mb-3" placeholder="09/05/2023" id="arrival" readonly value="{{$cont->Kapal->eta_date ?? ' ' }}">
                      </div>
                    </div>

                    <div class="col-6">
                      <div class="form-group">
                        <label>Departure Date</label>
                        <input name="departure" id="departure" required type="date-time" class="form-control flatpickr-range mb-3" placeholder="09/05/2023" id="departure" readonly value="{{$cont->Kapal->etd_date ?? ' ' }}">
                        <!-- <input type="hidden" name="ves_id" id="ves_id" required value=""> -->
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-6">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Upload Copart Document Online</h3>
                  <p>Please Upload your file by drag n drop or click the area</p>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-12">
                      <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">Container No</label>
                                <input type="text" name="container_no" class="form-control" value="{{$cont->container_no}}">
                                <input type="hidden" name="container_key" class="form-control" value="{{$cont->container_key}}">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">Booking Number</label>
                                <input type="text" name="booking_no" class="form-control" value="{{$cont->booking_no}}">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">Iso Code</label>
                                <select name="iso_code" class="js-example-basic-multiple form-control" style="height: 150%;">
                                    @foreach($isoCode as $iso)
                                    <option value="{{$iso->iso_code}}" {{$cont->iso_code == $iso->iso_code ? 'selected' : ''}}>{{$iso->iso_code}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">Status</label>
                                <select name="ctr_status" class="js-example-basic-multiple form-control" style="height: 150%;" value="{{$cont->ctr_status}}">
                                    <option value="FCL">Full</option>
                                    <option value="MTY">Empty</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">Load Port</label>
                                <select name="load_port" class="js-example-basic-multiple form-control" style="height: 150%;">
                                    @foreach($ports as $port)
                                        <option value="{{$port->port}}" {{$cont->load_port == $port->port ? 'selected' : ''}}>{{$port->port}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">Disch Port</label>
                                <select name="disch_port" class="js-example-basic-multiple form-control" style="height: 150%;">
                                    @foreach($ports as $port)
                                        <option value="{{$port->port}}" {{$cont->disch_port == $port->port ? 'selected' : ''}}>{{$port->port}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">Gross</label>
                                <input type="number" name="gross" class="form-control" value="{{$cont->gross}}">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">Operator</label>
                                <input type="text" name="ctr_opr" class="form-control" value="{{$cont->ctr_opr}}">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">Apply to All Booking Number</label>
                                <input class="form-check-input" type="checkbox" name="all" id="flexSwitchCheckDefault">
                            </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row mt-3">
                    <div class="col-2">
                      <button type="submit" class="btn btn-primary text-white">Submit</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>

        </div>
      </div>
    </div>
  </section>

</div>

@endsection
@section('custom_js')
<script>
      $(function(){
        $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$(function(){
    $('#vesselCoparn'). on('change', function(){
                let id = $('#vesselCoparn').val();

                $.ajax({
                    type: 'get',
                    url: "{{ route('getVesselData')}}",
                    data : {id : id
                            },
                    cache: false,
                    
                    success: function(response) {
                        console.log(response);
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Saved!',
                                timer: 2000, // Waktu tampilan SweetAlert (ms)
                                showConfirmButton: false
                            }).then(() => {
                                $('#voyage').val(response.data.voy_out);
                                $('#vesselcode').val(response.data.ves_code);
                                $('#vesName').val(response.data.ves_name);
                                $('#closing').val(response.data.clossing_date);
                                $('#arrival').val(response.data.eta_date);
                                $('#departure').val(response.data.etd_date);
                                $('#containerSelector').empty();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            }).then(() => {
                                window.location.reload();
                            });
                        }
                    },
                    error: function(data) {
                        console.log('error:', data);
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Terjadi kesalahan saat memuat data. Silakan coba lagi nanti.',
                        });
                    }
                })
            })
        })
    });
</script>
@endsection