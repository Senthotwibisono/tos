<!-- <!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Discharge List for {{$ves_name}}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <style>
        table {
    width: 100%;
    border-collapse: collapse;
}
.judul {
    margin-top: 10px;
            margin-left: 10px;
        }

        .tengah {
            margin-top: 10px;
        }

th, td {
    border: 1px solid #dee2e6;
    padding: 8px;
    text-align: left;
}

th {
    background-color: #f8f9fa;
}
.page-break {
            page-break-before: always;
        }
    </style>
  </head>
  <body>
    
    <h1 class="judul">{{$ves_name}}</h1>

    <h4 class="tengah">Discharge List</h4>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

    <table>
        <thead>
            <tr>
                <th>Bay</th>
                <th>Row</th>
                <th>Tier</th>
                <th>Container No</th>
                <th>Size</th>
                <th>Status</th>
                <th>Type</th>
            </tr>
        </thead>
        <tbody>
    
        </tbody>
    </table>
  </body>
</html> -->


<!DOCTYPE html>
<html>
<head>
    <title>Realisasi Bongkar {{$ves_name}}</title>
</head>
<style type="text/css">
    body{
        font-family: 'Roboto Condensed', sans-serif;
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
</style>
<body>
<div class="head-title">
    <h1 class="text-center m-0 p-0">Realisasi Bongkar</h1>
</div>
<div class="add-detail mt-10">
    <div class="w-50 float-left mt-10">
        <p class="m-0 pt-5 text-bold w-100">Vessel Name - <span class="gray-color">{{$ves_name}}</span></p>
        <p class="m-0 pt-5 text-bold w-100">Ves Code - <span class="gray-color">{{$ves_code}}</span></p>
        <p class="m-0 pt-5 text-bold w-100">Vessel Id - <span class="gray-color">{{$ves_id}}</span></p>
    </div>
    <div style="clear: both;"></div>
</div>
<div class="table-section bill-tbl w-100 mt-10">
    <table class="table w-100 mt-10">
        <tr>
            <th class="w-50">Jumlah Total</th>
            <th class="w-50">Jumlah Sudah Dibongkar</th>
        </tr>
        <tr>
            <td>
                <div class="box-text">
                    <p>{{$total}}</p>
                </div>
            </td>
            <td>
                <div class="box-text">
                    <p>{{$bongkar}}</p>
                </div>
            </td>
        </tr>
    </table>
</div>
<div class="table-section bill-tbl w-100 mt-10">
    <table class="table w-100 mt-10">
        <tr>
                <th class="w-50">Intern Stat</th>
                <th class="w-50">Block</th>
                <th class="w-50">Slot</th>
                <th class="w-50">Row</th>
                <th class="w-50">Tier</th>
                <th class="w-50">Container No</th>
                <th class="w-50">Size</th>
                <th class="w-50">Status</th>
                <th class="w-50">Type</th>
        </tr>
         @foreach($items as $item)
             <tr align="center">
                
                <td>
                        @if($item->ctr_intern_status == '02')
                            Confirmed
                        @elseif($item->ctr_intern_status == '03')
                            Placed
                        @elseif($item->ctr_intern_status == '04')
                            Stripped
                        @elseif($item->ctr_intern_status == '10')
                            Truck In
                        @elseif($item->ctr_intern_status == '09')
                            Truck Out
                        @endif
                </td>
                <td>{{$item->yard_block}}</td>
                <td>{{$item->yard_slot}}</td>
                <td>{{$item->yard_row}}</td>
                <td>{{$item->yard_tier}}</td>
                <td>{{$item->container_no}}</td>
                <td>{{$item->ctr_size}}</td>
                <td>{{$item->ctr_status}}</td>
                <td>{{$item->ctr_type}}</td>
            </tr>
            @endforeach
    </table>
</div>
</html>

