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
                <th class="text-center">Action</th>
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
            ajax: '/customer-import/unpaidData',
            columns: [
                { data: 'proforma_no', name: 'proforma_no', className: 'text-center' },
                { data: 'customer.name', name: 'customer.name', className: 'text-center' },
                { data: 'service.name', name: 'service.name', className: 'text-center' },
                { data: 'inv_type', name: 'inv_type', className: 'text-center' },
                { data: 'order_at', name: 'order_at', className: 'text-center' },
                { 
                    data: 'id',
                    name: 'id',
                    className: 'text-center',
                    render: function(data, type, row) {
                        // Cek nilai `inv_type` untuk menentukan rute yang sesuai
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
                    data: 'form_id',
                    name: 'form_id',
                    className: 'text-center',
                    render: function(data, row){
                        return `<button type="button" data-id="${data}" class="btn btn-sm btn-danger Delete"><i class="fa fa-trash"></i></button>`;
                    }
                },
            ],
            pageLength: 50
        });
    });
</script>
@endsection