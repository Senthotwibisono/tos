
<!DOCTYPE html>
<html>
<head>
    <title>Discharged View {{$vesSelect->ves_name}} -- {{$vesSelect->voy_out}}</title>
</head>
<link rel="stylesheet" href="{{asset('dist/assets/css/main/app.css')}}">

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
    body{
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
    .m-0{
        margin: 0px;
    }
    .p-0{
        padding: 0px;
    }
    .pt-5{
        padding-top:5px;
    }
    .mt-10{
        margin-top:10px;
    }
    .text-center{
        text-align:center !important;
    }
    .w-100{
        width: 100%;
    }
    .w-50{
        width:50%;   
    }
    .w-85{
        width:85%;   
    }
    .w-15{
        width:15%;   
    }
    .logo img{
        width:45px;
        height:45px;
        padding-top:30px;
    }
    .logo span{
        margin-left:8px;
        top:19px;
        position: absolute;
        font-weight: bold;
        font-size:25px;
    }
    .gray-color{
        color:#5D5D5D;
    }
    .text-bold{
        font-weight: bold;
    }
    .border{
        border:1px solid black;
    }
    table tr,th,td{
        border: 1px solid #d2d2d2;
        border-collapse:collapse;
        padding:7px 8px;
    }
    table tr th{
        background: #F4F4F4;
        font-size:15px;
    }
    table tr td{
        font-size:13px;
    }
    table{
        border-collapse:collapse;
    }
    .box-text p{
        line-height:10px;
    }
    .float-left{
        float:left;
    }
    .total-part{
        font-size:16px;
        line-height:12px;
    }
    .total-right p{
        padding-right:20px;
    }

    .tier-container {
    display: flex;
    flex-wrap: fixed; /* Mengatur agar kontainer tier bisa terlipat jika ukurannya melebihi lebar kontainer induk */
    gap: 5px; /* Mengatur jarak antar kotak */
}
.kotak {
        height: 5vh; /* Mengurangi tinggi kotak menjadi 5% dari tinggi viewport */
        line-height: 5vh; /* Menyesuaikan line-height agar sama dengan tinggi kotak */
        font-size: 8px; 
        background-color: #fff;
        text-align: center;
        border: 2px solid #000000;
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
<div class="head-title">
    <h1 class="text-center m-0 p-0">Discharge View</h1><br>
    <p class="text-center m-0 p-0"><strong>{{$vesSelect->ves_name}} - {{$vesSelect->voy_out}}</strong> Bay <strong>{{$baySelect}}</strong></p>
</div>


<div class="add-detail mt-10">
    <div class="card">
        <div class="card-header">
            <h4 class="text-center"> </h4>
        </div>
    </div>
    <div style="clear: both;">
        
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="text-center">
                <img src="/logo/ICON2.png" class="img" alt="">
            <h2>Laporan Bay Plan Bongkar</h2>
        </div>
   </div>
</div>
<div class="table-section bill-tbl w-100 mt-10">
    <table class="table w-100 mt-10">
        <thead>
        <tr>
            <th colspan="7" class="text-center w-50">Indonesia Kontainer Sarana</th>
        
        </tr>
        <tr>
            <th>Vessel Name</th>
            <th>Vessel Code</th>
            <th>Voy In</th>
            <th>Voy Out</th>
            <th>Estimate Arrival</th>
            <th>Estimate Departure</th>
            <th>Total Container</th>
        </tr>
        </thead>
        <tbody>
            <tr>
                <th>{{$vesSelect->ves_name}}</th>
                <th>{{$vesSelect->ves_code}}</th>
                <th>{{$vesSelect->voy_in}}</th>
                <th>{{$vesSelect->voy_out}}</th>
                <th>{{$vesSelect->eta_date}}</th>
                <th>{{$vesSelect->etd_date}}</th>
                <th>{{$totalCont}}</th>
            </tr>
        </tbody>
    </table>
</div>
<div class="row">
    <div class="col-12">
        <div class="text-center">
                <img src="{{asset('logo/foto/IKON4.jpg')}}" class="img" alt="" style="width:90%; ">
        </div>
   </div>
</div>
<div class="page-break"></div>
<section>
    <div class="body">
        <div class="head-title">
        <div class="card" id="onDeck">
                <h4><strong>On Deck</strong></h4>
               <div class="row">
               @php
                    $rows = $onDeck->unique('bay_row')->sortByDesc('bay_row');
                    $tiers = $onDeck->unique('bay_tier')->sortByDesc('bay_tier');
                @endphp
                 <table>
                 <tr>
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
                        <td ><h6>{{ $tier->bay_tier }}</h6></td>
                        @foreach($rows as $row)
                        @php
                            $box = $onDeck->where('bay_row', $row->bay_row)->where('bay_tier', $tier->bay_tier)->first();
                        @endphp
                        <td class="text-center">
                            @if($box && $row->bay_row % 2 == 0)
                                <div class="kotak{{($box->container_key != null && $box->ctr_i_e_t == 'I') ? ' filled' : ''}}">
                                @if($box->container_key != null && $box->ctr_i_e_t == "I")
                                    <Strong>{{$box->container_no}} / {{$box->cont->ctr_size}} / @if($box->cont->ctr_status == 'MTY') M @else F -- @endif </Strong>
                                @endif
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
                            <td class="text-center">
                                @if($box && $row->bay_row % 2 != 0)
                                    <div class="kotak{{($box->container_key != null && $box->ctr_i_e_t == 'I') ? ' filled' : ''}}">
                                @if($box->container_key != null && $box->ctr_i_e_t == "I")
                                <Strong>{{$box->container_no}} / {{$box->cont->ctr_size}} / @if($box->cont->ctr_status == 'MTY') M @else F -- @endif </Strong>
                                @endif
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


            <div class="card" id="underDeck">
            <h4><strong>Under Deck</strong></h4>
               <div class="row">
               @php
                    $rows = $underDeck->unique('bay_row')->sortByDesc('bay_row');
                    $tiers = $underDeck->unique('bay_tier')->sortByDesc('bay_tier');
                @endphp
                 <table>
                 <tr>
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
                        <td ><h6>{{ $tier->bay_tier }}</h6></td>
                        @foreach($rows as $row)
                        @php
                            $box = $underDeck->where('bay_row', $row->bay_row)->where('bay_tier', $tier->bay_tier)->first();
                        @endphp
                        <td>
                            @if($box && $row->bay_row % 2 == 0)
                                    <div class="kotak{{($box->container_key != null && $box->ctr_i_e_t == 'I') ? ' filled' : ''}}">
                                @if($box->container_key != null && $box->ctr_i_e_t == "I")
                                <Strong>{{$box->container_no}} / {{$box->cont->ctr_size}} / @if($box->cont->ctr_status == 'MTY') M @else F -- @endif </Strong>
                                @endif
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
                                @if($box && $row->bay_row % 2 != 0)
                                    <div class="kotak{{($box->container_key != null && $box->ctr_i_e_t == 'I') ? ' filled' : ''}}">
                                    @if($box->container_key != null && $box->ctr_i_e_t == "I")
                                    <Strong>{{$box->container_no}} / {{$box->cont->ctr_size}} / @if($box->cont->ctr_status == 'MTY') M @else F -- @endif </Strong>
                                    @endif
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
        </div>
    </div>
</section>
   
<div class="footer">
<h4>PT. Indo Kontainer Sarana
<br>
<p>Jl. Komp Yos Sudarso No. 31 Pontianak - Kalimantan Barat
    <br>
Telp :   056 173 0255
Fax :   056 173 0242</p>
</h4>
    </div>

</html>