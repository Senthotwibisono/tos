@extends('partial.main')

@section('custom_styles')
<style>
    .dashboard-card {
        border: 0;
        border-radius: .75rem;
        box-shadow: 0 .25rem .75rem rgba(0, 0, 0, .08);
        height: 100%;
    }

    .card-line {
        height: 5px;
        width: 100%;
        border-radius: 8px;
        margin-top: 10px;
    }

    #tableBehandle {
        width: 100% !important;
    }

    #tableBehandle thead th,
    #tableBehandle tbody td {
        white-space: nowrap;
        vertical-align: middle;
    }

    #tableBehandle_wrapper .dt-search input,
    #tableBehandle_wrapper .dt-length select,
    #filter-row input,
    #filter-row select {
        min-width: 80px;
    }

    #filter-row th {
        padding: .5rem;
        background: var(--bs-body-bg, #fff);
    }

    .status-cell {
        font-weight: 500;
    }
</style>
@endsection

@section('content')
<section class="section">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>{{ $title }}</h3>
                    <p class="text-subtitle text-muted">Ringkasan dan data opname container.</p>
                </div>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-3 row-cols-xxl-6 g-3 mb-4">
            <div class="col">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <p class="text-muted mb-2">Semua Data</p>
                        <h2 class="mb-0" id="totalData">0</h2>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <p class="text-muted mb-2">Active</p>
                        <h2 class="mb-0" id="active">0</h2>
                        <div class="card-line" style="background:#FF8C00"></div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <p class="text-muted mb-2">Inactive</p>
                        <h2 class="mb-0" id="inactive">0</h2>
                        <div class="card-line" style="background:#cc0a0a"></div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <p class="text-muted mb-2">YOR</p>
                        <h2 class="mb-0" id="YOR">0</h2>
                        <div class="card-line" style="background:#9df5b1"></div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <p class="text-muted mb-2">IKS</p>
                        <h2 class="mb-0" id="IKS">0</h2>
                        <div class="card-line bg-primary"></div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <p class="text-muted mb-2">MKB</p>
                        <h2 class="mb-0" id="MKB">0</h2>
                        <div class="card-line" style="background:#f5c6cb"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">{{ $title }}</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle w-100" id="tableBehandle">
                        <thead>
                            <tr>
                                <th class="text-center">Edit</th>
                                <th class="text-center">Container No</th>
                                <th class="text-center">Size</th>
                                <th class="text-center">Type</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Location</th>
                            </tr>
                            <tr id="filter-row">
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="addManual" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="editUserModalLabel">Container Modal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <div class="from-group">
            <label for="">Container</label>
            <input type="text" id="container_no" class="form-control" readonly>
            <input type="hidden" id="id" class="form-control">
        </div>
        <div class="from-group">
            <label for="">Size</label>
            <input type="text" id="ctr_size" class="form-control">
        </div>
        <div class="from-group">
            <label for="">Type</label>
            <input type="text" id="ctr_type" class="form-control">
        </div>
        <div class="from-group">
            <label for="">Status</label>
            <select name="" id="status" class="form-control">
                <option value="active">active</option>
                <option value="inactive">inactive</option>
            </select>
        </div>
        <div class="from-group">
            <label for="">Location</label>
            <input type="text" id="location" class="form-control">
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" onClick="submitContainer(this)" class="btn btn-primary">Save changes</button>
      </div>

    </div>
  </div>
</div>
@endsection

@section('custom_js')
<script>
    const excel = {
        extend: 'excelHtml5',
        autoFilter: true,
        sheetName: 'Exported data',
        className: 'btn btn-outline-success'
    };

    const pdf = {
        extend: 'pdfHtml5',
        text: 'Ekspor PDF',
        className: 'btn btn-outline-danger',
        orientation: 'landscape',
        pageSize: 'A1',
        download: 'open',
        exportOptions: {
            columns: function (idx, data, node) {
                return true;
            }
        },
        customize: function (doc) {
            doc.defaultStyle.fontSize = 8;
            doc.styles.tableHeader.fontSize = 8;
            doc.styles.title.fontSize = 12;
            doc.pageMargins = [2, 2, 2, 2];
            doc.content[1].table.widths =
                Array(doc.content[1].table.body[0].length).fill('*');
        }
    };

    $(document).ready(function () {
        const table = $('#tableBehandle').DataTable({
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
            pageLength: 25,
            layout: {
                topStart: ['pageLength', 'buttons'],
                topEnd: 'search',
                bottomStart: 'info',
                bottomEnd: 'paging'
            },
            buttons: [
                { extend: 'copy', className: 'btn btn-outline-secondary' },
                { extend: 'csv', className: 'btn btn-outline-secondary' },
                excel,
                pdf,
                { extend: 'print', className: 'btn btn-outline-primary' }
            ],
            orderCellsTop: true,
            processing: true,
            serverSide: true,
            scrollX: true,
            scrollY: '50vh',
            ajax: {
                url: "{{ route('report.dataOpname') }}",
                dataSrc: function (json) {
                    $('#totalData').text(json.summary.total);
                    $('#active').text(json.summary.active);
                    $('#inactive').text(json.summary.inactive);
                    $('#YOR').text(json.summary.YOR + '%');
                    $('#IKS').text(json.summary.IKS);
                    $('#MKB').text(json.summary.MKB);
                    return json.data;
                }
            },
            columns: [
                // Pastikan controller mengirim field "action" untuk kolom Edit.
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' },
                { data: 'container_no', name: 'container_no', className: 'text-center' },
                { data: 'ctr_size', name: 'ctr_size', className: 'text-center' },
                { data: 'ctr_type', name: 'ctr_type', className: 'text-center' },
                { data: 'status', name: 'status', className: 'text-center' },
                { data: 'location', name: 'location', className: 'text-center' }
            ],
            createdRow: function (row, data) {
                let backgroundColor = '';
                let textColor = '';

                if (data.status_behandle === null) {
                    if (data.flag_pkb === 'N') {
                        backgroundColor = '#FF8C00';
                        textColor = '#ffffff';
                    } else {
                        backgroundColor = '#cc0a0a';
                        textColor = '#ffffff';
                    }
                } else {
                    switch (Number(data.status_behandle)) {
                        case 1:
                            backgroundColor = '#9df5b1';
                            textColor = '#155724';
                            break;
                        case 2:
                            backgroundColor = '#3273dc';
                            textColor = '#ffffff';
                            break;
                        case 3:
                            backgroundColor = '#f5c6cb';
                            textColor = '#721c24';
                            break;
                    }
                }

                if (backgroundColor) {
                    $(row).find('td').css({
                        'background-color': backgroundColor,
                        'color': textColor
                    });
                }
            },
            initComplete: function () {
                const api = this.api();

                api.columns().every(function (index) {
                    if (index === 0) return;

                    const column = this;
                    const cell = $('#filter-row th').eq(index);

                    if (index === 4) {
                        $('<select class="form-select form-select-sm">' +
                            '<option value="">Semua</option>' +
                            '<option value="active">active</option>' +
                            '<option value="inactive">inactive</option>' +
                        '</select>')
                            .appendTo(cell.empty())
                            .on('change', function () {
                                column.search(this.value).draw();
                            });

                        return;
                    }

                    $('<input type="text" class="form-control form-control-sm" placeholder="Filter...">')
                        .appendTo(cell.empty())
                        .on('keyup change', function () {
                            column.search(this.value).draw();
                        });
                });
            }
        });

        $('#tableBehandle').on('init.dt', function () {
            setTimeout(function () {
                table.columns.adjust();
            }, 100);
        });
    });

    async function editContainer(button) {
        buttonLoading(button);

        const data = {
            id: button.dataset.id
        }

        const url ='{{route('getData.masterContainer')}}';
        const response = await globalResponse(data, url);
        hideButton(button);
        if (response.ok) {
            const hasil = await response.json();
            if (hasil.success) {
                $('#addManual').modal('show');
                $('#container_no').val(hasil.data.container_no);
                $('#id').val(hasil.data.id);
                $('#location').val(hasil.data.location);
                $('#status').val(hasil.data.status).trigger('change');
                $('#ctr_type').val(hasil.data.ctr_type);
                $('#ctr_size').val(hasil.data.ctr_size);
            }else{      
                errorHasil(hasil);                                
            }
        }else{
            return errorResponse(response);
        }
    }

    async function submitContainer(button) {
        const result = await confirmation();
        if (result.isConfirmed) {
            buttonLoading(button);
            const data = {
                id: document.getElementById('id').value,
                ctr_type: document.getElementById('ctr_type').value,
                ctr_size: document.getElementById('ctr_size').value,
                location: document.getElementById('location').value,
                status: document.getElementById('status').value,
            }

            const url = '{{route('report.updateMasterContianer')}}';
            const response = await globalResponse(data, url);
            hideButton(button); 
            if (response.ok) {
                const hasil = await response.json();
                if (hasil.success) {
                    $('#tableBehandle').DataTable().ajax.reload();
                    return successHasil(hasil);
                }else{
                    return errorHasil(hasil);
                }
            }else{
                return errorResponse(response);
            }
        }else{
            return;
        }
    }
</script>
@endsection
