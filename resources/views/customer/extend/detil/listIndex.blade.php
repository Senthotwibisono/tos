@extends('partial.customer.main')
@section('content')

<section>
    <div class="page-heading">
        <div class="col-auto">
            <div class="button">
                <a href="/customer-extend/formList" class="btn btn-success"><i class="fa fa-folder"></i> Create Invoice</a>    
            </div>
        </div>
    </div>
    <div class="page-content">
        <div class="card">
            <div class="card-header text-center">
                <h4><b>Unpaid Invoice</b></h4>
            </div>
            <div class="card-body">
                <div class="table">
                    <table class="table-hover" id="unpaidTable">
                        <thead style="white-space: nowrap;">
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
                                <th>Pay</th>
                                <th>Upload Bukti Bayar</th>
                                <th>Bukti Bayar</th>
                                <th>Status Pembayaran</th>
                                <th>Cancel</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header text-center">
                <h4><b>List Invoice</b></h4>
            </div>
            <div class="card-body">
                <div class="table">
                    <table class="table-hover" id="listData">
                        <thead style="white-space: nowrap;">
                            <tr>
                                <th>Proforma No</th>
                                <th>Invoice No</th>
                                <th>Customer</th>
                                <th>Order Service</th>
                                <th>Tipe Invoice</th>
                                <th>Dibuat Pada</th>
                                <th>Status</th>
                                <th>Pranota</th>
                                <th>Invoice</th>
                                <th>Materai</th>
                                <th>Job</th>
                                <th>Pay</th>
                                <th>Upload Bukti Bayar</th>
                                <th>Bukti Bayar</th>
                                <th>Status Pembayaran</th>
                                <th>Cancel</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="payModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable modal-lg"role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Payment Form</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"> <i data-feather="x"></i></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="">Poforma No</label>
                            <input type="text" name="proforma_no" id="proforma_no_edit" class="form-control" readonly>
                            <input type="hidden" name="id" id="id_edit" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Grand Total</label>
                            <input type="text" name="grand_total" id="grand_total_edit" class="form-control" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal"> <i class="bx bx-x d-block d-sm-none"></i> <span class="d-none d-sm-block">Close</span> </button>
                <button type="button" id="updateButton" class="btn btn-primary ml-1" onClick="createVA()"> <i class="bx bx-check d-block d-sm-none"></i> <span class="d-none d-sm-block">Submit</span> </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="uploadBuktiModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable modal-xl"role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Payment Form</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"> <i data-feather="x"></i></button>
            </div>
            <form action="/customer-extend/payImportFromCust" method="POST" id="updateFormBuktiBayar" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">Poforma No</label>
                                <input type="text" name="order_no" id="order_no_edit" class="form-control" readonly>
                                <input type="hidden" name="id" id="id_edit" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Grand Total</label>
                                <input type="text" name="grand_total" id="grand_total_edit" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">Upload Bukti Bayar</label>
                                <input type="file" name="bukti_bayar[]" id="bukti_bayar" class="form-control" multiple accept="image/*">
                                <div class="mt-2" id="preview_container">
                                    <!-- Gambar akan muncul di sini -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal"> <i class="bx bx-x d-block d-sm-none"></i> <span class="d-none d-sm-block">Close</span> </button>
                    <button type="button" id="updateButtonBuktiBayar" class="btn btn-primary ml-1"> <i class="bx bx-check d-block d-sm-none"></i> <span class="d-none d-sm-block">Submit</span> </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@include('materai.js');
@section('custom_js')
<script>
$(document).ready(function() {
    $('#unpaidTable').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        ajax: {
            url: '{{ route('customer.extend.listData') }}',
            type: 'GET',
            data: {
                type: 'unpaid'
            }
        },
        columns: [
            { data: 'proforma', name: 'proforma', className: 'text-center' },
            { data: 'customer', name: 'customer', className: 'text-center' },
            { data: 'service', name: 'service', className: 'text-center' },
            { data: 'type', name: 'type', className: 'text-center' },
            { data: 'orderAt', name: 'orderAt', className: 'text-center' },
            { data: 'status', name: 'status', className: 'text-center' },
            { data: 'pranota', name: 'pranota', className: 'text-center' },
            { data: 'invoice', name: 'invoice', className: 'text-center' },
            { data: 'job', name: 'job', className: 'text-center' },
            { data: 'action', name: 'action', className: 'text-center' },
            {data:'uploadBukti', name:'uploadBukti', classNmae:'text-center'},
            {data:'viewPhoto', name:'viewPhoto', classNmae:'text-center'},
            { data: 'payFlag', name: 'payFlag', className: 'text-center' },
            { data: 'delete', name: 'delete', className: 'text-center' },
        ],
        pageLength: 50
    });

    $('#listData').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        ajax: {
            url: '{{ route('customer.extend.listData') }}',
            type: 'GET',
        },
        columns: [
            { data: 'proforma', name: 'proforma', className: 'text-center' },
            { data: 'inv_no', name: 'inv_no', className: 'text-center' },
            { data: 'customer', name: 'customer', className: 'text-center' },
            { data: 'service', name: 'service', className: 'text-center' },
            { data: 'type', name: 'type', className: 'text-center' },
            { data: 'orderAt', name: 'orderAt', className: 'text-center' },
            { data: 'status', name: 'status', className: 'text-center' },
            { data: 'pranota', name: 'pranota', className: 'text-center' },
            { data: 'invoice', name: 'invoice', className: 'text-center' },
            { data: 'materai', name: 'materai', className: 'text-center' },
            { data: 'job', name: 'job', className: 'text-center' },
            { data: 'action', name: 'action', className: 'text-center' },
            {data:'uploadBukti', name:'uploadBukti', classNmae:'text-center'},
            {data:'viewPhoto', name:'viewPhoto', classNmae:'text-center'},
            { data: 'payFlag', name: 'payFlag', className: 'text-center' },
            { data: 'delete', name: 'delete', className: 'text-center' },
        ],
        pageLength: 50
    });
});
</script>

<script>
    async function cancelInvoice(event) {
        Swal.fire({
            icon: 'warning',
            title: 'Are You Sure?',
            showCancelButton: true,
        }).then( async(result) => {
            if (result.isConfirmed) {
                const formId = event.getAttribute('data-id');
                const url = '{{route('customer.extend.cancelInvoice')}}';
                const response = await fetch(url,{
                    method: 'POST',
                    headers: {
                        "Content-Type": "application/json",
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    body: JSON.stringify({ formId: formId }),
                });

                if (response.ok) {
                    const hasil = await response.json();
                    if (hasil.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: hasil.message,
                        }).then(() => {
                            location.reload();
                        });
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: hasil.message,
                        }).then(() => {
                            location.reload();
                        });
                    }
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: response.status,
                        text: response.statusMessage,
                    }).then(() => {
                        location.reload();
                    });
                }
            }
        });
    }
</script>

<script>
    async function payExtend(event) {
        const id = event.getAttribute('data-id');
        console.log(id);
        showLoading();
        const url = '/customer-extend/transaction/searchToPay-'+id;
        const response = await fetch(url);
        console.log(response);
        hideLoading();
        if (response.ok) {
            const result = await response.json();
            console.log(result);
            if (result.success) {
                $('#payModal').modal('show');
                $('#payModal #proforma_no_edit').val(result.data.proforma_no);
                $('#payModal #id_edit').val(result.data.id);
                $('#payModal #grand_total_edit').val(result.data.grand_total);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: result.message,
                });
            }
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: response.statusText,
            });
        }
    }

    async function createVA() {
        Swal.fire({
            icon: 'warning',
            title: 'Anda Yakin?',
            text: 'VA Akan terbit dan memiliki waktu 3 Jam sampai pembayaran!',
            showCancelButton: true,
        }).then( async (result) => {
            if (result.isConfirmed) {
                const id = document.getElementById('id_edit').value;
                showLoading();

                const url = '{{ route('customer.extend.createVA')}}';
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        "Content-Type": "application/json",
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    body: JSON.stringify({ id: id }),
                })
                hideLoading();
                if (response.ok) {
                    const hasil = await response.json();
                    if (hasil.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                        }).then(() => {
                            window.open('/pembayaran/virtual_account-' + hasil.data.id, '_blank', 'width=800,height=600');
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: hasil.message,
                        }).then(() => {
                            if (hasil.status === 30) {
                                window.open('/pembayaran/virtual_account-' + hasil.data.id, '_blank', 'width=800,height=600');
                            } else {
                                window.location.reload();
                            }
                        });
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: response.status,
                        text: response.statusText,
                    });
                }
            }
        });
    }
</script>
<script>
    document.getElementById('bukti_bayar').addEventListener('change', function(event) {
        let files = event.target.files; // Ambil semua file yang dipilih
        let previewContainer = document.getElementById('preview_container'); 

        // Hapus gambar sebelumnya
        previewContainer.innerHTML = '';

        for (let i = 0; i < files.length; i++) {
            let file = files[i];
            let reader = new FileReader();

            reader.onload = function(e) {
                let imgElement = document.createElement('img'); // Buat elemen img baru
                imgElement.src = e.target.result;
                imgElement.classList.add('img-thumbnail', 'm-1'); // Tambahkan kelas Bootstrap
                imgElement.style.maxWidth = '120px'; // Atur ukuran gambar
                imgElement.style.maxHeight = '120px';

                previewContainer.appendChild(imgElement); // Tambahkan gambar ke dalam container
            }

            reader.readAsDataURL(file); // Membaca file sebagai URL data
        }
    });
</script>

<script>
    $(document).on('click', '#uploadBukti', function(){
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
        let id = $(this).data('id');
        $.ajax({
          type: 'GET',
          url: '/customer-extend/payButton/' + id,
          cache: false,
          data: {
            id: id
          },
              dataType: 'json',

              success: function(response) {

            console.log(response);
            if (response.success) {
                swal.close();
                $('#uploadBuktiModal').modal('show');
                $("#uploadBuktiModal #id_edit").val(response.data.id);
                $("#uploadBuktiModal #order_no_edit").val(response.data.proforma_no);
                $("#uploadBuktiModal #grand_total_edit").val(response.data.grand_total);
            } else {
                Swal.fire({
                  icon: 'error',
                  title: 'Error',
                  text: response.message,
                });
            }

          },
          error: function(data) {
            console.log('error:', data)
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: response.message,
            });
          }
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Attach event listener to the update button
        document.getElementById('updateButtonBuktiBayar').addEventListener('click', function (e) {
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
                    document.getElementById('updateFormBuktiBayar').submit();
                }
            });
        });
    });
</script>
@endsection