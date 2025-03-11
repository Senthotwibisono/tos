@extends('customer.import.detil.main')
@section('table')

<div class="table-responsive">
    <table class="unpaidImport table table-hover">
        <thead>
            <tr>
                <th class="text-center">Pranota No</th>
                <th class="text-center">Customer</th>
                <th class="text-center">Order Service</th>
                <th class="text-center">Type</th>
                <th class="text-center">Created At</th>
                <th class="text-center">Pranota</th>
                <th class="text-center">Invoice</th>
                <th class="text-center">Job</th>
            </tr>
        </thead>
    </table>
</div>
@endsection

@section('table_js')
<script>
    $(document).ready(function() {
        $('.unpaidImport').DataTable({
            processing: true,
            serverSide: true,
            ajax: '/customer-import/piutangData',
            columns: [
                { data: 'proforma_no', name: 'proforma_no', className: 'text-center' },
                { data: 'customer.name', name: 'customer.name', className: 'text-center' },
                { data: 'service.name', name: 'service.name', className: 'text-center' },
                { data: 'inv_type', name: 'inv_type', className: 'text-center' },
                { data: 'order_at', name: 'order_at', className: 'text-center' },
                { 
                    data: 'id',
                    dataTest: 'form_id',
                    name: 'id',
                    className: 'text-center',
                    render: function(data, type, row) {
                        
                        if (row.inv_type === 'DSK') {
                            return `<a href="/pranota/import-DSK${data}" type="button" target="_blank" class="btn btn-sm btn-warning text-white"><i class="fa fa-file"></i></a>`;
                        } else if (row.inv_type === 'DS') {
                            return `<a href="/pranota/import-DS${data}"type="button" target="_blank" class="btn btn-sm btn-warning text-white"><i class="fa fa-file"></i></a>`;
                        } else {
                            // Jika `inv_type` tidak cocok, tampilkan data tanpa link
                            return data;
                        }
                    }
                },
                {
                    data: 'id',
                    dataTest: 'form_id',
                    name: 'id',
                    className: 'text-center',
                    render: function(data, type, row) {
                        
                        if (row.inv_type === 'DSK') {
                            return `<a href="/invoice/import-DSK${data}" type="button" target="_blank" class="btn btn-sm btn-primary text-white"><i class="fa fa-dollar"></i></a>`;
                        } else if (row.inv_type === 'DS') {
                            return `<a href="/invoice/import-DS${data}"type="button" target="_blank" class="btn btn-sm btn-primary text-white"><i class="fa fa-dollar"></i></a>`;
                        } else {
                            // Jika `inv_type` tidak cocok, tampilkan data tanpa link
                            return data;
                        }
                    }
                },
                {
                    data: 'id',
                    dataTest: 'form_id',
                    name: 'id',
                    className: 'text-center',
                    render: function(data, type, row) {
                        return `<a type="button" href="/invoice/job/import-${data}" target="_blank" class="btn btn-sm btn-info text-white"><i class="fa fa-ship"></i></a>`;
                    }
                },
            ],
            pageLength: 50
        });
    });
</script>
@endsection