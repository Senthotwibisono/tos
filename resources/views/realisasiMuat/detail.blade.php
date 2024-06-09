<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title> {{$title}} | Icon Sarana</title>
  <link rel="stylesheet" href="{{asset('dist/assets/css/main/app.css')}}">
  <link rel="shortcut icon" href="{{asset('logo/icon.png')}}" type="image/x-icon">
  <link rel="shortcut icon" href="{{asset('logo/icon.png')}}" type="image/png">
</head>


<style>
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

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="text-center">
                    <img src="/logo/ICON2.png" class="img" alt="">
                <h2>Baplei Muat {{$kapal->ves_name}} -- {{$kapal->voy_out}}</h2>
            </div>
       </div>
    </div>
    <br>
    <hr>
    <!-- <div class="row">
        <div class="col-3">
            <a herf="" class="btn btn-danger">Cetak PDF</a>
        </div>
        <div class="col-3">
            <a herf="" class="btn btn-success">Cetak Excel</a>
        </div>
    </div>
    <br>
    <hr> -->
    <div class="row">
        <div class="card">
            <div class="card-header">
                <h6>History Container</h6>
                <br>
                <div class="row">
                    <div class="col-4">
                        <p>Vessel Name :<strong>{{$kapal->ves_name}}</strong></p>
                        <p>Vessel Code : <strong>{{$kapal->ves_code}}</strong></p>
                        <p>Voy In : <strong>{{$kapal->voy_in}}</strong></p>
                        <p>Voy Out : <strong>{{$kapal->voy_out}}</strong></p>
                    </div>
                    <div class="col-4">
                        <p>Arrival Date :<strong>{{$kapal->arrival_date}}</strong></p>
                        <p>Departure Date : <strong>{{$kapal->deparature_date}}</strong></p>
                        <p>Open Stack Date : <strong>{{$kapal->open_stack_date}}</strong></p>
                        <p>Clossing Date : <strong>{{$kapal->clossing_date}}</strong></p>
                    </div>
                    <div class="col-4">
                        <h4>Jumlah Container : <strong>{{$jumlahCont}}</strong></h4>
                        <a href="/planning/detail/realisasiMuat/excel/{{$kapal->ves_id}}" class="btn btn-success">Cetak Excel</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-section table-responsive bill-tbl w-100 mt-10">
                    @include('realisasiMuat.table', $containers)
                </div>
            </div>
        </div>
    </div>
</div>

</html>