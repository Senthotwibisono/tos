@extends('partial.main')

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
        <div class="card-header">
            <button type="button" class="btn btn-primary" onClick="openModal(this)"><i class="fas fa-plus"></i></button>
        </div>
        <div class="card-body">
            <table class="table table-hover" id="tableSTID">
                <thead>
                    <tr>
                        <th>company</th>
                        <th>card_number</th>
                        <th>stid</th>
                        <th>truck_no</th>
                        <th>vehicle_type</th>
                        <th>merk</th>
                        <th>status</th>
                        <th>uid</th>
                        <th>created_at</th>
                        <th>uid_updated</th>
                        <th>last_update</th>
                        <th>edit</th>
                        <th>delete</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addManual" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="editUserModalLabel">Gate In Modal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <div class="from-group">
            <label for="">Company</label>
            <input type="text" id="company" class="form-control">
            <input type="hidden" id="id" class="form-control">
        </div>
        <div class="from-group">
            <label for="">Card Number</label>
            <input type="text" id="card_number" class="form-control">
        </div>
        <div class="from-group">
            <label for="">STID</label>
            <input type="text" id="stid" class="form-control">
        </div>
        <div class="from-group">
            <label for="">Plat Number</label>
            <input type="text" id="truck_no" class="form-control">
        </div>
        <div class="from-group">
            <label for="">Vechile Type</label>
            <input type="text" id="vehicle_type" class="form-control">
        </div>
        <div class="from-group">
            <label for="">Merk</label>
            <input type="text" id="merk" class="form-control">
        </div>
        <div class="from-group">
            <label for="">Status</label>
            <select id="status" class="form-select">
                <option value="active">active</option>
                <option value="block">block</option>
            </select>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" onClick="submitStid(this)" class="btn btn-primary">Save changes</button>
      </div>

    </div>
  </div>
</div>
@endsection

@section('custom_js')
<script>
    $(document).ready(function() {
        $('#tableSTID').dataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            scrollY: '50vh',
            ajax: '{{route('master.stid.data')}}',
            order: [[3, 'desc']],
            columns: [
                {name: 'company', data: 'company', className:'text-center'},
                {name: 'card_number', data: 'card_number', className:'text-center'},
                {name: 'stid', data: 'stid', className:'text-center'},
                {name: 'truck_no', data: 'truck_no', className:'text-center'},
                {name: 'vehicle_type', data: 'vehicle_type', className:'text-center'},
                {name: 'merk', data: 'merk', className:'text-center'},
                {name: 'status', data: 'status', className:'text-center'},
                {name: 'uid.name', data: 'uid.name', className:'text-center'},
                {name: 'created_at', data: 'created_at', className:'text-center'},
                {name: 'lastupdate.name', data: 'lastupdate.name', className:'text-center'},
                {name: 'last_updated', data: 'last_updated', className:'text-center'},
                {name: 'edit', data: 'edit', className:'text-center'},
                {name: 'delete', data: 'delete', className:'text-center'},
            ],
        });
    })
</script>
<script>
    async function openModal(button) {
        buttonLoading(button);
        $('#addManual').modal('show');
        hideButton(button)
    }

    async function submitStid(button) {
        const result = await confirmation();
        if (result.isConfirmed) {
            buttonLoading(button);
            const company = document.getElementById('company').value;
            const id = document.getElementById('id').value;
            const card_number = document.getElementById('card_number').value;
            const stid = document.getElementById('stid').value;
            const truck_no = document.getElementById('truck_no').value;
            const vehicle_type = document.getElementById('vehicle_type').value;
            const merk = document.getElementById('merk').value;
            const status = document.getElementById('status').value;
            
            const data = {
                company,
                id,
                card_number,
                stid,
                truck_no,
                vehicle_type,
                merk,
                status
            };
            

            const url = '{{route('master.stid.post')}}';
            const response = await globalResponse(data, url);
            hideButton(button);
            if (response.ok) {
                const hasil = await response.json();
                if (hasil.success) {
                    $('#tableSTID').DataTable().ajax.reload();
                    $('#company').val(null).trigger('change');
                    $('#id').val(null).trigger('change');
                    $('#card_number').val(null).trigger('change');
                    $('#stid').val(null).trigger('change');
                    $('#truck_no').val(null).trigger('change');
                    $('#vehicle_type').val(null).trigger('change');
                    $('#merk').val(null).trigger('change');
                    $('#status').val(null).trigger('change');
                   
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

    async function editStid(button) {
        // const result = await confirmation();
        // if (result.isConfirmed) {
            buttonLoading(button);
            const id = button.dataset.id;
            
            const data = {
                id,
            };
            

            const url = '{{route('master.stid.edit')}}';
            const response = await globalResponse(data, url);
            hideButton(button);
            if (response.ok) {
                const hasil = await response.json();
                if (hasil.success) {
                    $('#company').val(hasil.data.company).trigger('change');
                    $('#id').val(hasil.data.id).trigger('change');
                    $('#card_number').val(hasil.data.card_number).trigger('change');
                    $('#stid').val(hasil.data.stid).trigger('change');
                    $('#truck_no').val(hasil.data.truck_no).trigger('change');
                    $('#vehicle_type').val(hasil.data.vehicle_type).trigger('change');
                    $('#merk').val(hasil.data.merk).trigger('change');
                    $('#status').val(hasil.data.status).trigger('change');
                   
                    $('#addManual').modal('show');
                    // successHasil(hasil);
                }else{
                    errorHasil(hasil);
                    return;
                }
            }else{
                errorResponse(response);
                return;
            }
        // }else{
        //     return;
        // }
    }

    async function deleteStid(button) {
        const result = await confirmation();
        if (result.isConfirmed) {
            buttonLoading(button);
            const id = button.dataset.id;
            
            const data = {
                id,
            };
            

            const url = '{{route('master.stid.delete')}}';
            const response = await globalResponse(data, url);
            hideButton(button);
            if (response.ok) {
                const hasil = await response.json();
                if (hasil.success) {
                    $('#tableSTID').DataTable().ajax.reload();
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