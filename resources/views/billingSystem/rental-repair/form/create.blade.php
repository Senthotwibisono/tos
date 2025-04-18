@extends ('partial.invoice.main')

@section('content')

<div class="page-heading">
  <h3>{{ $title }}</h3>
  <p>Masukan Data untuk form Delivery</p>
</div>
<div class="page-content mb-5">
  <section class="row">
    <form action="{{ route('rental-repair-create-post')}}" method="post" id="updateForm" enctype="multipart/form-data">
      @csrf
      <div class="card">
        <div class="card-body">
          <div class="row">
            <!-- Customer Information -->
            <div class="col-12">
              <h5>Customer Information</h5>
              <p>Masukan Data Customer</p>
            </div>
            <div class="col-4">
              <label for="customer">Customer</label>
              <div class="form-group">
                <select required name="customer" id="customer" class="js-example-basic-single form-control">
                  <option disabled selected value>Pilih Satu</option>
                  @foreach($customer as $cust)
                    <option value="{{ $cust->id }}">{{ $cust->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-4">
              <div class="form-group">
                <label for="npwp">NPWP</label>
                <input required type="text" class="form-control" id="npwp" name="npwp" placeholder="Pilih Customer Dahulu!.." readonly>
              </div>
            </div>
            <div class="col-4">
              <div class="form-group">
                <label for="address">Address</label>
                <input required type="text" class="form-control" id="address" name="address" placeholder="Pilih Customer Dahulu!.." readonly>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label for="orderService">Order Service</label>
                <select name="order_service" class="form-select" required id="orderService">
                  <option value="" default selected disabled>Pilih Salah Satu..</option>
                  @foreach($orderService as $os)
                    <option value="{{ $os->id }}">{{ $os->name }}</option>
                  @endforeach
                </select>
                <input type="hidden" id="order">
              </div>
            </div>
          </div>

          <!-- Add Container -->
          <div class="row mt-5">
            <div class="col-12">
              <h5>Add Container</h5>
              <p>Pilih Kapal Terlebih Dahulu, Kemudian Masukan Nomor Container</p>
            </div>
            <div class="col-12">
              <label for="kapalPlugging">Vessel</label>
              <select name="ves_id" id="kapalPlugging" class="js-example-basic-single form-control" style="height: 150%;">
                <option disabled selected value>Pilih Satu</option>
                @foreach($vessel as $ves)
                  <option value="{{ $ves->ves_id }}">{{ $ves->ves_name }} -- {{ $ves->voy_out }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-12">
              <label for="" id="selector" >Jumlah Container</label>
              <label for="" id="palka" style="display:none;">Jumlah Palka</label>
              <input type="number" class="form-control" name="palka">
            </div>
            <!-- <div class="col-12" id="palka" style="display:none;">
              <label for="">Jumlah Palka</label>
              <input type="number" class="form-control" name="palka">
            </div> -->
          </div>
          <div class="col-12">
            <label for="">Tarif</label>
            <input type="number" class="form-control" name="tarif">
          </div>
          
          <div class="col-12">
            <label for="">Keterangan</label>
            <input type="text" class="form-control" name="keterangan">
          </div>
          
          <!-- Discount -->
          <div class="row mt-5">
            <h5>Discount</h5>
            <p>Di Isi dengan Persen (%)</p>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="discount_ds">Discount OS</label>
                  <p>Di Isi dengan Nomial !!</p>
                <input type="text" class="form-control" name="discount_ds" value="0">
              </div>
            </div>
          </div>

          <!-- Submit Buttons -->
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
  $(document).ready(function() {
    // Initialize Select2
    $('.js-example-basic-single').select2();
    $('.js-example-basic-multiple').select2();

    // Customer Change Event
    $('#customer').on('change', function() {
      let id = $(this).val();
      $.ajax({
        type: 'GET',
        url: "{{ route('getCust') }}",
        data: { id: id },
        success: function(response) {
          $('#npwp').val(response.data.npwp);
          $('#address').val(response.data.alamat);
        },
        error: function(data) {
          console.log('error:', data);
        }
      });
    });

    // Vessel Change Event
    // $('#kapalPlugging').on('change', function() {
    //   let kapal = $(this).val();
    //   $.ajax({
    //     type: 'GET',
    //     url: "/rental&repair-create-container",
    //     data: { kapal: kapal },
    //     success: function(response) {
    //       if (response.success) {
    //         Swal.fire({
    //           icon: 'success',
    //           title: 'Saved!',
    //           timer: 2000,
    //           showConfirmButton: false
    //         }).then(() => {
    //           $('#containerSelector').empty();
    //           $.each(response.data, function(index, item) {
    //             $('#containerSelector').append($('<option>', {
    //               value: item.container_key,
    //               text: item.container_no
    //             }));
    //           });
    //         });
    //       } else {
    //         Swal.fire({
    //           icon: 'error',
    //           title: 'Error',
    //           text: response.message
    //         }).then(() => {
    //           window.location.reload();
    //         });
    //       }
    //     },
    //     error: function(data) {
    //       console.log('error:', data);
    //       Swal.fire({
    //         icon: 'error',
    //         title: 'Oops...',
    //         text: 'Terjadi kesalahan saat memuat data. Silakan coba lagi nanti.',
    //       });
    //     }
    //   });
    // });

    $('#orderService'). on('change', function(){
        let id = $('#orderService').val();
        $.ajax({
            type: 'get',
            url: "/renta&repair/osData",
            data : {id : id},
            cache: false,
            
            success: function(response){
                $('#order').val(response.data.order);
                $('#containerSelector').empty();
                // $('#booking_no').empty();
                // $('#RoNo').empty();
                  if (response.data.order == "P") {
                    $('#selector').hide();
                    $('#palka').show();
                  } else {
                    $('#selector').show();
                    $('#palka').hide();
                  }
            },
            error: function(data){
                console.log('error:',data)
            },
        })
    })

    // Container Selector Change Event
    // $('#containerSelector').on('change', function() {
    //   $('#tarifContainer').empty();
    //   const selectedContainers = Array.from(this.selectedOptions).map(option => ({
    //     value: option.value,
    //     label: option.text
    //   }));
    //   selectedContainers.forEach(container => {
    //     const tarifInput = $('<div>').addClass('tarif-input').html(`
    //       <label for="tarif-${container}">Tarif for ${container.label}</label>
    //       <input type="text" id="tarif-${container}" name="tarif-${container.value}" class="form-control" placeholder="Enter tarif for ${container.label}">
    //     `);
    //     $('#tarifContainer').append(tarifInput);
    //   });
    // });
  });
</script>
@endsection
