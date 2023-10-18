@extends ('partial.invoice.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Detail Master Tarif Delivery</p>

</div>
<?php
$menuArr = [
  "SP2 Kapal Sandar icon (MT Balik IKS)",
  "SP2 Kapal Sandar icon (MKB)",
  "SP2 Kapal Sandar icon (MT Balik Pelindo)",
  "SPPS",
  "SPPS (Relokasi Pelindo - ICON)",
  "SP2 (Relokasi Pelindo - ICON)",
];
$tarifArr = [
  "sp2iks" => [
    "DSK" => ["Lift On / Off Full", "Penumpukan Masa 1", "Penumpukan Masa 2", "Penumpukan Masa 3", "Pass Truck Keluar"],
    "codeDSK" => ["lift_full", "masa1", "masa2", "masa3", "pass_truck"],
    "DS" => ["Lift On / Off Empty", "Pass Truck Masuk", "Administrasi"],
    "codeDS" => ["lift_empty", "pass_truck", "administrasi"],
  ],
  "sp2mkb" => [
    "DSK" => ["Lift On / Off Full", "Penumpukan Masa 1", "Penumpukan Masa 2", "Penumpukan Masa 3", "Pass Truck Keluar"],
    "codeDSK" => ["lift_full", "masa1", "masa2", "masa3", "pass_truck"],
    "DS" => ["Lift On / Off Empty", "Pass Truck Masuk", "Administrasi"],
    "codeDS" => ["lift_empty", "pass_truck", "administrasi"],
  ],
  "sp2pelindo" => [
    "DSK" => ["Lift On / Off Full", "Pass Truck Keluar", "Penumpukan Masa 1", "Penumpukan Masa 2", "Penumpukan Masa 3", "Administrasi"],
    "codeDSK" => ["lift_full", "pass_truck", "masa1", "masa2", "masa3", "administrasi"],

  ],
  "spps" => [
    "DSK" => ["Pass Truck"],
    "codeDSK" => ["pass_truck"],
    "DS" => ["Paket Stripping", "Pemindahan Peti", "Penumpukan Masa 1", "Penumpukan Masa 2", "Penumpukan Masa 3", "Administrasi"],
    "codeDS" => ["paket_stripping", "pemindahan", "masa1", "masa2", "masa3", "administrasi"],
  ],
  "sppsrelokasipelindo" => [
    "DS" => ["Paket Stripping", "Penumpukan Masa 1", "Penumpukan Masa 2", "Penumpukan Masa 3", "Pass Truck", "Administrasi"],
    "codeDS" => ["paket_stripping", "masa1", "masa2", "masa3", "pass_truck", "administrasi"],

  ],
  "sp2icon" => [
    "DS" => ["Lift Off MT", "Pass Truck", "Administrasi"],
    "codeDS" => ["lift_empty", "pass_truck", "administrasi"],

  ]
];
// dd($tarifArr);
?>
<div class="page-content">
  <form action="/delivery/mastertarif/update" method="POST" enctype="multipart/form-data">
    @CSRF

    <section class="row">
      <input type="hidden" name="id" value="<?= $mastertarif->id ?>">
      <input type="hidden" name="orderService" value="<?= $mastertarif->orderservice ?>">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">
              Detail Master Tarif <?= $mastertarif->orderservice ?>
            </h4>
            <p>Type <?= $mastertarif->type ?>, Status <?= $mastertarif->size ?>, Ukuran <?= $mastertarif->status ?></p>
          </div>
          <div class="card-body">
            <div class="row">
              <?php

              $filteredTarifArr = [];

              if (isset($mastertarif->orderservice)) { ?>
                <?php
                $searchValue = $mastertarif->orderservice;
                ?>

                <?php if (array_key_exists($searchValue, $tarifArr)) { ?>
                  <?php $filteredTarifArr[$searchValue] = $tarifArr[$searchValue]; ?>
                <?php  } ?>
              <?php } ?>
              <?php foreach ($filteredTarifArr as $data) { ?>
                <?php if (isset($data["DSK"]) && $data["DSK"] != null) { ?>

                  <h4 class="card-title">Master Tarif Tipe Billing DSK</h4>
                  <div class="row">
                    <?php
                    $i = 0;
                    foreach ($data["DSK"] as $value) { ?>
                      <div class="col-6">
                        <div class="form-group">
                          <label><?= $value ?></label>
                          <input type="text" class="form-control" name="<?= $data["codeDSK"][$i] ?>" value="<?= rupiah($mastertarif->{$data["codeDSK"][$i]}) ?>" readonly required>
                        </div>
                      </div>
                    <?php $i++;
                    } ?>
                  </div>
                <?php } ?>
                <?php if (isset($data["DS"]) && $data["DS"] != null) { ?>

                  <h4 class="card-title">Master Tarif Tipe Billing DS</h4>
                  <div class="row">
                    <?php
                    $i = 0;
                    foreach ($data["DS"] as $value) { ?>
                      <div class="col-6">
                        <div class="form-group">
                          <label><?= $value ?></label>
                          <input type="text" class="form-control" name="<?= $data["codeDS"][$i] ?>" value="<?= rupiah($mastertarif->{$data["codeDS"][$i]}) ?>" readonly required>
                        </div>
                      </div>
                    <?php $i++;
                    } ?>
                  </div>
                <?php } ?>
              <?php } ?>
            </div>
            <button style="display: none !important;" id="submit" type="submit" class="btn btn-success text-white"><i class="fa fa-check"></i> Submit</button>
            <a id="edit" type="button" class="btn btn-primary"><i class="fa fa-pen"></i> Edit</a>
            <a style="display: none !important;" type="button" id="cancel" onclick="canceladdCustomer();" class="btn btn-warning text-white"><i class="fa fa-times"></i> Cancel</a>
          </div>
        </div>
      </div>
    </section>
  </form>


</div>


@endsection