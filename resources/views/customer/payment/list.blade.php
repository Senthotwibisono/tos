@extends('partial.customer.main')

@section('content')

<section>
    <div class="page-content">
        <div class="card">
            <div class="card-header">
                <div class="text-center">
                    <h4>List Virtual Account Logs</h4>
                </div>
            </div>
            <div class="card-body">
                <table class="table-hover" id="tableList">
                    <thead>
                        <tr>
                            <th>Virtual Account</th>
                            <th>Status</th>
                            <th>Invoice Type</th>
                            <th>Description</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Expired</th>
                            <th>Repay</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</section>

@endsection

@section('custom_js')
<script>
    $(document).ready(function(){
        $('#tableList').dataTable({
            processing: true,
            serverSide: true,
            ajax: '{{route('pembayaran.va.dataList')}}',
            columns: [
                {data:'virtual_account', data:'virtual_account'},
                {data:'status', data:'status'},
                {data:'invoice_type', data:'invoice_type'},
                {data:'description', data:'description'},
                {data:'customer_name', data:'customer_name'},
                {data:'billing_amount', data:'billing_amount'},
                {data:'expired_va', data:'expired_va'},
                {data:'rePay', data:'rePay'},
                {data:'cancel', data:'cancel'},
            ],
        });
    });
</script>

<script>
    async function cancelVA(event) {
        Swal.fire({
            icon: 'warning',
            title: 'Apakah anda yakin?',
            text: 'VA tidak dapat digunakan lagi jika anda membatalkan',
            showCancelButton: true,
        }).then(async(result) => {
            if (result.isConfirmed) {
                const id = event.getAttribute('data-id');
                console.log(id);
                showLoading();
                const url = '{{route('pembayaran.va.cancelVA')}}';
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        "Content-Type": "application/json",
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    body: JSON.stringify({ id: id}),
                });
                hideLoading();
                console.log(response);
                if (response.ok) {
                    const hasil = await response.json();
                    if (hasil.success) {
                        Swal.fire({
                            icon: 'success',
                            text: hasil.message,
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
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