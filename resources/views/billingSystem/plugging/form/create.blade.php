@extends ('partial.invoice.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Masukan Data untuk form Delivery</p>
</div>
<div class="page-content mb-5">
  <section class="row">
    <form action="{{ route('plugging-create-post')}}" method="post" id="updateForm" enctype="multipart/form-data">
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
                    <option disabled selected value>Plih Satu</option>
                    @foreach($customer as $cust)
                    <option value="{{$cust->id}}">{{$cust->name}}</option>
                    @endforeach
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
                 @foreach($orderService as $os)
                 <option value="{{$os->id}}">{{$os->name}}</option>
                 @endforeach
                </select>
                <input type="hidden" id="order">
              </div>
            </div>
          </div>
    
          <div class="row mb-5">
            <div class="col-sm-6">
              <div class="form-group">
                <label for="">Start Hour</label>
                <input name="disc_date" type="datetime-local" class="form-control flatpickr-range mb-1"  id="hour">
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="">End Hour</label>
                <input name="expired_date" type="datetime-local" class="form-control flatpickr-range mb-1"  id="hour">
              </div>
            </div>
          </div>
                  
          <div class="row mt-5">
            <div class="col-12">
              <h5>Add Container</h5>
              <p>Pilih Kapal Terlebih Dahulu, Kemudian Masukan Nomor Container</p>
            </div>
            <div class="col-12">
              <label for="">Vessel</label>
              <select name="ves_id" id="kapalPlugging" class="js-example-basic-single form-control" style="height: 150%;">
                <option disabeled selected values>Pilih Satu</option>
                @foreach($vessel as $ves)
                <option value="{{$ves->ves_id}}">{{$ves->ves_name}} -- {{$ves->voy_out}}</option>
                @endforeach
              </select>
            </div>
            <div class="col-12" id="selector">
              <label for="">Container Number</label>
              <select name="container[]" id="containerSelector" class="js-example-basic-multiple form-control" style="height: 150%;" multiple="multiple">
                <option disabled value="">Pilih Salah Satu</option>
               
              </select>
            </div>
          </div>

          <div class="row mt-5">
          <h5>Discount</h5>
          <p>Di Isi dengan Persen (%)</p>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="">Discount OS</label>
              <input type="text" class="form-control" name="discount_ds" value="0">
            </div>
          </div>
         </div>

          
          
         
          <div class="row mt-5">
            <div class="col-12 text-right">
            <button type="button" id="updateButton" class="btn btn-success">Submit</button>
            <button type="button" class="btn btn-light-secondary" onclick="window.history.back();"><i class="bx bx-x d-block d-sm-none"></i><span class="d-none d-sm-block">Back</span></button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </section>
</div>
<!-- update test  -->
@endsection

@section('custom_js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Attach event listener to the update button
        document.getElementById('updateButton').addEventListener('click', function (e) {
            e.preventDefault(); // Prevent the default form submission

            // Show SweetAlert confirmation dialog
            Swal.fire({
                title: 'Are you sure?',
                text: "",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form programmatically if confirmed
                        Swal.fire({
                        title: 'Processing...',
                        text: 'Please wait while we update the container',
                        icon: 'info',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                            willOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    document.getElementById('updateForm').submit();
                }
            });
        });
    });
</script>

<script>
  $(function(){
        $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$(function(){
    // Cust
            $('#customer'). on('change', function(){
                let id = $('#customer').val();

                $.ajax({
                    type: 'get',
                    url: "{{ route('getCust')}}",
                    data : {id : id},
                    cache: false,
                    
                    success: function(response){
                        $('#npwp').val(response.data.npwp);
                        $('#address').val(response.data.alamat);
                   
                    },
                    error: function(data){
                        console.log('error:',data)
                    },
                })
            })
    // Do
        $('#kapalPlugging'). on('change', function(){
                let kapal = $('#kapalPlugging').val();

                $.ajax({
                    type: 'get',
                    url: "{{ route('container-plugging')}}",
                    data : {kapal : kapal
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
                                $('#containerSelector').empty();
                            
                                $.each(response.data, function(index, item) {
                                    $('#containerSelector').append($('<option>', {
                                        value: item.container_key,
                                        text: item.container_no,
                                    }));
                                });
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



            // RO
            $('#RoNo'). on('change', function(){
                let RoNo = $('#RoNo').val();
                let os = $('#orderService').val();

                $.ajax({
                    type: 'get',
                    url: "{{ route('getROdataExport')}}",
                    data : {RoNo : RoNo,
                            os:os
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
                                $('#containerSelector').empty();
                            
                                $.each(response.data, function(index, item) {
                                    $('#containerSelector').append($('<option>', {
                                        value: item.container_key,
                                        text: item.container_no,
                                        selected: 'selected'
                                    }));
                                });
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

<script>
  $(function(){
        $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$(function(){
        $('.cekDokumen'). on('click', function(){
            var data = {
            'container_key': $('#containerSelector').val(),
            'no_dok': $('#documentNumber').val(),
        }
                $.ajax({
                    type: 'get',
                    url: "{{ route('getDokImport')}}",
                    data : data,
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
                                $('#documentType').val(response.dok.name);
                                $('#documentDate').val(response.tgl);
                                
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

<script>
  document.getElementById('orderService').addEventListener('change', function() {
    var selectedValue = $('#order').val();
    var do_fill = document.getElementById('do_fill');
    var roDok = document.getElementById('roDok');
    
    if (selectedValue == "SPPS") {
      do_fill.style.display = 'none';
      roDok.style.display = 'block';
      $('.js-example-basic-single').select2();
      $('.js-example-basic-multiple').select2();
    } else {
      do_fill.style.display = 'block';
      roDok.style.display = 'none';
      $('.js-example-basic-single').select2();
      $('.js-example-basic-multiple').select2();
    }
  });
</script>

@endsection