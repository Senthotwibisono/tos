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
                    <option disabled selected value>Plih Satu</option>
                    @foreach($customer as $cust)
                    <option value="{{$cust->id}}" {{$form->cust_id == $cust->id ? 'selected' : ''}}>{{$cust->name}}</option>
                    @endforeach
                </select>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label for="">NPWP</label>
                <input required type="text" class="form-control" id="npwp" name="npwp" placeholder="Pilih Customer Dahulu!.." readonly value="{{$form->customer->npwp ?? '-'}}">
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label for="">Address</label>
                <input required type="text" class="form-control" id="address" name="address" placeholder="Pilih Customer Dahulu!.." readonly value="{{$form->customer->alamat ?? '-'}}">
                <!-- <textarea class="form-control" id="address" name="address" cols="10" rows="4"></textarea> -->
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label for="">Old Invoice Expired</label>
                <input name="oldExpired" id="oldExpired" type="date" class="form-control flatpickr-range mb-3 oldExpired" value="{{Carbon\Carbon::parse($form->disc_date)->format('Y-m-d')}}" readonly>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label for="">Expired Date</label>
                <input required name="exp_date" id="exp_date" type="date" class="form-control flatpickr-range mb-3 expiredForm" value="{{Carbon\Carbon::parse($form->expired_date)->format('Y-m-d')}}"required>
              </div>
            </div>
          </div>
          <div class="row mt-5">
            <div class="col-12">
              <h5>Order Service</h5>
              <p>Pilih Order Service</p>
            </div>
            <div class="col-12" id="selector">
              <label for=""></label>
              <select name="order_service"  class="js-example-basic-single form-control" id="os_id" style="height: 150%;">
                <option disabeled selected value>Pilih Satu !</option>
               @foreach($OrderService as $os)
                <option value="{{$os->id}}" {{$form->os_id == $os->id ? 'selected' : ''}}>{{$os->name}}</option>
               @endforeach
              </select>
            </div>

          <div class="row mt-5">
            <div class="col-12">
              <h5>Add Invoice</h5>
              <p>Masukan Nomor Invoice Sebelumnya</p>
            </div>
            <div class="col-12" id="selector">
              <label for="">Invoice Number</label>
              <div class="input-group">
                <select name="inv_id"  class="form-select select2" id="inv_id" style="height: 150%;">
                  <option value="{{$form->oldInv->inv_no}}" selected>{{$form->oldInv->inv_no}}</option>
                  <option disabeled value>Pilih Satu !</option>
                </select>
                <button type="button" class="btn btn-primary" id="inv_id_search"><i class="fas fa-search"></i></button>
              </div>
            </div>
            <br>
            <hr>
            <br>
            <div class="col-12">
              <label for="">Select Container</label>
              <select name="container_key[]" id="container" class="js-example-basic-multiple form-control" style="height: 150%;" multiple="multiple">
                @foreach($jobs as $contianer)
                  <option value="{{$contianer->id}}" selected>{{$contianer->container_no}}</option>
                @endforeach
            </select>
            </div>
            <input type="hidden" id="tipe" name="tipe">
          </div>

          <div class="row mt-5">
          <h5>Discount</h5>
            <div class="col-sm-3">
              <label for="">Discount</label>
              <p>Di Isi dengan Nomial !!</p>
              <input type="text" class="form-control"  name="discount_ds" id="discount" value="{{$form->discount_ds}}"> 
              <input type="hidden" class="form-control" name="formId" id="formId" value="{{$form->id}}"> 
            </div>
          </div>
          <div class="row mt-5">
            <div class="col-12 text-right">
            <button type="button" id="" onClick="submitExtendForm()" class="btn btn-success">Submit</button>
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
    })

    $('#inv_id').on('change', async function(){
      oldInvoiceProccess();
    })

    $('#inv_id_search').on('click', async function(){
      oldInvoiceProccess();
    })

    async function oldInvoiceProccess() {
      showLoading();
      const id = document.getElementById('inv_id').value;
      const data = {
        id:id
      };
      console.log(id);
      const response = await getInvoiceData(data);
      if (response.ok) {
        const hasil = await response.json();
        if (hasil.success) {
          $('#container').empty();
          $.each(hasil.data.containers, function(index, item) {
              $('#container').append($('<option>', {
                  value: item.id,
                  text: item.container_no,
                  selected: 'selected'
              }));
          });
          $('#oldExpired').val(hasil.data.oldExpired).trigger('change');
          const customer = document.getElementById('customer').value;
          if (hasil.data.oldForm.cust_id != customer) {
            hideLoading();
            Swal.fire({
              icon: 'warning',
              title: 'Apakah anda akan melakukan perubahan?',
              text: 'Data customer yang anda masukan berbeda dengan data customer dari invoice terdahulu, klik confirm jika anda ingin melakukan perubahan!!',
              showCancelButton: true
            }).then((result) => {
              if (result.isConfirmed) {
                showLoading();
                $('#customer').val(hasil.data.oldForm.cust_id).trigger('change');
              }
              const order = hasil.data.order;
              checkService(order);
            })
          }else{
            const order = hasil.data.order;
            checkService(order);
          }
          hideLoading();
        }else{
          errorHasil(hasil);
        }
      }else{
        errorResponse(response);
      }
    }
  })
</script>

<script>
  function checkService(order) {
    const service = document.getElementById('os_id').value;
    console.log('order ='  + order, ', service = ' + service);
    if (order === 'SP2') {
      if (service !== '10') {
        hideLoading();
        Swal.fire({
          icon: 'warning',
          title: 'Apakah anda ingin melakukan perubahan?',
          text: 'Pilihan order service anda berbeda dengan service dari invoice sebelumnya, klik confirm jika anda ingin melakukan perubahan!!',
          showCancelButton: true,
        }).then((result) => {
          if (result.isConfirmed) {
            $('#os_id').val(10).trigger('change');
          }
        })
      }
    }else if (order === 'SPPS') {
      if (service !== '11') {
        hideLoading();
        Swal.fire({
          icon: 'warning',
          title: 'Apakah anda ingin melakukan perubahan?',
          text: 'Pilihan order service anda berbeda dengan service dari invoice sebelumnya, klik confirm jika anda ingin melakukan perubahan!!',
          showCancelButton: true,
        }).then((result) => {
          if (result.isConfirmed) {
            $('os_id').val(11).trigger('change');
          }
        })
      }
    }
  }
</script>
<script>
  $(document).ready(function(){
    $('#inv_id').select2({
        theme: "bootstrap-5",
        placeholder: "Pilih Old Invoice",
        allowClear: true,
        ajax: {
            url: "{{route('invoiceService.getData.OldInvoiceList')}}",
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
                            id: item.inv_no, // ID utama
                            text: item.inv_no, // Teks yang ditampilkan di dropdown
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
  $(document).ready(function(){
    const oldExpired = document.getElementById('oldExpired').value;
   // onChange Trigger;
    flatpickr('.expiredForm', {
      "minDate" : oldExpired,
    
    })
    console.log(oldExpired);
    flatpickr('.oldExpired', {
      "minDate" : null,
      clickOpens: false,
      dateFormat: 'Y-m-d',
    })
    
    $('#oldExpired').on('change', async function(){
      const newMinDate = document.getElementById('oldExpired').value;
      const date = new Date(newMinDate);
      date.setDate(date.getDate() + 1);
      const minDate =  date.toISOString().split('T')[0];
      console.log(minDate);
      flatpickr('.expiredForm', {
        "minDate" : minDate,
        
      })
    });
  });
</script>

<script>
  async function submitExtendForm() {
    Swal.fire({
      icon: 'warning',
      title: 'Are you sure?',
      text: 'Patikan data yang anda masukkan sudah benar',
      showCancelButton: true,
    }).then(async(result) => {
      if (result.isConfirmed) {
        showLoading();
        const data = await takeDataExtend();
        if (data) {
          submitFormExtend(data);
        }
      }else{
        return;
      }
    })
  }

  async function takeDataExtend() {
    const customer = document.getElementById('customer').value;
    const expired = document.getElementById('exp_date').value;
    const orderService = document.getElementById('os_id').value;
    const oldInvoice = document.getElementById('inv_id').value;
    const formId = document.getElementById('formId').value;
    const container = Array.from(document.getElementById('container').selectedOptions).map(option => option.value);
    if (!customer || !expired || !orderService || !oldInvoice || container.length === 0) {
      hideLoading();
        Swal.fire({
            icon: 'error',
            title: 'Data Tidak Lengkap',
            text: 'Harap isi semua data yang diperlukan!',
        });
        return; // Hentikan proses selanjutnya
    }

    const discount = document.getElementById('discount').value;
    const data = {
      customer : customer,
      expired : expired,
      orderService : orderService,
      oldInvoice : oldInvoice,
      container : container,
      discount : discount,
      formId : formId,
    };

    return data;
  }
</script>

@endsection