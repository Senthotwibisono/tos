<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title><?= $title ?> #<?= $invoices->invoiceNumber ?> | Icon Sarana</title>
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
                <h2>Pranota<br>
                  <span class="small">Proforma No. #<?= $invoices->proformaId ?></span>
                  <br>
                  <span class="small">Invoice No. #<?= $invoices->invoiceNumber ?></span>
                </h2>
              </div>
              <?php if ($invoices->isPaid == "1" || $invoices->isPiutang == "1") { ?>
                <div class="col-xs-12 col-4 text-center">
                  <img src="/images/paid.png" class="img" style="width:50%;" alt="">
                </div>
              <?php } ?>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-6">
              <div class="col-xs-12 col-12">
                <address>
                  <strong>Billed To:</strong><br>
                  Customer No. : <?= $invoices->deliveryForm->customer->customer_no ?>
                  <br>
                  Customer Name : <?= $invoices->deliveryForm->customer->customer_name ?>
                  <br>
                  Fax : <?= $invoices->deliveryForm->customer->fax ?>
                  <br>
                  Address : <?= $invoices->deliveryForm->customer->address ?>
                  <br>
                </address>
              </div>
              <div class="col-xs-12 col-12">
                <address>
                  <strong>Tipe Invoice:</strong><br>
                  <?= $invoices->billingName ?>
                </address>
              </div>
              <div class="col-xs-12 col-12">
                <address>
                  <strong>Order Service:</strong><br>
                  <?php if ($invoices->deliveryForm->orderService == "sp2iks") { ?>
                    SP2 Kapal Sandar Icon (MT Balik IKS / MKB)
                  <?php } else if ($invoices->deliveryForm->orderService == "sp2pelindo") { ?>
                    SP2 Kapal Sandar icon (MT Balik Pelindo)
                  <?php } else if ($invoices->deliveryForm->orderService == "spps") { ?>
                    SPPS
                  <?php } else if ($invoices->deliveryForm->orderService == "sppsrelokasipelindo") { ?>
                    SPPS (Relokasi Pelindo - ICON)
                  <?php } else if ($invoices->deliveryForm->orderService == "sp2icon") { ?>
                    SP2 (MT Balik ICON / MKB)
                  <?php } ?>
                </address>
              </div>
            </div>
            <div class="col-6">
              <div class="col-xs-12 col-12">
                <address>
                  <strong>Order Date:</strong><br>
                  <?= DateTimeFormat($invoices->deliveryForm->createdAt) ?>
                </address>
              </div>
              <div class="col-xs-12 col-12">
                <address>
                  <strong>Metode Pembayaran</strong><br>
                  Nama Bank : <?= $payments->bank ?> <br>
                  Pemilik Rekening : <?= $payments->name ?> <br>
                  Kode Bank : <?= $payments->bankCode ?><br>
                  Nomor Rekening : <?= $payments->bankNumber ?><br>
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
                    <th>Status</th>
                    <th>Size</th>
                    <th>Type</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td><?= $invoices->containerDetail->{'Container Number'} ?></td>
                    <td><?= $invoices->containerDetail->{'Container Status'} ?></td>
                    <td><?= $invoices->containerDetail->{'Container Size'} ?></td>
                    <td><?= $invoices->containerDetail->{'Container Type'} ?></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <h3>PRANOTA SUMMARY</h3>
              <table class="table table-striped">
                <thead>
                  <tr class="line">
                    <!-- <td><strong>#</strong></td> -->
                    <td class="text-right"><strong>Keterangan</strong></td>
                    <td class="text-right"><strong>Hari</strong></td>
                    <td class="text-right"><strong>Tarif Satuan</strong></td>
                    <td class="text-right"><strong>Amount</strong></td>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($invoices->billingDetail as $description => $bill) { ?>
                    <tr>
                      <td><?= $description ?></td>
                      <td><?= $bill->Hari ?? "-" ?></td>
                      <td>Rp. <?= rupiah($bill->{'Tarif Satuan'}) ?>,00 ~</td>
                      <td>Rp. <?= rupiah($bill->Amount) ?>,00 ~</td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
          <div class="row p-3">
            <div class="col-xs-12 col-6">
              <p>Total Amount: </p>
              <p>Tax (11%): </p>
              <p>Grand Total: </p>
            </div>
            <div class="col-xs-12 col-6" style="text-align: right;">
              <p><strong>Rp.<?= rupiah($invoices->billingTotal->{'Total Amount'}) ?> ,00 ~</strong></p>
              <p><strong>Rp.<?= rupiah($invoices->billingTotal->{'Tax'}) ?> ,00 ~</strong></p>
              <p><strong>Rp.<?= rupiah($invoices->billingTotal->{'Grand Total'}) ?> ,00 ~</strong></p>

            </div>
            <div class="col-12">
              <p>Terbilang <strong>"<?= terbilang($invoices->billingTotal->{'Grand Total'}) ?> Rupiah"</strong></p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- END INVOICE -->
  </div>
</div>

</html>