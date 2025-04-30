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

<div class="container">
  <div class="row">
    <!-- BEGIN INVOICE -->
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
                <h2>Invoice<br>
                  <span class="small">Proforma No. # {{$invoice->proforma_no}}</span><br>
                  <span class="small">Invoice No. # {{$invoice->inv_no}}</span>
                  <br>
                </h2>
              </div>
              <div class="col-xs-12 col-4 text-center">
                  <img src="/images/paid.png" class="img" style="width:50%;" alt="">
                </div>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-6">
              <div class="col-xs-12 col-12">
                <address>
                  <strong>Billed To:</strong><br>
                  Customer Name : {{$invoice->customer->name}}
                  <br>
                  Fax : {{$invoice->customer->fax}}
                  <br>
                  Address : {{$invoice->customer->alamat}}
                  <br>
                </address>
              </div>
              <div class="col-xs-12 col-12">
                <address>
                  <strong>Tipe Invoice:</strong><br>
                            OS
                </address>
              </div>
              <div class="col-xs-12 col-12">
                <address>
                  <strong>Order Service:</strong><br>
                  {{$invoice->os_name}}
                </address>
              </div>
              <div class="col-xs-12 col-12">
                <address>
                  <strong>Booking Number:</strong><br>
                  {{$singleCont->SingleCont->booking_no ?? ''}}
                </address>
              </div>
            </div>
            <div class="col-6">
              <div class="col-xs-12 col-12">
                <address>
                  <strong>Order Date:</strong><br>
                  {{$invoice->invoice_date}}
                </address>
              </div>
              <div class="col-xs-12 col-12">
                <address>
                  <strong>Metode Pembayaran</strong><br>
                  Nama Bank: <strong>MANDIRI</strong> <br>
                  Pemilik Rekening: <strong>PT. DEPO INDO KONTAINER SARANA</strong><br>
                  Kode Bank: <strong>008</strong><br>
                  Nomor Rekening: <strong>1460021308742</strong><br>
                </address>
                <address>
                <strong>Veesel</strong><br>
                  Ves Name : <strong>{{$form->Kapal->ves_name ?? ''}}</strong> <br>
                  Voy No :  <strong>{{$form->Kapal->voy_out ?? ''}}</strong><br>
                  Arrival Date : <strong>{{$form->Kapal->arrival_date ?? ''}}</strong><br>
                  Departure Date : <strong>{{$form->Kapal->deparature_date ?? ''}}</strong><br>
                  <!-- h.elaine@gmail.com<br> -->
                </address>
              </div>

            </div>
            <br>
            <br>
            <div class="col-6">
              <div class="col-xs-12 col-12">
                
              </div>

            </div>
          </div>
          <!-- <div class="row">
            <div class="col-12">
              <h3>CONTAINER SUMMARY</h3>
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Container No</th>
                    <th>Size</th>
                    <th>Status</th>
                    <th>Type</th>
                  </tr>
                </thead>
                <tbody>
                @foreach($contInvoice as $cont)
                    <tr>
                        <td>{{$cont->container_no}}</td>
                        <td>{{$cont->ctr_size}}</td>
                        <td>{{$cont->ctr_status}}</td>
                        <td>{{$cont->ctr_type}}</td>
                    </tr>
                 @endforeach
                </tbody>
              </table>
            </div>
          </div> -->
          <div class="row mt-3">
              <div class="col-md-12">
                  <!-- <h3>PRANOTA SUMMARY</h3> -->
                  @foreach ($invGroup as $ukuran => $details)
                  <span>Container <strong>{{$ukuran}}</strong></span>
              <table class="table table-striped">
                <thead>
                  <tr class="line">
                    <!-- <td><strong>#</strong></td> -->
                    <td class="text-right"><strong>Keterangan</strong></td>
                    <td class="text-right"><strong>Jumlah Container</strong></td>
                    <td class="text-right"><strong>Hari</strong></td>
                    <td class="text-right"><strong>Tarif Satuan</strong></td>
                    <td class="text-right"><strong>Amount</strong></td>
                  </tr>
                </thead>
                <tbody>
                @foreach ($details as $detail)
                <tr>
                    <td class="text-right">{{ $detail->master_item_name }}</td>
                    <td class="text-right">{{ $detail->jumlah }}</td>
                    <td class="text-right">{{ $detail->jumlah_hari }}</td>
                    <td class="text-right">{{ $detail->tarif }}</td>
                    <td class="text-right">{{ $detail->total }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
            @endforeach
            </div>
          </div>
          <div class="row p-3">
          <div class="col-xs-12 col-6">
              <p>Admin (NK):</p>
              <p>Total Amount: </p>
              <p>Discount :</p>
              <p>PPN : </p>
              <p>Grand Total: </p>
            </div>
            <div class="col-xs-12 col-6" style="text-align: right;">
            <p><strong>Rp. {{ number_format($admin, 2, ',', '.') }}</strong></p>
            <p><strong>Rp. {{ number_format($invoice->total + $admin, 2, ',', '.') }}</strong></p>
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
    <!-- END INVOICE -->
  </div>
</div>

</html>