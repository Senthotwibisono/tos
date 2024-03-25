@extends ('partial.invoice.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Management Data Billing Delivery</p>

</div>
<div class="page-content">

  <section class="row">
    <div class="col-12 mb-3">
      <a href="{{ route('extendForm')}}" type="button" class="btn btn-primary">
        <i class="fa fa-folder"></i>
        Delivery Form
      </a>
    </div>
  </section>

  

  @foreach($service as $os)
    <section class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Tabel Data Billing Delivery {{$os->name}}</h4>
            <p>Rekap Data Billing</p>
          </div>
          <div class="card-body">
          <form action="{{ route('report-invoice-extend')}}" method="GET" enctype="multipart/form-data">
              @CSRF
              <div class="row">

                <div class="col-4">
                  <div class="form-group">
                    <label>Pick Start Date Range</label>
                    <!-- <input name="start" type="date" class="form-control flatpickr-range mb-1" placeholder="09/05/2023" id="expired"> -->
                    <input type="date" name="start" class="form-control" required>
                  </div>
                </div>
                <div class="col-4">
                  <div class="form-group">
                    <label>Pick End Date Range</label>
                    <!-- <input name="end" type="date" class="form-control flatpickr-range mb-1" placeholder="09/05/2023" id="expired"> -->
                    <input type="date" name="end" class="form-control" required>
                    <input type="hidden" name="os_id" value="{{$os->id}}">

                  </div>
                </div>
                <div class="col-4 mt-4">
                  <button class="btn btn-primary" type="submit"><i class=" fa fa-file"></i> Export Active Invoice to Excel</button>
                </div>
              </div>
            </form>

            <div class="row">

              <div class="col-12">
                <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table{{$loop->iteration}}">
                  <thead>
                    <tr>
                      <th>Proforma No</th>
                      <th>Customer</th>
                      <th>Order Service</th>
                      <th>Tipe Invoice</th>
                      <th>Dibuat Pada</th>
                      <th>Status</th>
                      <th>Pranota</th>
                      <th>Invoice</th>
                      <th>Job</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach($invoice as $inv)
                    @if($inv->os_id == $os->id)
                    <tr>
                      <td>{{$inv->proforma_no}}</td>
                      <td>{{$inv->cust_name}}</td>
                      <td>{{$inv->os_name}}</td>
                      <td>{{$inv->inv_type}}</td>
                      <td>{{$inv->order_at}}</td>
                      @if($inv->lunas == "N")
                      <td>
                      <span class="badge bg-danger text-white">Not Paid</span>
                      </td>
                      @elseif($inv->lunas == "P")
                      <td>
                      <span class="badge bg-warning text-white">Piutang</span>
                      </td>
                      @else
                      <td>
                      <span class="badge bg-success text-white">Paid</span>
                      </td>
                      @endif
                      <td>
                      <a type="button" href="/pranota/extend-{{$inv->id}}" target="_blank" class="btn btn-sm btn-warning text-white"><i class="fa fa-file"></i></a>
                      </td>
                      @if($inv->lunas == "N")
                      <td>
                      <button type="button" href="/invoice/import-DSK{{$inv->id}}" target="_blank" class="btn btn-sm btn-primary text-white" disabled><i class="fa fa-dollar"></i></button>
                      </td>
                      <td>
                      <button type="button" href="/invoice/job/import-{{$inv->id}}" target="_blank" class="btn btn-sm btn-info text-white" disabeled><i class="fa fa-ship"></i></button>
                      </td>
                      @else
                      <td>
                      <a type="button" href="/invoice/extend-{{$inv->id}}" target="_blank" class="btn btn-sm btn-primary text-white"><i class="fa fa-dollar"></i></a>
                      </td>
                      <td>
                      <a type="button" href="/invoice/job/extend-{{$inv->id}}" target="_blank" class="btn btn-sm btn-info text-white"><i class="fa fa-ship"></i></a>
                      </td>
                      @endif
                      <td>
                      <button type="button" id="pay" data-id="{{$inv->id}}" class="btn btn-sm btn-success pay"><i class="fa fa-cogs"></i></button>
                      </td> <!-- Tambahkan aksi sesuai kebutuhan -->
                    </tr>
                    @endif
                   @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
@endforeach
</div>

<!-- Edit Modal Single Data Table  -->
<div class="modal fade text-left modal-borderless" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Verify Payment</h5>
        <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
          <i data-feather="x"></i>
        </button>
      </div>
      <form action="#">
        <div class="modal-body" style="height:auto;">
          <div class="form-group">
            <label for="">Jumlah Container</label>
            <input type="text" id="contInv" disabled value="kosong" class="form-control">
          </div>
          <div class="form-group">
            <label for="">Customer</label>
            <input type="text" id="customer" class="form-control" disabled value="kosong">
          </div>
          <input type="hidden" id="idInvoice">

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
            <i class="bx bx-x d-block d-sm-none"></i>
            <span class="d-none d-sm-block">Cancel</span>
          </button>
        
          <button id="payFull" type="button" class="btn btn-primary ml-1 payFull">
            Verify This Payment
          </button>
          <button id="piutang" type="button" class="btn btn-warning ml-1 piutang" >
            Piutang This Invoices
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- end of Edit Modal Single Data Table  -->




@endsection

@section('custom_js')
<script>
   $(document).on('click', '.pay', function() {
    let id = $(this).data('id');
    $.ajax({
      type: 'GET',
      url: '/extend/pay-button' + id,
      cache: false,
      data: {
        id: id
      },
      dataType: 'json',

      success: function(response) {

        console.log(response);
        $('#editModal').modal('show');
        $("#editModal #idInvoice").val(response.data.id);
        $("#editModal #customer").val(response.data.cust_name);
        $("#editModal #contInv").val(response.data.ctr_20 + response.data.ctr_21 + response.data.ctr_40 + response.data.ctr_42 );

        if (response.data.lunas === 'Y') {
        // Jika lunas, nonaktifkan tombol "Verify This Payment"
            $('#payFull').prop('disabled', true);
            $('#piutang').prop('disabled', true);

        }else if(response.data.lunas === 'P') {
            // Jika belum lunas, aktifkan tombol "Verify This Payment"
            $('#payFull').prop('disabled', false);
            $('#piutang').prop('disabled', true);

        }else{
          $('#payFull').prop('disabled', false);
            $('#piutang').prop('disabled', false);
        }
      

      },
      error: function(data) {
        console.log('error:', data)


      }



    });

  });
</script>


<script>
  $(document).on('click', '.payFull', function(e) {
    e.preventDefault();

    var data = {
      'inv_id': $('#idInvoice').val(),
   
    }
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    Swal.fire({
      icon: 'question',
      title: 'Do you want to save the changes?',
      showDenyButton: false,
      showCancelButton: true,
      confirmButtonText: 'Save',
      denyButtonText: `Don't save`,
    }).then((result) => {
      if (result.isConfirmed) {


        $.ajax({
          type: 'POST',
          url: '/invoice/extend-payFullExtend',
          data: data,
          cache: false,
          dataType: 'json',
          success: function(response) {
            console.log(response);
                        if (response.success) {
                            Swal.fire('Saved!', '', 'success')
                            .then(() => {
                            // Memuat ulang halaman setelah berhasil menyimpan data
                            window.location.reload();
                        });
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
          },
          error: function(response) {
            var errors = response.responseJSON.errors;
            if (errors) {
              var errorMessage = '';
              $.each(errors, function(key, value) {
                errorMessage += value[0] + '<br>';
              });
              Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: errorMessage,
              });
            } else {
              console.log('error:', response);
            }
          },
        });
      } else if (result.isDenied) {
        Swal.fire('Changes are not saved', '', 'info');
      }
    });
  });
</script>

<script>
  $(document).on('click', '.piutang', function(e) {
    e.preventDefault();

    var data = {
      'inv_id': $('#idInvoice').val(),
   
    }
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    Swal.fire({
      icon: 'question',
      title: 'Do you want to save the changes?',
      showDenyButton: false,
      showCancelButton: true,
      confirmButtonText: 'Save',
      denyButtonText: `Don't save`,
    }).then((result) => {
      if (result.isConfirmed) {


        $.ajax({
          type: 'POST',
          url: '/invoice/extend-piutangExtend',
          data: data,
          cache: false,
          dataType: 'json',
          success: function(response) {
            console.log(response);
                        if (response.success) {
                            Swal.fire('Saved!', '', 'success')
                            .then(() => {
                            // Memuat ulang halaman setelah berhasil menyimpan data
                            window.location.reload();
                        });
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
          },
          error: function(response) {
            var errors = response.responseJSON.errors;
            if (errors) {
              var errorMessage = '';
              $.each(errors, function(key, value) {
                errorMessage += value[0] + '<br>';
              });
              Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: errorMessage,
              });
            } else {
              console.log('error:', response);
            }
          },
        });
      } else if (result.isDenied) {
        Swal.fire('Changes are not saved', '', 'info');
      }
    });
  });
</script>
@endsection