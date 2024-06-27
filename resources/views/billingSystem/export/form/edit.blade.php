@extends ('partial.invoice.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Masukan Data untuk form Delivery</p>
</div>
<div class="page-content mb-5">
  <section class="row">
    <form action="{{ route('updateFormExport')}}" method="POST" id="formSubmit" enctype="multipart/form-data">
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
                    <option value="{{$cust->id}}" {{$form->cust_id == $cust->id ? 'selected' : ''}}>{{$cust->name}}</option>
                    @endforeach
                </select>
                <input type="hidden" name="form_id" value="{{$form->id}}" >
              </div>
            </div>
            <div class="col-4">
              <div class="form-group">
                <label for="">NPWP</label>
                <input required type="text" class="form-control" id="npwp" value="{{$form->customer->npwp}}" name="npwp" placeholder="Pilih Customer Dahulu!.." readonly>
              </div>
            </div>
            <div class="col-4">
              <div class="form-group">
                <label for="">Expired Date</label>
                <input required name="exp_date" id="exp_date" type="date" class="form-control flatpickr-range mb-3" value="{{$form->expired_date}}" placeholder="09/05/2023" id="expired">
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label for="">Address</label>
                <input required type="text" class="form-control" id="address" name="address" value="{{$form->customer->alamat}}" placeholder="Pilih Customer Dahulu!.." readonly>
                <!-- <textarea class="form-control" id="address" name="address" cols="10" rows="4"></textarea> -->
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label>Order Service</label>
                <select name="order_service" class="form-select js-example-basic-single" required id="orderService">
                  <option value="" default selected disabled>Pilih Salah Satu..</option>
                 @foreach($orderService as $os)
                 <option value="{{$os->id}}" {{$form->os_id == $os->id ? 'selected' : ''}}>{{$os->name}}</option>
                 @endforeach
                </select>
                <input type="hidden" id="order">
              </div>
            </div>
          </div>
          <div id="do_fill"style="display: {{ $form->Service->order == 'SP2' ? 'block' : 'none' }};">
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
                  <option value="{{$cont}}" {{$form->do_id == $cont ? 'selected' : ''}}>{{$cont}}</option>
                  @endforeach
                  </select>
                </div>
              </div>

            
            <div class="col-12 col-md-4">
              <div class="form-group">
                <label for="">Estimate Departure of Vessel</label>
                <input name="do_exp_date" id="do_exp_date"  readonly type="date-time"value="{{ $form->ves_id == 'PELINDO' ? \Carbon\Carbon::now()->format('Y-m-d\TH:i') : $form->Kapal->etd_date }}" class="form-control flatpickr-range mb-3" >
              </div>
            </div>
            <div class="col-12 col-md-4">
              <div class="form-group">
                <label for="">Vessel Name</label>
                <input name="boln" id="boln"  readonly type="text" class="form-control" value="{{ $form->ves_id == 'PELINDO' ? "PELINDO" : $form->Kapal->etd_date }}" placeholder="Bill Of Loading Number">
              </div>
            </div>

          </div>
          </div>

          <!-- RO -->
        <div id="roDok" style="display: {{ $form->Service->order == 'SPPS' ? 'block' : 'none' }};">
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
                    <option value="{{$dok->ro_no}}" {{$form->do_id == $dok->ro_no ? 'selected' : ''}}>{{$dok->ro_no}}</option>
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
                    <option value="{{$kpl->ves_id}}" {{$form->ves_id == $kpl->ves_id ? 'selected' : ''}}>{{$kpl->ves_name}} -- {{$kpl->voy_out}}</option>
                    @endforeach
                    <option value="PELINDO" {{$form->ves_id == "PELINDO" ? 'selected' : ''}}>Relokasi Pelindo</option>
                  </select>
              </div>
            </div>
          </div>
        </div>
          

          <div class="row mt-5">
            <div class="col-12">
              <h5>Add Container</h5>
              <p>Masukan Nomor Container</p>
            </div>
            <div class="col-12" id="selector">
              <label for="">Container Number</label>
              <select name="container[]" id="containerSelector" class="js-example-basic-multiple form-control" style="height: 150%;" multiple="multiple">
                <option disabled value="">Pilih Salah Satu</option>
                @foreach($containerInvoice as $container)
                    <option value="{{$container->container_key}}" selected>{{$container->container_no}}</option>
                @endforeach
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
              <input type="number" class="form-control" value='{{$form->discount_dsk}}' name="discount_dsk">
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="">Discount OS</label>
              <input type="number" class="form-control" value='{{$form->discount_ds}}' name="discount_ds">
            </div>
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
            <button type="submit" class="btn btn-success">Submit</button>
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

            $('#orderService'). on('change', function(){
                let id = $('#orderService').val();

                $.ajax({
                    type: 'get',
                    url: "{{ route('getOrder')}}",
                    data : {id : id},
                    cache: false,
                    
                    success: function(response){
                        $('#order').val(response.data.order);
                        $('#containerSelector').empty();
                        $('#booking_no').val(null);
                        $('#RoNo').val(null);
                          if (response.data.order == "SPPS") {
                            $('#do_fill').hide();
                            $('#roDok').show();
                          } else {
                            $('#do_fill').show();
                            $('#roDok').hide();
                          }
                    },
                    error: function(data){
                        console.log('error:',data)
                    },
                })
            })


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