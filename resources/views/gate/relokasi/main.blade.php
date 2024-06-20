@extends('partial.main')
@section('custom_styles')
<style>
  .border {
    border: 1px solid transparent;
    /* Set border dulu ke transparan */
    border-image: linear-gradient(to right, rgba(128, 128, 128, 0.5), transparent);
    /* Gunakan linear gradient untuk border dengan gradasi */
    border-image-slice: 1;
    /* Memastikan border image mencakup seluruh border */
  }
</style>
@endsection

@section('content')
<div class="page-heading">
  <div class="page-title">
    <div class="row">
      <div class="col-12 col-md-6 order-md-1 order-last">
        <h3>Gate Relokasi</h3>
      </div>

      <div class="col-12 col-md-6 order-md-2 order-first">
        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
          <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Gate MT & Relokasi Pelindo</li>
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
            <input type="text" class="form-control" id="truck" required>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-4">
            <h6>Container</h6>
          </div>
          <div class="col-1">
            :
          </div>
          <div class="col-6">
            <select class="choices form-control" name="container_key" id="container">
              <option value="" disabled values selected>Select Container</option>
              @foreach($item as $tem)
              <option value="{{$tem->container_key}}">{{$tem->container_no}}</option>
              @endforeach
            </select>

          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-4">
            <h6>Invoice</h6>
          </div>
          <div class="col-1">
            :
          </div>
          <div class="col-3">
            <label for="">Invoice No</label>
            <input type="text" class="form-control" id="invoice" readonly>
          </div>
          <div class="col-3">
            <label for="">Job No</label>
            <input type="text" class="form-control" id="job" readonly>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-4">
            <h6>Dokumen BC</h6>
          </div>
          <div class="col-1">
            :
          </div>
          <div class="col-3">
            <label for="">Order Service</label>
            <input type="text" class="form-control" id="orderservice" readonly>
            <input type="hidden" class="form-control" id="orderserviceCode">
          </div>
          <div class="col-3">
            <label for="">Dok BC</label>
            <input type="text" class="form-control" id="dok" readonly> 
            <input type="hidden" class="form-control" id="jenisDok">
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-4">
            <h6>Depo Tujuan</h6>
          </div>
          <div class="col-1">
            :
          </div>
          <div class="col-3">
            <input type="text" class="form-control" id="depo" readonly>
          </div>
          <div class="col-3">
            <input type="text" class="form-control" id="opr" readonly>
          </div>
        </div>
      </div>
      <div class="card-footer">
        <div class="d-flex justify-content-end">
          <button type="button" class="btn btn-outline-primary ml-1 permit"><i class="bx bx-check d-block d-sm-none"></i><span class="d-none d-sm-block">Permit</span>
          </button>
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
              <th>Container No</th>
              <th>Order Service</th>
              <th>Truck No</th>
              <th>Truck in Date</th>
            </tr>
          </thead>
          <tbody>
            @foreach($item_confirmed as $itm)
            <tr>
              <td>{{$loop->iteration}}</td>
              <td>{{$itm->container_no}}</td>
              <td>{{$itm->service->name ?? ''}}</td>
              <td>{{$itm->truck_no}}</td>
              <td>{{$itm->truck_in_date}}</td>
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
    $(document).on('click', '.permit', function(e) {
      e.preventDefault();
      var container_key = $('#container').val();
      var data = {
        'container_key': $('#container').val(),  
        'truck_no': $('#truck').val(),
        'depo_return': $('#depo').val(),





      }
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      Swal.fire({
        title: 'Are you Sure?',
        text: " ",
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
            url: '/gate-relokasi',
            data: data,
            cache: false,
            dataType: 'json',
            success: function(response) {
              console.log(response);
              if (response.success) {
                Swal.fire('Saved!', 'Silahkan Menuju Bagian Placement', 'success')
                  .then(() => {
                    // Memuat ulang halaman setelah berhasil menyimpan data
                    window.location.reload();
                  });
              } else {
                Swal.fire('Error', response.message, 'error');
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

  <script>
    $(function() {
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $(document).ready(function() {
        $('#container').on('change', function() {
          let id = $(this).val();
          $.ajax({
            type: 'POST',
            url: '/relokasi-data_container',
            data: {
              container_key: id
            },
            success: function(response) {
              // console.log(res);
              $('#job').val(response.data.job_no);
              $('#invoice').val(response.data.invoice_no);
              $('#orderservice').val(response.data.order_service);
              $('#opr').val(response.data.ctr_opr);
              $('#depo').val(response.data.service.depo_return);

            },
            error: function(data) {
              console.log('error:', data);
            },
          });
        });
      });
    });
  </script>
  @endsection