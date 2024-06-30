@extends ('partial.invoice.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
</div>

<div class="page-content">

  <section class="row">
    <form action="{{ route('storeData')}}" method="POST" enctype="multipart/form-data">
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
                        <option value="{{$data->ves_id}}">{{$data->ves_name}}--{{$data->voy_out}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label>Voyage</label>
                    <input type="text" id="voyage" name="voyage" class="form-control" placeholder="Voyage.." readonly>
                  </div>
                </div>

                <div class="col-6">
                  <div class="form-group">
                    <label>Vessel Code</label>
                    <input type="text" id="vesselcode" name="vesselcode" class="form-control" placeholder="Vessel Code.." readonly>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label>Vessel Name</label>
                    <input type="text" id="vesName" name="vesName" class="form-control" placeholder="Vessel ID.." readonly>
                  </div>
                </div>

                <div class="col-6">
                  <div class="form-group">
                    <label>Closing Time</label>
                    <input name="closingtime" id="closing" required type="date-time" class="form-control flatpickr-range mb-3" placeholder="09/05/2023" id="closingtime" readonly>
                  </div>
                </div>

                <div class="col-6">
                  <div class="form-group">
                    <label>Arival Date</label>
                    <input name="arrival" id="arrival" required type="date-time" class="form-control flatpickr-range mb-3" placeholder="09/05/2023" id="arrival" readonly>
                  </div>
                </div>

                <div class="col-6">
                  <div class="form-group">
                    <label>Departure Date</label>
                    <input name="departure" id="departure" required type="date-time" class="form-control flatpickr-range mb-3" placeholder="09/05/2023" id="departure" readonly>
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
                  <input id="storecoparn" type="file" name="file" class="dropify" data-height="270" data-max-file-size="3M" data-allowed-file-extensions="xls xlsx" />
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