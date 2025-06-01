@extends ('partial.invoice.main')
@section('custom_styles')
<style>
    .select2-container--bootstrap-5 .select2-selection {
    border: 1px solid rgb(0, 0, 0) !important; /* Border berwarna biru */
    border-radius: 1px; /* Agar sudutnya sedikit melengkung */
    padding: 6px; /* Tambahkan padding agar terlihat lebih rapi */
    height: 100%;
}

.select2-container--bootstrap-5 .select2-selection:focus {
    border-color: #0056b3 !important; /* Border berubah saat fokus */
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); /* Efek shadow saat fokus */
}
</style>
@endsection

@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Masukan Data untuk form Delivery</p>
</div>
<div class="page-content mb-5">
  <section class="row">
    <form action="" method="POST" id="updateForm" enctype="multipart/form-data">
      @CSRF
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-12">
              <h5>Customer Information</h5>
              <p>Masukan Data Customer</p>
            </div>
            <div class="col-6">
              <label for="">Customer</label>
              <div class="form-group">
                <select required name="customer" id="customer" class="js-example-basic-single form-control">
                    <option disabled selected value>Pilih Satu</option>
                    @foreach($customer as $cust)
                    <option value="{{$cust->id}}">{{$cust->name}}</option>
                    @endforeach
                </select>
              </div>
            </div>
            <div class="col-6">
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
            <div class="col-6">
              <div class="form-group">
                <label>Order Service</label>
                <select name="order_service" class="form-select js-example-basic-single" required id="orderService">
                  <option value="" default selected disabled>Pilih Salah Satu..</option>
                 @foreach($orderService as $os)
                 <option value="{{$os->id}}">{{$os->name}}</option>
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
                 <option value="{{$os->ves_id}}">{{$os->ves_name}}--{{$os->voy_out}}</option>
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
              <!-- <div class="col-12 col-md-4" id="do_manual" style="display: none !important;">
                <div class="form-group">
                  <label for="">Do Number</label>
                  <div class="input-group mb-3">
                    <input name="do_number" id="do_number_type" type="text" class="form-control" placeholder="DO910934">
                    <a onclick="checkDoNumber();" class="btn btn-primary" type="button" id="doNumberCheck"><i class="fa fa-magnifying-glass"></i> Check</a>
                  </div>
                </div>
              </div> -->
              <div class="col-12 col-md-4" id="do_auto">
                <div class="form-group">
                  <label for="">Do Number Auto</label>
                  <div class="input-group">
                    <select required name="do_number_auto" id="do_number_auto" class="form-control select2" style="height: 150%;">
                    </select>
                    <button type="button" class="btn btn-primary" id="doButtonSerach"><i class="fas fa-search"></i></button>
                  </div>
                </div>
              </div>

            <div class="col-12 col-md-4">
              <div class="form-group">
                <label for="">Do Expired</label>
                <input name="do_exp_date" id="do_exp_date" required readonly type="date" class="form-control flatpickr-range mb-3" placeholder="09/05/2023">
              </div>
            </div>
            <div class="col-12 col-md-4">
              <div class="form-group">
                <label for="">Bill of Loading Number</label>
                <input name="boln" id="boln" required readonly type="text" class="form-control" placeholder="Bill Of Loading Number">
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
              </select>
            </div>
          </div>
        <!-- Discount -->
         <div class="row mt-5">
          <h5>Discount</h5>
          <p>Di Isi dengan Persen (%)</p>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="">Discount DSK</label>
                <p>Di Isi dengan Nomial !!</p>
              <input type="text" class="form-control" name="discount_dsk" id="discountDSK" value="0">
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="">Discount DS</label>
                <p>Di Isi dengan Nomial !!</p>
              <input type="text" class="form-control" name="discount_ds" id="discountDS" value="0">
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
            <button type="button" id="sbumitButton" class="btn btn-success" onClick="submitForm()">Submit</button>
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
@include('billingSystem.js.jsInvoice')
<!-- <script>
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
</script> -->

<script>
  $(document).ready(function(){
    $('#customer').on('change', async function() {
      const customerId = document.getElementById('customer').value;
      showLoading();// console.log(customerId);
      const response = await changeCustomer(customerId);
      if (response) {
        $('#npwp').val(response.npwp);
        $('#address').val(response.alamat);
      }
      hideLoading();
      // console.log(response);
    })

    $('#do_number_auto').on('change', async function(){
        doProccess();
    });
    $('#doButtonSerach').on('click', async function(){
        doProccess();
    });

    async function doProccess() {
      showLoading();
        const doId = document.getElementById('do_number_auto').value;
        const vesId = document.getElementById('vessel').value;
        const customerId = document.getElementById('customer').value;
        // console.log(customerId);
        if (doId == null || doId.length < 1) {
          hideLoading();
          Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            text: 'Nomor Do tidak boleh kosong',
          });
          return;
          // location.reload();
        }
        if (vesId == null || vesId.length < 1) {
          hideLoading();
          Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            text: 'Kapal tidak boleh kosong',
          });
          return;
        }
        const data = {
          doId : doId,
          vesId : vesId,
          customerId: customerId
        };
        const response = await getDoData(data);
        // console.log(response);
        if (response.ok) {
          const hasil = await response.json();
          if (hasil.success) {
            $('#do_exp_date').val(hasil.data.doOnline.expired);
            $('#boln').val(hasil.data.doOnline.bl_no);
            $('#containerSelector').empty();
            $.each(hasil.data.items, function(index, item) {
                $('#containerSelector').append($('<option>', {
                    value: item.container_key,
                    text: item.container_no,
                    selected: 'selected'
                }));
            });
            console.log(hasil.data.customer.flagTrue);
            if (hasil.data.customer.flagTrue === 'N') {
              hideLoading();
              Swal.fire({
                icon: 'warning',
                title: 'Konfirmasi',
                text: 'Customer yang anda pilih berbeda dengan data DO Online, klik confirm jika ingin merubah customer beradasarkan DO online!!',
                showDenyButton: true,
              }).then((result) => {
                showLoading();
                if (result.isConfirmed) {
                  $('#customer').val(hasil.data.customer.dataCustomer.id).trigger('change');
                  hideLoading();
                  Swal.fire({
                    icon: 'success',
                    title: 'Sukses',
                  });
                }else{
                  hideLoading();
                  Swal.fire({
                    icon: 'success',
                    title: 'Sukses',
                  });
                  return;
                }
              });
            }else{
              hideLoading();
              Swal.fire({
                icon: 'success',
                title: 'Sukses',
              });
              return;
            }
          }else{
            hideLoading();
            errorHasil(hasil);
          }
        }else{
          hideLoading();
          errorResponse(response);
        }
    }
    
  })
  async function submitForm() {
    Swal.fire({
      icon: 'warning',
      title: 'Are You Sure?',
      text: 'Pastikan anda sudah memasukkan data yang benar',
      showCancelButton: true,
    }).then( async(result) => {
      if (result.isConfirmed) {
        showLoading();
        const customer = document.getElementById('customer').value;
        const orderService = document.getElementById('orderService').value;
        const vessel = document.getElementById('vessel').value;
        const doId = document.getElementById('do_number_auto').value;
        const container = Array.from(document.getElementById('containerSelector').selectedOptions).map(option => option.value);
        const discountDSK = document.getElementById('discountDSK').value;
        const discountDS = document.getElementById('discountDS').value;
        const data = {
          customer : customer,
          orderService : orderService,
          vessel : vessel,
          doId : doId,
          container : container,
          discountDSK : discountDSK,
          discountDS : discountDS,
        };
        if (data.container.length < 1) {
          hideLoading();
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Container tidak boleh kosong!!'
          }).then((result) => {
            // location.reload();
            return;
          });
        }
        // console.log(data);
        const response = await createForm(data);
        hideLoading();
        if (response.ok) {
          const hasil = await response.json();
          if (hasil.success) {
            Swal.fire({
              icon: 'success',
              title: 'Sukses',
            }).then(() => {
              showLoading();
              const id = hasil.data;
              const url = '{{ route("invoiceImport.form.preinvoice", ["id" => ":id"]) }}'.replace(':id', id);
              window.location.href = url;
            })
          }else{
            errorHasil(hasil);
          }
        }else{
          errorResponse(response);
        }
      } else{
        return;
      }
    });
  }
</script>

<script>
  $(document).ready(function(){
    $('#do_number_auto').select2({
        theme: "bootstrap-5",
        placeholder: "Pilih DO Online",
        allowClear: true,
        ajax: {
            url: "{{route('invoiceService.getData.doList')}}",
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    search: params.term,
                    page: params.page || 1
                };
            },
            processResults: function(response) {
                return {
                    results: $.map(response.data, function(item) {
                        return {
                            id: item.id, // ID utama
                            text: item.do_no, // Teks yang ditampilkan di dropdown
                        };
                    }),
                    pagination: {
                        more: response.more
                    }
                };
            },
            cache: true
        }
    });
  })
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
@endsection