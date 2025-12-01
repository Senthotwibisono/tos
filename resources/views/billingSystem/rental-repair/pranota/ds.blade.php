<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=0.5">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{$title}} | Icon Sarana</title>
  <link rel="stylesheet" href="{{ asset('dist/assets/css/main/app.css') }}">
  <link rel="shortcut icon" href="{{ asset('logo/icon.png') }}" type="image/x-icon">
  <link rel="shortcut icon" href="{{ asset('logo/icon.png') }}" type="image/png">
</head>

<style>
 body{
        font-family: 'Roboto Condensed', sans-serif;
        margin: 0;
        padding: 0;
    }
    .img {
      width: 100%;
      max-width: 100%;
      height: auto;
    }

    .page-break {
                page-break-before: always;
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
  <div class="container">
    <div class="row">
      <div class="col-xs-12">
        <div class="grid invoice">
          <div class="grid-body">
            <div class="invoice-title">
            <div class="row">
              <div class="col-xs-12">
                <!-- <img src="http://vergo-kertas.herokuapp.com/assets/img/logo.png" alt="" height="35"> -->
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-xs-12 col-8 my-auto">
                <h2>Pranota<br>
                  <span class="small">Proforma No. # {{$invoice->proforma_no}}</span>
                  <br>
                </h2>
              </div>
              <div class="col-xs-12 col-4 text-center">
                <div class="position-relative d-inline-block" style="width: 70%;">
                  @php
    $invoiceDate = \Carbon\Carbon::parse($invoice->invoice_date);
@endphp

@if($invoiceDate->lessThan(\Carbon\Carbon::create(2025, 5, 1)))
    <img src="/logoInvoice/logoDSK.jpg" class="img-fluid w-100" alt="Logo">
@else
    <img src="/logoInvoice/logoDS.jpg" class="img-fluid w-100" alt="Logo">
@endif
                  <!-- <img src="/images/paid.png" class="position-absolute" alt="Paid" style="top: 150px; left: 50px; width: 80%; opacity: 0.7;"> -->
                </div>
              </div>
            </div>
          </div>
            <hr>
            <div class="row">
              <div class="col-6">
                <address>
                  <strong>Billed To:</strong><br>
                  Customer Name : {{$invoice->customer->name}}
                  <br>
                  Fax : {{$invoice->customer->fax}}
                  <br>
                  Address : {{$invoice->customer->alamat}}
                  <br>
                </address>
                <address>
                  <strong>Tipe Invoice:</strong><br> DS
                </address>
                <address>
                  <strong>Order Service:</strong><br> {{$invoice->os_name}}
                </address>
              </div>
              <div class="col-6">
                <address>
                  <strong>Order Date:</strong><br> {{$invoice->order_at}}
                </address>
               @php
    use Carbon\Carbon;
    $invoiceDate = Carbon::parse($invoice->invoice_date);
    $cutoffDate = Carbon::create(2025, 5, 1);
@endphp

@if($invoiceDate->lt($cutoffDate))
    <address>
        <strong>Metode Pembayaran</strong><br>
        Nama Bank : <strong>MANDIRI</strong> <br>
        Pemilik Rekening : <strong>PT. INDO KONTAINER SARANA</strong><br>
        Kode Bank : <strong>008</strong><br>
        Nomor Rekening : <strong>1460002771975</strong><br>
        {{-- <small>h.elaine@gmail.com</small> --}}
    </address>
@else
    <address>
        <strong>Metode Pembayaran</strong><br>
        Nama Bank: <strong>MANDIRI</strong> <br>
        Pemilik Rekening: <strong>PT. DEPO INDO KONTAINER SARANA</strong><br>
        Kode Bank: <strong>008</strong><br>
        Nomor Rekening: <strong>1460021308742</strong><br>
    </address>
@endif
              </div>
            </div>

            <div class="row mt-3">
              <div class="col-md-12">
                <h6>PRANOTA SUMMARY</h6>
                <table class="table table-striped">
                @if($form->service->order != 'P')
                  <thead>
                    <tr>
                      <td class="text-center"><strong>Keterangan</strong></td>
                      <td class="text-center"><strong>Detik</strong></td>
                      <td class="text-center"><strong>Jumlah Container</strong></td>
                    </tr>
                  </thead>
                  <tbody>
               
                    <tr>
                      <td class="text-center">{{$form->service->name}}</td>
                      <td class="text-center">{{$form->keterangan ?? '-'}}</td>
                      <td class="text-center">{{$form->palka}}</td>
                    </tr>
             
                  </tbody>
                  @else
                    <thead>
                      <tr>
                        <th class="text-center">Kapal</th>
                        <th class="text-center">Voy In</th>
                        <th class="text-center">Voy Out</th>
                        <th class="text-center">Jumlah Palka</th>
                      </tr>
                    </thead>
                    <tbody>
                    <tr>
                      <td class="text-center">{{$form->Kapal->ves_name ?? '-'}}</td>
                      <td class="text-center">{{$form->Kapal->voy_in ?? '-'}}</td>
                      <td class="text-center">{{$form->Kapal->voy_out ?? '-'}}</td>
                      <td class="text-center">{{$form->palka ?? '-'}}</td>
                    </tr>
                    </tbody>
                  @endif
                </table>
              </div>
            </div>
            <div class="row p-3">
              <div class="col-6">
                <p>Admin (NK):</p>
                <p>Total Amount: </p>
                <p>Discount {{ number_format($form->discount_ds, 2) }}% :</p>
                <p>PPN : </p>
                <p>Grand Total: </p>
              </div>
              <div class="col-6 text-right">
                <!-- <p><strong>Rp. {{ number_format($invoice->total, 0, ',', '.') }} ,00 ~</strong></p>
                <p><strong>Rp. {{ number_format($admin, 0, ',', '.') }} ,00 ~</strong></p>
                <p><strong>Rp. {{ number_format($invoice->pajak, 0, ',', '.') }}, 00 ~</strong></p>
                <p><strong>Rp. {{ number_format($invoice->grand_total, 0, ',', '.') }},00 ~</strong></p> -->
                <p><strong>Rp. {{ number_format($admin, 2, ',', '.') }}</strong></p>
                <p><strong>Rp. {{ number_format($invoice->total, 2, ',', '.') }}</strong></p>
                <p><strong>Rp. {{ number_format($invoice->discount, 2, ',', '.') }}</strong></p>
                <p><strong>Rp. {{ number_format($invoice->pajak, 2, ',', '.') }}</strong></p>
                <p><strong>Rp. {{ number_format($invoice->grand_total, 2, ',', '.') }}</strong></p>

              </div>
              <div class="col-12">
                <p>Terbilang <strong>"{{$terbilang}} Rupiah"</strong></p>
              </div>
              <div class="col-12">
              <h6>Note : </h6>
              <p><strong>1. Pembayaran secara penuh sesuai nilai invoice. Biaya lainnya diluar tanggung jawab kami. 2. Complain Invoice Maksimal 3 (tiga) hari setelah invoice diterima. 3. Invoice dianggap lunas jika pembayaran masuk ke rekening yang telah diinfokan di invoice ini.
                4. Due date dihitung dari ATD. 5. Tidak Menerima pembayaran dalam bentuk tunai.</strong></p>
            </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>
