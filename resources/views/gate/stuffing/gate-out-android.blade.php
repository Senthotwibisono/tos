@extends('partial.android')
@section('custom_styles')

@endsection
@section('content')

<div class="page-heading">
  <div class="page-title">
    <div class="row">
      <div class="col-12 col-md-6 order-md-1 order-last">
        <h3>Gate Out Stuffing</h3>
      </div>

      <div class="col-12 col-md-6 order-md-2 order-first">
        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
          <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Stuffing Gate Out</li>
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
            <h6>Nomor Truck</h6>
          </div>
          <div class="col-1">
            :
          </div>
          <div class="col-6">
            <select class="choices form-control" name="truck_id" id="tayo">
              <option value="">Select Truck</option>
              @foreach($ro as $truck)
              <option value="{{$truck->ro_id_gati}}">{{$truck->truck_no}}</option>
              @endforeach
            </select>

          </div>
        </div>
        <br>
      </div>
      <div class="card-footer">
        <div class="d-flex justify-content-end">
          <button type="button" class="btn btn-outline-primary ml-1 confirm-gate-out-stuffing">Permit</button>

        </div>
      </div>
    </div>
    <br>
    <div class="card">
      <div class="card-header">
        <h4>Waiting to Out</h4>
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
            @foreach($ro_table as $r)
            <tr>
              <td>{{$loop->iteration}}</td>
              <td>{{$r->ro_no}}</td>
              <td>{{$r->truck_no}}</td>
              <td>@if($r->truck_out_date_after != null)
                {{$r->truck_out_date_after}}
                @else
                {{$r->truck_out_date}}
                @endif
              </td>
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
    $(document).on('click', '.confirm-gate-out-stuffing', function(e) {
      e.preventDefault();

      var ro_id_gati = $('#tayo').val();


      var data = {
        'ro_id_gati': $('#tayo').val(),



      }
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      Swal.fire({
        title: 'Are you Sure?',
        text: "Permit Truck ? ",
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
            url: '/stuf-gate-out',
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
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