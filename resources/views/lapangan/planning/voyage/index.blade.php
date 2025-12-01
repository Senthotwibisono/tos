@extends('partial.main')

@section('content')

<div class="card">
    <div class="card-content">
        <div class="card-header">
            <div class="button-container">
                <button type="button" class="btn btn-primary" onClick="createButton(this)"><i class="fas fa-plus"></i></button>
            </div>
        </div>
        <div class="card-body">
            <div class="table">
                <table class="table-hover" id="tableVessel">
                    <thead style="white-space: nowrap;">
                        <tr>
                            <th>Ves Name</th>
                            <th>Ves Code</th>
                            <th>Agent</th>
                            <th>Voy In</th>
                            <th>Voy Out</th>
                            <th>Estimate Arrival</th>
                            <th>Actual Arrival</th>
                            <th>Estimate Departure</th>
                            <th>Actual Departure</th>
                            <th>Edit</th>
                            <th>Cancel</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade text-left" id="modalVessel" role="dialog" aria-labelledby="myModalLabel110" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title white" id="myModalLabel110">Data Schedule</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="divider divider-left">
                        <div class="divider-text">
                            Vessel Information
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="">Vessel Code</label>
                            <select name="" id="ves_code" class="form-select selectSingle required-field" style="width: 100%;">
                                <option disabled selected value>Pilih Satu</option>
                                @foreach($vessels as $ves)
                                    <option value="{{$ves->ves_code}}">{{$ves->ves_code}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <input type="hidden" id="ves_id">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="">Vessel Name</label>
                            <input type="text" class="form-control" id="ves_name" readonly>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="">Agent</label>
                            <input type="text" class="form-control" id="agent" readonly>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="">Liner</label>
                            <input type="text" class="form-control" id="liner" readonly>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="">Owner</label>
                            <input type="text" class="form-control required-field" id="owner">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="">Ves Length</label>
                            <input type="number" step="any" class="form-control" id="ves_length">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="">Voy In</label>
                            <input type="text" id="voy_in" class="form-control required-field">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="">Voy Out</label>
                            <input type="text" id="voy_out" class="form-control required-field">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="">Flag</label>
                            <input type="text" id="reg_flag" class="form-control">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="">Service</label>
                            <select name="" id="ves_service" class="selectSingle form-control required-field" style="width: 100%">
                                <option disabled selected value>Pilih Satu</option>
                                @foreach($services as $service)
                                    <option value="{{$service->service}}">{{$service->service}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="divider divider-left">
                    <div class="divider-text">
                        Port Information
                    </div>
                </div>
                <div class="row">
                    <div class="col-3">
                        <div class="form-group">
                            <label for="">Origin Port</label>
                            <select name="" id="origin_port" class="form-select" style="width: 100%">
                                <option disabled selected value>Pilih Satu</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="">Next Port</label>
                            <select name="" id="next_port" class="form-select" style="width: 100%">
                                <option disabled selected value>Pilih Satu</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="">Dest Port</label>
                            <select name="" id="dest_port" class="form-select" style="width: 100%">
                                <option disabled selected value>Pilih Satu</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="">last Port</label>
                            <select name="" id="last_port" class="form-select" style="width: 100%">
                                <option disabled selected value>Pilih Satu</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="divider divider-left">
                    <div class="divider-text">
                        Berth Information
                    </div>
                </div>
                <div class="row">
                    <div class="col-3">
                        <label for="">Berth No</label>
                        <select name="" id="berthNo" class="selectSingle form-select" style="width: 100%">
                            <option disabled selected value>Pilih Satu</option>
                            @foreach($berths as $b)
                                <option value="{{$b->berth_no}}">{{$b->berth_no}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3">
                        <label for="">CY Code</label>
                        <select name="" id="cyCode" class="selectSingle form-select required-field" style="width: 100%">
                            <option disabled selected value>Pilih Satu</option>
                            <option value="T">TPK</option>
                            <option value="K">KON</option>
                        </select>
                    </div>
                    <div class="col-3">
                        <label for="">BTOA Side</label>
                        <select name="" id="btoaSide" class="selectSingle form-select required-field" style="width: 100%">
                            <option disabled selected value>Pilih Satu</option>
                            <option value="B">Berth</option>
                            <option value="S">Ship</option>
                        </select>
                    </div>
                    <div class="col-3">
                        <label for="">Berth Grid</label>
                        <input type="text" class="form-control required-field" id="berthGrid">
                    </div>
                </div>
                <div class="divider divider-rleft">
                    <div class="divider-text">
                        Booking Information
                    </div>
                </div>
                <div class="table">
                    <table class="table table-stripped">
                        <thead>
                            <tr>
                                <th>-</th>
                                <th>Import</th>
                                <th>Export</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Booking</td>
                                <td><input type="number" step="any" name="" id="booking_import" class="form-control"></td>
                                <td><input type="number" step="any" name="" id="booking_export" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>Counter</td>
                                <td><input type="number" step="any" name="" id="counter_import" class="form-control" readonly></td>
                                <td><input type="number" step="any" name="" id="counter_export" class="form-control" readonly></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="divider divider-left">
                    <div class="divider-text">
                        Time Schedule
                    </div>
                </div>
                <div class="table">
                    <table class="table table-stripped">
                        <thead>
                            <tr>
                                <th>-</th>
                                <th>Estimate</th>
                                <th>Ariival</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Arrival Date</td>
                                <td><input type="datetime-local" name="" id="eta_date" class="form-control" value="{{ carbon\carbon::now()}}"></td>
                                <td><input type="datetime-local" name="" id="arrival_date" class="form-control" value="{{ carbon\carbon::now()}}"></td>
                            </tr>
                            <tr>
                                <td>Waktu Sandar</td>
                                <td><input type="datetime-local" name="" id="est_berthing_date" class="form-control" value="{{ carbon\carbon::now()}}"></td>
                                <td><input type="datetime-local" name="" id="berthing_date" class="form-control" value="{{ carbon\carbon::now()}}"></td>
                            </tr>
                            <tr>
                                <td>Mulai Kerja</td>
                                <td><input type="datetime-local" name="" id="est_start_work_date" class="form-control" value="{{ carbon\carbon::now()}}"></td>
                                <td><input type="datetime-local" name="" id="act_start_work_date" class="form-control" value="{{ carbon\carbon::now()}}"></td>
                            </tr>
                            <tr>
                                <td>Selesai Kerja</td>
                                <td><input type="datetime-local" name="" id="est_end_work_date" class="form-control" value="{{ carbon\carbon::now()}}"></td>
                                <td><input type="datetime-local" name="" id="act_end_work_date" class="form-control" value="{{ carbon\carbon::now()}}"></td>
                            </tr>
                            <tr>
                                <td>Departure Date</td>
                                <td><input type="datetime-local" name="" id="etd_date" class="form-control" value="{{ carbon\carbon::now()}}"></td>
                                <td><input type="datetime-local" name="" id="deparature_date" class="form-control" value="{{ carbon\carbon::now()}}"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-4">
                        <label for="">Open Stack Date</label>
                        <input type="datetime-local" name="" id="open_stack_date" class="form-control" value="{{ carbon\carbon::now()}}">
                    </div>
                    <div class="col-4">
                        <label for="">Clossing Date</label>
                        <input type="datetime-local" name="" id="doc_clossing_date" class="form-control" value="{{ carbon\carbon::now()}}">
                    </div>
                    <div class="col-4">
                        <label for="">Cargo Clossing Date</label>
                        <input type="datetime-local" name="" id="clossing_date" class="form-control" value="{{ carbon\carbon::now()}}">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Close</span>
                </button>
                <button type="submit" class="btn btn-warning"  onClick="postVoyage(this)">
                    <span class="d-none d-sm-block">Confirm</span>
                </button>
            </div>
        </div>
    </div>
</div>


@endsection

@section('custom_js')

<script>
    $('document').ready(function() {
        $('#tableVessel').dataTable({
            serverSide: true,
            processing: true,
            scrollX: true,
            scrollY: '50vh',
            ajax: '{{route('planning.voyage.data')}}',
            columns: [
                {data:'ves_name', name:'ves_name', className:'text-center'},
                {data:'ves_code', name:'ves_code', className:'text-center'},
                {data:'agent', name:'agent', className:'text-center'},
                {data:'voy_in', name:'voy_in', className:'text-center'},
                {data:'voy_out', name:'voy_out', className:'text-center'},
                {data:'eta_date', name:'eta_date', className:'text-center'},
                {data:'arrival_date', name:'arrival_date', className:'text-center'},
                {data:'etd_date', name:'etd_date', className:'text-center'},
                {data:'deparature_date', name:'deparature_date', className:'text-center'},
                {data:'edit', name:'edit', className:'text-center', oderable: false, searchable: false, sortable: false},
                {data:'delete', name:'delete', className:'text-center', oderable: false, searchable: false, sortable: false},
            ]
        })

        $('#ves_code').on('change', async function(){
            const vesCode = $(this).val();
            const data = await getVessel(vesCode);
            if (data) {
                $('#ves_name').val(data.ves_name);
                $('#agent').val(data.agent);
                $('#liner').val(data.liner);
                $('#ves_length').val(data.ves_length);
                $('#reg_flag').val(data.reg_flag);
            }
        });

        $('#ves_service').on('change', async function () {
            const service = $(this).val();
            $.ajax({
                type: 'POST',
                url: '/origin',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data : {service},
                cache: false,
                
                success: function(msg){
                $('#origin_port, #next_port, #last_port, #dest_port').html(msg);
            
                },
                error: function(data){
                    console.log('error:',data)
                },
            })
        })
    });
</script>


<script>
    async function createButton(button) {
        buttonLoading(button);
        $('#ves_code').val(null).trigger('change');
        $('#ves_name').val(null);
        $('#ves_id').val(null);
        $('#agent').val(null);
        $('#liner').val(null);
        $('#owner').val(null);
        $('#ves_length').val(null);
        $('#voy_in').val(null);
        $('#voy_out').val(null);
        $('#reg_flag').val(null);
        $('#ves_service').val(null).trigger('change');
        $('#origin_port').val(null).trigger('change');
        $('#next_port').val(null).trigger('change');
        $('#dest_port').val(null).trigger('change');
        $('#last_port').val(null).trigger('change');
        $('#berthNo').val(null).trigger('change');
        $('#cyCode').val(null).trigger('change');
        $('#btoaSide').val(null).trigger('change');
        $('#berthGrid').val(null);
        $('#booking_import').val(null);
        $('#booking_export').val(null);
        $('#counter_import').val(null);
        $('#counter_export').val(null);
        $('#modalVessel').modal('show');
        
        hideButton(button);
    }

    async function getVessel(vesCode) {
        showLoading();
        const data = {
            vesCode
        };
        const url = '{{route('getData.vessel')}}';
        const response = await globalResponse(data, url);
        hideLoading();
        if (response.ok) {
            const hasil = await response.json();
            if (hasil.success) {
                const resultData = hasil.data;
                return resultData;
            }else{
                errorHasil();
                return;
            }
        }else{
            errroResponse(response);
            return;
        }
    }

    async function postVoyage(button) {
        const result = await confirmation();
        if (result.isConfirmed) {
            buttonLoading(button);
            const ves_code = document.getElementById('ves_code').value;
            const ves_id = document.getElementById('ves_id').value;
            const ves_name = document.getElementById('ves_name').value;
            const agent = document.getElementById('agent').value;
            const liner = document.getElementById('liner').value;
            const owner = document.getElementById('owner').value;
            const ves_length = document.getElementById('ves_length').value;
            const voy_in = document.getElementById('voy_in').value;
            const voy_out = document.getElementById('voy_out').value;
            const reg_flag = document.getElementById('reg_flag').value;
            const ves_service = document.getElementById('ves_service').value;
            const origin_port = document.getElementById('origin_port').value;
            const next_port = document.getElementById('next_port').value;
            const dest_port = document.getElementById('dest_port').value;
            const last_port = document.getElementById('last_port').value;
            const berthNo = document.getElementById('berthNo').value;
            const cyCode = document.getElementById('cyCode').value;
            const btoaSide = document.getElementById('btoaSide').value;
            const berthGrid = document.getElementById('berthGrid').value;
            const booking_import = document.getElementById('booking_import').value;
            const booking_export = document.getElementById('booking_export').value;
            const eta_date = document.getElementById('eta_date').value;
            const arrival_date = document.getElementById('arrival_date').value;
            const est_berthing_date = document.getElementById('est_berthing_date').value;
            const berthing_date = document.getElementById('berthing_date').value;
            const est_start_work_date = document.getElementById('est_start_work_date').value;
            const act_start_work_date = document.getElementById('act_start_work_date').value;
            const est_end_work_date = document.getElementById('est_end_work_date').value;
            const act_end_work_date = document.getElementById('act_end_work_date').value;
            const etd_date = document.getElementById('etd_date').value;
            const deparature_date = document.getElementById('deparature_date').value;
            const open_stack_date = document.getElementById('open_stack_date').value;
            const doc_clossing_date = document.getElementById('doc_clossing_date').value;
            const clossing_date = document.getElementById('clossing_date').value;

            const data = {
                ves_code,
                ves_id,
                ves_name,
                agent,
                liner,
                owner,
                ves_length,
                voy_in,
                voy_out,
                reg_flag,
                ves_service,
                origin_port,
                next_port,
                dest_port,
                last_port,
                berthNo,
                cyCode,
                btoaSide,
                berthGrid,
                booking_import,
                booking_export,
                eta_date,
                arrival_date,
                est_berthing_date,
                berthing_date,
                est_start_work_date,
                act_start_work_date,
                est_end_work_date,
                act_end_work_date,
                etd_date,
                deparature_date,
                open_stack_date,
                doc_clossing_date,
                clossing_date,
            };

            const url = '{{route('planning.voyage.post')}}';

            const response = await globalResponse(data, url);
            hideButton(button);
            if (response.ok) {
                const hasil = await response.json();
                if (hasil.success) {
                    successHasil(hasil).then(() =>  {
                        showLoading();
                        location.reload();
                    });
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

    async function editVoyage(button) {
        buttonLoading(button);
        const id = button.dataset.id;
        const data = {
            id
        };

        const url = '{{route('getData.voyage')}}'
        const response = await globalResponse(data, url);
        hideButton(button);
        if (response.ok) {
            const hasil = await response.json();
            if (hasil) {
                $('#ves_code').val(hasil.data.vessel.ves_code).trigger('change');
                $('#ves_name').val(hasil.data.vessel.ves_name);
                $('#ves_id').val(hasil.data.vessel.ves_id);
                $('#agent').val(hasil.data.vessel.agent);
                $('#liner').val(hasil.data.vessel.liner);
                $('#owner').val(hasil.data.vessel.voyage_owner);
                $('#ves_length').val(hasil.data.vessel.vessel_length);
                $('#voy_in').val(hasil.data.vessel.voy_in);
                $('#voy_out').val(hasil.data.vessel.voy_out);
                $('#reg_flag').val(hasil.data.vessel.reg_flag);
                $('#ves_service').val(hasil.data.vessel.vessel_service).trigger('change');
                $('#origin_port').val(hasil.data.vessel.origin_port).trigger('change');
                $('#next_port').val(hasil.data.vessel.next_port).trigger('change');
                $('#dest_port').val(hasil.data.vessel.dest_port).trigger('change');
                $('#last_port').val(hasil.data.vessel.last_port).trigger('change');
                $('#berthNo').val(hasil.data.vessel.berth_no).trigger('change');
                $('#cyCode').val(hasil.data.vessel.cy_code).trigger('change');
                $('#btoaSide').val(hasil.data.vessel.btoa_side).trigger('change');
                $('#berthGrid').val(hasil.data.vessel.berth_grid);
                $('#booking_import').val(hasil.data.vessel.import_booking);
                $('#booking_export').val(hasil.data.vessel.export_booking);
                $('#counter_import').val(hasil.data.import);
                $('#counter_export').val(hasil.data.export);
                $('#eta_date').val(hasil.data.vessel.eta_date,);
                $('#arrival_date').val(hasil.data.vessel.arrival_date,);
                $('#est_berthing_date').val(hasil.data.vessel.est_berthing_date,);
                $('#berthing_date').val(hasil.data.vessel.berthing_date,);
                $('#est_start_work_date').val(hasil.data.vessel.est_start_work_date,);
                $('#act_start_work_date').val(hasil.data.vessel.act_start_work_date,);
                $('#est_end_work_date').val(hasil.data.vessel.est_end_work_date,);
                $('#act_end_work_date').val(hasil.data.vessel.act_end_work_date,);
                $('#etd_date').val(hasil.data.vessel.etd_date,);
                $('#deparature_date').val(hasil.data.vessel.deparature_date,);
                $('#open_stack_date').val(hasil.data.vessel.open_stack_date,);
                $('#doc_clossing_date').val(hasil.data.vessel.doc_clossing_date,);
                $('#clossing_date').val(hasil.data.vessel.clossing_date,);
                $('#modalVessel').modal('show');
            }else{
                errorHasil(hasil);
                return;
            }
        }else{
            errorResponse(response);
            return;
        }
    }
    
</script>
@endsection