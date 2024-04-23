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
                            Stevadooring
                </address>
              </div>
              <div class="col-xs-12 col-12">
                <address>
                  <strong>Activity Include:</strong><br>
                  @if($invoice->tambat_tongkak == 'Y') Tambat Tongkak, @endif 
                  @if($invoice->tambat_kapal == 'Y') Tambat Kapal, @endif 
                  @if($invoice->stevadooring == 'Y') Stevadooring, @endif 
                  @if($invoice->shifting == 'Y') Shifting @endif 
                </address>
              </div>
            </div>
            <div class="col-6">
              <div class="col-xs-12 col-12">
                <address>
                  <strong>Order Date:</strong><br>
                  {{$invoice->created_at}}
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
          </div>
          <h4>Vessel Information</h4>
            <div class="col-3">
                <div class="form-group">
                    <label for="">Vessel</label>
                    <input type="text" class="form-control" name="ves_name" value="{{$invoice->ves_name}}" readonly>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label for="">Ves Code</label>
                    <input type="text" class="form-control" value="{{$invoice->ves_code}}" readonly>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label for="">Voy Out</label>
                    <input type="text" class="form-control" value="{{$invoice->voy_out}}" readonly>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label for="">Ves Code</label>
                    <input type="text" class="form-control" value="{{$invoice->ves_code}}" readonly>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label for="">Arrival Date</label>
                    <input type="text" class="form-control" name="arrival_date" value="{{$invoice->arrival_date}}" readonly>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label for="">Deparature Date</label>
                    <input type="text" class="form-control" name="deparature_date" value="{{$invoice->deparature_date}}" readonly>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label for="">Open Stack Date</label>
                    <input type="text" class="form-control" value="{{$invoice->open_stack_date}}" readonly>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label for="">Clossing Date</label>
                    <input type="text" class="form-control" value="{{$invoice->clossing_date}}" readonly>
                </div>
            </div>
        </div>
          <div class="row mt-3">
              <div class="col-md-12">
                  <h3>PRANOTA SUMMARY</h3>
                @if($invoice->tambat_tongkak == 'Y')
                <h4>Jasa Tambat Tongkak</h4>
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Item Name</th>
                      <th>Tarif Satuan</th>
                      <th>Jumlah</th>
                      <th>Total</th>
                    </tr>
                  </thead>
                  <tbody>
                   @foreach($tongkak as $tt)
                   <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$tt->name}}</td>
                    <td>{{number_format($tt->tarif, 0, ',', '.')}}</td>
                    <td>{{$tt->jumlah}}</td>
                    <td>{{number_format($tt->total, 0 ,',', '.' )}}</td>
                   </tr>
                   @endforeach
                  </tbody>
                </table>
                <hr>
                @endif
                @if($invoice->tambat_kapal == 'Y')
                <h4>Jasa Tambat Kapal</h4>
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Item Name</th>
                      <th>Tarif Satuan</th>
                      <th>GT Kapal</th>
                      <th>Etmal</th>
                      <th>Total</th>
                    </tr>
                  </thead>
                  <tbody>
                   <tr>
                    <td>1</td>
                    <td>Jasa Tambat Kapal</td>
                    <td>{{number_format($tkapal->tarif, 0 , ',', '.')}}</td>
                    <td>{{$tkapal->gt_kapal}}</td>
                    <td>{{$tkapal->etmal}}</td>
                    <td>{{number_format($tkapal->total, 0 , ',', '.')}}</td>
                   </tr>
                  </tbody>
                </table>
                <hr>
                @endif
                @if($invoice->stevadooring == 'Y')
                <h4>Stevadooring</h4>
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Container Size</th>
                      <th>Status</th>
                      <th>Tarif Satuan</th>
                      <th>Jumlah</th>
                      <th>Total</th>
                    </tr>
                  </thead>
                  <tbody>
                   @foreach($stevadooring as $stv)
                   <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$stv->ctr_size}}</td>
                    <td>{{$stv->ctr_status}}</td>
                    <td>{{number_format($stv->tarif, 0, ',', '.')}}</td>
                    <td>{{$stv->jumlah}}</td>
                    <td>{{number_format($stv->total, 0, ',', '.')}}</td>
                   </tr>
                   @endforeach
                  </tbody>
                </table>
                @endif
                @if($invoice->shifting == 'Y')
                <h4>Shifting</h4>
                <div class="row">
                    <strong class="text-center">Crane Dermaga</strong>
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Container Size</th>
                          <th>Status</th>
                          <th>Dengan Landing</th>
                          <th>Tarif Satuan</th>
                          <th>Jumlah</th>
                          <th>Total</th>
                        </tr>
                      </thead>
                      <tbody>
                      @foreach($crane_dermaga as $cd)
                      <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$cd->ctr_size}}</td>
                        <td>{{$cd->ctr_status}}</td>
                        <td>{{$cd->landing}}</td>
                        <td>{{number_format($cd->tarif, 0, ',', '.')}}</td>
                        <td>{{$cd->jumlah}}</td>
                        <td>{{number_format($cd->total, 0, ',', '.')}}</td>
                      </tr>
                      @endforeach
                      </tbody>
                    </table>
                </div>
                <div class="row">
                    <strong class="text-center">Crane Kapal</strong>
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Container Size</th>
                          <th>Status</th>
                          <th>Dengan Landing</th>
                          <th>Tarif Satuan</th>
                          <th>Jumlah</th>
                          <th>Total</th>
                        </tr>
                      </thead>
                      <tbody>
                      @foreach($crane_kapal as $ck)
                      <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$ck->ctr_size}}</td>
                        <td>{{$ck->ctr_status}}</td>
                        <td>{{$ck->landing}}</td>
                        <td>{{number_format($ck->tarif, 0, ',', '.')}}</td>
                        <td>{{$ck->jumlah}}</td>
                        <td>{{number_format($ck->total, 0, ',', '.')}}</td>
                      </tr>
                      @endforeach
                      </tbody>
                    </table>
                </div>
                @endif
            </div>
          </div>
          <div class="row p-3">
            <div class="col-xs-12 col-6">
              <p>Total Amount: </p>
              <p>Tax (11%): </p>
              <p>Admin: </p>
              <p>Grand Total: </p>
            </div>
            <div class="col-xs-12 col-6" style="text-align: right;">
              <p><strong>Rp. {{number_format ($invoice->total), 0, ',', '.'}} ,00 ~</strong></p>
              <p><strong>Rp. {{number_format ($invoice->pajak), 0, ',', '.'}}, 00 ~</strong></p>
              <p><strong>Rp. {{number_format ($invoice->admin), 0, ',', '.'}}, 00 ~</strong></p>
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