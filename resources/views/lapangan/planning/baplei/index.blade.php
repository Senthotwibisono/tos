@extends('partial.main')

@section('content')

<div class="page-content">
    <div class="card">
        <div class="card-content">
            <div class="card-header">
                <button type="button" class="btn btn-primary" onClick="addContainer(this)"><i class="fas fa-plus"></i></button>
            </div>
            <div class="card-body">
                <div class="table">
                    <table class="table-hover" id="tableBaplei">
                        <thead style="white-space: nowrap;">
                            <tr>
                                <th>Contianer No</th>
                                <th>Vessel</th>
                                <th>Voy_no</th>
                                <th>Size</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Gross</th>
                                <th>Slot</th>
                                <th>Row</th>
                                <th>Tier</th>
                                <th>Load Port</th>
                                <th>Disc Port</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade text-left" id="modalBaplei" role="dialog" aria-labelledby="myModalLabel110" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title white" id="myModalLabel110">Data Schedule</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="divider divider-left">
                    <div class="divider-text">
                        Container Fill
                    </div>
                </div>
                <div class="row">
                    <div class="col-3">
                        <div class="form-group">
                            <label for="">Container No</label>
                            <input type="text" class="form-control" id="container_no">
                            <input type="hidden" class="form-control" id="container_key">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="">ISO Code</label>
                            <select name="" id="isocode" class="selectSingle form-select" style="width: 100%">
                                <option disabled selected value>Pilih Satu</option>
                                @foreach($isoCodes as $iso)
                                    <option value="{{$iso->iso_code}}">{{$iso->iso_code}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="">Type</label>
                            <input type="text" class="form-control" id="ctr_type">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="">Size</label>
                            <select name="" id="ctr_size" class="selectSingle form-select" style="width: 100%">
                                <option disabled selected value>Pilih Satu</option>
                                <option value="20">20</option>
                                <option value="22">22</option>
                                <option value="40">40</option>
                                <option value="42">42</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="">Type</label>
                            <select name="" id="ctr_status" class="selectSingle form-select" style="width: 100%">
                                <option disabled selected value>Pilih Satu</option>
                                <option value="FCL">FCL</option>
                                <option value="MTY">MTY</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="">Gross Class</label>
                            <select name="" id="gross_class" class="selectSingle form-select" style="width: 100%">
                                <option disabled selected value>Pilih Satu</option>
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="L">L</option>
                                <option value="XL">XL</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="">Gross</label>
                            <input type="text" class="form-control" id="gross">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="">BL NO</label>
                            <input type="text" class="form-control" id="bl_no">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="">Seal No</label>
                            <input type="text" class="form-control" id="seal_no">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="">Commodity Name</label>
                            <input type="text" class="form-control" id="commodity_name">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="">Imo Code</label>
                            <input type="text" class="form-control" id="imocode" value="1" readonly>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="">Dangerous</label>
                            <select name="" id="dangerous" class="selectSingle form-select" style="width: 100%">
                                <option disabled selected value>Pilih Satu</option>
                                <option value="Y">Y</option>
                                <option value="N">N</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="">Dangerous Label</label>
                            <select name="" id="dangerous_label" class="selectSingle form-select" style="width: 100%">
                                <option disabled selected value>Pilih Satu</option>
                                <option value="Y">Y</option>
                                <option value="N">N</option>
                            </select>
                        </div>
                    </div>
                     <div class="col-3">
                        <div class="form-group">
                            <label for="">Over Height</label>
                            <input type="number" step="any" class="form-control" id="over_height">
                        </div>
                    </div>
                     <div class="col-3">
                        <div class="form-group">
                            <label for="">Over Weight</label>
                            <input type="number" step="any" class="form-control" id="over_weight">
                        </div>
                    </div>
                     <div class="col-3">
                        <div class="form-group">
                            <label for="">Over Lenght</label>
                            <input type="number" step="any" class="form-control" id="over_lenght">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="">Child Temp</label>
                            <input type="text" class="form-control" id="child_temp">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="">Agent</label>
                            <input type="text" class="form-control" id="agent">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="">Relokasi Ke Pelindo</label>
                            <select name="" id="relokasi_flag" class="form-control">
                                <option value="N">N</option>
                                <option value="Y">Y</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="divider divider-left">
                        <div class="divider-text">
                            Vessel Fill
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="">Vessel</label>
                            <select name="" id="vessel" class="selectSingle form-select" style="width: 100%">
                                <option disabled selectedd value>Pilih Satu</option>
                                @foreach($vessels as $vessel)
                                    <option value="{{$vessel->ves_id}}">{{$vessel->ves_name}}//{{$vessel->voy_out}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="">CTR OPR</label>
                            <input type="text" class="form-control" id="ctr_opr">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="">DESC SEQ</label>
                            <input type="text" class="form-control" id="disc_load_seq">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="">Disc/Tran/Shift</label>
                            <input type="text" class="form-control" id="disc_load_trans_shift">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="">Bay Slot</label>
                            <input type="text" class="form-control" id="bay_slot">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="">Bay Row</label>
                            <input type="text" class="form-control" id="bay_row">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="">Bay Tier</label>
                            <input type="text" class="form-control" id="bay_tier">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="">Load Port</label>
                            <select name="" id="load_port" class="selectSingle form-select" style="width: 100%">
                                <option disabled selected value>Pilih Satu!</option>
                                @foreach($ports as $pm)
                                    <option value="{{$pm->port}}">{{$pm->port}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="">Disc Port</label>
                            <select name="" id="disc_port" class="selectSingle form-select" style="width: 100%">
                                <option disabled selected value>Pilih Satu!</option>
                                @foreach($ports as $pm)
                                    <option value="{{$pm->port}}">{{$pm->port}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Close</span>
                </button>
                <button type="submit" class="btn btn-warning" id="saveButton" onClick="postVoyage(this)">
                    <span class="d-none d-sm-block">Confirm</span>
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('custom_js')

<script>
    $(document).ready(function() {
        $('#tableBaplei').dataTable({
            serverSide: true,
            processing: true,
            scrollX: true,
            scrollY: '50vh',
            ajax: '{{route('planning.baplei.data')}}',
            columns: [
                {name: 'container_no', data: 'container_no', className: 'text-center'},
                {name: 'ves_name', data: 'ves_name', className: 'text-center'},
                {name: 'voy_no', data: 'voy_no', className: 'text-center'},
                {name: 'ctr_size', data: 'ctr_size', className: 'text-center'},
                {name: 'ctr_type', data: 'ctr_type', className: 'text-center'},
                {name: 'ctr_status', data: 'ctr_status', className: 'text-center'},
                {name: 'gross', data: 'gross', className: 'text-center'},
                {name: 'bay_slot', data: 'bay_slot', className: 'text-center'},
                {name: 'bay_row', data: 'bay_row', className: 'text-center'},
                {name: 'bay_tier', data: 'bay_tier', className: 'text-center'},
                {name: 'load_port', data: 'load_port', className: 'text-center'},
                {name: 'disch_port', data: 'disch_port', className: 'text-center'},
                {name: 'edit', data: 'edit', className: 'text-center', searchable: false, sortable: false},
            ]
        })

        $('#isocode').on('change', function () {
            const iso_code = $(this).val();
            if (iso_code != null) {
                getIso(iso_code);
            }
            return;
        })
    })
</script>

<script>
    async function getIso(iso_code) {
        showLoading();
        const data = {
            iso_code
        };
        const url = '{{route('getData.iso')}}';
        const response = await globalResponse(data, url);
        hideLoading();
        if (response.ok) {
            const hasil = await response.json();
            if (hasil.success) {
                $('#modalBaplei #ctr_type').val(hasil.data.iso_type).trigger('change');
                $('#modalBaplei #ctr_size').val(hasil.data.iso_size).trigger('change');
            }else{
                errorHasil(hasil);
                return;
            }
        }else{
            errorResponse(response);
            return;
        }
    }

    async function addContainer(button) {
        buttonLoading(button);
        $('#modalBaplei #container_no').val(null).trigger('change');
        $('#modalBaplei #container_key').val(null).trigger('change');
        $('#modalBaplei #isocode').val(null).trigger('change');
        $('#modalBaplei #ctr_type').val(null).trigger('change');
        $('#modalBaplei #ctr_size').val(null).trigger('change');
        $('#modalBaplei #ctr_status').val(null).trigger('change');
        $('#modalBaplei #gross_class').val(null).trigger('change');
        $('#modalBaplei #gross').val(null).trigger('change');
        $('#modalBaplei #bl_no').val(null).trigger('change');
        $('#modalBaplei #seal_no').val(null).trigger('change');
        $('#modalBaplei #commodity_name').val(null).trigger('change');
        $('#modalBaplei #imocode').val(null).trigger('change');
        $('#modalBaplei #dangerous').val(null).trigger('change');
        $('#modalBaplei #dangerous_label').val(null).trigger('change');
        $('#modalBaplei #over_height').val(null).trigger('change');
        $('#modalBaplei #over_weight').val(null).trigger('change');
        $('#modalBaplei #over_lenght').val(null).trigger('change');
        $('#modalBaplei #child_temp').val(null).trigger('change');
        $('#modalBaplei #agent').val(null).trigger('change');
        // $('#modalBaplei #relokasi_flag').val(null).trigger('change');
        $('#modalBaplei #vessel').val(null).trigger('change');
        $('#modalBaplei #ctr_opr').val(null).trigger('change');
        $('#modalBaplei #disc_load_seq').val(null).trigger('change');
        $('#modalBaplei #disc_load_trans_shift').val(null).trigger('change');
        $('#modalBaplei #bay_slot').val(null).trigger('change');
        $('#modalBaplei #bay_row').val(null).trigger('change');
        $('#modalBaplei #bay_tier').val(null).trigger('change');
        $('#modalBaplei #load_port').val(null).trigger('change');
        $('#modalBaplei #disc_port').val(null).trigger('change');
        $('#modalBaplei').modal('show');
        hideButton(button);
    }

    async function eidtBaplei(button) {
        buttonLoading(button);
        const container_key = button.dataset.id;
        const data = {
            container_key
        };
        const url = '{{route('getData.container')}}';

        const response = await globalResponse(data, url);
        hideButton(button);
        if (response.ok) {
            const hasil = await response.json();
            if (hasil.success) {
                $('#modalBaplei #container_no').val(hasil.data.container_no).trigger('change');
                $('#modalBaplei #container_key').val(hasil.data.container_key).trigger('change');
                $('#modalBaplei #isocode').val(hasil.data.iso_code).trigger('change');
                $('#modalBaplei #ctr_type').val(hasil.data.ctr_type).trigger('change');
                $('#modalBaplei #ctr_size').val(hasil.data.ctr_size).trigger('change');
                $('#modalBaplei #ctr_status').val(hasil.data.ctr_status).trigger('change');
                $('#modalBaplei #gross_class').val(hasil.data.gross_class).trigger('change');
                $('#modalBaplei #gross').val(hasil.data.gross).trigger('change');
                $('#modalBaplei #bl_no').val(hasil.data.bl_no).trigger('change');
                $('#modalBaplei #seal_no').val(hasil.data.seal_no).trigger('change');
                $('#modalBaplei #commodity_name').val(hasil.data.commodity_name).trigger('change');
                $('#modalBaplei #imocode').val(hasil.data.imocode).trigger('change');
                $('#modalBaplei #dangerous').val(hasil.data.dangerous_yn).trigger('change');
                $('#modalBaplei #dangerous_label').val(hasil.data.dangerous_label_yn).trigger('change');
                $('#modalBaplei #over_height').val(hasil.data.over_height).trigger('change');
                $('#modalBaplei #over_weight').val(hasil.data.over_weight).trigger('change');
                $('#modalBaplei #over_lenght').val(hasil.data.over_lenght).trigger('change');
                $('#modalBaplei #child_temp').val(hasil.data.chilled_temp).trigger('change');
                $('#modalBaplei #agent').val(hasil.data.agent).trigger('change');
                $('#modalBaplei #relokasi_flag').val(hasil.data.relokasi_flag).trigger('change');
                $('#modalBaplei #vessel').val(hasil.data.ves_id).trigger('change');
                $('#modalBaplei #ctr_opr').val(hasil.data.ctr_opr).trigger('change');
                $('#modalBaplei #disc_load_seq').val(hasil.data.disc_load_seq).trigger('change');
                $('#modalBaplei #disc_load_trans_shift').val(hasil.data.disc_load_trans_shift).trigger('change');
                $('#modalBaplei #bay_slot').val(hasil.data.bay_slot).trigger('change');
                $('#modalBaplei #bay_row').val(hasil.data.bay_row).trigger('change');
                $('#modalBaplei #bay_tier').val(hasil.data.bay_tier).trigger('change');
                $('#modalBaplei #load_port').val(hasil.data.load_port).trigger('change');
                $('#modalBaplei #disc_port').val(hasil.data.disch_port).trigger('change');
                $('#modalBaplei').modal('show');
                successHasil(hasil);
            }else{
                errorHasil(hasil);
                return;
            }
        }else{
            errorResponse(response);
            return;
        }
    }

    async function postVoyage(button) {
        const result = await confirmation(button);
        if (result.isConfirmed) {
            const container_no = document.getElementById('container_no').value;
            const container_key = document.getElementById('container_key').value;
            const isocode = document.getElementById('isocode').value;
            const ctr_type = document.getElementById('ctr_type').value;
            const ctr_size = document.getElementById('ctr_size').value;
            const ctr_status = document.getElementById('ctr_status').value;
            const gross_class = document.getElementById('gross_class').value;
            const gross = document.getElementById('gross').value;
            const bl_no = document.getElementById('bl_no').value;
            const seal_no = document.getElementById('seal_no').value;
            const commodity_name = document.getElementById('commodity_name').value;
            const imocode = document.getElementById('imocode').value;
            const dangerous = document.getElementById('dangerous').value;
            const dangerous_label = document.getElementById('dangerous_label').value;
            const over_height = document.getElementById('over_height').value;
            const over_weight = document.getElementById('over_weight').value;
            const over_lenght = document.getElementById('over_lenght').value;
            const child_temp = document.getElementById('child_temp').value;
            const relokasi_flag = document.getElementById('relokasi_flag').value;
            const vessel = document.getElementById('vessel').value;
            const ctr_opr = document.getElementById('ctr_opr').value;
            const disc_load_seq = document.getElementById('disc_load_seq').value;
            const disc_load_trans_shift = document.getElementById('disc_load_trans_shift').value;
            const bay_slot = document.getElementById('bay_slot').value;
            const bay_row = document.getElementById('bay_row').value;
            const bay_tier = document.getElementById('bay_tier').value;
            const load_port = document.getElementById('load_port').value;
            const disc_port = document.getElementById('disc_port').value;
            const data = {
                container_no,
                container_key,
                isocode,
                ctr_type,
                ctr_size,
                ctr_status,
                gross_class,
                gross,
                bl_no,
                seal_no,
                commodity_name,
                imocode,
                dangerous,
                dangerous_label,
                over_height,
                over_weight,
                over_lenght,
                child_temp,
                relokasi_flag,
                vessel,
                ctr_opr,
                disc_load_seq,
                disc_load_trans_shift,
                bay_slot,
                bay_row,
                bay_tier,
                load_port,
                disc_port,
            };
        
            const url = '{{route('planning.baplei.post')}}';
            const response = await globalResponse(data, url);
            hideButton(button);
            if (response.ok) {
                const hasil = await response.json();
                if (hasil.success) {
                    $('#modalBaplei').modal('hide');
                    successHasil(hasil);
                    $('#tableBaplei').DataTable().ajax.reload();
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