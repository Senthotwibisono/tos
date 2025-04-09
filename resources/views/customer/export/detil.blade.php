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

        <div class="card">
            <div class="card-header">
                <h4>
                    Piutang Invoice
                </h4>
            </div>
            <div class="card-body">
                <div class="table">
                    <table class="table-hover table-stripped" id="tablePiutang">
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
                {name:'payFlag', data:'payFlag', className:'text-center', searchable:false, orderable:false},
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

@endsection
