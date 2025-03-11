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
                <th>Action</th>
                <th>Status Pembayaran</th>
                <th>Cancel</th>
            </tr>
        </thead>
    </table>
</div>

<input type="hidden" id="os_id" value="8" class="hidden">
@endsection

@section('table_js')
<script>
    $(document).ready(function() {
    var osId ="{{$osId}}"; // Ambil osId dari hidden input

    $('#unpaidImport').DataTable({
        processing: true,
        serverSide: true,
        scrollY: '50hv',
        scrollX: true,
        ajax: {
            url: '/customer-extend/serviceData',
            type: 'GET',
            data: {
                os_id: osId // Kirimkan osId sebagai parameter
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