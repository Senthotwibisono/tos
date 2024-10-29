@extends('partial.customer.main')
@section('custom_styles')

@endsection

@section('content')

<div class="page-header">
    <h4>{{$title}}</h4>
</div>

<div class="page-content">
    <div class="card">
        <div class="card-header">
            <a href="{{ route('deliveryForm')}}" type="button" class="btn btn-success">
              <i class="fa-solid fa-plus"></i>
              Tambah Delivery Form
            </a>
        </div>
        <div class="card-body">
            <div class="table table-responsive">
                <table class="table table-hover" id="formList">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Do Number</th>
                            <th>Order Service</th>
                            <th>Expired Date</th>
                            <th>Bill Of Loading Number</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('custom_js')
<script>
    $(document).ready(function() {
        $('#formList').DataTable({
            processing: true,
            serverSide: true,
            ajax: '/customer-import/formData',
            columns: [
                { data: 'customer.name', name: 'customer.name', className: 'text-center' },
                { data: 'doOnline.do_no', name: 'doOnline.do_no', className: 'text-center',
                    render: function(data, row){
                        return data ? data : '';
                    }
                 },
                { data: 'service.name', name: 'service.name', className: 'text-center' },
                { data: 'expired_date', name: 'expired_date', className: 'text-center' },
                { data: 'doOnline.bl_no', name: 'doOnline.bl_no', className: 'text-center',
                    render: function(data, type, row) {
                        return data ? data : ''; // Return the data if it exists, otherwise return an empty string
                    }
                },
                {
                    data: 'id',
                    name: 'id',
                    className: 'text-center',
                    render: function(data, row){
                        return `<div class="button-container">
                        <button type="button" data-id="${data}" class="btn btn-sm btn-danger Delete"><i class="fa fa-trash"></i></button>
                            <a herf="" class="btn btn-warning"><i class="fa fa-pencil"></i></a>
                            </div>
                        `;
                    }
                },
            ],
            pageLength: 50
        });
    });
</script>
@endsection