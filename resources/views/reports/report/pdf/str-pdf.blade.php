
<!DOCTYPE html>
<html>
<head>
    <title>Stripping Reports (Container 04) {{$ves_name}}</title>
</head>
<style type="text/css">
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
</style>
<body>
<div class="head-title">
    <h1 class="text-center m-0 p-0">Stripping Reports (Container 04)</h1>
</div>
<div class="add-detail mt-10">
    <div class="w-50 float-left mt-10">
        <p class="m-0 pt-5 text-bold w-100">Vessel Name - <span class="gray-color">{{$ves_name}}    //      Voy no. {{$voy_no}}</span></p>
        <p class="m-0 pt-5 text-bold w-100">Ves Code - <span class="gray-color">{{$ves_code}}</span></p>
        <p class="m-0 pt-5 text-bold w-100">Vessel Id - <span class="gray-color">{{$ves_id}}</span></p>
    </div>
    <div style="clear: both;"></div>
</div>
<div class="table-section bill-tbl w-100 mt-10">
    <table class="table w-100 mt-10">
        <tr>
            <th class="w-50">Jumlah Total</th>
        
        </tr>
        <tr>
            <td>
                <div class="box-text">
                    <p>{{$total}}</p>
                </div>
            </td>
            
        </tr>
    </table>
</div>
<div class="table-section bill-tbl w-100 mt-10">
    <table class="table w-100 mt-10">
        
        @php
                $previousBay = null;
                $currentPage = 1;
        $totalPages = ceil(count($item) / 10); // Menghitung jumlah total halaman berdasarkan jumlah data dan asumsi 10 baris per halaman
    @endphp
            @endphp
            @foreach($item as $index => $item)
                @php
                    $bay = $item->yard_block;
                    if ($previousBay !== $bay) {
                        if ($previousBay !== null) {
                    echo '</table></div></div><div class="page-break"></div><div class="table-section bill-tbl w-100 mt-10"><table class="table w-100 mt-10">'; // Penutup dan pembukaan tabel baru pada halaman baru
                }                 
                        echo '<tr><th class="w-50">Yard Block</th><th class="w-50">Yard Slot</th><th class="w-50">Yard Row</th><th class="w-50">Yard Tier</th><th class="w-50">Container No</th><th class="w-50">Size</th><th class="w-50">Status</th><th class="w-50">Type</th></tr>'; // Header tabel baru
                        echo '<tr class="bay-title"><td colspan="7">' . $bay . '</td></tr>'; // Menampilkan judul bay
                        $previousBay = $bay;
                    }
                @endphp
            <tr align="center">
                <td></td>
                
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
<div class="footer">
<!-- Halaman {{ $pageNumber }} dari {{ $pageCount }} -->
<h4>PT. Indo Container Lines
<br>
<p>Jl. Komp Yos Sudarso No. 31 Pontianak - Kalimantan Barat
    <br>
Telp :   056 173 0255
Fax :   056 173 0242</p>
</h4>
    </div>
    <script type="text/javascript">
        window.onload = function() {
            var pageCount = {{$totalPages}};
            var pageCounter = 1;

            // Mengatur nomor halaman pada setiap pergantian halaman
            var pageBreakElements = document.querySelectorAll('.page-break');
            pageBreakElements.forEach(function(element) {
                pageCounter++;
                element.addEventListener('mouseenter', function() {
                    document.querySelector('.footer').innerHTML = 'Halaman ' + pageCounter + ' dari ' + pageCount;
                });
            });
        };
    </script>
</html>