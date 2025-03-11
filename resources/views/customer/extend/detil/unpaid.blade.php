@extends('customer.import.detil.main')
@section('table')

<div class="table">
    <table class="table-hover" id="unpaidImport">
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
                <th>Pay</th>
                <th>Status Pembayaran</th>
                <th>Delete & Cancel</th>
            </tr>
        </thead>
    </table>
</div>


<div class="modal fade" id="editCust" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable modal-xl"role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Payment Form</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"> <i data-feather="x"></i></button>
            </div>
            <form action="/customer-extend/payImportFromCust" method="POST" id="updateForm" enctype="multipart/form-data">
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
                    <button type="button" id="updateButton" class="btn btn-primary ml-1"> <i class="bx bx-check d-block d-sm-none"></i> <span class="d-none d-sm-block">Submit</span> </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('table_js')
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
    $(document).ready(function() {
    $('#unpaidImport').DataTable({
        processing: true,
        serverSide: true,
        scrollY: '50hv',
        scrollX: true,
        ajax: {
            url: '/customer-extend/serviceData',
            type: 'GET',
            data: {
                type: 'unpaid' // Kirimkan osId sebagai parameter
            }
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
            {data:'payFlag', name:'payFlag', classNmae:'text-center'},
            {data:'delete', name:'delete', classNmae:'text-center'},
        ],
        pageLength: 50
    });
});

</script>
@endsection