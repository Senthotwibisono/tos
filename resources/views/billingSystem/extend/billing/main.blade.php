@extends ('partial.invoice.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Management Data Billing Delivery</p>

</div>
<div class="page-content">

  <section class="row">
    <div class="col-12 mb-3">
      <a href="{{ route('listForm-extend')}}" type="button" class="btn btn-primary">
        <i class="fa fa-folder"></i>
        Delivery Extend
      </a>
    </div>
    <div class="card">
      <div class="card-header">
        <h4>EXport to Zahir</h4>
        <form action="{{ route('zahir-invoice-extend')}}" method="GET" enctype="multipart/form-data">
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
              <button class="btn btn-info" type="submit"><i class=" fa fa-file"></i> Export Active Invoice to CSV</button>
            </div>
          </div>
        </form>
      </div>

      <div class="card-header">
      <h4>Report Delivery</h4>
        <form action="{{ route('report-invoice-extend-All')}}" method="GET" enctype="multipart/form-data">
          <div class="row">
            <div class="col-sm-3">
              <div class="form-group">
                <label>Pick Start Date Range</label>
                <input name="start" type="date" class="form-control flatpickr-range mb-1" placeholder="09/05/2023" id="expired">
                <!-- <input type="date" name="start" class="form-control" required> -->
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label>Pick End Date Range</label>
                <input name="end" type="date" class="form-control flatpickr-range mb-1" placeholder="09/05/2023" id="expired">
                <!-- <input type="date" name="end" class="form-control" required> -->
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label for="">Invoice Type</label>
                 <div class="row">
                    <div class="col-6">
                      <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="inv_type" value="XTD" id="checkbox-dsk" checked>
                        <label class="form-check-label" for="checkbox-dsk">DS-P</label>
                      </div>
                    </div>
                 </div>
              </div>
            </div>
            <div class="col-3 mt-4">
              <button class="btn btn-primary" type="submit"><i class=" fa fa-file"></i> Export Active Invoice to Excel</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>

  
  <section class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Tabel Data Billing Extend (Belum Bayar)</h4>
            <p>Rekap Data Billing</p>
          </div>
          <div class="card-body">
            <form action="{{ route('report-invoice-extendUnpaid')}}" method="GET" enctype="multipart/form-data">
              <div class="row">

                <div class="col-4">
                  <div class="form-group">
                    <label>Pick Start Date Range</label>

                    <input type="date" name="start" class="form-control" required>
                  </div>
                </div>
                <div class="col-4">
                  <div class="form-group">
                    <label>Pick End Date Range</label>

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
                <div class="table">
                  <table class="table-hover" id="unpaidTable">
                    <thead style="white-space: nowrap;">
                      <tr>
                        <th>Bukti Bayar</th>
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
                        <th>Cancel</th>
                      </tr>
                    </thead>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Tabel Data Billing Extend (Piutang)</h4>
            <p>Rekap Data Billing</p>
          </div>
          <div class="card-body">
            <form action="{{ route('report-invoice-extendPiutang')}}" method="GET" enctype="multipart/form-data">
              <div class="row">

                <div class="col-4">
                  <div class="form-group">
                    <label>Pick Start Date Range</label>

                    <input type="date" name="start" class="form-control" required>
                  </div>
                </div>
                <div class="col-4">
                  <div class="form-group">
                    <label>Pick End Date Range</label>

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
                <div class="table">
                  <table class="table-hover" id="piutangTable">
                    <thead style="white-space: nowrap;">
                      <tr>
                        <th>Bukti Bayar</th>
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
                        <th>Cancel</th>
                        <th>Edit</th>
                      </tr>
                    </thead>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
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
          <form action="{{ route('report-invoice-extend-All')}}" method="GET" enctype="multipart/form-data">
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
                <div class="table">
                  <table class="table-hover" id="invoiceTable-{{$os->id}}">
                    <thead style="white-space: nowrap;">
                      <tr>
                        <th>Bukti Bayar</th>
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
                        <th>Cancel</th>
                        <th>Edit</th>
                      </tr>
                    </thead>
                  </table>
                </div>
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
          <button id="cancel" type="button" class="btn btn-danger ml-1 cancel" >
            Canceled This Invoices
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
$(document).ready(function() {
     // Initialize all tables with class 'dataTable-wrapper'
    $('.dataTable-wrapperIMP').each(function() {
        $(this).DataTable();
    });
    // Event delegation for delete button
    $(document).on('click', '.Delete', function() {
        var formId = $(this).data('id'); // Ambil ID dari data-id atribut

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda tidak akan bisa mengembalikan ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/billing/import/extend-deleteInvoice/' + formId, // Ganti dengan endpoint penghapusan Anda
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}' // Sertakan token CSRF untuk keamanan
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire(
                                'Dihapus!',
                                response.message, // Display success message from server
                                'success'
                            ).then(() => {
                                window.location.href = '/billing/import/extendIndex'; // Arahkan ke halaman beranda setelah penghapusan sukses
                            });
                        } else {
                            Swal.fire(
                                'Gagal!',
                                response.message, // Display error message from server
                                'error'
                            );
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        Swal.fire(
                            'Gagal!',
                            'Terjadi kesalahan saat menghapus data.',
                            'error'
                        );
                    }
                });
            }
        });
    });
});

</script>
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

<script>
  $(document).on('click', '.cancel', function(e) {
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
          url: '/invoice/extend-cancelExtend',
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
  $(document).ready(function(){
    $('#unpaidTable').DataTable({
      processing: true,
      serverSide: true,
      scrollY: '50hv',
      scrollX: true,
      ajax : {
        url : '/billing/import/extend/dataIndex',
        data: {type : 'unpaid'}
      },
      columns: [
        {data:'viewPhoto', name:'viewPhoto', classNmae:'text-center'},
        {data:'proforma', name:'proforma', classNmae:'text-center'},
        {data:'customer', name:'customer', classNmae:'text-center'},
        {data:'service', name:'service', classNmae:'text-center'},
        {data:'type', name:'type', classNmae:'text-center'},
        {data:'orderAt', name:'orderAt', classNmae:'text-center'},
        {data:'status', name:'status', classNmae:'text-center'},
        {data:'pranota', name:'pranota', classNmae:'text-center'},
        {data:'invoice', name:'invoice', classNmae:'text-center'},
        {data:'job', name:'job', classNmae:'text-center'},
        {data:'action', name:'action', classNmae:'text-center'},
        {data:'delete', name:'delete', classNmae:'text-center'},
      ],
    })


    $('#piutangTable').DataTable({
      processing: true,
      serverSide: true,
      scrollY: '50hv',
      scrollX: true,
      ajax : {
        url : '/billing/import/extend/dataIndex',
        data: {type : 'piutang'}
      },
      columns: [
        {data:'viewPhoto', name:'viewPhoto', classNmae:'text-center'},
        {data:'proforma', name:'proforma', classNmae:'text-center'},
        {data:'customer', name:'customer', classNmae:'text-center'},
        {data:'service', name:'service', classNmae:'text-center'},
        {data:'type', name:'type', classNmae:'text-center'},
        {data:'orderAt', name:'orderAt', classNmae:'text-center'},
        {data:'status', name:'status', classNmae:'text-center'},
        {data:'pranota', name:'pranota', classNmae:'text-center'},
        {data:'invoice', name:'invoice', classNmae:'text-center'},
        {data:'job', name:'job', classNmae:'text-center'},
        {data:'action', name:'action', classNmae:'text-center'},
        {data:'delete', name:'delete', classNmae:'text-center'},
        {data:'edit', name:'edit', classNmae:'text-center'},
      ],
    })

    @foreach($service as $os)
      $('#invoiceTable-{{$os->id}}').DataTable({
        processing: true,
        serverSide: true,
        scrollY: '50hv',
        scrollX: true,
        ajax : {
          url : '/billing/import/extend/dataIndex',
          data: {os_id : '{{$os->id}}'}
        },
        columns: [
          {data:'viewPhoto', name:'viewPhoto', classNmae:'text-center'},
          {data:'proforma', name:'proforma', classNmae:'text-center'},
          {data:'customer', name:'customer', classNmae:'text-center'},
          {data:'service', name:'service', classNmae:'text-center'},
          {data:'type', name:'type', classNmae:'text-center'},
          {data:'orderAt', name:'orderAt', classNmae:'text-center'},
          {data:'status', name:'status', classNmae:'text-center'},
          {data:'pranota', name:'pranota', classNmae:'text-center'},
          {data:'invoice', name:'invoice', classNmae:'text-center'},
          {data:'job', name:'job', classNmae:'text-center'},
          {data:'action', name:'action', classNmae:'text-center'},
          {data:'delete', name:'delete', classNmae:'text-center'},
          {data:'edit', name:'edit', classNmae:'text-center'},
        ],
      })
    @endforeach
    
  })
</script>

<script>
    function openWindow(url) {
        window.open(url, '_blank', 'width=600,height=800');
    }
</script>
@endsection