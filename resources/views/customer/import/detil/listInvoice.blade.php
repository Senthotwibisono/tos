@extends('partial.customer.main')
@section('content')
<section>
    <div class="page-heading">
        <div class="button">
            <a href="/customer-import/formList" class="btn btn-success"><i class="fa fa-folder"></i> Create Invoice</a>    
        </div>
    </div>
    <div class="page-content">
        <div class="card">
            <div class="card-header text-center">
                <h4>Unpaid Invoice</h4>
            </div>
            <div class="card-body">
                <div class="table">
                    <table class="table-hover" id="unpaidImport">
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
                <h4>List Invoice</h4>
            </div>
            <div class="card-body">
                <div class="table">
                    <table class="table-hover" id="listInvoice">
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
                            <label for="">Type</label>
                            <input type="text" name="inv_type" id="inv_type_edit" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label for="">Grand Total</label>
                            <input type="text" name="grand_total" id="grand_total_edit" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-6 d-none" id="anotherForm">
                        <b style="color: red;">Anda juga memiliki tagihan lain dengan tiper berbeda</b>
                        <div class="form-group">
                            <label for="">Proforma</label>
                            <input type="text" class="form-control" id="anotherProforma">
                            <label for="">Type</label>
                            <input type="text" class="form-control" id="anotherType">
                            <label for="">Grand Total</label>
                            <input type="text" class="form-control" id="anotherGrandTotal">
                            <label for="">Ceklis untuk membayar tagihan lainnya</label>
                            <input class="form-check-input" type="checkbox" name="couple" id="couple" checked>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal"> <i class="bx bx-x d-block d-sm-none"></i> <span class="d-none d-sm-block">Close</span> </button>
                <button type="button" id="" class="btn btn-primary ml-1" onClick="createVA()"> <i class="bx bx-check d-block d-sm-none"></i> <span class="d-none d-sm-block">Submit</span> </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_js')
<!-- <script>
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
</script> -->

<script>
    async function searchToPay(event) {
        showLoading();
        const id = event.getAttribute('data-id');
        const url = '{{route('customer.import.searchToPay')}}';
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                        "Content-Type": "application/json",
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    body: JSON.stringify({ id: id }),
        });
        hideLoading();
        if (response.ok) {
            const result = await response.json();
            if (result.success) {
                $('#payModal').modal('show');
                $('#payModal #proforma_no_edit').val(result.data.proforma_no);
                $('#payModal #id_edit').val(result.data.id);
                $('#payModal #inv_type_edit').val(result.data.inv_type);
                $('#payModal #grand_total_edit').val(result.data.grand_total);
                if (result.another) {
                    $('#payModal #anotherForm').removeClass('d-none');
                    $('#payModal #anotherProforma').val(result.anotherData.proforma_no);
                    $('#payModal #anotherType').val(result.anotherData.inv_type);
                    $('#payModal #anotherGrandTotal').val(result.anotherData.grand_total);
                } else {
                    $('#payModal #anotherForm').addClass('d-none');
                    $('#payModal #couple').prop('checked', false);
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: result.message,
                }).then(() => {
                    location.reload();
                });
            }
        } else {
            Swal.fire({
                icon: 'error',
                title: response.status,
                text: response.statusText,
            }).then(() => {
                location.reload();
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
                const coupleCheckbox = document.getElementById('couple');
                const isCoupleChecked = coupleCheckbox.checked;
                const couple = isCoupleChecked ? 'Y' : 'N';
                console.log(couple);
                showLoading();

                const url = '{{ route('customer.import.createVA')}}';
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        "Content-Type": "application/json",
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    body: JSON.stringify({ id: id, couple:couple }),
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

<!-- <script>
    $(document).on('click', '#pay', function(){
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
          url: '/customer-import/payButton/' + id,
          cache: false,
          data: {
            id: id
          },
              dataType: 'json',

              success: function(response) {

            console.log(response);
            if (response.success) {
                swal.close();
                $('#editCust').modal('show');
                $("#editCust #id_edit").val(response.data.id);
                $("#editCust #order_no_edit").val(response.data.proforma_no);
                $("#editCust #grand_total_edit").val(response.data.grand_total);
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
</script> -->

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
    $(document).ready(function() {
    $('#unpaidImport').DataTable({
        processing: true,
        serverSide: true,
        scrollY: '50hv',
        scrollX: true,
        ajax: {
            url: '{{route('customer.import.listData')}}',
            type: 'GET',
            data: {
                type: 'unpaid' // Kirimkan osId sebagai parameter
            }
        },
        columns: [
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
            {data:'payFlag', name:'payFlag', classNmae:'text-center'},
            {data:'cancel', name:'cancel', classNmae:'text-center'},
        ],
        pageLength: 50
    });
});

</script>


<script>
    $(document).ready(function() {
    $('#listInvoice').DataTable({
        processing: true,
        serverSide: true,
        scrollY: '50hv',
        scrollX: true,
        ajax: {
            url: '{{route('customer.import.listData')}}',
            type: 'GET',
        },
        columns: [
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
            {data:'payFlag', name:'payFlag', classNmae:'text-center'},
            {data:'cancel', name:'cancel', classNmae:'text-center'},
            
        ],
        pageLength: 50
    });
});

</script>

<script>
    async function cancelInvoice(event) {
        Swal.fire({
            icon: 'warning',
            title: 'Apakah anda yakin?',
            showCancelButton: true,
        }).then(async(result) => {
            if (result.isConfirmed) {
                showLoading();
                const formId = event.getAttribute('data-id');
                console.log(formId);
                const url = '{{route('customer.import.cancelInvoice')}}';
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        "Content-Type": "application/json",
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    body: JSON.stringify({ formId: formId }),
                });
                hideLoading();
                if (response.ok) {
                    const hasil = await response.json();
                    if (hasil.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses',
                            text: hasil.message,
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Something Wrong',
                            text: hasil.message,
                        }).then(() => {
                            location.reload();
                        });
                    }
                } else {
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
</script>
@endsection