@extends('partial.customer.main')
@section('custom_styles')

@endsection

@section('content')

<section>
    <div class="page-content">
        <div class="row mb-3">
            <div class="col-auto">
                <button type="button" class="btn btn-primary" id="createForm" onClick="createInvoice">Create Form</button>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4>List Index</h4>
            </div>
            <div class="card-body">
                <div class="table">
                    <table class="table-hover table-stripped" id="tableForm">
                        <thead>
                            <tr>
                                <th>Cutomer</th>
                                <th>Order Service</th>
                                <th>Booking Number</th>
                                <th>Edit</th>
                                <th>Delete</th>
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
    $(document).ready(function () {
        $('#tableForm').dataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('customer.export.dataForm')}}',
            columns: [
                {name:'customer', data:'customer', className:'text-center'},
                {name:'service', data:'service', className:'text-center'},
                {name:'do_id', data:'do_id', className:'text-center'},
                {orderable:false, searchable:false, name:'edit', data:'edit', className:'text-center'},
                {orderable:false, searchable:false, name:'delete', data:'delete', className:'text-center'},
            ],
        });
    });
</script>
@endsection