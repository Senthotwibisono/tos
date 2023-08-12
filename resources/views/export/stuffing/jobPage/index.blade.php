<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title><?= $title ?></title>
  <link rel="stylesheet" href="{{asset('dist/assets/css/main/app.css')}}">

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
    <?php
    $i = 0;
    foreach ($jobs as $job) { ?>
      <div class="row">
        <!-- LEFT SIDE  -->
        <div class="card">
          <div class="card-body">
            <div class="row">

              <div class="col-6">
                <div class="text-center">
                  <img src="/logo/ICON2.png" class="img" alt="">
                  <br>
                  <?= $qrcodes[$i] ?>
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
    <?php
      $i++;
    } ?>
  </div>
</body>

</html>