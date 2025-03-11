@extends('partial.customer.main')
@section('custom_styles')
<style>
    .select2-container--bootstrap-5 .select2-selection {
    border: 1px solid rgb(0, 0, 0) !important; /* Border berwarna biru */
    border-radius: 5px; /* Agar sudutnya sedikit melengkung */
    padding: 6px; /* Tambahkan padding agar terlihat lebih rapi */
}

.select2-container--bootstrap-5 .select2-selection:focus {
    border-color: #0056b3 !important; /* Border berubah saat fokus */
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); /* Efek shadow saat fokus */
}
</style>
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

          <!-- Old Invoice -->
          <div class="row mt-5" id="do_fill">
            <div class="col-12">
              <h5>Information Old Invoice</h5>
            </div>
            
              <div class="col-12" id="do_auto">
                <div class="form-group">
                  <label for="">Old Invlice</label>
                    <select name="oldInvoice" id="oldInvoice" class="form-select select2" placeholder="Masukan beberapa Karakter Terlebih Dahulu">
                        <option disabled selected value>Pilih Satu</option>
                    </select> 
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
$(function(){
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  })
  
  // OldInvoiceChange
  $(function(){
    $('#oldInvoice').on('change', function(){
      let oldId = $('#oldInvoice').val();
      console.log('id terpilih adalah : ' + oldId);

    })
  })

})
</script>

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

<script>
    $(document).ready(function() {
        $('#oldInvoice').select2({
            theme: "bootstrap-5", // Jika pakai Bootstrap 5
            placeholder: "Pilih Old Invoice",
            allowClear: true,
            ajax: {
                url: '/customer-extend/getOldInvoice', // Route yang menangani permintaan data
                dataType: 'json',
                delay: 250, // Menunda permintaan untuk menghindari terlalu banyak request
                data: function(params) {
                    return {
                        search: params.term, // Mengambil keyword pencarian
                        page: params.page || 1 // Paginasi
                    };
                },
                processResults: function(response) {
                    return {
                        results: $.map(response.data, function(item) {
                            return {
                                id: item.id,
                                text: item.inv_no // Sesuaikan dengan kolom yang ingin ditampilkan
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
    });
</script>
@endsection