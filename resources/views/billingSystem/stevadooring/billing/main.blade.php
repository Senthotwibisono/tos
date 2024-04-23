@extends ('partial.invoice.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Management Data Billing Delivery</p>

</div>
<div class="page-content">

  <section class="row">
    <div class="col-12 mb-3">
      <a href="{{ route('index-stevadooring-listForm')}}" type="button" class="btn btn-primary">
        <i class="fa fa-folder"></i>
        Delivery Form
      </a>
    </div>
  </section>

  

  
    <section class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Tabel Data Billing Stevadooring</h4>
            <p>Rekap Data Billing</p>
          </div>
          <div class="card-body">
            <form action="{{ route('report-invoice-stevadooring')}}" method="GET" enctype="multipart/form-data">
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
                  </div>
                </div>
                <div class="col-4 mt-4">
                  <button class="btn btn-primary" type="submit"><i class=" fa fa-file"></i> Export Active Invoice to Excel</button>
                </div>
              </div>
            </form>

            <div class="row">

              <div class="col-12">
                <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
                  <thead>
                    <tr>
                      <th>Proforma No</th>
                      <th>Customer</th>
                      <th>Dibuat Pada</th>
                      <th>Status</th>
                      <th>Pranota</th>
                      <th>Invoice</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($inv as $header)
                    <tr>
                      <td>{{$header->proforma_no}}</td>
                      <td>{{$header->cust_name}}</td>
                      <td>{{$header->created_at}}</td>
                      @if($header->lunas == "N")
                      <td>
                      <span class="badge bg-danger text-white">Not Paid</span>
                      </td>
                      @elseif($header->lunas == "P")
                      <td>
                      <span class="badge bg-warning text-white">Piutang</span>
                      </td>
                      @else
                      <td>
                      <span class="badge bg-success text-white">Paid</span>
                      </td>
                      @endif
                      <td>
                        <a type="button" href="/pranota/stevadooring-{{$header->id}}" target="_blank" class="btn btn-sm btn-warning text-white"><i class="fa fa-file"></i></a>
                      </td>
                      @if($header->lunas == "N")
                      <td>
                      <button type="button" href="" target="_blank" class="btn btn-sm btn-primary text-white" disabled><i class="fa fa-dollar"></i></button>
                      </td>
                      @else
                      <td>
                        <a type="button" href="/invoice/stevadooring-{{$header->id}}" target="_blank" class="btn btn-sm btn-primary text-white"><i class="fa fa-dollar"></i></a>
                      </td>
                      @endif
                      <td>
                        <button type="button" id="pay" data-id="{{$header->id}}" class="btn btn-sm btn-success pay"><i class="fa fa-cogs"></i></button>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

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
            <label for="">Jumlah Tagihan</label>
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
      url: '/stevadooring/pay-button' + id,
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
        $("#editModal #contInv").val(number_format(response.data.grand_total, 2, '.', ','));

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
  function number_format(number, decimals, dec_point, thousands_sep) {
    // Mengonversi ke bilangan bulat jika tidak ada desimal
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Menyusun tanda desimal
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
  }

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
          url: '/invoice/stevadooring-payFull',
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
          url: '/invoice/stevadooring-piutang',
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