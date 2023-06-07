<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

  <title><?= $title ?></title>
  <link rel="stylesheet" href="<?php echo e(asset('dist/assets/css/main/app.css')); ?>">

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
                  <span class="small">Performa No. #<?= $invoices->performaId ?></span>
                </h2>
              </div>
              <?php if ($invoices->isPaid == "1") { ?>
                <div class="col-xs-12 col-4 text-center">
                  <img src="/images/paid.png" class="img" style="width:50%;" alt="">
                </div>
              <?php } ?>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-xs-6">
              <address>
                <strong>Billed To:</strong><br>
                <?= $invoices->data7->customer ?><br>
              </address>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-6">
              <address>
                <strong>Metode Pembayaran:</strong><br>
                BCA rekening : 0788928819<br>
                <!-- h.elaine@gmail.com<br> -->
              </address>
            </div>
            <div class="col-xs-6 text-right">
              <address>
                <strong>Order Date:</strong><br>
                <?= DateTimeFormat($invoices->createdAt) ?> WIB
              </address>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <h3>ORDER SUMMARY</h3>
              <table class="table table-striped">
                <thead>
                  <tr class="line">
                    <!-- <td><strong>#</strong></td> -->
                    <td class="text-right"><strong>Keterangan</strong></td>
                    <td class="text-right"><strong>Jumlah Container</strong></td>
                    <td class="text-right"><strong>Ukuran</strong></td>
                    <td class="text-right"><strong>Type</strong></td>
                    <td class="text-right"><strong>Status</strong></td>
                    <td class="text-right"><strong>Hari</strong></td>
                    <td class="text-right"><strong>Tarif Satuan</strong></td>
                    <td class="text-right"><strong>Amount</strong></td>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($invoices->data1 as $value) { ?>
                    <tr>
                      <td><?= $value->title ?></td>
                      <td><?= $value->jumlah ?></td>
                      <td><?= $value->size ?></td>
                      <td><?= $value->type ?></td>
                      <td><?= $value->status ?></td>
                      <td><?= $value->hari ?></td>
                      <td>Rp. <?= rupiah($value->tarif) ?>,00 ~</td>
                      <td>Rp. <?= rupiah($value->amount) ?>,00 ~</td>

                    </tr>
                  <?php } ?>
                  <?php foreach ($invoices->data2 as $value) { ?>
                    <tr>
                      <td><?= $value->title ?></td>
                      <td><?= $value->jumlah ?></td>
                      <td><?= $value->size ?></td>
                      <td><?= $value->type ?></td>
                      <td><?= $value->status ?></td>
                      <td><?= $value->hari ?></td>
                      <td>Rp. <?= rupiah($value->tarif) ?>,00 ~</td>
                      <td>Rp. <?= rupiah($value->amount) ?>,00 ~</td>

                    </tr>
                  <?php } ?>
                  <?php foreach ($invoices->data3 as $value) { ?>
                    <tr>
                      <td><?= $value->title ?></td>
                      <td><?= $value->jumlah ?></td>
                      <td><?= $value->size ?></td>
                      <td><?= $value->type ?></td>
                      <td><?= $value->status ?></td>
                      <td><?= $value->hari ?></td>
                      <td>Rp. <?= rupiah($value->tarif) ?>,00 ~</td>
                      <td>Rp. <?= rupiah($value->amount) ?>,00 ~</td>

                    </tr>
                  <?php } ?>
                  <?php foreach ($invoices->data4 as $value) { ?>
                    <tr>
                      <td><?= $value->title ?></td>
                      <td><?= $value->jumlah ?></td>
                      <td><?= $value->size ?></td>
                      <td><?= $value->type ?></td>
                      <td><?= $value->status ?></td>
                      <td><?= $value->hari ?></td>
                      <td>Rp. <?= rupiah($value->tarif) ?>,00 ~</td>
                      <td>Rp. <?= rupiah($value->amount) ?>,00 ~</td>

                    </tr>
                  <?php } ?>
                  <?php foreach ($invoices->data5 as $value) { ?>
                    <tr>
                      <td><?= $value->title ?></td>
                      <td><?= $value->jumlah ?></td>
                      <td><?= $value->size ?></td>
                      <td><?= $value->type ?></td>
                      <td><?= $value->status ?></td>
                      <td><?= $value->hari ?></td>
                      <td>Rp. <?= rupiah($value->tarif) ?>,00 ~</td>
                      <td>Rp. <?= rupiah($value->amount) ?>,00 ~</td>

                    </tr>
                  <?php } ?>
                  <?php foreach ($invoices->data6 as $value) { ?>
                    <tr>
                      <td><?= $value->title ?></td>
                      <td><?= $value->jumlah ?></td>
                      <td><?= $value->size ?></td>
                      <td><?= $value->type ?></td>
                      <td><?= $value->status ?></td>
                      <td><?= $value->hari ?></td>
                      <td>Rp. <?= rupiah($value->tarif) ?>,00 ~</td>
                      <td>Rp. <?= rupiah($value->amount) ?>,00 ~</td>

                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
          <div class="row p-3">
            <div class="col-xs-12 col-6">
              <p>Total Amount: </p>
              <p>Administration: </p>
              <p>Tax (10%): </p>
              <p>Grand Total: </p>
            </div>
            <div class="col-xs-12 col-6" style="text-align: right;">
              <p><strong>Rp. <?= rupiah($invoices->data7->totalamount) ?> ,00 ~</strong></p>
              <p><strong>Rp. <?= rupiah($invoices->data7->admin) ?> ,00 ~</strong></p>
              <p><strong>Rp. <?= rupiah($invoices->data7->tax) ?> ,00 ~</strong></p>
              <p><strong>Rp. <?= rupiah($invoices->data7->grandtotal) ?> ,00 ~</strong></p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- END INVOICE -->
  </div>
</div>

</html><?php /**PATH D:\Fdw Files\CTOS\dev\frontend\tos-dev-local\resources\views/invoice/paid_invoice/index.blade.php ENDPATH**/ ?>