@php
    $layout = auth()->user()->hasRole('admin') ? 'partial.main' : 'partial.android';
@endphp

@extends($layout)

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
                                <th>Truck Out Date</th>
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
        <h5 class="modal-title" id="editUserModalLabel">Gate Ambil MTY Modal</h5>
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
            <label for="">Date</label>
            <input type="datetime-local" class="form-control" id="out_mty_date">
        </div>
        <div class="form-group">
            <label for="">Truck No</label>
            <input type="text" class="form-control" id="out_mty_truck">
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
            ajax: '{{route('gate.ambilMt.data')}}',
            order: [[2, 'desc']],
            columns: [
                {name: 'container_no', data: 'container_no', className:'text-center'},
                {name: 'out_mty_date', data: 'out_mty_date', className:'text-center'},
                {name: 'out_mty_truck', data: 'out_mty_truck', className:'text-center'},
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
                        type: 'ambilMt' // <- ini dikirim ke controller
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
                $('#out_mty_date').val(hasil.item.out_mty_date).trigger('change');
                $('#out_mty_truck').val(hasil.item.out_mty_truck).trigger('change');
                successHasil(hasil);
            }else{
                $('#out_mty_truck').val(null).trigger('change');
                $('#out_mty_date').val(null).trigger('change');
                errorHasil(hasil);
                return;
            }
        }else{
            $('#out_mty_truck').val(null).trigger('change');
            $('#out_mty_date').val(null).trigger('change');
            errorResponse(response);
            return;
        }
    }

    async function submitGateIn(button) {
        const result = await confirmation();
        if (result.isConfirmed) {
            buttonLoading(button);
            const container_key = document.getElementById('container_key').value;
            const out_mty_truck = document.getElementById('out_mty_truck').value;
            const out_mty_date = document.getElementById('out_mty_date').value;

            const data = {
                container_key,
                out_mty_truck,
                out_mty_date,
            };

            const url = '{{route('gate.ambilMt.post')}}';
            const response = await globalResponse(data, url);
            hideButton(button);
            if (response.ok) {
                const hasil = await response.json();
                if (hasil.success) {
                    $('#tableGateIn').DataTable().ajax.reload();
                    $('#out_mty_truck').val(null).trigger('change');
                    $('#out_mty_date').val(null).trigger('change');
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

            const url = '{{route('gate.ambilMt.cancel')}}';
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