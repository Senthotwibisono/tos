@extends ('partial.invoice.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Masukan Data untuk membuat master tarif baru</p>
</div>
<div class="page-content mb-5">
  <form action="/delivery/mastertarif/store" method="POST" enctype="multipart/form-data">
    @CSRF

    <?php
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
    ?>
    <input type="hidden" name="orderservice" value="<?= $_GET["orderservice"] ?>">
    <section class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">
              Create Master Tarif
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
                <?php if (isset($data["DSK"]) && $data["DSK"] != null) { ?>

                  <h4 class="card-title">Master Tarif Tipe Billing DSK</h4>
                  <div class="row">
                    <?php
                    $i = 0;
                    foreach ($data["DSK"] as $value) { ?>
                      <div class="col-6">
                        <div class="form-group">
                          <label><?= $value ?></label>
                          <input placeholder="Input Here" type="number" class="form-control" name="<?= $data["codeDSK"][$i] ?>" required>
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
                          <input placeholder="Input Here" type="number" class="form-control" name="<?= $data["codeDS"][$i] ?>" required>
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