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
  body {
    margin-top: 20px;
    background: #eee;
  }

  .invoice {
    padding: 30px;
  }

  .invoice h2 {
    margin-top: 0px;
    line-height: 0.8em;
  }

  .invoice .small {
    font-weight: 300;
  }

  .invoice hr {
    margin-top: 10px;
    border-color: #ddd;
  }

  .invoice .table tr.line {
    border-bottom: 1px solid #ccc;
  }

  .invoice .table td {
    border: none;
  }

  .invoice .identity {
    margin-top: 10px;
    font-size: 1.1em;
    font-weight: 300;
  }

  .invoice .identity strong {
    font-weight: 600;
  }


  .grid {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    background: #fff;
    color: #666666;
    border-radius: 2px;
    margin-bottom: 25px;
    box-shadow: 0px 1px 4px rgba(0, 0, 0, 0.1);
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
                <h2>Pranota<br>
                  <span class="small">Proforma No. # {{$invoice->proforma_no}}</span>
                  <br>
                </h2>
              </div>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-6">
              <div class="col-xs-12 col-12">
                <address>
                  <strong>Billed To:</strong><br>
                  Customer Name : {{$invoice->cust_name}}
                  <br>
                  Fax : {{$invoice->fax}}
                  <br>
                  Address : {{$invoice->alamat}}
                  <br>
                </address>
              </div>
              <div class="col-xs-12 col-12">
                <address>
                  <strong>Tipe Invoice:</strong><br>
                            OSK
                </address>
              </div>
              <div class="col-xs-12 col-12">
                <address>
                  <strong>Order Service:</strong><br>
                  {{$invoice->os_name}}
                </address>
              </div>
            </div>
            <div class="col-6">
              <div class="col-xs-12 col-12">
                <address>
                  <strong>Order Date:</strong><br>
                    {{$invoice->order_at}}
                </address>
              </div>
              <div class="col-xs-12 col-12">
                <address>
                  <strong>Metode Pembayaran</strong><br>
                  Nama Bank : <strong>Bank Central Asia (BCA)</strong> <br>
                  Pemilik Rekening :  <strong>PT. INDO KONTAINER SARANA</strong><br>
                  Kode Bank : <strong>014</strong><br>
                  Nomor Rekening : <strong>0295197531</strong><br>
                  <!-- h.elaine@gmail.com<br> -->
                </address>
              </div>

            </div>
          </div>
          <div class="row">
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
          </div>
          <div class="row mt-3">
              <div class="col-md-12">
                  <h3>PRANOTA SUMMARY</h3>
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
              <p>Total Amount: </p>
              <p>Admin :</p>
              <p>Tax (11%): </p>
              <p>Grand Total: </p>
            </div>
            <div class="col-xs-12 col-6" style="text-align: right;">
              <p><strong>Rp. {{number_format ($invoice->total), 0, ',', '.'}} ,00 ~</strong></p>
              <p><strong>Rp. {{number_format ($admin), 0, ',', '.'}} ,00 ~</strong></p>
              <p><strong>Rp. {{number_format ($invoice->pajak), 0, ',', '.'}}, 00 ~</strong></p>
              <p><strong>Rp.  {{number_format ($invoice->grand_total), 0, ',', '.'}},00 ~</strong></p>

            </div>
            <div class="col-12">
              <p>Terbilang <strong>"{{$terbilang}} Rupiah"</strong></p>
            </div>
            <div class="col-12">
              <h4>Note : </h4>
              <p><strong>1. Pembayaran secara penuh sesuai nilai invoice. Biaya lainnya diluar tanggung jawab kami.</strong></p>
              <p><strong>2. Complain Invoice Maksimal 3 (tiga) hari setelah invoice diterima.</strong></p>
              <p><strong>3. Invoice dianggap lunas jika pembayaran masuk ke rekening yang telah diinfokan di invoice ini.</strong></p>
              <p><strong>4. Due date dihitung dari ATD. </strong></p>
              <p><strong>5. Tidak Menerima pembayaran dalam bentuk tunai.</strong></p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- END INVOICE -->
  </div>
</div>

</html>