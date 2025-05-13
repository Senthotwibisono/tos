@extends('partial.invoice.main')

@section('content')

<section>
    <div class="page-content">
        <div class="card">
            <div class="card-header">
                <div class="text-center">
                    <h4><b>Isi Form Berikut</b></h4>
                </div>
            </div>
            <div class="card-body">
                <div class="col-12">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">Kapal</label>
                                <select name="ves_code" id="ves_code" class="js-example-basic-single form-select select2" style="width: 100%;">
                                    <option disabled selected value>Pilih Satu !!</option>
                                    @foreach($vessels as $ves)
                                        <option value="{{$ves->ves_code}}">{{$ves->ves_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="">Voy In</label>
                                <input type="text" class="form-control" name="voy_in" id="voy_in">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="">Voy Out</label>
                                <input type="text" class="form-control" name="voy_out" id="voy_out">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Do Number</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="do_no" id="do_no">
                            <button type="button" class="btn btn-info" onClick="searchContainer()"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="card">
        <div class="card-header">
            <div class="text-center">
                <b>List Container</b>
            </div>
        </div>
        <div class="card-body">
            <div class="table">
                <table class="table-hover" id="tableContainerInvoice">
                    <thead>
                        <tr>
                            <th></th>
                            <th>No Container</th>
                            <th>Last Invoice</th>
                            <th>Last Job</th>
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
    async function searchContainer() {
        Swal.fire({
            icon: 'warning',
            title: 'Apakah anda yakin dengan data yg anda masukkan?',
            showCancelButton: true,
        }).then(async(result) =>{
            if (result.isConfirmed) {
                showLoading();
                if ($.fn.DataTable.isDataTable('#tableContainerInvoice')) {
                    $('#tableContainerInvoice').DataTable().clear().destroy();
                }
                const ves_code = document.getElementById('ves_code').value;
                const voy_in = document.getElementById('voy_in').value;
                const voy_out = document.getElementById('voy_out').value;
                const do_no = document.getElementById('do_no').value;
              
                if (!ves_code || !voy_in || !voy_out || !do_no) {
                    hideLoading();
                    Swal.fire({
                        icon: 'error',
                        title: 'Data tidak lengkap',
                        text: 'Pastikan semua input sudah diisi!',
                    });
                    return; // hentikan proses jika ada yang kosong
                }

                var formData = {
                    ves_code : ves_code,
                    voy_in : voy_in,
                    voy_out : voy_out,
                    do_no : do_no,
                };

                const url = '/api/trackingInvoice/searchByDo';
                const response = await fetch(url, {
                    method: "POST",
                    headers: {
                      "Content-Type": "application/json",
                    },
                    body: JSON.stringify(formData),
                });

                console.log(response);
                if (response.ok) {
                    var hasil = await response.json();
                    hideLoading();
                    if (hasil.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Data Ditemukan',
                        }).then(() => {
                            var items = hasil.data.items;
                            console.log(items);
                            tableInvoice(items);
                        });
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Something Wrong',
                            text: hasil.message,
                        });
                    }
                } else {
                    hideLoading();
                    Swal.fire({
                        icon: 'error',
                        title: 'Something Wrong',
                        text: response.status + ' ' + response.statusText,
                    });
                }
            }
        });
    }

    function tableInvoice(items)
    {
        var table = new DataTable('#tableContainerInvoice', {
            processing: true,
            serverSide:true,
            ajax : {
                url: '{{route('invoiceService.tracking.dataImport')}}',
                data: {
                    items:items,
                },
            },
            columns:[
                {
                    data: null,
                    className: 'dt-control',
                    orderable: false,
                    defaultContent: '',
                },
                {name:'container_no', data:'container_no'},
                {name:'invoice_no', data:'invoice_no'},
                {name:'job_no', data:'job_no'},
            ],
        })
        bindDetailRowClick(table);
    }
    function formatDetailRow(rowData) {
        return `
            <table class="table table-bordered table-detail" id="detail-${rowData.container_key}">
                <thead>
                    <tr>
                        <th>Pranota</th>
                        <th>Invoice Number</th>
                        <th>Job Number</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        `;
    }

    function bindDetailRowClick(table) {
        $('#tableContainerInvoice tbody').off('click', 'td.dt-control'); // hapus handler lama
        $('#tableContainerInvoice tbody').on('click', 'td.dt-control', function () {
            const tr = $(this).closest('tr');
            const row = table.row(tr);

            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            } else {
                row.child(formatDetailRow(row.data())).show();
                tr.addClass('shown');
                const detailTableId = `#detail-${row.data().container_key}`;
                new DataTable(detailTableId, {
                    serverSide: true,
                    processing: true,
                    ajax: {
                        url: '{{route('invoiceService.tracking.dataDetilImport')}}',
                        data: {
                            container_key: row.data().container_key
                        },
                    },
                    columns: [
                       {data:'pranota'},
                       {data:'invoice_no'},
                       {data:'job_no'},
                    ],
                });
            }
        });
    }

</script>

@endsection
