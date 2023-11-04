<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title><?= $title ?></title>
  <link rel="stylesheet" href="{{asset('dist/assets/css/main/app.css')}}">
  <link rel="shortcut icon" href="{{asset('logo/icon.png')}}" type="image/x-icon">
  <link rel="shortcut icon" href="{{asset('logo/icon.png')}}" type="image/png">
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

    .text-center {
      text-align: center;
    }

    .img {
      width: 100%;
      max-width: 50%;
      height: auto;
    }

    h5 {
      margin-top: 10px;
      margin-bottom: 10px;
      font-size: 14px;
      font-weight: bold;
    }

    h6 {
      margin-top: 10px;
      margin-bottom: 10px;
      font-size: 12px;
      font-weight: bold;
    }
  </style>

</head>

<body>
  <div class="section">

    <div class="row">
      <!-- LEFT SIDE  -->
      <div class="card">
        <div class="card-body">
          <div class="row">

            <div class="col-6">
              <div class="text-center">
                <img src="/logo/ICON2.png" class="img" alt="">
                <!-- <br> -->
                <?php if ($delivery->orderService == "export" || $delivery->orderService == "stuffing") { ?>
                  <p>Export Card</p>
                <?php } ?>
                <br>
                <?= $qrcodes ?>
                <br>
                <br>
                <p><?= $job->container_key ?></p>
                <p><?= $job->container_no ?></p>
                <p><?= $job->ctr_size ?> / <?= $job->ctr_type ?> / <?= $job->ctr_status ?></p>
                <p><?= $job->ves_name ?> / <?= $job->voy_no ?> </p>
                <p><?= $job->yard_block != "" ? $job->yard_block . " /" : "" ?> <?= $job->yard_slot != "" ? $job->yard_slot . " /" : "" ?> <?= $job->yard_row != "" ? $job->yard_row . " /" : "" ?> <?= $job->yard_tier != "" ? $job->yard_tier : "" ?></p>
                <p><?= $job->bl_no ?></p>
                <p><?= $job->consignee ?></p>
                <p>Active to <?= DateFormat($delivery->exp_date) ?></p>
                <!-- <br> -->
                <?php if ($delivery->orderService == "export" || $delivery->orderService == "stuffing") { ?>
                  <p>Closing time <?= DateFormat($delivery->closingtime) ?></p>
                <?php } ?>
                <p>Arabus Kencana</p>

              </div>

            </div>
            <!-- RIGHT SIDE -->
            <div class="col-6">
              <div class="text-center">
                <h6>EQUIPMENT INTERCHANGE RECEIPT (EIR)</h6>
                <img src="/images/EIR.png" class="img" alt="">
                <h6>WAJIB MEMAKAI APD <br> SAAT TURUN TRUK</h6>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

  </div>
  <section class="row">
    <div class="col-12">

      <div class="card">
        <div class="card-body">
          <div class="row">

            <div class="col-6 text-center">
              <h3 class="bold">DEPO INDO KONTAINER SARANA PONTIANAK</h3>
            </div>
            <div class="col-6 text-center">
              <h5 class="lead">No. Nota : <?= $invoice->proformaId ?></h5>
              <h5 class="lead">No. Invoice : <?= $invoice->invoiceNumber ?></h5>
              <h5 class="lead">No. Job : <?= $invoice->jobNumber ?></h5>
            </div>
          </div>
          <div class="row">
            <div class="col-12 text-center py-5">
              <h3 class="bold">SURAT PENYERAHAN PETIKEMAS (SP2)</h3>
            </div>
            <div class="col-5" style="margin-left: 50px;">
              <h5 class="lead">No. Petikemas : <?= $containerItem->container_no ?></h5>
              <h5 class="lead">Ukuran / Statu : <?= $containerItem->ctr_size ?></h5>
              <h5 class="lead">Ex. Kapal / Voyage : <?= $containerItem->ves_name ?></h5>
              <h5 class="lead">Agen : <?= $containerItem->agent ?></h5>
              <h5 class="lead">Lokasi dilapangan : <?= $containerItem->yard_block != "" ? $containerItem->yard_block . " /" : "" ?> <?= $containerItem->yard_slot != "" ? $containerItem->yard_slot . " /" : "" ?> <?= $containerItem->yard_row != "" ? $containerItem->yard_row . " /" : "" ?> <?= $containerItem->yard_tier != "" ? $containerItem->yard_tier : "" ?></h5>
              <h5 class="lead">Penerima / Consignee : <?= $containerItem->consignee ?></h5>
              <h5 class="lead">Tujuan : </h5>
              <h5 class="lead">Remark : </h5>
              <h5 class="lead">No. Kendaraan : <?= $containerItem->truck_no ?></h5>
            </div>
            <div class="col-5" style="margin-left: 100px;">
              <style>
                .rectangle {
                  display: inline-block;
                  width: 30px;
                  height: 30px;
                  border: 2px solid #000;
                  /* Set the border style with a black color */
                  /* margin-left: 10px; */
                  background: transparent;
                  /* Make the background transparent */
                }
              </style>
              <h5 class="lead">Cek <span class="rectangle"></span></h5>
              <h5 class="lead">O/H. O/W. Temp : <?= $containerItem->over_height ?> / <?= $containerItem->over_weight ?></h5>
              <h5 class="lead">Tgl. Tiba : <?= dateFormat($container->arrival_date) ?></h5>
              <h5 class="lead">No. B/L : <?= $containerItem->bl_no ?? "" ?></h5>
              <h5 class="lead">No. D.O : <?= $invoice->deliveryForm->do_number ?></h5>
              <h5 class="lead">Pembayaran dari Tgl. : <?= dateFormat($invoice->deliveryForm->createdAt) ?></h5>
              <h5 class="lead">s/d Tgl. : <?= dateFormat($delivery->exp_date) ?></h5>


            </div>
          </div>
          <div class="row pt-5">
            <div class="col-5" style="margin-left: 50px;">
              <h5 class="bold">PETUGAS LAPANGAN</h5>
              <br>
              <br>
              <br>
              <p><i>(Nama Jelas)</i></p>
            </div>
            <div class="col-5" style="margin-left: 100px;">
              <p>PONTIANAK,</p>
              <h5 class="bold">PT.INDO KONTAINER SARANA</h5>
              <br>
              <br>
              <br>
              <p><i>(Nama Jelas)</i></p>
            </div>
          </div>
          <div class="row pt-5">
            <div class="col-12 text-center">
              <h3 class="bold">BERILAH TANDA YANG JELAS BILA TERDAPAT KERUSAKAN</h3>
            </div>
            <div class="col-12 mt-5">
              <img src="/images/container.png" class="img" style="width: 100% !important; max-width:100% !important;" alt="">
            </div>
          </div>
          <div class="row mt-4">
            <div class="col-4">
              <h5 class="bold">Seal Nomor : <?= $container->seal_no ?></h5>
              <h4 class="bold">KETERANGAN TAMBAHAN : </h4>
              <br>
              <br>
              <br>
              <br>
              <p class="bold">PENERIMA : </p>
              <br>
              <br>
              <br>
              <p><i>(Nama Jelas)</i></p>

            </div>
            <div class="col-4 text-center pt-5">
              <img src="/images/GATE.png" class="img" style="width: 100% !important; max-width:100% !important;" alt="">
            </div>
            <div class="col-4" style="padding-top : 150px;">

              <p class="bold">PETUGAS PINTU : </p>
              <br>
              <br>
              <br>
              <p><i>(Nama Jelas)</i></p>

            </div>
          </div>
        </div>

      </div>
    </div>
    </div>
  </section>
</body>

</html>