@extends('partial.main')

@section('content')

<section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h1>Create Schedule -Form</h1>
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
                            <form class="form" method="post" action='/planning/vessel_schedule_store'>
                                @CSRF
                                <div class="row" style="border:1px solid blue;">
                                    <div class="col-md-6 col-12" >
                                        <div class="form-group">
                                            <label for="first-name-column">Vessel Code</label>
                                            <select class="form-select" id="vescode" name="ves_code">
                                            <option value="-">-</option>
                                                @foreach($vessel_master as $master)
                                            <option value="{{$master->ves_code}}">{{$master->ves_code}}</option>
                                                @endforeach
                                        </select>
                                        </div>
                                    </div>
                                   
                                    <div class="col-md-12 col-12">
                                        <div class="form-group">
                                            <label for="-column">Vessel Name</label>
                                            <select class="form-select" id="vesname" name="ves_name" required readonly >
                                            <option value="-">-</option>                                
                                        </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="-floating">Voy In</label>
                                            <input type="text" id="-floating" class="form-control" name="voy_in" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="-column">Voy Out</label>
                                            <input type="text" id="-column" class="form-control" name="voy_out" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="-column">Agent</label>
                                            <select class="form-select" id="agent" name="agent" required readonly >
                                            <option value="-">-</option>                                
                                        </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="-id-column">Liner</label>
                                            <select class="form-select" id="liner" name="liner" required readonly >
                                            <option value="-">-</option>                                
                                        </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="-id-column">Voy Owner</label>
                                            <input type="text" id="-id-column" class="form-control" name="voyage_owner" placeholder="" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 col-12">
                                      <div class="form-group" style="border:1px solid red;">
                                        <label for="export">Export:</label>
                                        <input type="checkbox"  name="export_yn" value="Y" style="margin-left: 10px;">
                                        <label for="import" style="margin-left: 20px;">Import:</label>
                                        <input type="checkbox"  name="import_yn" value="Y" style="margin-left: 10px;">
                                      </div>
                                    </div>
                                    <!-- <div class="col-md-6 col-12" >
                                        <div class="form-group" style="border:1px solid red;">
                                        <input type="checkbox" name="export_yn" value="Y"> Export 
                                        <br>
                                        <input type="checkbox" name="import_yn" value="Y"> Import
                                        </div>
                                    </div> -->
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                        <label for="-id-column">Liner/Tramp</label>
                                            <select class="form-select" id="" name="liner_tramp"  > 
                                            <option value="L">Liner</option>
                                            <option value="T">Tramp</option>                                   
                                        </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="-id-column">Feeder/Direct</label>
                                            <select class="form-select" id="" name="feeder_direct"  >
                                            <option value="F">Feeder</option>
                                            <option value="D">Direct</option>                                   
                                        </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="-id-column">Vessel Service</label>
                                            <select class="form-select" id="service" name="vessel_service" required>
                                            <option value="-">-</option>
                                                @foreach($vessel_service as $service)
                                            <option value="{{$service->service}}">{{$service->service}}</option>
                                                @endforeach
                                            </select>
                                            </div>
                                        <div class="form-group">
                                        <label for="-id-column">Ocean/Interisland</label>
                                            <select class="form-select" id="" name="ocean_interisland" required>
                                            <option value="O">Ocean Going</option>                                             
                                            <option value="I">Inter Island</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-12">
                                        <div class="form-group">
                                            <label for="-id-column">Reg Flag</label>
                                            <select class="form-select" id="reg_flag" name="reg_flag" required readonly >
                                            <option value="-">-</option>                                
                                        </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-12">
                                        <div class="form-group">
                                            <label for="-id-column">No PPK</label>
                                            <input type="text" id="-id-column" class="form-control" name="no_ppk" placeholder="" required>
                                        </div>
                                    </div>
                                    <br>
                                    <hr>
                                    <h6>Port Informatiom</h6>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="first-name-column">Origin Port</label>
                                            <select class="form-select" id="origin" name="origin_port" required>
                                            <option value="-">-</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="last-name-column">Next Port</label>
                                            <select class="form-select" id="next" name="next_port" required>
                                            <option value="-">-</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="-column">Dest Port</label>
                                            <select class="form-select" id="dest" name="dest_port" required>
                                            <option value="-">-</option>
                                        </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="-column">Last Port</label>
                                            <select class="form-select" id="last" name="last_port" required>
                                            <option value="-">-</option>
                                            </select>
                                        </div>
                                    </div>
                                    <br>
                                   

                                    <hr>
                                    <h6>Vessel & Berth Informatiom</h6>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="first-name-column">Vessel Length</label>
                                            <select class="form-select" id="length" name="ves_length" required readonly >
                                            <option value="-">-</option>                                
                                        </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="last-name-column">Berth No</label>
                                            <select class="form-select" id="berth" name="berth_no" required>
                                            <option value="-">-</option>
                                                @foreach($berth as $b)
                                            <option value="{{$b->berth_no}}">{{$b->berth_no}}</option>
                                                @endforeach
                                        </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="-column">Berth Fr Metre</label>
                                            <select class="form-select" id="from" name="berth_fr_metre" required readonly >
                                            <option value="-">-</option>                                               
                                        </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="-column">Berth to Metre</label>
                                            <select class="form-select" id="tlength" name="berth_to_metre" required readonly >
                                            <option value="-">-</option>                                               
                                        </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="-column">Berth Code</label>
                                            <select class="form-select" id="bcode" name="berth_code" required readonly >
                                            <option value="-">-</option>                                               
                                        </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="-column">CY Code</label>
                                            <select class="form-select" id="cy" name="cy_code" required>
                                            <option value="T">TPK</option>   
                                            <option value="K">KON</option>                                              
                                        </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="-column">Berth Grid</label>
                                            <input type="text"   name="berth_grid" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="-column">Btoa Side</label>
                                            <select class="form-select" id="side" name="btoa_side" required>
                                            <option value="B">Berth</option>  
                                            <option value="S">Ship</option>                                               
                                        </select>
                                        </div>
                                    </div>

                                    <hr>
                                    <!-- <h6>Booking Information</h6>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label style="font-weight; bold;" for="first-name-column" >Export</label>
                                            <br>
                                            Booking
                                            <input type="number" id="first-name-column" class="form-control" placeholder="" name="export_booking" required>
                                            Counter
                                            <input type="text" id="first-name-column" class="form-control" placeholder="" name="export_counter" readonly>
                                            <button type="refresh" onclick="refreshData()" class="btn btn-light-success me-1 mb-1">Refresh</button>
                                        </div>
                                    </div> -->
                                    <div class="table">
                            <table class="table mb-0">
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
                                        <td><input type="number"   name="import_booking" class="form-control"></td>
                                        <td ><input type="number" name="export_booking" class="form-control"></td>
                                        <td>-</td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold-500"><h5>Counter</h5></td>
                                        <td><input type="number"   name="import_counter" class="form-control" disabled></td>
                                        <td ><input type="number" name="export_counter" class="form-control" disabled></td>
                                        <td>-</td>
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
                                        <td><input type="datetime-local"   name="eta_date" class="form-control"></td>
                                        <td ><input type="datetime-local" value="{{ $currentDateTimeString }}"  name="arrival_date" class="form-control"  readonly ></td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold-500"><h5>Anchorage Date</h5></td>
                                        <td><input type="datetime-local"  name="est_anchorage_date" class="form-control"></td>
                                        <td><input type="datetime-local" value="{{ $currentDateTimeString }}" name="act_anchorage_date" class="form-control" readonly ></td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold-500"><h5>Pilot Date</h5></td>
                                        <td><input type="datetime-local"  name="est_pilot_date" class="form-control"></td>
                                        <td ><input type="datetime-local" value="{{ $currentDateTimeString }}" name="act_pilot_date" class="form-control" readonly ></td>
                                    </tr>                                    
                                    <tr>
                                        <td class="text-bold-500"><h5>Berthing Date</h5></td>
                                        <td><input type="datetime-local"  name="est_berthing_date" class="form-control"></td>
                                        <td ><input type="datetime-local" value="{{ $currentDateTimeString }}" name="berthing_date" class="form-control" readonly > </td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold-500"><h5>Start Work Date</h5></td>
                                        <td><input type="datetime-local"  name="est_start_work_date" class="form-control"></td>
                                        <td ><input type="datetime-local" value="{{ $currentDateTimeString }}" name="act_start_work_date" class="form-control" readonly ></td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold-500"><h5>End Work Date</h5></td>
                                        <td><input type="datetime-local"  name="est_end_work_date" class="form-control"></td>
                                        <td ><input type="datetime-local" value="{{ $currentDateTimeString }}" name="act_end_work_date" class="form-control" readonly ></td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold-500"><h5>Deparature Date</h5></td>
                                        <td><input type="datetime-local"  name="etd_date" class="form-control"></td>
                                        <td ><input type="datetime-local" value="{{ $currentDateTimeString }}" name="deparature_date" class="form-control" readonly ></td>
                                    </tr>
                                   
                                </tbody>
                            </table>
                        </div>
                                    

                                    <div class="col-md-12 col-12">
                                        <div class="form-group">
                                            <label for="-column"><h6>Idle Time</h6></label>
                                            <input type="number" id="-column" class="form-control"  placeholder="" name="idle_time" required>
                                        </div>
                                    </div>

                                    <hr>
                                    <h4>Open Stack Information</h4>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="-column">Open Stack Date</label>
                                            <input type="datetime-local" id="-column" class="form-control" value="{{ $currentDateTimeString }}" placeholder="" name="open_stack_date">
                                        </div>
                                    </div>

                                    <hr>
                                    <h4>Closing Time Information</h4>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="-column">Clossing Date</label>
                                            <input type="datetime-local" id="-column" class="form-control" value="{{ $currentDateTimeString }}" placeholder="" name="doc_clossing_date">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="-column">Cargo Closing Date</label>
                                            <input type="datetime-local" id="-column" class="form-control" value="{{ $currentDateTimeString }}" placeholder="" name="clossing_date">
                                        </div>
                                    </div>
                                    <hr>
                                    <hr>
                                    <br>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="-column">User</label>
                                            <input type="text" id="-column" class="form-control" value ="{{ Auth::user()->name }}" placeholder="{{ Auth::user()->name }}"  required readonly>
                                            <input type="hidden" id="-column" class="form-control" value ="{{ Auth::user()->id }}" placeholder="{{ Auth::user()->name }}" name="user_id" required readonly>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="-column">Update Time</label>
                                            <input type="datetime-local" id="-column" class="form-control" value="{{ $currentDateTimeString }}" name="update_time" required readonly>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>

<script>
    $(function(){
        $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

        $(function(){
            $('#vescode'). on('change', function(){
                let ves_code = $('#vescode').val();

                $.ajax({
                    type: 'POST',
                url: '/getvessel',
                    data : {ves_code : ves_code},
                    cache: false,
                    
                    success: function(msg){
                        $('#vesname').html(msg);
                   
                    },
                    error: function(data){
                        console.log('error:',data)
                    },
                })
                $.ajax({
                    type: 'POST',
                url: '/getvessel_agent',
                    data : {ves_code : ves_code},
                    cache: false,
                    
                    success: function(msg){
                        $('#agent').html(msg);
                   
                    },
                    error: function(data){
                        console.log('error:',data)
                    },
                })
                $.ajax({
                    type: 'POST',
                url: '/getvessel_liner',
                    data : {ves_code : ves_code},
                    cache: false,
                    
                    success: function(msg){
                        $('#liner').html(msg);
                   
                    },
                    error: function(data){
                        console.log('error:',data)
                    },
                })
                $.ajax({
                    type: 'POST',
                url: '/reg_flag',
                    data : {ves_code : ves_code},
                    cache: false,
                    
                    success: function(msg){
                        $('#reg_flag').html(msg);
                   
                    },
                    error: function(data){
                        console.log('error:',data)
                    },
                })
                $.ajax({
                    type: 'POST',
                url: '/length',
                    data : {ves_code : ves_code},
                    cache: false,
                    
                    success: function(msg){
                        $('#length').html(msg);
                   
                    },
                    error: function(data){
                        console.log('error:',data)
                    },
                })
                
            })
        })
        $(function(){
            $('#berth'). on('change', function(){
                let berth_no = $('#berth').val();

                $.ajax({
                    type: 'POST',
                url: '/bcode',
                    data : {berth_no : berth_no},
                    cache: false,
                    
                    success: function(msg){
                        $('#bcode').html(msg);
                   
                    },
                    error: function(data){
                        console.log('error:',data)
                    },
                })
               
                $.ajax({
                    type: 'POST',
                    url: '/from',
                    data : {berth_no : berth_no},
                    cache: false,
                    
                    success: function(msg){
                        $('#from').html(msg);
                   
                    },
                    error: function(data){
                        console.log('error:',data)
                    },
                })
                $.ajax({
                    type: 'POST',
                    url: '/tlength',
                    data : {berth_no : berth_no},
                    cache: false,
                    
                    success: function(msg){
                        $('#tlength').html(msg);
                   
                    },
                    error: function(data){
                        console.log('error:',data)
                    },
                })
                
            })
        })
        $(function(){
            $('#service'). on('change', function(){
                let service = $('#service').val();

                $.ajax({
                    type: 'POST',
                url: '/origin',
                    data : {service : service},
                    cache: false,
                    
                    success: function(msg){
                        $('#origin').html(msg);
                   
                    },
                    error: function(data){
                        console.log('error:',data)
                    },
                })
               
                $.ajax({
                    type: 'POST',
                url: '/next',
                    data : {service : service},
                    cache: false,
                    
                    success: function(msg){
                        $('#next').html(msg);
                   
                    },
                    error: function(data){
                        console.log('error:',data)
                    },
                })
                $.ajax({
                    type: 'POST',
                url: '/dest',
                    data : {service : service},
                    cache: false,
                    
                    success: function(msg){
                        $('#dest').html(msg);
                   
                    },
                    error: function(data){
                        console.log('error:',data)
                    },
                })
                $.ajax({
                    type: 'POST',
                url: '/last',
                    data : {service : service},
                    cache: false,
                    
                    success: function(msg){
                        $('#last').html(msg);
                   
                    },
                    error: function(data){
                        console.log('error:',data)
                    },
                })
                
            })
        })
    });
</script>



@endsection