@extends('partial.customer.main')

@section('custom_styles')

@endsection

@section('content')

<section>
    <div class="page-content">
        <div class="container">
            <div class="mb-3">
                <div class="col-auto">
                    <a href="{{route('customer.export.indexForm')}}" class="btn btn-primary">Create Form</a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h4>
                    Unpaid Invoice
                </h4>
            </div>
            <div class="card-body">
                <div class="table">
                    <table class="table-hover table-stripped" id="tableUnpaid">
                        <thead class="text-center" style="white-space: nowrap;">
                            <tr>
                                <th style="min-width: 150px">Proforma No</th>
                                <th style="min-width: 150px">Customer</th>
                                <th style="min-width: 150px">Order Service</th>
                                <th style="min-width: 150px">Tipe Invoice</th>
                                <th style="min-width: 150px">Dibuat Pada</th>
                                <th style="min-width: 150px">Status</th>
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

        <div class="card">
            <div class="card-header">
                <h4>
                    Canceled Invoice
                </h4>
            </div>
            <div class="card-body">
                <div class="table">
                    <table class="table-hover table-stripped" id="tableCancel">
                        <thead class="text-center" style="white-space: nowrap;">
                            <tr>
                                <th style="min-width: 150px">Proforma No</th>
                                <th style="min-width: 150px">Customer</th>
                                <th style="min-width: 150px">Order Service</th>
                                <th style="min-width: 150px">Tipe Invoice</th>
                                <th style="min-width: 150px">Dibuat Pada</th>
                                <th style="min-width: 150px">Status</th>
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

        <div class="card">
            <div class="card-header">
                <h4>
                    List Invoice
                </h4>
            </div>
            <div class="card-body">
                <div class="table">
                    <table class="table-hover table-stripped" id="tableAll">
                        <thead class="text-center" style="white-space: nowrap;">
                            <tr>
                                <th style="min-width: 150px">Proforma No</th>
                                <th style="min-width: 150px">Invoice No</th>
                                <th style="min-width: 150px">Customer</th>
                                <th style="min-width: 150px">Order Service</th>
                                <th style="min-width: 150px">Tipe Invoice</th>
                                <th style="min-width: 150px">Dibuat Pada</th>
                                <th style="min-width: 150px">Status</th>
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
                            <input class="form-check-input" type="checkbox" name="couple" id="couple" disabled>
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
@endsection

@section('custom_js')

<script>
    $(document).ready(function(){
        $('#tableUnpaid').dataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            ajax: {
                data: {
                    'status' : 'N',
                },
                url: '{{ route('customer.export.dataDetail')}}',
            },
            columns: [
                {name:'proforma', data:'proforma', className:'text-center'},
                {name:'customer', data:'customer', className:'text-center'},
                {name:'service', data:'service', className:'text-center'},
                {name:'type', data:'type', className:'text-center'},
                {name:'orderAt', data:'orderAt', className:'text-center'},
                {name:'status', data:'status', className:'text-center'},
                {name:'pranota', data:'pranota', className:'text-center', searchable:false, orderable:false},
                {name:'invoice', data:'invoice', className:'text-center', searchable:false, orderable:false},
                {name:'job', data:'job', className:'text-center', searchable:false, orderable:false},
                {name:'action', data:'action', className:'text-center', searchable:false, orderable:false},
                {name:'delete', data:'delete', className:'text-center', searchable:false, orderable:false},
            ],
            initComplete: function () {
                var api = this.api();
                
                api.columns().every(function (index) {
                    var column = this;
                    var excludedColumns = [6, 7, 8, 9, 10, 11]; // Kolom yang tidak ingin difilter (detil, flag_segel_merah, lamaHari)
                    
                    if (excludedColumns.includes(index)) {
                        $('<th></th>').appendTo(column.header()); // Kosongkan header pencarian untuk kolom yang dikecualikan
                        return;
                    }

                    var $th = $(column.header());
                    var $input = $('<input type="text" class="form-control form-control-sm" placeholder="Search ' + $th.text() + '">')
                        .appendTo($('<th class="text-center"></th>').appendTo($th))
                        .on('keyup change', function () {
                            column.search($(this).val()).draw();
                        });
                });
            },
        });

        $('#tableCancel').dataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            ajax: {
                data: {
                    'status' : 'C',
                },
                url: '{{ route('customer.export.dataDetail')}}',
            },
            columns: [
                {name:'proforma', data:'proforma', className:'text-center'},
                {name:'customer', data:'customer', className:'text-center'},
                {name:'service', data:'service', className:'text-center'},
                {name:'type', data:'type', className:'text-center'},
                {name:'orderAt', data:'orderAt', className:'text-center'},
                {name:'status', data:'status', className:'text-center'},
                {name:'pranota', data:'pranota', className:'text-center', searchable:false, orderable:false},
                {name:'invoice', data:'invoice', className:'text-center', searchable:false, orderable:false},
                {name:'job', data:'job', className:'text-center', searchable:false, orderable:false},
                {name:'action', data:'action', className:'text-center', searchable:false, orderable:false},
                {name:'delete', data:'delete', className:'text-center', searchable:false, orderable:false},
            ],
            initComplete: function () {
                var api = this.api();
                
                api.columns().every(function (index) {
                    var column = this;
                    var excludedColumns = [6, 7, 8, 9, 10, 11]; // Kolom yang tidak ingin difilter (detil, flag_segel_merah, lamaHari)
                    
                    if (excludedColumns.includes(index)) {
                        $('<th></th>').appendTo(column.header()); // Kosongkan header pencarian untuk kolom yang dikecualikan
                        return;
                    }

                    var $th = $(column.header());
                    var $input = $('<input type="text" class="form-control form-control-sm" placeholder="Search ' + $th.text() + '">')
                        .appendTo($('<th class="text-center"></th>').appendTo($th))
                        .on('keyup change', function () {
                            column.search($(this).val()).draw();
                        });
                });
            },
        });

        $('#tableAll').dataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            ajax: {
                
                url: '{{ route('customer.export.dataDetail')}}',
            },
            columns: [
                {name:'proforma', data:'proforma', className:'text-center'},
                {name:'inv_no', data:'inv_no', className:'text-center'},
                {name:'customer', data:'customer', className:'text-center'},
                {name:'service', data:'service', className:'text-center'},
                {name:'type', data:'type', className:'text-center'},
                {name:'orderAt', data:'orderAt', className:'text-center'},
                {name:'status', data:'status', className:'text-center'},
                {name:'pranota', data:'pranota', className:'text-center', searchable:false, orderable:false},
                {name:'invoice', data:'invoice', className:'text-center', searchable:false, orderable:false},
                {name:'job', data:'job', className:'text-center', searchable:false, orderable:false},
                {name:'action', data:'action', className:'text-center', searchable:false, orderable:false},
                {name:'delete', data:'delete', className:'text-center', searchable:false, orderable:false},
            ],
            initComplete: function () {
                var api = this.api();
                
                api.columns().every(function (index) {
                    var column = this;
                    var excludedColumns = [6, 7, 8, 9, 10, 11]; // Kolom yang tidak ingin difilter (detil, flag_segel_merah, lamaHari)
                    
                    if (excludedColumns.includes(index)) {
                        $('<th></th>').appendTo(column.header()); // Kosongkan header pencarian untuk kolom yang dikecualikan
                        return;
                    }

                    var $th = $(column.header());
                    var $input = $('<input type="text" class="form-control form-control-sm" placeholder="Search ' + $th.text() + '">')
                        .appendTo($('<th class="text-center"></th>').appendTo($th))
                        .on('keyup change', function () {
                            column.search($(this).val()).draw();
                        });
                });
            },
        });
    })
</script>

<script>
    async function deleteButton(event) {
        Swal.fire({
            icon: 'warning',
            title: 'Yakin menghapus data ini?',
            showCancelButton: true,
        }).then(async(result) => {
            if (result.isConfirmed) {
                showLoading();
                const id = event.getAttribute('data-id');
                const url = '{{route('customer.export.cancelInvoice')}}';
                const response = await fetch(url, {
                    method : 'POST',
                    headers: {
                        "Content-Type": "application/json",
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    body: JSON.stringify({ id: id }),
                });
                console.log(response);
                hideLoading();
                if (response.ok) {
                    var hasil = await response.json();
                    if (hasil.success) {
                        Swal.fire({
                            icon: 'success',
                            text: hasil.message,
                        }).then(() => {
                            location.reload();
                        });
                    }else{
                        Swal.fire({
                            icon: 'error',
                            text: hasil.message,
                        });
                    }
                }else{
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
    async function payButton(event) {
        const id = event.getAttribute('data-id');
        console.log(id);
        showLoading();
        const url = '/customer-export/transaction/searchToPay-'+id;
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
                $('#payModal #inv_type_edit').val(result.data.inv_type);
                $('#payModal #grand_total_edit').val(result.data.grand_total);
                if (result.another) {
                    // $('#payModal #anotherForm').removeClass('d-none');
                    // $('#payModal #anotherProforma').val(result.anotherData.proforma_no);
                    // $('#payModal #anotherType').val(result.anotherData.inv_type);
                    // $('#payModal #anotherGrandTotal').val(result.anotherData.grand_total);
                } else {
                    $('#payModal #anotherForm').addClass('d-none');
                    $('#payModal #couple').prop('checked', false);
                }
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
                const coupleCheckbox = document.getElementById('couple');
                const isCoupleChecked = coupleCheckbox.checked;
                const couple = isCoupleChecked ? 'Y' : 'N';
                console.log(couple);
                showLoading();

                const url = '{{ route('customer.export.createVA')}}';
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

@endsection
