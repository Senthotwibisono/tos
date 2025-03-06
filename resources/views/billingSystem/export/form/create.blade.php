@extends ('partial.invoice.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Masukan Data untuk form Delivery</p>
</div>
<div class="page-content mb-5">
  <section class="row">
    <form action="{{ route('beforeCreateExport')}}" method="post" id="updateForm" enctype="multipart/form-data">
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
                 @foreach($orderService as $os)
                 <option value="{{$os->id}}">{{$os->name}}</option>
                 @endforeach
                </select>
                <input type="hidden" id="order">
              </div>
            </div>
          </div>
          <div id="do_fill">
          <div class="row mt-5" >
            <div class="col-12">
              <h5>Information Shipping Agent</h5>
              <p>Masukan Data Shipping dan Pilih Metode Do Checking</p>
            </div>

              <div class="col-12">
                <div class="row">
                  <div class="col-6">
                    <div class="btn-group mb-3">
                      <a id="auto" class="btn btn-info ml-3" type="button">Booking Number Checking</a>
                    </div>
                  </div>
                </div>
              </div>
           
              <div class="col-12 col-md-4" id="do_auto">
                <div class="form-group">
                  <label for="">Booking Number Auto</label>
                  <select  name="booking_no" id="booking_no" class="js-example-basic-single form-control" style="height: 150%;">
                    <option value="" disabled selected>Pilih Salah Satu</option>
                  @foreach($contBooking as $cont)
                  <option value="{{$cont}}">{{$cont}}</option>
                  @endforeach
                  </select>
                </div>
              </div>

            
            <div class="col-12 col-md-4">
              <div class="form-group">
                <label for="">Estimate Departure of Vessel</label>
                <input name="do_exp_date" id="do_exp_date"  readonly type="date-time" class="form-control flatpickr-range mb-3" >
              </div>
            </div>
            <div class="col-12 col-md-4">
              <div class="form-group">
                <label for="">Vessel Name</label>
                <input name="boln" id="boln"  readonly type="text" class="form-control" placeholder="Bill Of Loading Number">
              </div>
            </div>

          </div>
          </div>

          <!-- RO -->
        <div id="roDok" style="display:none;">
          <div class="row mt-5" >
            <div class="col-12">
              <h5>Information Shipping Agent</h5>
              <p>Masukan Data Shipping dan Pilih Metode RO Checking</p>
            </div>

              <div class="col-12">
                <div class="row">
                  <div class="col-6">
                    <div class="btn-group mb-3">
                      <a id="auto" class="btn btn-info ml-3" type="button">Automatic RO Number Checking</a>
                    </div>
                  </div>
                </div>
              </div>
           
              <div class="col-12 col-md-4">
                <div class="form-group">
                  <label for="">RO Number</label>
                  <select  name="booking_no" id="RoNo" class="js-example-basic-single form-control" style="height: 150%;">
                    <option value="" disabled selected>Pilih Salah Satu</option>
                    @foreach($roDok as $dok)
                    <option value="{{$dok->ro_no}}">{{$dok->ro_no}}</option>
                    @endforeach
                  </select>
                </div>
              </div>

            
            <div class="col-12 col-md-4">
              <div class="form-group">
                <label for="">Select Vessel</label>
                <select  name="ves_id" id="vesStuffing" class="js-example-basic-single form-control" style="height: 150%;">
                    <option value="" disabled selected>Pilih Salah Satu</option>
                    @foreach($kapalRO as $kpl)
                    <option value="{{$kpl->ves_id}}">{{$kpl->ves_name}} -- {{$kpl->voy_out}}</option>
                    @endforeach
                    <option value="PELINDO">Relokasi Pelindo</option>
                  </select>
              </div>
            </div>
          </div>
        </div>
          

          <div class="row mt-5">
            <div id="kapalManual" class="col-12 col-md-4" style="display:none;">
              <div class="form-group">
                <label for="">Select Vessel</label>
                <select  name="ves_id" id="vesStuffing" class="js-example-basic-single form-control" style="height: 150%;">
                    <option value="" disabled selected>Pilih Salah Satu</option>
                    @foreach($kapalRO as $kpl)
                    <option value="{{$kpl->ves_id}}">{{$kpl->ves_name}} -- {{$kpl->voy_out}}</option>
                    @endforeach
                    <option value="PELINDO">Relokasi Pelindo</option>
                  </select>
              </div>
            </div>
            <div class="col-12">
              <h5>Add Container</h5>
              <p>Masukan Nomor Container</p>
            </div>
            <div class="col-12" id="selector">
              <label for="">Container Number</label>
              <select name="container[]" id="containerSelector" class="js-example-basic-multiple form-control" style="height: 150%;" multiple="multiple">
                <option disabled value="">Pilih Salah Satu</option>
               
              </select>
            </div>
            <div class="col-12" id="selectorView" style="display: none !important;">
              <select name="" id="containerSelectorView" disabled class="js-example-basic-multiple form-control" style="height: 150%;" multiple="multiple">
                <option disabled value="">Pilih Salah Satu</option>
              </select>
            </div>
          </div>

          <div class="row mt-5">
          <h5>Discount</h5>
          <p>Di Isi dengan Persen (%)</p>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="">Discount OSK</label>
              <p>Di Isi dengan Nomial !!</p>
              <input type="text" class="form-control" name="discount_dsk" value="0">
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="">Discount OS</label>
              <p>Di Isi dengan Nomial !!</p>
              <input type="text" class="form-control" name="discount_ds" value="0">
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
                  <!-- <a onclick="checkBeacukaiImport();" class="btn btn-primary" type="button" id="beacukaicheck"><i class="fa fa-magnifying-glass"></i> Check</a> -->
                  <button type="button" class="btn btn-primary cekDokumen" id="cekDokumen"><i class="fa fa-magnifying-glass"></i> Check</button>
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

            
            $('#orderService').on('change', function() {
                let id = $('#orderService').val();
            
                $.ajax({
                    type: 'get',
                    url: "{{ route('getOrder') }}",
                    data: { id: id },
                    cache: false,
                    success: function(response) {
                        $('#order').val(response.data.order);
                        $('#containerSelector').empty();
                    
                        if (response.data.order == "SPPS") {
                            $('#do_fill').hide();
                            $('#roDok').show();
                            $('#kapalManual').hide();
                        } else if (response.data.order == "SPPSD") {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Saved!',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    $('#do_fill').hide();
                                    $('#roDok').hide();
                                    $('#kapalManual').show();
                                    $.each(response.cont, function(index, item) {
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
                        } else {
                            $('#do_fill').show();
                            $('#roDok').hide();
                            $('#kapalManual').hide();
                        }
                    },
                    error: function(data) {
                        console.log('error:', data);
                    },
                });
            });




    // Do
        $('#booking_no'). on('change', function(){
                let bookingNo = $('#booking_no').val();
                let os = $('#orderService').val();

                $.ajax({
                    type: 'get',
                    url: "{{ route('getDOdataExport')}}",
                    data : {bookingNo : bookingNo,
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
                              $('#boln').val(response.kapal.ves_name);
                              $('#do_exp_date').val(response.kapal.etd_date);
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