@extends ('partial.invoice.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Masukan Data untuk form Delivery</p>
</div>
<div class="page-content mb-5">
  <section class="row">
    <form action="{{ route('extendPostForm')}}" method="POST" id="formSubmit" enctype="multipart/form-data">
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
                <label for="">Expired Date</label>
                <input required name="exp_date" id="exp_date" type="date" class="form-control flatpickr-range mb-3" placeholder="{{$now}}">
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label for="">Address</label>
                <input required type="text" class="form-control" id="address" name="address" placeholder="Pilih Customer Dahulu!.." readonly>
                <!-- <textarea class="form-control" id="address" name="address" cols="10" rows="4"></textarea> -->
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
                <option value="{{$os->id}}">{{$os->name}}</option>
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
              <select name="inv_id"  class="js-example-basic-single form-control" id="invId" style="height: 150%;">
                <option disabeled selected value>Pilih Satu !</option>
               @foreach($oldInv as $inv)
                <option value="{{$inv->form_id}}">{{$inv->inv_no}}</option>
               @endforeach
              </select>
              <!-- <input type="hidden" name="inv_no" id="inv_no"> -->
            </div>
            <br>
            <hr>
            <br>
            <div class="col-12">
              <label for="">Select Container</label>
              <select name="container_key[]" id="container" class="js-example-basic-multiple form-control" style="height: 150%;" multiple="multiple">
              <option disabeled selected value>Pilih Container !</option>
            </select>
            </div>
            <input type="hidden" id="tipe" name="tipe">
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

            $('#invId'). on('change', function(){
                let id = $('#invId').val();
               

                $.ajax({
                    type: 'get',
                    url: "{{ route('getContToExtend')}}",
                    data : {id : id,
                    
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
                                $('#container').empty();
                            
                                $.each(response.cont, function(index, item) {
                                    $('#container').append($('<option>', {
                                        value: item.container_key,
                                        text: item.container_no,
                                        selected: 'selected'
                                    }));
                                });
                                $('#tipe').val(response.tipe);
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