<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Discharged View </title>
</head>
<link rel="stylesheet" href="{{asset('dist/assets/css/main/app.css')}}">
<link rel="stylesheet" href="{{asset('dist/assets/extensions/sweetalert2/sweetalert2.min.css')}}">

<style type="text/css">
    .section {
        padding-top: 5%;
    }

    .card {
        margin-bottom: 20px;
    }

    .card-body {
        padding: 15px;
    }

    .row {
        display: flex;
        flex-wrap: wrap;
        margin-right: -15px;
        margin-left: -15px;
    }

    .col-6 {
        flex: 0 0 50%;
        max-width: 50%;
        padding-right: 15px;
        padding-left: 15px;
    }

    body {
        font-family: 'Roboto Condensed', sans-serif;
    }

    .page-break {
        page-break-before: always;
    }

    .footer {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        text-align: center;
        padding: 5px;
        font-size: 12px;
        background-color: #f9f9f9;
        border-top: 1px solid #ddd;
    }

    .m-0 {
        margin: 0px;
    }

    .p-0 {
        padding: 0px;
    }

    .pt-5 {
        padding-top: 5px;
    }

    .mt-10 {
        margin-top: 10px;
    }

    .text-center {
        text-align: center !important;
    }

    .w-100 {
        width: 100%;
    }

    .w-50 {
        width: 50%;
    }

    .w-85 {
        width: 85%;
    }

    .w-15 {
        width: 15%;
    }

    .logo img {
        width: 45px;
        height: 45px;
        padding-top: 30px;
    }

    .logo span {
        margin-left: 8px;
        top: 19px;
        position: absolute;
        font-weight: bold;
        font-size: 25px;
    }

    .gray-color {
        color: #5d5d5d;
    }

    .text-bold {
        font-weight: bold;
    }

    .border {
        border: 1px solid black;
    }

    table tr,
     {
        border: 1px solid #d2d2d2;
        border-collapse: collapse;
        padding: 7px 8px;
    }

    table tr th {
        background: #f4f4f4;
        font-size: 15px;
    }

    table tr td {
        font-size: 13px;
    }

    table {
        border-collapse: collapse;
    }

    .box-text p {
        line-height: 10px;
    }

    .float-left {
        float: left;
    }

    .total-part {
        font-size: 16px;
        line-height: 12px;
    }

    .total-right p {
        padding-right: 20px;
    }

    .tier-container {
        display: flex;
        flex-wrap: wrap; /* Set to wrap */
        gap: -10px;
    }

    .kotak {
        height: 10vh;
        background-color: #fff;
        text-align: center;
        line-height: 150px;
        border: 2px solid #000000;
        font-size: 10px;
        flex: 1;
        margin: 0px;
        border-radius: 0px;
    }

    .kotak.filled {
        background-color: red;
        color: #fff;
    }

</style>

<body>
<section>
@if(session('success'))
          <div class="alert alert-success">
              {{ session('success') }}
          </div>
      @endif
      @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
<form action="/profile-tier/update" method="post">
@csrf
    <div class="row">
        <div class="col-10" style="border-right: 4px solid black;">
        <div class="row">
            <h4><strong>On Deck {{$bay->BAY1}}</strong></h4>
                @php
                    $rows = $onDeck->unique('bay_row')->sortByDesc('bay_row');
                    $tiers = $onDeck->unique('bay_tier')->sortByDesc('bay_tier');
                @endphp
                 <table>
                 <tr>
                    <th></th>
                    <th></th>
                        @foreach($rows as $row)
                        
                        <th class="text-center">
                            @if($row->bay_row % 2 == 0)
                            <p>{{$row->bay_row }}</p>    
                            @endif
                        </th>
                        @endforeach
                        @php
                            $reversedRows =  $onDeck->unique('bay_row')->sortBy('bay_row');
                        @endphp
                        @foreach($reversedRows as $row)
                        <th class="text-center">
                            @if($row->bay_row % 2 != 0)
                            <p>{{$row->bay_row}}</p>    
                            @endif
                        </th>
                        @endforeach  
                    </tr>
                    @foreach($tiers as $tier)
                   
                    
                    <tr>
                        <td>Tier</td>
                        <td ><h6>{{ $tier->bay_tier }}</h6></td>
                        @foreach($rows as $row)
                        @php
                            $box = $onDeck->where('bay_row', $row->bay_row)->where('bay_tier', $tier->bay_tier)->first();
                        @endphp
                        <td>
                            @if($row->bay_row % 2 == 0)
                            <div class="kotak{{$box->active == 'Y' ? ' filled' : ''}}">
                                <input type="checkbox" class="checkbox_input" name="checkbox_input[]" value="{{$row->bay_row}}-{{$tier->bay_tier}}" {{$box->active == 'Y' ? 'checked' : ''}}>
                                R<strong>{{ $row->bay_row }}</strong> T<strong>{{ $tier->bay_tier }}</strong> 
                            </div>
                            @endif
                        </td>
                        @endforeach
                        @php
                            $reversedRows = $onDeck->unique('bay_row')->sortBy('bay_row');
                        @endphp
                        @foreach($reversedRows as $row)
                        @php
                            $box = $onDeck->where('bay_row', $row->bay_row)->where('bay_tier', $tier->bay_tier)->first();
                        @endphp
                            <td>
                                @if($row->bay_row % 2 != 0)
                                <div class="kotak{{$box->active == 'Y' ? ' filled' : ''}}">
                                    <input type="checkbox" class="checkbox_input" name="checkbox_input[]" value="{{$row->bay_row}}-{{$tier->bay_tier}}" {{$box->active == 'Y' ? 'checked' : ''}}>
                                    R<strong>{{ $row->bay_row }}</strong> T<strong>{{ $tier->bay_tier }}</strong> 
                                </div>
                                @endif
                            </td>
                        @endforeach
                        <td><h6>{{ $tier->bay_tier}}</h6></td>
                    </tr>
          
                    @endforeach
                 </table>
        </div>
        <br>
        <hr>
        <br>
        <!-- UNDER -->
        <div class="row">
            <h4><strong>Under Deck {{$bay->BAY1}}</strong></h4>
            @php
                    $rows = $underDeck->unique('bay_row')->sortByDesc('bay_row');
                    $tiers = $underDeck->unique('bay_tier')->sortByDesc('bay_tier');
                @endphp
                 <table>
                 <tr>
                    <th></th>
                    <th></th>
                        @foreach($rows as $row)
                        
                        <th class="text-center">
                            @if($row->bay_row % 2 == 0)
                            <p>{{$row->bay_row }}</p>    
                            @endif
                        </th>
                        @endforeach
                        @php
                            $reversedRows =  $underDeck->unique('bay_row')->sortBy('bay_row');
                        @endphp
                        @foreach($reversedRows as $row)
                        <th class="text-center">
                            @if($row->bay_row % 2 != 0)
                            <p>{{$row->bay_row}}</p>    
                            @endif
                        </th>
                        @endforeach  
                    </tr>
                    @foreach($tiers as $tier)
                   
                    
                    <tr>
                        <td>Tier</td>
                        <td ><h6>{{ $tier->bay_tier }}</h6></td>
                        @foreach($rows as $row)
                        @php
                            $box = $underDeck->where('bay_row', $row->bay_row)->where('bay_tier', $tier->bay_tier)->first();
                        @endphp
                        <td>
                            @if($row->bay_row % 2 == 0)
                                <div class="kotak{{$box->active == 'Y' ? ' filled' : ''}}">
                                    <input type="checkbox" class="checkbox_input" name="checkbox_input[]" value="{{$row->bay_row}}-{{$tier->bay_tier}}" {{$box->active == 'Y' ? 'checked' : ''}}>
                                    R<strong>{{ $row->bay_row }}</strong> T<strong>{{ $tier->bay_tier }}</strong> 
                                </div>
                            @endif
                        </td>
                        @endforeach
                        @php
                            $reversedRows = $underDeck->unique('bay_row')->sortBy('bay_row');
                        @endphp
                        @foreach($reversedRows as $row)
                        @php
                            $box = $underDeck->where('bay_row', $row->bay_row)->where('bay_tier', $tier->bay_tier)->first();
                        @endphp
                            <td>
                                @if($row->bay_row % 2 != 0)
                                <div class="kotak{{$box->active == 'Y' ? ' filled' : ''}}">
                                    <input type="checkbox" class="checkbox_input" name="checkbox_input[]" value="{{$row->bay_row}}-{{$tier->bay_tier}}" {{$box->active == 'Y' ? 'checked' : ''}}>
                                    R<strong>{{ $row->bay_row }}</strong> T<strong>{{ $tier->bay_tier }}</strong> 
                                </div>
                                @endif
                            </td>
                        @endforeach
                        <td><h6>{{ $tier->bay_tier}}</h6></td>
                    </tr>
          
                    @endforeach
                 </table>
        </div>
        </div>
        <div class="col-2">
            <div class="card">
                <div class="card-header">
                    <h4>{{$kapal->ves_name}}</h4>
                    <p>{{$kapal->ves_code}} -- {{$kapal->agent}} <br> <strong>Bay {{$bay->BAY1}}</strong></p>
                </div>
                <div class="card-body">
                    <strong>On Deck</strong>
                    <div class="form-group">
                        <label for="">Start Row On Deck</label>
                        <input type="text" class="form-control" value="{{$bay->START_ROW}}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="">Max Row On Deck</label>
                        <input type="text" class="form-control" value="{{$bay->MAX_ROW}}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="">Start Tier On Deck</label>
                        <input type="text" class="form-control" value="{{$bay->START_TIER}}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="">Max Tier On Deck</label>
                        <input type="text" class="form-control" value="{{$bay->TIER + $bay->START_TIER}}" readonly>
                    </div>
                    <hr>
                    <strong>Under Deck</strong>
                    <div class="form-group">
                        <label for="">Start Row Under Deck</label>
                        <input type="text" class="form-control" value="{{$bay->START_ROW_UNDER}}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="">Max Row Under Deck</label>
                        <input type="text" class="form-control" value="{{$bay->MAX_ROW_UNDER}}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="">Start Tier Under Deck</label>
                        <input type="text" class="form-control" value="{{$bay->START_TIER_UNDER}}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="">Max Tier Under Deck</label>
                        <input type="text" class="form-control" value="{{$bay->TIER_UNDER + $bay->START_TIER_UNDER}}" readonly>
                        <input type="text" class="form-control" name="ves_code" value="{{$kapal->ves_code}}" readonly>
                        <input type="text" class="form-control" name="bay_slot" value="{{$bay->BAY1}}" readonly>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="form-group">
                        <button type="submit" class="btn btn-outline-primary">Update</button>
                        <button class="btn btn-outline-danger" onclick="closeWindow()">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
</section>
</body>
<script>
    function closeWindow() {
        window.close();
    }
</script>
<script src="{{asset('dist/assets/extensions/sweetalert2/sweetalert2.min.js')}}"></script>
    <script src="{{asset('dist/assets/js/pages/sweetalert2.js')}}"></script>
@if (\Session::has('success'))
  <script type="text/javascript">
    // Add CSRF token to the headers
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var successMessage = "{!! \Session::get('success') !!}";

    if (successMessage) {
      Swal.fire({
        icon: 'success',
        title: 'Success',
        text: successMessage,
      }).then(function() {
        // Make an AJAX request to unset session variable
        $.ajax({
          url: "{{ route('unset-session', ['key' => 'success']) }}",
          type: 'POST',
          success: function(response) {
            console.log('Success session unset');
            // {{logger('Success session unset')}} -> call func logger in helper
          },
          error: function(error) {
            console.log('Error unsetting session', error);
          }
        });
      });
    }
  </script>
  @endif
</html>
