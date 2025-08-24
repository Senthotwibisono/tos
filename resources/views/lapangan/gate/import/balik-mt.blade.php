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
                                <th>Truck No</th>
                                <th>Tanggal MT</th>
                                <th>Depo MT</th>
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
        <h5 class="modal-title" id="editUserModalLabel">Gate MT Balik IKS/MKB Modal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <div class="form-group">
            <label for="">Container</label>
            <select class="form-select select2" id="container_key" style="height: 150%; width: 100%;">
              <option disabeled selected value>Pilih Satu !</option>
            </select>
        </div>
        <div class="form-group">
            <label for="">MTY Date</label>
            <input type="datetime-local" class="form-control" id="mty_date">
        </div>
        <div class="form-group">
            <label for="">Depo MTY</label>
            <select name="" id="depo_mty" class="form-control">
                <option value="IKS">IKS</option>
                <option value="MKB">MKB</option>
            </select>
        </div>
        <div class="form-group">
            <label for="">Truck No</label>
            <input type="text" class="form-control" id="truck_no_mty">
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
            ajax: '{{route('gate.balikMt.data')}}',
            order: [[2, 'desc']],
            columns: [
                {name: 'container_no', data: 'container_no', className:'text-center'},
                {name: 'truck_no_mty', data: 'truck_no_mty', className:'text-center'},
                {name: 'mty_date', data: 'mty_date', className:'text-center'},
                {name: 'depo_mty', data: 'depo_mty', className:'text-center'},
                {name: 'cancel', data: 'cancel', className:'text-center', sortable: false},
            ],
        });

        $('#container_key').select2({
            theme: "bootstrap-5",
            placeholder: "Pilih Container",
            allowClear: true,
            dropdownParent: $('#addManual'),
            ajax: {
                url: "{{route('getData.containerSelect')}}",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term,
                        page: params.page || 1,
                        type: 'balikMt' // <- ini dikirim ke controller
                    };
                },
                processResults: function(response) {
                    return {
                        results: $.map(response.data, function(item) {
                            return {
                                id: item.container_key, // ID utama
                                text: item.container_no, // Teks yang ditampilkan di dropdown
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

        $('#container_key').on('change', function() {
            const container_key = $(this).val();
            if (container_key) { // akan false kalau null, undefined, atau ""
                getJobData(container_key);
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

    async function getJobData(container_key) {
        showLoading();
        const data = {
            container_key
        };
        
        const url = "{{route('getData.container')}}";

        const response = await globalResponse(data, url);
        hideLoading();
        if (response.ok) {
            const hasil = await response.json();
            if (hasil.success) {
                $('#mty_date').val(hasil.item.mty_date).trigger('change');
                $('#truck_no_mty').val(hasil.item.truck_no_mty).trigger('change');
                $('#depo_mty').val(hasil.item.depo_mty).trigger('change');
                successHasil(hasil);
            }else{
                $('#mty_date').val(null).trigger('change');
                $('#truck_no_mty').val(null).trigger('change');
                errorHasil(hasil);
                return;
            }
        }else{
            $('#mty_date').val(null).trigger('change');
            $('#truck_no_mty').val(null).trigger('change');
            errorResponse(response);
            return;
        }
    }

    async function submitGateIn(button) {
        const result = await confirmation();
        if (result.isConfirmed) {
            buttonLoading(button);
            const container_key = document.getElementById('container_key').value;
            const truck_no_mty = document.getElementById('truck_no_mty').value;
            const depo_mty = document.getElementById('depo_mty').value;
            const mty_date = document.getElementById('mty_date').value;

            const data = {
                container_key,
                truck_no_mty,
                depo_mty,
                mty_date,
            };

            const url = '{{route('gate.balikMt.post')}}';
            const response = await globalResponse(data, url);
            hideButton(button);
            if (response.ok) {
                const hasil = await response.json();
                if (hasil.success) {
                    $('#tableGateIn').DataTable().ajax.reload();
                    $('#mty_date').val(null).trigger('change');
                    $('#truck_no_mty').val(null).trigger('change');
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

            const url = '{{route('gate.balikMt.cancel')}}';
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