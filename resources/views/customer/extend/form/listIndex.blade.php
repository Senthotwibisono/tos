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
            <form action="/customer-extend/formStoreFirst" method="post">
                @csrf
              <button type="submit" class="btn btn-success"> <i class="fa-solid fa-plus"></i>Tambah Form</button>
            </form>
        </div>
        <div class="card-body">
            <div class="table table-responsive">
                <table class="table table-hover" id="formList">
                    <thead>
                        <tr>
                            <th>Edit</th>
                            <th>Delete</th>
                            <th>Customer</th>
                            <th>Do Number</th>
                            <th>Order Service</th>
                            <th>Expired Date</th>
                            <th>Bill Of Loading Number</th>
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
            ajax: '/customer-extend/formData',
            columns: [
                {name:'edit', data:'edit', className:'text-center'},
                {name:'delete', data:'delete', className:'text-center'},
                { data: 'customer.name', name: 'customer.name', className: 'text-center',
                    render: function(data, row){
                        return data ? data : '';
                    }
                 },
                { data: 'doOnline.do_no', name: 'doOnline.do_no', className: 'text-center',
                    render: function(data, row){
                        return data ? data : '';
                    }
                 },
                { data: 'service.name', name: 'service.name', className: 'text-center',
                    render: function(data, row){
                        return data ? data : '';
                    }
                 },
                { data: 'expired_date', name: 'expired_date', className: 'text-center',
                    render: function(data, row){
                        return data ? data : '';
                    }
                 },
                { data: 'doOnline.bl_no', name: 'doOnline.bl_no', className: 'text-center',
                    render: function(data, type, row) {
                        return data ? data : ''; // Return the data if it exists, otherwise return an empty string
                    }
                },
            ],
        });
    });
</script>

<script>
    async function deleteForm(event) {
        Swal.fire({
            icon: 'warning',
            title: 'Are you sure?',
            showCancelButton: true,
        }).then( async(result) => {
            if (result.isConfirmed) {
                const formId = event.getAttribute('data-id');
                showLoading();
                const url = '{{route('customer.extend.deleteInvoice')}}';
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        "Content-Type": "application/json",
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    body: JSON.stringify({ formId: formId }),
                });
                hideLoading();
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
            }
        });
    }
</script>
@endsection