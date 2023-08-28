@extends('partial.main')
@section('custom_styles')

@endsection
@section('content')

<div class="page-heading">
  <div class="page-title">
    <div class="row">
      <div class="col-12 col-md-6 order-md-1 order-last">
        <h3>Gate In Stuffing</h3>
      </div>

      <div class="col-12 col-md-6 order-md-2 order-first">
        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
          <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Stuffing Gate In</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>

 <section>
    <div class="card">
        <div class="card-header">
            <h6>Masukkan Data dengan Benar!!</h6>
            <div>
            <span>Data akan diproses secara otomatis pada tahap selanjutnya</span>
            </div>
            <hr style="border:1px solid red;">
        </div>
        <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <h6 >Dokumen R.O</h6>
                    </div>
                    <div class="col-1">
                        :
                    </div>
                    <div class="col-6">
                        <input type="text" id="ro" class="form-control">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-4">
                        <h6 >Jenis Stuffing</h6>
                    </div>
                    <div class="col-1">
                        :
                    </div>
                    <div class="col-6">
                        <select class="form-control" name="" id="service">
                            <option  disabled selected><span>Pilih Satu</span></option>
                            <option value="in">Dalam</option>
                            <option value="out">Luar</option>
                        </select>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-4">
                        <h6 >Nomor Truck</h6>
                    </div>
                    <div class="col-1">
                        :
                    </div>
                    <div class="col-6">
                        <input type="text" id="tayo" class="form-control">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-4">
                        <h6 >Jumlah Container dalam dokumen R.O</h6>
                    </div>
                    <div class="col-1">
                        :
                    </div>
                    <div class="col-6">
                        <input type="number" id="cont" class="form-control">
                    </div>
                </div>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-outline-primary ml-1 confirm-gate-stuffing"><i class="bx bx-check d-block d-sm-none"></i><span class="d-none d-sm-block">Permit</span>
                </button>
            </div>    
        </div>
    </div>
    <br>
    <div class="card">
        <div class="card-header">
            <h4>Last Permited</h4>
        </div>
        <div class="card-body">
            <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
              <thead>
                  <tr>
                      <th>No</th>
                      <th>Nomor R.O</th>
                      <th>Nomor Truck</th>
                      <th>Waktu Masuk</th>
                  </tr>
              </thead>
              <tbody>
                @foreach($ro as $r)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$r->ro_no}}</td>
                    <td>{{$r->truck_no}}</td>
                    <td>{{$r->truck_in_date}}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
        </div>
    </div>
 </section>

@endsection
@section('custom_js')
<script src="{{ asset('vendor/components/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{asset('dist/assets/extensions/sweetalert2/sweetalert2.min.js')}}"></script>
<script src="{{asset('dist/assets/js/pages/sweetalert2.js')}}"></script>
<script>
     $(document).on('click', '.confirm-gate-stuffing', function(e) {
    e.preventDefault();
    var ro_no = $('#ro').val();
    var stuffing_service = $('#service').val();
    var truck_no = $('#tayo').val();
    var jmlh_cont = $('#cont').val();

    var data = {
      'ro_no': $('#ro').val(),
      'stuffing_service': $('#service').val(),
      'truck_no': $('#tayo').val(),
      'jmlh_cont': $('#cont').val(),


    }
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    Swal.fire({
      title: 'Are you Sure?',
      text: "Permit Truck " + truck_no + "? ",
      icon: 'warning',
      showDenyButton: false,
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      confirmButtonText: 'Confirm',
    }).then((result) => {
      /* Read more about isConfirmed, isDenied below */
      if (result.isConfirmed) {


        $.ajax({
          type: 'POST',
          url: '/stuf-gate-in',
          data: data,
          cache: false,
          dataType: 'json',
          success: function(response) {
            console.log(response);
            if (response.success) {
              Swal.fire('Saved!', '', 'success')
               .then(() => {
                            // Memuat ulang halaman setelah berhasil menyimpan data
                            window.location.reload();
                        });
              
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: response.message,
              });
            }
          },
          error: function(response) {
            var errors = response.responseJSON.errors;
            if (errors) {
              var errorMessage = '';
              $.each(errors, function(key, value) {
                errorMessage += value[0] + '<br>';
              });
              Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: errorMessage,
              });
            } else {
              console.log('error:', response);
            }
          },
        });

      } else if (result.isDenied) {
        Swal.fire('Changes are not saved', '', 'info')
      }


    })

  });
</script>
@endsection