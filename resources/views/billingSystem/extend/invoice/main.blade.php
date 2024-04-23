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
    position: relative;
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
                            DS
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
                  Nama Bank : Bank Central Asia (BCA) <br>
                  Pemilik Rekening :  PT. INDO KONTAINER SARANA<br>
                  Kode Bank : 014<br>
                  Nomor Rekening : 0295197531<br>
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
                 @foreach($item as $cont)
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
                  <h3>INVOICE SUMMARY</h3>
                  @if($invoice->ctr_20 != null)
                  <span>Container <strong>20</strong></span>
                  <table class="table table-striped">
                      <thead>
                        <tr class="line">
                          <th>Keterangan</th>
                          <th>Jumlah</th>
                          <th>Hari</th>
                          <th>Tarif Satuan</th>
                          <th>Amount</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($invoice->m1 != '0')
                       <tr>
                        <td>Massa 1</td>
                        <td>{{$invoice->ctr_20}}</td>
                        <td>{{$invoice->m1}}</td>
                        <td>{{($invoice->m1_20 / $invoice->ctr_20) / $invoice->m1}}</td>
                        <td>{{$invoice->m1_20}} </td>
                       </tr>
                       @endif
                       @if($invoice->m2 != '0')
                       <tr>
                       <td>Massa 2</td>
                        <td>{{$invoice->ctr_20}}</td>
                        <td>{{$invoice->m2}}</td>
                        <td>{{($invoice->m2_20 / $invoice->ctr_20 / $invoice->m2)}}</td>
                        <td>{{$invoice->m2_20}} </td>
                       </tr>
                       @endif
                       @if($invoice->m3 != '0')
                       <tr>
                       <td>Massa 3</td>
                        <td>{{$invoice->ctr_20}}</td>
                        <td>{{$invoice->m3}}</td>
                        <td>{{($invoice->m3_20 / $invoice->ctr_20) / $invoice->m3}}</td>
                        <td>{{$invoice->m3_20}} </td>
                       </tr>
                       @endif
                      </tbody>
                    </table>
                  @endif
                  <br>
                  @if($invoice->ctr_21 != null)
                  <span>Container <strong>21</strong></span>
                  <table class="table table-striped">
                      <thead>
                        <tr class="line">
                          <th>Keterangan</th>
                          <th>Jumlah</th>
                          <th>Hari</th>
                          <th>Tarif Satuan</th>
                          <th>Amount</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($invoice->m1 != '0')
                       <tr>
                        <td>Massa 1</td>
                        <td>{{$invoice->ctr_21}}</td>
                        <td>{{$invoice->m1}}</td>
                        <td>{{($invoice->m1_21 / $invoice->ctr_21) / $invoice->m1}}</td>
                        <td>{{$invoice->m1_21}} </td>
                       </tr>
                       @endif
                       @if($invoice->m2 != '0')
                       <tr>
                       <td>Massa 2</td>
                        <td>{{$invoice->ctr_21}}</td>
                        <td>{{$invoice->m2}}</td>
                        <td>{{($invoice->m2_21 / $invoice->ctr_21) / $invoice->m2}}</td>
                        <td>{{$invoice->m2_21}} </td>
                       </tr>
                       @endif
                       @if($invoice->m3 != '0')
                       <tr>
                       <td>Massa 3</td>
                        <td>{{$invoice->ctr_21}}</td>
                        <td>{{$invoice->m3}}</td>
                        <td>{{($invoice->m3_21 / $invoice->ctr_21) / $invoice->m3}}</td>
                        <td>{{$invoice->m3_21}} </td>
                       </tr>
                       @endif
                      </tbody>
                    </table>
                  @endif
                  <br>
                  @if($invoice->ctr_40 != null)
                  <span>Container <strong>40</strong></span>
                  <table class="table table-striped">
                      <thead>
                        <tr class="line">
                          <th>Keterangan</th>
                          <th>Jumlah</th>
                          <th>Hari</th>
                          <th>Tarif Satuan</th>
                          <th>Amount</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($invoice->m1 != '0')
                       <tr>
                        <td>Massa 1</td>
                        <td>{{$invoice->ctr_40}}</td>
                        <td>{{$invoice->m1}}</td>
                        <td>{{($invoice->m1_40 / $invoice->ctr_40) / $invoice->m1}}</td>
                        <td>{{$invoice->m1_40}} </td>
                       </tr>
                       @endif
                       @if($invoice->m2 != '0')
                       <tr>
                       <td>Massa 2</td>
                        <td>{{$invoice->ctr_40}}</td>
                        <td>{{$invoice->m2}}</td>
                        <td>{{($invoice->m2_40 / $invoice->ctr_40) / $invoice->m2}}</td>
                        <td>{{$invoice->m2_40}} </td>
                       </tr>
                       @endif
                       @if($invoice->m3 != '0')
                       <tr>
                       <td>Massa 3</td>
                        <td>{{$invoice->ctr_40}}</td>
                        <td>{{$invoice->m3}}</td>
                        <td>{{($invoice->m3_40 / $invoice->ctr_40) / $invoice->m3}}</td>
                        <td>{{$invoice->m3_40}} </td>
                       </tr>
                       @endif
                      </tbody>
                    </table>
                  @endif
                  @if($invoice->ctr_42 != null)
                  <span>Container <strong>42</strong></span>
                  <table class="table table-striped">
                      <thead>
                        <tr class="line">
                          <th>Keterangan</th>
                          <th>Jumlah</th>
                          <th>Hari</th>
                          <th>Tarif Satuan</th>
                          <th>Amount</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($invoice->m1 != '0')
                       <tr>
                        <td>Massa 1</td>
                        <td>{{$invoice->ctr_42}}</td>
                        <td>{{$invoice->m1}}</td>
                        <td>{{($invoice->m1_42 / $invoice->ctr_42) / $invoice->m1}}</td>
                        <td>{{$invoice->m1_42}} </td>
                       </tr>
                       @endif
                       @if($invoice->m2 != '0')
                       <tr>
                       <td>Massa 2</td>
                        <td>{{$invoice->ctr_42}}</td>
                        <td>{{$invoice->m2}}</td>
                        <td>{{($invoice->m2_42 / $invoice->ctr_42) / $invoice->m2}}</td>
                        <td>{{$invoice->m2_42}} </td>
                       </tr>
                       @endif
                       @if($invoice->m3 != '0')
                       <tr>
                       <td>Massa 3</td>
                        <td>{{$invoice->ctr_42}}</td>
                        <td>{{$invoice->m3}}</td>
                        <td>{{($invoice->m3_42 / $invoice->ctr_42) / $invoice->m3}}</td>
                        <td>{{$invoice->m3_42}} </td>
                       </tr>
                       @endif
                      </tbody>
                    </table>
                  @endif
            </div>
          </div>
          <div class="row p-3">
            <div class="col-xs-12 col-6">
              <p>Total Amount: </p>
              <p>Admin : </p>
              <p>Tax (11%): </p>
              <p>Grand Total: </p>
            </div>
            <div class="col-xs-12 col-6" style="text-align: right;">
              <p><strong>Rp. {{number_format ($invoice->total), 0, ',', '.'}} ,00 ~</strong></p>
              <p><strong>Rp. {{number_format ($invoice->admin), 0, ',', '.'}} ,00 ~</strong></p>
              <p><strong>Rp. {{number_format ($invoice->pajak), 0, ',', '.'}}, 00 ~</strong></p>
              <p><strong>Rp.  {{number_format ($invoice->grand_total), 0, ',', '.'}},00 ~</strong></p>

            </div>
            <div class="col-12">
              <p>Terbilang <strong>"{{$terbilang}} Rupiah"</strong></p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- END INVOICE -->
  </div>
</div>

</html>