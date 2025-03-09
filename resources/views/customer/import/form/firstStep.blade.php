@extends('partial.customer.main')
@section('custom_styles')

@endsection

@section('content')

<div class="page-header">
    <h4>{{$title}}</h4>
</div>

<div class="page-content mb-5">
  <section class="row">
    <form action="/customer-import/storeFormStep1" method="POST" id="updateForm" enctype="multipart/form-data">
      @CSRF
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-12">
              <h5>Customer Information</h5>
              <p>Masukan Data Customer</p>
            </div>
            <div class="col-3">
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
            <div class="col-3">
              <div class="form-group">
                <label for="">NPWP</label>
                <input required type="text" class="form-control" id="npwp" value="{{$form->customer->npwp ?? null}}" name="npwp" placeholder="Pilih Customer Dahulu!.." readonly>
              </div>
            </div>
            <div class="col-3">
              <div class="form-group">
                <label for="">Tanggal Discharge</label>
                <input required name="disc_date" id="disc_date" type="date"  value="{{ isset($form->disc_date) ? \Carbon\Carbon::parse($form->disc_date)->format('Y-m-d') : null }}" class="form-control flatpickr-range mb-3" placeholder="09/05/2023">
              </div>
            </div>
            <div class="col-3">
              <div class="form-group">
                <label for="">Rencana Keluar</label>
                <input required name="exp_date" id="exp_date" type="date" class="form-control flatpickr-range mb-3" value="{{ isset($form->expired_date) ? \Carbon\Carbon::parse($form->expired_date)->format('Y-m-d') : null ?? $expired ?? null}}" placeholder="09/05/2023">
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label for="">Address</label>
                <input required type="text" class="form-control" id="address" name="address" value="{{$form->customer->alamat?? null}}" placeholder="Pilih Customer Dahulu!.." readonly>
                <!-- <textarea class="form-control" id="address" name="address" cols="10" rows="4"></textarea> -->
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label>Order Service</label>
                <select name="order_service" class="form-select js-example-basic-single" required id="orderService">
                  <option value="" default selected disabled>Pilih Salah Satu..</option>
                 @foreach($orderService as $os)
                 <option value="{{$os->id}}" {{$form->os_id == $os->id ? 'selected' : ''}}>{{$os->name}}</option>
                 @endforeach
                </select>
              </div>
            </div>

            <div class="col-6">
              <div class="form-group">
                <label>Vessel Service</label>
                <select name="ves_id" class="js-example-basic-multiple form-select" required id="vessel">
                  <option value="" default selected disabled>Pilih Salah Satu..</option>
                 @foreach($ves as $os)
                 <option value="{{$os->ves_id}}" {{$form->ves_id == $os->ves_id ? 'selected' : ''}}>{{$os->ves_name}}--{{$os->voy_out}}</option>
                 @endforeach
                 <option value="PRLINDO">Relokasi Pelindo</option>
                </select>
              </div>
            </div>
          </div>
          <div class="row mt-5" id="do_fill">
            <div class="col-12">
              <h5>Information Shipping Agent</h5>
              <p>Masukan Data Shipping dan Pilih Metode Do Checking</p>
            </div>
              <!-- <div class="col-12 col-md-4" style="display: none !important;" id="do_manual">
                <div class="form-group">
                  <label for="">Do Number</label>
                  <input name="do_number" type="text" class="form-control" placeholder="Do Number">
                </div>
              </div> -->
              <div class="col-12 col-md-4" id="do_auto">
                <div class="form-group">
                  <label for="">Do Number</label>
                  <div class="input-group mb-3">
                    <input name="do_number" id="do_number_type" type="text" class="form-control" placeholder="DO910934" value="{{$form->doOnline->do_no ?? null}}">
                    <input name="do_number_auto" id="do_number_id" type="hidden" class="form-control" placeholder="DO910934" value="{{$form->do_id ?? '-'}}">
                    <a class="btn btn-primary cekDokumen" type="button" id="doNumberCheck"><i class="fa fa-magnifying-glass"></i> Check</a>
                  </div>
                </div>
              </div>

          
            <div class="col-12 col-md-4">
              <div class="form-group">
                <label for="">Do Expired</label>
                <input name="do_exp_date" id="do_exp_date" value="{{$form->doOnline->expired ?? null}}" required readonly type="date" class="form-control flatpickr-range mb-3" placeholder="09/05/2023">
              </div>
            </div>
            <div class="col-12 col-md-4">
              <div class="form-group">
                <label for="">Bill of Loading Number</label>
                <input name="boln" id="boln" required readonly type="text" class="form-control" value="{{$form->doOnline->bl_no ?? null}}" placeholder="Bill Of Loading Number">
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

          <!-- <div class="row mt-5">
          <h5>Discount</h5>
          <p>Di Isi dengan Persen (%)</p>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="">Discount DSK</label>
                <p>Di Isi dengan Nomial !!</p>
              <input type="text" class="form-control" name="discount_dsk" value="{{$form->discount_dsk ?? 0}}">
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="">Discount OS</label>
                <p>Di Isi dengan Nomial !!</p>
              <input type="text" class="form-control" name="discount_ds" value="{{$discount->ds ?? 0}}">
            </div>
          </div>
         </div> -->

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
          $('#do_number_auto'). on('change', function(){
                let id = $('#do_number_auto').val();
                let os = $('#orderService').val();
                let ves = $('#vessel').val();

                $.ajax({
                    type: 'get',
                    url: "{{ route('getDOdata')}}",
                    data : {id : id,
                            os:os,
                            ves:ves,
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
                                $('#do_exp_date').val(response.data.expired);
                                $('#boln').val(response.data.bl_no);
                                $('#exp_date').val(response.expired);
                                $('#containerSelector').empty();
                            
                                $.each(response.cont, function(index, item) {
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
              let doNo = $('#do_number_type').val();
              let os = $('#orderService').val();
              let ves = $('#vessel').val();
              let customerId = $('#customer').val();
              swal.showLoading();
              $.ajax({
                  type: 'get',
                  url: "{{ route('doManual')}}",
                  data : {doNo : doNo,
                          os:os,
                          ves:ves,
                          customerId: customerId,
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
                              $('#do_exp_date').val(response.data.expired);
                              $('#boln').val(response.data.bl_no);
                              $('#do_number_id').val(response.data.id);
                              $('#disc_date').val(response.discDate);
                              $('#containerSelector').empty();
                          
                              $.each(response.cont, function(index, item) {
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
document.addEventListener("DOMContentLoaded", function() {
    let discDateInput = document.getElementById("disc_date");
    let expDateInput = document.getElementById("exp_date");

    function updateExpDateMin() {
        let discDate = new Date(discDateInput.value);
        if (!isNaN(discDate.getTime())) {
            let minExpDate = new Date(discDate);
            minExpDate.setDate(minExpDate.getDate() + 4);
            
            let minDateStr = minExpDate.toISOString().split("T")[0];
            expDateInput.min = minDateStr;

            // Jika tanggal exp_date saat ini kurang dari batas min, reset nilainya
            if (expDateInput.value < minDateStr) {
                expDateInput.value = minDateStr;
            }
        }
    }

    // Saat disc_date berubah, atur batas minimal untuk exp_date
    discDateInput.addEventListener("change", updateExpDateMin);

    // Jalankan saat halaman dimuat (jika disc_date sudah ada)
    updateExpDateMin();
});
</script>


<!-- <script>
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
</script> -->
@endsection