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
    <form action="" method="post" id="updateForm" enctype="multipart/form-data">
      @CSRF
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-12">
              <h5>Customer Information</h5>
              <p>Masukan Data Customer</p>
            </div>
            <div class="row">
              <div class="col-6">
                <div>
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
                <div>
                  <div class="form-group">
                    <label for="">NPWP</label>
                    <input required type="text" class="form-control" id="npwp" name="npwp" placeholder="Pilih Customer Dahulu!.." readonly>
                  </div>
                </div>

              </div>
              <div class="col-6">
                <div class="form-group">
                  <label for="">Address</label>
                  <!-- <input required type="text" class="form-control" id="address" name="address" placeholder="Pilih Customer Dahulu!.." readonly> -->
                  <textarea class="form-control" id="address" name="address" cols="10" rows="4" readonly></textarea>
                </div>
              </div>
            </div>
            
            <div class="col-12">
              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <label>Order Service</label>
                    <select name="order_service" class="js-example-basic-single form-control" style="height: 150%;" required id="orderService">
                      <option value="" default selected disabled>Pilih Salah Satu..</option>
                     @foreach($orderService as $os)
                     <option value="{{$os->id}}">{{$os->name}}</option>
                     @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label for="">Booking No</label>
                    <div class="input-group">
                      <select  name="booking_no" id="booking_no"  class="select2 form-select" style="height: 150%;">
                        <option value="" disabled selected>Pilih Salah Satu</option>
                      </select>
                      <button type="button" class="btn btn-primary" id="buttonBooking"><i class="fas fa-search"></i></button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-4">
                <div class="form-group">
                  <label for="">Vessel</label>
                  <select name="ves_id" id="ves_id" class="js-example-basic-single form-select" style="height: 150%;">
                    <option disabled selected value>Pilih Satu!</option>
                    @foreach($vessels as $vessel)
                      <option value="{{$vessel->ves_id}}">{{$vessel->ves_name}} | {{$vessel->voy_out}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="">Estimate Deparature</label>
                  <input type="datetime-local" id ="etd" class="form-control">
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="">Clossing Date</label>
                  <input type="datetime-local" class="form-control" id="clossingDate">
                </div>
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
          </div>

          <div class="row mt-5">
          <h5>Discount</h5>
          <p>Di Isi dengan Persen (%)</p>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="">Discount OSK</label>
              <p>Di Isi dengan Nomial !!</p>
              <input type="text" class="form-control" name="discount_dsk" id="discount_dsk" value="0">
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="">Discount OS</label>
              <p>Di Isi dengan Nomial !!</p>
              <input type="text" class="form-control" name="discount_ds" id="discount_ds" value="0">
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
            <button type="button" id="updateButton" onclick="submitForm()" class="btn btn-success">Submit</button>
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
    $(document).ready(function(){

        $('#customer').on('change', function(){
            customersExport();
        });

        $('#ves_id').on('change', function(){
            vesselExport();
        });

        $('#booking_no').on('change', function(){
          searchByBookingNo();
        });

        $('#buttonBooking').on('click', function(){
          searchByBookingNo()
        });

    })

    function openWindow(url) {
        window.open(url, '_blank', 'width=1000,height=1000');
    }
</script>
<script>
    async function customersExport() {
        showLoading();
        const cust_id = document.getElementById('customer').value;
        // console.log(cust_id);
        const url = '{{route('api.customer.GetData-customer')}}';
        const response = await fetch(url, {
            method : 'POST',
            headers: {
                "Content-Type": "application/json",
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Optional kalau butuh CSRF untuk GET
            },
            body: JSON.stringify({ cust_id: cust_id }),
        });
        console.log(response);
        hideLoading();
        if (response.ok) {
            const hasil = await response.json();
            if (hasil.success) {
                $('#npwp').val(hasil.data.npwp);
                $('#address').val(hasil.data.alamat);
            }
        }
    }

    async function vesselExport() {
        showLoading();
        const ves_id = document.getElementById('ves_id').value;
        // console.log(ves_id);
        const url = '{{route('api.customer.GetData-vessel')}}';
        const response = await fetch(url, {
            method : 'POST',
            headers: {
                "Content-Type": "application/json",
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Optional kalau butuh CSRF untuk GET
            },
            body: JSON.stringify({ ves_id: ves_id }),
        });
        console.log(response);
        hideLoading();
        if (response.ok) {
            const hasil = await response.json();
            if (hasil.success) {
               $('#voy_in').val(hasil.data.voy_in);
               $('#voy_out').val(hasil.data.voy_out);
               $('#etd').val(hasil.data.etd_date);
               $('#clossingDate').val(hasil.data.clossing_date);
            }
        }
    }

    async function searchByBookingNo() {
        Swal.fire({
            icon: 'warning',
            title: 'Apakah nomor booking yg anda masukkan sudah benar?',
            showCancelButton: true,
        }).then( async(result) => {
            if (result.isConfirmed) {
                const bookingNo = document.getElementById('booking_no').value;
                const userId = {{Auth::user()->id}};
                console.log(userId);
                if (!bookingNo || bookingNo === null) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Anda Belum Mengisi',
                    });
                    return;
                }
                showLoading();
                const url = '{{route('api.customer.getBookingGlobal')}}';
                const response = await fetch(url, {
                    method : 'POST',
                    headers: {
                        "Content-Type": "application/json",
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Optional kalau butuh CSRF untuk GET
                    },
                    body: JSON.stringify({ booking_no: bookingNo, userId:userId }),
                });
                console.log(response);
                hideLoading();
                if (response.ok) {
                    const hasil = await response.json();
                    if (hasil.success) {
                        Swal.fire({
                            icon: 'success'
                        }).then(async() => {
                            $('#ves_id').val(hasil.data.vessel.ves_id).trigger('change');

                            $('#containerSelector').empty();
                          
                            $.each(hasil.data.items, function(index, item) {
                                $('#containerSelector').append($('<option>', {
                                    value: item.container_key,
                                    text: item.container_no,
                                    selected: 'selected'
                                }));
                            });
                        });
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Something wrong',
                            text: hasil.message,
                        }).then(() => {
                            location.reload();
                        });
                    }
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: response.status,
                        text: response.statusText,
                    }).then(() => {
                        location.reload();
                    });
                }
            }
        });
    }

    async function submitForm() {
      Swal.fire({
        icon: 'warning',
        title: 'Are you sure?',
        text: 'Pastikan data yang anda isi sudah benar!!!',
        showCancelButton: true,
      }).then( async(result) => {
        if (result.isConfirmed) {
          showLoading();
          const customer = document.getElementById('customer').value;
          const service = document.getElementById('orderService').value;
          const bookingNo = document.getElementById('booking_no').value;
          const vessel = document.getElementById('ves_id').value;
          const containerKeySelect = document.getElementById('containerSelector');
          const container_key = Array.from(containerKeySelect.selectedOptions).map(option => option.value);
          const discountDSK = document.getElementById('discount_dsk').value; 
          const discountDS = document.getElementById('discount_ds').value;
          const data = {
            customer,
            service,
            bookingNo,
            vessel,
            container_key,
            discountDSK,
            discountDS
          };
          const url = "{{route('invoiceService.export.formPost')}}";
          const response = await fetch(url, {
            method : 'POST',
            headers: {
                "Content-Type": "application/json",
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Optional kalau butuh CSRF untuk GET
            },
            body: JSON.stringify(data),
          });
          hideLoading();
          if (response.ok) {
            const hasil = await response.json();
            if (hasil.success) {
              Swal.fire({
                icon: 'success',
                title: 'Data berhasil di simpan'
              }).then(() => {
                const href = '/billing/export/delivey-system/formInvoice/' + hasil.data.id;
                location.href = href;
              });
            }else{
              errorHasil(hasil);
              return;
            }
          }else{
            errorResponse(response);
            return;
          }
        }else{
          return;
        }
      })
    }
</script>

<script>
  $(document).ready(function(){
    $('#booking_no').select2({
        theme: "bootstrap-5",
        placeholder: "Pilih Booking Number",
        allowClear: true,
        ajax: {
            url: "{{route('invoiceService.export.getBookingNo')}}",
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
                            id: item, // ID utama
                            text: item, // Teks yang ditampilkan di dropdown
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

@endsection