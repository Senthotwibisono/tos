@extends('partial.android')

@section('content')

<section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                <div class="card-header">
                        <h1>Edit Schedule -Form</h1>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                        @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form class="form" method="post" action='/planning/schedule_update={{$vessel_voyage->ves_id}}'>
                                @CSRF
                                @method('PATCH')
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="first-name-column">Vessel Code</label>
                                            <input type="text" id="first-name-vertical" class="form-control" name="ves_code" value="{{ $vessel_voyage->ves_code }}"  disabled>
                                        </div>
                                    </div>
                                   
                                    <div class="col-md-12 col-12">
                                        <div class="form-group">
                                            <label for="-column">Vessel Name</label>
                                            <input type="text" id="first-name-vertical" class="form-control" name="ves_code" value="{{ $vessel_voyage->ves_name }}"  disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="-floating">Voy In</label>
                                            <input type="text" id="first-name-vertical" class="form-control" name="voy_in" value="{{ $vessel_voyage->voy_in }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="-column">Voy Out</label>
                                            <input type="text" id="first-name-vertical" class="form-control" name="voy_out" value="{{ $vessel_voyage->voy_out }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="-column">Agent</label>
                                            <input type="text" id="first-name-vertical" class="form-control" name="ves_code" value="{{ $vessel_voyage->agent }}"  disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="-id-column">Liner</label>
                                            <input type="text" id="first-name-vertical" class="form-control" name="ves_code" value="{{ $vessel_voyage->liner }}"  disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="-id-column">Voy Owner</label>
                                            <input type="text" id="first-name-vertical" class="form-control" name="ves_code" value="{{ $vessel_voyage->voyage_owner }}"  disabled>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                        <input type="checkbox" name="export_yn" value="Y" {{ $vessel_voyage->export_yn == 'Y' ? 'checked' : '' }}> Muat 
                                        <input type="checkbox" name="import_yn" value="Y" {{ $vessel_voyage->import_yn == 'Y' ? 'checked' : '' }}> Bongkar
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-6 col-12">
                                        <div class="form-group">
                                        <label for="-id-column">Liner/Tramp</label>
                                            <input type="text" id="first-name-vertical" class="form-control" name="ves_code" value="{{ $vessel_voyage->liner_tramp }}"  disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="-id-column">Feeder/Direct</label>
                                            <input type="text" id="first-name-vertical" class="form-control" name="ves_code" value="{{ $vessel_voyage->feeder_direct }}"  disabled>                                  
                                        </div>
                                    </div> -->
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="-id-column">Vessel Service</label>
                                            <input type="text" id="first-name-vertical" class="form-control" name="vessel_service" value="{{ $vessel_voyage->vessel_service }}" disabled>
                                            </div>
                                        <!-- <div class="form-group">
                                        <label for="-id-column">Ocean/Interisland</label>
                                        <input type="text" id="first-name-vertical" class="form-control" name="ves_code" value="{{ $vessel_voyage->ocean_interisland }}"  disabled>
                                        </div> -->
                                    </div>
                                    <div class="col-md-12 col-12">
                                        <div class="form-group">
                                            <label for="-id-column">Reg Flag</label>
                                            <input type="text" id="first-name-vertical" class="form-control" name="ves_code" value="{{ $vessel_voyage->red_flag }}"  disabled>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-12 col-12">
                                        <div class="form-group">
                                            <label for="-id-column">No PPK</label>
                                            <input type="text" id="first-name-vertical" class="form-control" name="ves_code" value="{{ $vessel_voyage->no_ppk }}"  disabled>
                                        </div>
                                    </div> -->
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="-id-column">Billing Complete</label>
                                            <input type="text" id="first-name-vertical" class="form-control" name="ves_code" value="{{ $vessel_voyage->billing_complate }}"  disabled>
                                        </div>
                                    </div>
                                    <br>
                                    <hr>
                                    <hr>
                                    <div class="col-md-12 col-12">
                                        <div class="form-group">
                                            <label for="-id-column">No BC 11</label>
                                            <input type="text" id="first-name-vertical" class="form-control" name="no_bc11">
                                        </div>
                                    </div>
                                    <hr>

                   
                                    <br>
                                    <hr>
                                    <h6>Port Informatiom</h6>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="first-name-column">Origin Port</label>
                                            <input type="text" id="first-name-vertical" class="form-control" name="ves_code" value="{{ $vessel_voyage->origin_port }}"  disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="last-name-column">Next Port</label>
                                            <input type="text" id="first-name-vertical" class="form-control" name="ves_code" value="{{ $vessel_voyage->next_port }}"  disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="-column">Dest Port</label>
                                            <input type="text" id="first-name-vertical" class="form-control" name="ves_code" value="{{ $vessel_voyage->dest_port }}"  disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="-column">Last Port</label>
                                            <input type="text" id="first-name-vertical" class="form-control" name="ves_code" value="{{ $vessel_voyage->last_port }}"  disabled>
                                        </div>
                                    </div>
                                    <br>
                                   

                                    <hr>
                                    <h6>Vessel & Berth Informatiom</h6>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="first-name-column">Vessel Length</label>
                                            <input type="text" id="first-name-vertical" class="form-control" name="ves_code" value="{{ $vessel_voyage->ves_length }}"  disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="last-name-column">Berth No</label>
                                            <input type="text" id="first-name-vertical" class="form-control" name="vessel_service" value="{{ $vessel_voyage->berth_no }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="-column">Berth Fr Metre</label>
                                            <input type="text" id="first-name-vertical" class="form-control" name="ves_code" value="{{ $vessel_voyage->berth_fr_metre }}"  disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="-column">Berth to Metre</label>
                                            <input type="text" id="first-name-vertical" class="form-control" name="ves_code" value="{{ $vessel_voyage->berth_to_metre }}"  disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="-column">Berth Code</label>
                                            <input type="text" id="first-name-vertical" class="form-control" name="ves_code" value="{{ $vessel_voyage->berth_code }}"  disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="-column">CY Code</label>
                                            <input type="text" id="first-name-vertical" class="form-control" name="ves_code" value="{{ $vessel_voyage->cy_code }}"  disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="-column">Berth Grid</label>
                                            <input type="text" id="first-name-vertical" class="form-control" name="ves_code" value="{{ $vessel_voyage->berth_grid }}"  disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="-column">Btoa Side</label>
                                            <input type="text" id="first-name-vertical" class="form-control" name="ves_code" value="{{ $vessel_voyage->btoa_side }}"  disabled>
                                        </div>
                                    </div>

                                    <hr>
                                    <!-- <h6>Booking Information</h6>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label style="font-weight; bold;" for="first-name-column" >Export</label>
                                            <br>
                                            Booking
                                            <input type="number" id="first-name-column" class="form-control" placeholder="" name="export_booking" >
                                            Counter
                                            <input type="text" id="first-name-column" class="form-control" placeholder="" name="export_counter" readonly>
                                            <button type="refresh" onclick="refreshData()" class="btn btn-light-success me-1 mb-1">Refresh</button>
                                        </div>
                                    </div> -->
                                    <div class="table">
                            <table class="table mb-0" id="counter">
                                <thead class="thead-dark">
                                    <tr>
                                        <th><h4>Booking Information</h4></th>
                                        <th>Import</th>
                                        <th>Export</th>
                                        <th>Action</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-bold-500"><h5>Booking</h5></td>
                                        <td><input type="number"   name="import_booking" value="{{$vessel_voyage->import_booking}}" class="form-control"></td>
                                        <td ><input type="number" name="export_booking"  value="{{ $vessel_voyage->export_booking }}" class="form-control"></td>
                                        <td>-</td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold-500"><h5>Counter</h5></td>
                                        <td><input type="number"   id="import_counter" name="import_counter" class="form-control" value="{{$bongkar_import}}" readonly></td>
                                        <td ><input type="number"  id="export_counter" name="export_counter" class="form-control"  readonly></td>
                                        <td><button id="refresh" type="button" class="btn btn-outline-warning me-1 mb-1">Refresh</button></td>
                                    </tr>
                                    </tbody>
                            </table>
                        </div>
                                    

                                    <hr>
                                    
                                    <div class="table">
                            <table class="table mb-0">
                                <thead class="thead-dark">
                                    <tr>
                                        <th><h4>Time schedule</h4></th>
                                        <th>Estimate</th>
                                        <th>Actual</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-bold-500"><h5>Arrival Date</h5></td>
                                        <td> <input type="datetime-local" id="first-name-vertical" class="form-control" name="ves_code" value="{{ $vessel_voyage->eta_date }}"  disabled></td>
                                        <td ><input type="datetime-local"   name="arrival_date" class="form-control"    value="{{ $vessel_voyage->arrival_date }}"></td>
                                    </tr>
                                    <!-- <tr>
                                        <td class="text-bold-500"><h5>Anchorage Date</h5></td>
                                        <td> <input type="datetime-local" id="first-name-vertical" class="form-control" name="ves_code" value="{{ $vessel_voyage->est_anchorage_date }}"  disabled></td>
                                        <td><input type="datetime-local"  name="act_anchorage_date" class="form-control" value="{{ $vessel_voyage->act_anchorage_date }}"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold-500"><h5>Pilot Date</h5></td>
                                        <td><input type="datetime-local" id="first-name-vertical" class="form-control" name="ves_code" value="{{ $vessel_voyage->est_pilot_date }}"  disabled></td>
                                        <td ><input type="datetime-local"  name="act_pilot_date" class="form-control" value="{{ $vessel_voyage->act_pilot_date }}" ></td>
                                    </tr>                                     -->
                                    <tr>
                                        <td class="text-bold-500"><h5>Waktu Sndar</h5></td>
                                        <td><input type="datetime-local" id="first-name-vertical" class="form-control" name="ves_code" value="{{ $vessel_voyage->est_berthing_date }}"  disabled></td>
                                        <td ><input type="datetime-local"  name="berthing_date" class="form-control" value="{{ $vessel_voyage->berthing_date }}"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold-500"><h5>Mulai Kerja</h5></td>
                                        <td><input type="datetime-local" id="first-name-vertical" class="form-control" name="ves_code" value="{{ $vessel_voyage->est_start_work_date }}"  disabled></td>
                                        <td ><input type="datetime-local"  name="act_start_work_date" class="form-control" value="{{ $vessel_voyage->act_start_work_date }}"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold-500"><h5>Selesai Kerja</h5></td>
                                        <td> <input type="datetime-local" id="first-name-vertical" class="form-control" name="ves_code" value="{{ $vessel_voyage->est_end_work_date }}"  disabled></td>
                                        <td ><input type="datetime-local"  name="act_end_work_date" class="form-control" value="{{ $vessel_voyage->act_end_work_date }}"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold-500"><h5>Deparature Date</h5></td>
                                        <td> <input type="datetime-local" id="first-name-vertical" class="form-control" name="ves_code" value="{{ $vessel_voyage->etd_date }}"  disabled></td>
                                        <td ><input type="datetime-local"  name="deparature_date" class="form-control" value="{{ $vessel_voyage->deparature_date }}"></td>
                                    </tr>
                                   
                                </tbody>
                            </table>
                        </div>
                                    

                                    <!-- <div class="col-md-12 col-12">
                                        <div class="form-group">
                                            <label for="-column"><h6>Idle Time</h6></label>
                                            <input type="number" id="first-name-vertical" class="form-control" name="ves_code" value="{{ $vessel_voyage->idle_time }}"  disabled>
                                        </div>
                                    </div> -->

                                    <hr>
                                    <h4>Open Stack Information</h4>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="-column">Open Stack Date</label>
                                            <input type="datetime-local" id="first-name-vertical" class="form-control" name="open_stack_date" value="{{ $vessel_voyage->open_stack_date }}"  >
                                        </div>
                                    </div>

                                    <hr>
                                    <h4>Closing Time Information</h4>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="-column">Clossing Date</label>
                                          <input type="datetime-local" id="first-name-vertical" class="form-control" name="doc_clossing_date" value="{{ $vessel_voyage->doc_clossing_date }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="-column">Cargo Closing Date</label>
                                            <input type="datetime-local" id="first-name-vertical" class="form-control" name="clossing_date" value="{{ $vessel_voyage->clossing_date }}" >
                                        </div>
                                    </div>
                                    <hr>
                                    <hr>
                                    <br>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="-column">User</label>
                                            <input type="text" id="first-name-vertical" class="form-control" name="ves_code" value="{{ Auth::user()->name }}"  disabled>
                                       
                                        </div>
                                    </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="-column">Update Time</label>
                                            <input type="datetime-local" id="-column" class="form-control" value="{{ $currentDateTimeString }}" name="update_time"  readonly>
                                        </div>
                                    </div>
                                    <hr>
                                    <hr>
                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                                        <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                                    </div>
                                </div>
                            </form>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
 @section('custom_js')
 <script>
$(document).on('click', '#refresh', function() {
    var ves_id = '{{ $vessel_voyage->ves_id }}';

    $.ajax({
        type: 'GET',
        url: '/refresh_counter',
        data: {
            ves_id: ves_id
        },
        success: function(response) {
            $('#import_counter').val(response.import_counter);
            $('#export_counter').val(response.export_counter);
        },
        error: function(response) {
            console.log('error:', response);
        },
    });
});

</script>
@endsection