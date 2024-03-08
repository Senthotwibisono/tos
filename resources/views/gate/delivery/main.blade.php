@extends('partial.main')
@section('content')
<div class="page-heading">
  <div class="page-title">
    <div class="row">
      <div class="col-12 col-md-6 order-md-1 order-last">
        <h3>Delivery Gate In</h3>
      </div>

      <div class="col-12 col-md-6 order-md-2 order-first">
        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
          <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Delivery Gate In</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <section class="section">
    <div class="card" id="load_ini">
      <div class="card-header">
        <button class="btn icon icon-left btn-outline-info" data-bs-toggle="modal" data-bs-target="#success">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit">
            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
          </svg> Confirmed</button>
      </div>
      <div class="card-body">
        <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
          <thead>
            <tr>
              <th>Container No</th>
              <th>Size</th>
              <th>Type</th>
              <th>Vessel</th>
              <th>Voy</th>
              <th>Truck No</th>
              <th>Truck In</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($formattedData as $d)
            <tr>
              <td>{{$d['container_no']}}</td>
              <td>{{$d['ctr_size']}}</td>
              <td>{{$d['ctr_type']}}</td>
              <td>{{$d['ves_name']}}</td>
              <td>{{$d['voy_no']}}</td>
              <td>{{$d['truck_no']}}</td>
              <td>{{$d['truck_in_date']}}</td>
              <td>
                <button type="button" class="btn icon icon-left btn-outline-info edit" data-bs-toggle="modal" data-id="{{$d['container_key']}}">edit</button>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </section>
</div>

<!-- Modal Update Status -->
<div class="modal fade text-left" id="success" role="dialog" aria-labelledby="myModalLabel110" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header bg-info">
        <h5 class="modal-title white" id="myModalLabel110">Delivery Gate In</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><i data-feather="x"></i></button>
      </div>
      <div class="modal-body">
        <!-- form -->
        <div class="form-body" id="place_cont">
          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <label for="first-name-vertical">Choose Container Number</label>
                <select class="choices form-select" id="key" name="container_key" required>
                  <option disabled selected value="">Select Container</option>
                  @foreach($contGati as $gati)
                  <option value="{{$gati->container_key}}">{{$gati->container_no}}</option>
                  @endforeach
                </select>
                <input type="hidden" id="container_no" class="form-control" name="container_no">
                <input type="hidden" id="contKey" class="form-control" name="container_key">
              </div>
              {{ csrf_field()}}
            </div>
            <div class="col-12">
              <div class="row">
                <div class="col-6">
                    <div class="form-group">
                      <label for="first-name-vertical">Job Number</label>
                      <input type="text" id="job" class="form-control" name="job_no" readonly>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                      <label for="first-name-vertical">Order Service</label>
                      <input type="text" id="orderservice" class="form-control"  readonly>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                      <label for="first-name-vertical">Dok</label>
                      <input type="text" id="dok" class="form-control"  readonly>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                      <label for="first-name-vertical">Jenis Dok</label>
                      <input type="text" id="jenisDok" class="form-control"  readonly>
                    </div>
                </div>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label for="first-name-vertical">Invoice Number</label>
                <input type="text" id="invoice" class="form-control" name="invoice_no" readonly>
                <input type="hidden" value="{{ $currentDateTimeString }}" name="truck_in_date" class="form-control" readonly>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label for="first-name-vertical">Truck Number</label>
                <input type="text" id="tayo" class="form-control" name="truck_no" required>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label for="first-name-vertical">Date In</label>
                <input type="datetime-local" value="{{ $currentDateTimeString }}" id="datein" name="truck_in_date" class="form-control" readonly>
              </div>
            </div>


          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal"> <i class="bx bx-x d-block d-sm-none"></i><span class="d-none d-sm-block">Close</span></button>
        <button type="submit" class="btn btn-success ml-1 update_status"><i class="bx bx-check d-block d-sm-none"></i><span class="d-none d-sm-block">Confirm</span></button>
      </div>
    </div>
  </div>
</div>


<!-- edit truck -->
<div class="modal fade text-left" id="edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel130" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title white" id="myModalLabel130">Info Modal</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- content -->
                    <div class="col-12">
                      <div class="form-group">
                        <label for="">Container</label>
                        <input type="text" class="form-control" id="cont" disabled>
                        <input type="hidden" class="form-control" id="contKey">
                      </div>
                    </div>
                    <br>
                    <div class="col-12">
                      <div class="form-group">
                        <label for="">Truck</label>
                        <input type="text" id="nomor_truck" class="form-control">
                      </div>
                    </div>
                    <!-- end content -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal"> <i class="bx bx-x d-block d-sm-none"></i><span class="d-none d-sm-block">Close</span></button>
                    <button type="button" class="btn btn-info ml-1 edit-truck" data-bs-dismiss="modal"><i class="bx bx-check d-block d-sm-none"></i><span class="d-none d-sm-block">Accept</span></button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('custom_js')
<script src="{{ asset('vendor/components/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{asset('dist/assets/extensions/sweetalert2/sweetalert2.min.js')}}"></script>
<script src="{{asset('dist/assets/js/pages/sweetalert2.js')}}"></script>

<script>
  $(document).ready(function() {
    $('.container').select2({
      dropdownParent: '#success',
    });
  });
  $(document).on('click', '.update_status', function(e) {
    e.preventDefault();
    var container_key = $('#contKey').val();
    var container_no = $('#container_no').val();
    var invoice_no = $('#invoice').val();
    var job_no = $('#job').val();
    var truck_no = $('#tayo').val();
    var truck_in_date = $('#datein').val();
    var order_service = $('#orderserviceCode').val();
    if (!truck_no) {
        // If any of the required fields are empty, show an error message and return
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            text: 'Nomor Truck Belum Diisi, cek kembali Ya !!',
        });
        return;
        }
    var data = {
      'container_key': $('#contKey').val(),
      'container_no': $('#container_no').val(),
      'truck_no': $('#tayo').val(),
      'truck_in_date': $('#datein').val(),
      'order_service': $('#orderserviceCode').val(),
      'invoice_no': $('#invoice').val(),
      'jno_dok': $('#dok').val(),
      'jenis_dok': $('#jenisDok').val(),


    }
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    Swal.fire({
      title: 'Are you Sure?',
      text: "Truck " + truck_no + " will bring container" + container_no + "? ",
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
          url: '/gati-del',
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


  $(function() {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $(document).ready(function() {
      $('#key').on('change', function() {
        let id = $(this).val();
        $.ajax({
          type: 'POST',
          url: '/gati-data_container',
          data: {
            container_key: id
          },
          success: function(response) {
            console.log(response);
            $('#contKey').val(response.data.container_key);
            $('#container_no').val(response.data.container_no);
            $('#job').val(response.data.job_no);
            $('#invoice').val(response.data.invoice_no);
            $('#orderservice').val(response.data.order_service);
          },
          error: function(data) {
            console.log('error:', data);
          },
        });
      });
    });
    // $(function(){
    //         $('#block'). on('change', function(){
    //             let yard_block = $('#block').val();

    //             $.ajax({
    //                 type: 'POST',
    //             url: '/get-slot',
    //                 data : {yard_block : yard_block},
    //                 cache: false,

    //                 success: function(msg){
    //                     $('#slot').html(msg);

    //                 },
    //                 error: function(data){
    //                     console.log('error:',data)
    //                 },
    //             })               
    //         })
    //     })
  });
</script>
<script>
  $(function() {
   $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });

   $(document).on('click', '.edit', function() {
      let id = $(this).data('id');
      $.ajax({
         type: 'GET',
         url: '/gati-del/edit-' + id,
         cache: false,
         data: {
            container_key: id
         },
         dataType: 'json',
         success: function(response) {
            console.log(response);
            $('#edit').modal('show');
            $('#edit #cont').val(response.data.container_no);
            $('#edit #contKey').val(response.data.container_key);
        
        
         },
         error: function(data) {
            console.log('error:', data);
         }
      });
   });
});

</script>

<script>
  $(document).on('click', '.edit-truck', function(e) {
    e.preventDefault();
    var container_key = $('#contKey').val();
    var truck = $('#nomor_truck').val();
    var data = {
      'container_key': $('#contKey').val(),
      'truck': $('#nomor_truck').val(),
      


    }
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    Swal.fire({
      title: 'Are you Sure?',
      text: "Ganti Nomor Truck ? ",
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
          url: '/gati-del/update-truck',
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
                          console.log('error:', response);
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