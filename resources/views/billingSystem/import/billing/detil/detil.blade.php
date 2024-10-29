@extends ('partial.invoice.main')


@section('content')

<div class="page-content">
  <section class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Tabel Data Billing Bongkaran {{$os->name}}</h4>
            <p>Rekap Data Billing</p>
          </div>
          <div class="card-body">
            <form action="{{ route('report-invoice-import')}}" method="GET" enctype="multipart/form-data">
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
                    <input type="hidden" name="os_id" value="{{$os->id}}">

                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                    <label for="">Invoice Type</label>
                     <div class="row">
                        <div class="col-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="inv_type[]" value="DSK" id="checkbox-dsk">
                            <label class="form-check-label" for="checkbox-dsk">DSK</label>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="inv_type[]" value="DS" id="checkbox-ds">
                            <label class="form-check-label" for="checkbox-ds">DS</label>
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

            <div class="row">

              <div class="col-12">
                <div class="table table-responsive">
                  <table class="table table-hover" id="serviceImport">
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
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <div class="footer">
        <a href="/billing/import/delivey-system" class="btn btn-primary">Back</a>
    </div>
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
          <div class="form-group">
            <label for="">Pay Couple Invoice</label>
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="couple" id="couple">
              </div>
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
    var osId ="{{$osId}}"; // Ambil osId dari hidden input

    $('#serviceImport').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/invoice/import/delivery-data/service',
            type: 'GET',
            data: {
                osId: osId // Kirimkan osId sebagai parameter
            }
        },
        columns: [
            { data: 'proforma_no', name: 'proforma_no', className: 'text-center' },
            { data: 'cust_name', name: 'cust_name', className: 'text-center' },
            { data: 'os_name', name: 'os_name', className: 'text-center' },
            { data: 'inv_type', name: 'inv_type', className: 'text-center' },
            { data: 'order_at', name: 'order_at', className: 'text-center' },
            {
                data: 'lunas',
                name: 'lunas',
                className: 'text-center',
                render: function(data, type, row) {
                    let badgeClass = '';
                    let badgeText = '';
                    switch (data) {
                        case 'N':
                            badgeClass = 'bg-danger text-white';
                            badgeText = 'Not Paid';
                            break;
                        case 'P':
                            badgeClass = 'bg-warning text-white';
                            badgeText = 'Piutang';
                            break;
                        case 'Y':
                            badgeClass = 'bg-success text-white';
                            badgeText = 'Paid';
                            break;
                        default:
                            badgeClass = 'bg-danger text-white';
                            badgeText = 'Canceled';
                            break;
                    }
                    return `<span class="badge ${badgeClass}">${badgeText}</span>`;
                }
            },
            {
                data: 'inv_type',
                name: 'inv_type',
                className: 'text-center',
                render: function(data, type, row) {
                    const link = data === 'DSK' ? `/pranota/import-DSK${row.id}` : `/pranota/import-DS${row.id}`;
                    return `<a href="${link}" target="_blank" class="btn btn-sm btn-warning text-white"><i class="fa fa-file"></i></a>`;
                }
            },
            {
                data: 'lunas',
                name: 'lunas',
                className: 'text-center',
                render: function(data, type, row) {
                    if (data === 'N') {
                        return `<button class="btn btn-sm btn-primary text-white" disabled><i class="fa fa-dollar"></i></button>`;
                    } else {
                        const link = row.inv_type === 'DSK' ? `/invoice/import-DSK${row.id}` : `/invoice/import-DS${row.id}`;
                        return `<a href="${link}" target="_blank" class="btn btn-sm btn-primary text-white"><i class="fa fa-dollar"></i></a>`;
                    }
                }
            },
            {
                data: 'id',
                name: 'id',
                className: 'text-center',
                render: function(data, type, row) {
                    if (row.lunas === 'N') {
                        return `<button class="btn btn-sm btn-info text-white" disabled><i class="fa fa-ship"></i></button>`;
                    } else {
                        return `<a href="/invoice/job/import-${data}" target="_blank" class="btn btn-sm btn-info text-white"><i class="fa fa-ship"></i></a>`;
                    }
                }
            },
            {
                data: 'id',
                name: 'id',
                className: 'text-center',
                render: function(data, type, row) {
                    return `<div class="row">
                                <div class="col-5">
                                    <button type="button" id="pay" data-id="${data}" class="btn btn-sm btn-success pay"><i class="fa fa-cogs"></i></button>
                                </div>
                                ${row.lunas === 'N' ? `
                                <div class="col-5">
                                    <button type="button" data-id="${row.form_id}" class="btn btn-sm btn-danger Delete"><i class="fa fa-trash"></i></button>
                                </div>` : ''}
                            </div>`;
                }
            }
        ],
        pageLength: 25
    });
});

</script>
<script>
$(document).ready(function() {
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
                    url: '/billing/import/delivery-deleteInvoice/' + formId, // Ganti dengan endpoint penghapusan Anda
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
                                window.location.href = '/billing/import/delivey-system'; // Arahkan ke halaman beranda setelah penghapusan sukses
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
<!-- <script>
$(document).ready(function() {
    $('.Delete').on('click', function() {
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
                    url: '/billing/import/delivery-deleteInvoice/' + formId, // Ganti dengan endpoint penghapusan Anda
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}' // Sertakan token CSRF untuk keamanan
                    },
                    success: function(response) {
                        Swal.fire(
                            'Dihapus!',
                            'Data berhasil dihapus.',
                            'success'
                        ).then(() => {
                            window.location.href = '/billing/import/delivey-system'; // Arahkan ke halaman beranda setelah penghapusan sukses
                        });
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
</script> -->

<script>
   $(document).on('click', '.pay', function() {
    let id = $(this).data('id');
    $.ajax({
      type: 'GET',
      url: '/import/pay-button' + id,
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
      'couple': $('#couple').val(),
   
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
          url: '/invoice/import-payFull',
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
      'couple': $('#couple').val(),
   
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
          url: '/invoice/import-piutang',
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
          url: '/invoice/import-cancel',
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