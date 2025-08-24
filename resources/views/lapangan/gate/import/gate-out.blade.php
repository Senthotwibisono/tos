@extends(auth()->user()->role == 'admin' ? 'partial.main' : 'partial.android')

@section('custom_styles')
<style>
    .select2-container--bootstrap-5 .select2-selection {
        border: 1px solid rgb(0, 0, 0) !important; /* Border berwarna biru */
        border-radius: 1px; /* Agar sudutnya sedikit melengkung */
        padding: 6px; /* Tambahkan padding agar terlihat lebih rapi */
        height: 100%;
    }   

    .select2-container--bootstrap-5 .select2-selection:focus {
        border-color: #0056b3 !important; /* Border berubah saat fokus */
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); /* Efek shadow saat fokus */
    }
</style>
@endsection

@section('content')

<div class="page-content">
    <div class="card">
        <div class="card-content">
            <div class="card-header">
                <button type="button" class="btn btn-primary" onClick="addContainer(this)"><i class="fas fa-plus"></i></button>
            </div>
            <div class="card-body">
                <div class="table">
                    <table class="table-hover table-stripped" id="tableGateIn">
                        <thead style="white-space: nowrap;">
                            <tr>
                                <th>Container No</th>
                                <th>Bongkar/Muat</th>
                                <th>Truck No</th>
                                <th>Time Out</th>
                                <th>Cancel</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="addManual" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="editUserModalLabel">Gate Out Modal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <div class="from-group">
            <label for="">Tipe</label>
            <select id="type" class="form-select">
                <option value="I">Bongkar</option>
                <option value="E">Muat</option>
            </select>
        </div>
        <div class="form-group">
            <label for="">Job Order</label>
            <select class="form-select select2" id="job_no" style="height: 150%; width: 100%;">
              <option disabeled selected value>Pilih Satu !</option>
            </select>
        </div>
        <div class="form-group">
            <label for="">Container No</label>
            <input type="text" class="form-control" id="container_no" readonly>
            <input type="text" class="form-control" id="container_key" readonly>
        </div>
        <div class="form-group">
            <label for="">Active To</label>
            <input type="datetime-local" class="form-control" id="active_to" readonly>
        </div>
        <div class="form-group">
            <label for="">Truck Out Date</label>
            <input type="datetime-local" class="form-control" id="truck_in_date">
        </div>
        <div class="form-group">
            <label for="">Keterangan</label>
            <input type="text" class="form-control" id="keterangan" readonly>
        </div>
        <div class="form-group">
            <label for="">Truck No</label>
            <input type="text" class="form-control" id="truck_no">
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" onClick="submitGateIn(this)" class="btn btn-primary">Save changes</button>
      </div>

    </div>
  </div>
</div>
@endsection

@section('custom_js')
<script>
    $(document).ready(function() {
        $('#tableGateIn').dataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            scrollY: '50vh',
            ajax: '{{route('gate.import.dataOut')}}',
            order: [[3, 'desc']],
            columns: [
                {name: 'container_no', data: 'container_no', className:'text-center'},
                {name: 'iet', data: 'iet', className:'text-center'},
                {name: 'truck_no', data: 'truck_no', className:'text-center'},
                {name: 'truck_out_date', data: 'truck_out_date', className:'text-center'},
                {name: 'cancel', data: 'cancel', className:'text-center', sortable: false},
            ],
        });

        $('#job_no').select2({
            theme: "bootstrap-5",
            placeholder: "Pilih Job Order Number",
            allowClear: true,
            dropdownParent: $('#addManual'),
            ajax: {
                url: "{{route('getData.jobImport')}}",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term,
                        page: params.page || 1,
                        type: $('#type').val() // <- ini dikirim ke controller
                    };
                },
                processResults: function(response) {
                    return {
                        results: $.map(response.data, function(item) {
                            return {
                                id: item.job_no, // ID utama
                                text: item.job_no, // Teks yang ditampilkan di dropdown
                            };
                        }),
                        pagination: {
                            more: response.more
                        }
                    };
                },
                cache: true
            }
        });

        $('#type').on('change', function() {
            $('#job_no').val(null).trigger('change');
            $('#container_no').val(null).trigger('change');
            $('#container_key').val(null).trigger('change');
            $('#keterangan').val(null).trigger('change');
            $('#truck_in_date').val(null).trigger('change');
            $('#truck_no').val(null).trigger('change');
        });

        $('#job_no').on('change', function() {
            const jobNo = $(this).val();
            if (jobNo) { // akan false kalau null, undefined, atau ""
                getJobData(jobNo);
            }
        })
    })
</script>

<script>
    async function addContainer(button) {
        buttonLoading(button);
        $('#addManual').modal('show');
        hideButton(button);
    }

    async function getJobData(jobNo) {
        showLoading();
      
        const type = document.getElementById('type').value;

        const data = {
            jobNo,
            type
        };
        
        const url = "{{route('getData.jobImportDetilOut')}}";

        const response = await globalResponse(data, url);
        hideLoading();
        if (response.ok) {
            const hasil = await response.json();
            if (hasil.success) {
                $('#active_to').val(hasil.data.active_to).trigger('change');
                $('#container_no').val(hasil.item.container_no).trigger('change');
                $('#container_key').val(hasil.item.container_key).trigger('change');
                $('#keterangan').val(hasil.keterangan).trigger('change');
                $('#truck_in_date').val(hasil.item.truck_out_date).trigger('change');
                $('#truck_no').val(hasil.item.truck_no).trigger('change');
                successHasil(hasil);
            }else{
                $('#container_no').val(null).trigger('change');
                $('#container_key').val(null).trigger('change');
                $('#keterangan').val(null).trigger('change');
                $('#truck_in_date').val(null).trigger('change');
                $('#truck_no').val(null).trigger('change');
                errorHasil(hasil);
                return;
            }
        }else{
            $('#container_no').val(null).trigger('change');
            $('#container_key').val(null).trigger('change');
            $('#keterangan').val(null).trigger('change');
            $('#truck_in_date').val(null).trigger('change');
            $('#truck_no').val(null).trigger('change');
            errorResponse(response);
            return;
        }
    }

    async function submitGateIn(button) {
        const result = await confirmation();
        if (result.isConfirmed) {
            buttonLoading(button);
            const container_key = document.getElementById('container_key').value;
            const truck_no = document.getElementById('truck_no').value;
            const active_to = document.getElementById('active_to').value;
            const truck_in_date = document.getElementById('truck_in_date').value;

            // if (!truck_in_date) {
            //     hideButton(button);
            //     Swal.fire({
            //         icon: 'error',
            //         title: 'Tidak Lengkap',
            //         text: 'Active To Kosong',
            //     });
            //     return;
            // }

            // if (active_to < truck_in_date) {
            //     hideButton(button);
            //     Swal.fire({
            //         icon: 'error',
            //         title: 'Kadaluarsa',
            //         text: 'Job order expired, perlu perpanjangan',
            //     });
            //     return;
            // }
            const data = {
                container_key,
                truck_no,
                active_to,
                truck_in_date,
            };

            const url = '{{route('gate.import.postOut')}}';
            const response = await globalResponse(data, url);
            hideButton(button);
            if (response.ok) {
                const hasil = await response.json();
                if (hasil.success) {
                    $('#tableGateIn').DataTable().ajax.reload();
                    $('#container_no').val(null).trigger('change');
                    $('#container_key').val(null).trigger('change');
                    $('#keterangan').val(null).trigger('change');
                    $('#truck_in_date').val(null).trigger('change');
                    $('#truck_no').val(null).trigger('change');
                    $('#addManual').modal('hide');
                    successHasil(hasil);
                }else{
                    errorHasil(hasil);
                    return;
                }
            }else{
                errorResponse(response);
                return;
            }
        }else{
            return;
        }
    }

    async function cancelGateIn(button) {
        const result = await confirmation();
        if (result.isConfirmed) {
            buttonLoading(button);
            const data = {
                container_key : button.dataset.id,
                tipe : button.dataset.tipe
            };

            const url = '{{route('gate.import.cancelIn')}}';
            const response = await globalResponse(data, url);
            hideButton(button);
            if (response.ok) {
                const hasil = await response.json();
                if (hasil.success) {
                    $('#tableGateIn').DataTable().ajax.reload();
                    successHasil(hasil);
                }else{
                    errorHasil(hasil);
                    return;
                }
            }else{
                errorResponse(response);
                return;
            }
        }else{
            return;
        }
    }
</script>
@endsection