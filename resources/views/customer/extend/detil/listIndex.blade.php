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

@endsection

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
            { data: 'job', name: 'job', className: 'text-center' },
            { data: 'action', name: 'action', className: 'text-center' },
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
@endsection