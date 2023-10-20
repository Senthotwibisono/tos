@extends ('partial.invoice.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Masukan Data untuk membuat master tarif baru</p>
</div>
<div class="page-content mb-5">
  <form action="/receiving/mastertarif/store" method="POST" enctype="multipart/form-data">
    @CSRF

    <?php
    if ($_GET["orderservice"] == "lolofull") {
      $orderService = "LOLO FULL KAPAL SANDAR ICON (2 Invoice)";
    } else if ($_GET["orderservice"] == "lolofull1inv") {
      $orderService = "LOLO FULL KAPAL SANDAR ICON (1 Invoice)";
    } else if ($_GET["orderservice"] == "lolomt") {
      $orderService = "LOLO MT (1 Invoice)";
    } else if ($_GET["orderservice"] == "jpbicon") {
      $orderService = "JPB EX-TRUCK/ STUFFING MUATAN KAPAL ICON";
    } else if ($_GET["orderservice"] == "jpbluar") {
      $orderService = "JPB EX-TRUCK/ STUFFING MUATAN KAPAL LUAR";
    } else if ($_GET["orderservice"] == "ernahandling1inv") {
      $orderService = "HANDLING CHARGE ERNA VIA KAPAL ICON (INVOICE ICL) 1 INVOICE";
    } else if ($_GET["orderservice"] == "ernahandling2inv") {
      $orderService = "HANDLING CHARGE ERNA VIA KAPAL ICON (INVOICE ERNA) 2 INVOICE";
    } else if ($_GET["orderservice"] == "ernahandlingluar") {
      $orderService = "HANDLING CHARGE ERNA VIA KAPAL LUAR (INVOICE ERNA) 2 INVOICE";
    } else if ($_GET["orderservice"] == "sp2dry") {
      $orderService = "MUAT DRY SP2";
    } else if ($_GET["orderservice"] == "sppsdry") {
      $orderService = "MUAT DRY SPPS";
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
    ?>
    <input type="hidden" name="orderservice" value="<?= $_GET["orderservice"] ?>">
    <section class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">
              Create Master Tarif untuk service <?= $orderService ?>
            </h4>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label for="">Type</label>
                  <input placeholder="Input Here" type="text" name="type" class="form-control" required>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label for="">Size</label>
                  <input placeholder="Input Here" type="text" name="size" class="form-control" required>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label for="">Status</label>
                  <input placeholder="Input Here" type="text" name="status" class="form-control" required>
                </div>
              </div>
            </div>
            <div class="row mt-2">
              <?php

              $filteredTarifArr = [];

              if (isset($_GET["orderservice"])) { ?>
                <?php
                $searchValue = $_GET["orderservice"];
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
                          <input placeholder="Input Here" type="number" class="form-control" name="<?= $data["codeOSK"][$i] ?>" required>
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
                          <input placeholder="Input Here" type="number" class="form-control" name="<?= $data["codeOS"][$i] ?>" required>
                        </div>
                      </div>
                    <?php $i++;
                    } ?>
                  </div>
                <?php } ?>
              <?php } ?>
            </div>
            <button type="submit" class="btn btn-success text-white"><i class="fa fa-check"></i> Submit</button>
            <a onclick="canceladdCustomer();" class="btn btn-warning text-white"><i class="fa fa-times"></i> Cancel</a>
          </div>
        </div>
      </div>
    </section>
  </form>
</div>
<!-- update test  -->
@endsection