@extends ('partial.invoice.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Detail Master Tarif Delivery</p>

</div>
<?php
$menuArr = [
  "LOLO FULL KAPAL SANDAR ICON (2 Invoice)",
  "LOLO FULL KAPAL SANDAR ICON(1 Invoice)",
  "LOLO MT (1 INVOICE)",
  "JPB EX-TRUCK/ STUFFING MUATAN KAPAL ICON",
  "JPB EX-TRUCK/ STUFFING MUATAN KAPAL LUAR ",
  "HANDLING CHARGE ERNA VIA KAPAL ICON (INVOICE ICL) 1 INVOICE",
  "HANDLING CHARGE ERNA VIA KAPAL ICON (INVOICE ERNA) 2 INVOICE ",
  "HANDLING CHARGE ERNA VIA KAPAL LUAR (INVOICE ERNA) 2 INVOICE",
  "MUAT DRY SP2",
  "MUAT DRY SPPS",
];
if ($mastertarif->orderservice == "lolofull") {
  $data["orderService"] = "LOLO FULL KAPAL SANDAR ICON (2 Invoice)";
} else if ($mastertarif->orderservice == "lolofull1inv") {
  $data["orderService"] = "LOLO FULL KAPAL SANDAR ICON (1 Invoice)";
} else if ($mastertarif->orderservice == "lolomt") {
  $data["orderService"] = "LOLO MT (1 Invoice)";
} else if ($mastertarif->orderservice == "jpbicon") {
  $data["orderService"] = "JPB EX-TRUCK/ STUFFING MUATAN KAPAL ICON";
} else if ($mastertarif->orderservice == "jpbluar") {
  $data["orderService"] = "JPB EX-TRUCK/ STUFFING MUATAN KAPAL LUAR";
} else if ($mastertarif->orderservice == "ernahandling1inv") {
  $data["orderService"] = "HANDLING CHARGE ERNA VIA KAPAL ICON (INVOICE ICL) 1 INVOICE";
} else if ($mastertarif->orderservice == "ernahandling2inv") {
  $data["orderService"] = "HANDLING CHARGE ERNA VIA KAPAL ICON (INVOICE ERNA) 2 INVOICE";
} else if ($mastertarif->orderservice == "ernahandlingluar") {
  $data["orderService"] = "HANDLING CHARGE ERNA VIA KAPAL LUAR (INVOICE ERNA) 2 INVOICE";
} else if ($mastertarif->orderservice == "sp2dry") {
  $data["orderService"] = "MUAT DRY SP2";
} else if ($mastertarif->orderservice == "sppsdry") {
  $data["orderService"] = "MUAT DRY SPPS";
}
$tarifArr = [
  "lolofull" => [
    "OSK" => ["Lift On / Off Full", "Penumpukan Masa 1", "Penumpukan Masa 2", "Penumpukan Masa 3", "Pass Truck Masuk"],
    "codeOSK" => ["lift_full", "masa1", "masa2", "masa3", "pass_truck"],
    "OS" => ["Lift On / Off MT", "Pass Truck keluar", "Administrasi"],
    "codeOS" => ["lift_empty", "pass_truck", "administrasi"],
  ],
  "lolofull1inv" => [
    "OSK" => ["Lift On / Off Full", "Penumpukan Masa 1", "Penumpukan Masa 2", "Penumpukan Masa 3", "Pass Truck Masuk"],
    "codeOSK" => ["lift_full", "masa1", "masa2", "masa3", "pass_truck"],
  ],
  "lolomt" => [
    "OS" => ["Lift On / Off Full", "Pass Truck Masuk"],
    "codeOS" => ["lift_full", "pass_truck"],
  ],
  "jpbicon" => [
    "OSK" => ["Pass Truck"],
    "codeOSK" => ["pass_truck"],
    "OS" => ["JPB Extruck", "Penumpukan Masa 1", "Penumpukan Masa 2", "Penumpukan Masa 3", "Administrasi"],
    "codeOS" => ["jpbExtruck", "masa1", "masa2", "masa3", "administrasi"]
  ],
  "jpbluar" => [
    "OS" => ["JPB Extruck", "Penumpukan Masa 1", "Penumpukan Masa 2", "Penumpukan Masa 3", "Lift Off Full", "Pass Truck", "Administrasi"],
    "codeOS" => ["jpbExtruck", "masa1", "masa2", "masa3", "lift_full", "pass_truck", "administrasi"]
  ],
  "ernahandling1inv" => [
    "OS" => ["Handling Charge"],
    "codeOS" => ["handlingCharge"]
  ],
  "ernahandling2inv" => [
    "OSK" => ["Cargo Dooring", "Sewa Crane"],
    "codeOSK" => ["cargoDooring", "sewaCrane"],
    "OS" => ["Paket Stuffing"],
    "codeOS" => ["paketStuffing"]
  ],
  "ernahandlingluar" => [
    "OSK" => ["Cargo Dooring", "Sewa Crane"],
    "codeOSK" => ["cargoDooring", "sewaCrane"],
    "OS" => ["Paket Stuffinng", "Lift On / Off MT", "Pass Truck", "Lift On / Off Full", "Pass Truck"],
    "codeOS" => ["paketStuffing", "lift_empty", "pass_truck", "lift_full", "pass_truck"],
  ],
  "sp2dry" => [
    "OSK" => ["Lift On / Off Full", "Penumpukan Masa 1", "Penumpukan Masa 2", "Penumpukan Masa 3", "Pass Truck keluar", "Pass Truck Masuk"],
    "codeOSK" => ["lift_full", "masa1", "masa2", "masa3", "pass_truck", "pass_truck"],
    "OS" => ["Lift On / Off MT"],
    "codeOS" => ["lift_empty"]
  ],
  "sppsdry" => [
    "OSK" => ["Pass Truck"],
    "codeOSK" => ["pass_truck"],
    "OS" => ["Paket Stuffing"],
    "codeOS" => ["paketStuffing"]
  ]
];
// dd($tarifArr);
?>
<div class="page-content">
  <form action="/receiving/mastertarif/update" method="POST" enctype="multipart/form-data">
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
                <?php if (isset($data["OSK"]) && $data["OSK"] != null) { ?>

                  <h4 class="card-title">Master Tarif Tipe Billing OSK</h4>
                  <div class="row">
                    <?php
                    $i = 0;
                    foreach ($data["OSK"] as $value) { ?>
                      <div class="col-6">
                        <div class="form-group">
                          <label><?= $value ?></label>
                          <input type="text" class="form-control" name="<?= $data["codeOSK"][$i] ?>" value="<?= rupiah($mastertarif->{$data["codeOSK"][$i]}) ?>" readonly required>
                        </div>
                      </div>
                    <?php $i++;
                    } ?>
                  </div>
                <?php } ?>
                <?php if (isset($data["OS"]) && $data["OS"] != null) { ?>

                  <h4 class="card-title">Master Tarif Tipe Billing OS</h4>
                  <div class="row">
                    <?php
                    $i = 0;
                    foreach ($data["OS"] as $value) { ?>
                      <div class="col-6">
                        <div class="form-group">
                          <label><?= $value ?></label>
                          <input type="text" class="form-control" name="<?= $data["codeOS"][$i] ?>" value="<?= rupiah($mastertarif->{$data["codeOS"][$i]}) ?>" readonly required>
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