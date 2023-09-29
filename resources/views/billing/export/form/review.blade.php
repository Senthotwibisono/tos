@extends ('partial.invoice.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Review Data Pranota Form & Kalkulasi</p>
</div>
<div class="page content mb-5">
  <form action="/receiving/form/storeBilling" method="POST" enctype="multipart/form-data">
    @CSRF
    <input type="hidden" name="deliveryFormId" value="<?= $deliveryForm->id ?>">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-12">
            <h4 class="card-title">
              Receiving Form Detail
            </h4>
            <p>Informasi Detil Formulir Receiving</p>
          </div>
          <div class="col-4">
            <div class="form-group">
              <label for="">Customer</label>
              <input type="text" class="form-control" readonly value="<?= $customer->customer_name ?>">
            </div>
          </div>
          <div class="col-4">
            <div class="form-group">
              <label for="">NPWP</label>
              <input type="text" class="form-control" readonly value="<?= $customer->npwp ?>">
            </div>
          </div>
          <div class="col-4">
            <div class="form-group">
              <label for="">Deparature Date</label>
              <input type="text" class="form-control" readonly value="<?= $deliveryForm->exp_date ?>">
            </div>
          </div>
          <div class="col-12">
            <div class="form-group">
              <label for="">Address</label>
              <input type="text" class="form-control" readonly value="<?= $customer->address ?>">
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <label for="">Booking Number</label>
              <input type="text" class="form-control" readonly value="<?= $deliveryForm->booking_no ?>">
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <label for="">Order Service</label>
              <input type="text" class="form-control" readonly value="<?= $orderService ?>">
            </div>
          </div>

        </div>

        <div class="row mt-3">
          <div class="col-12">
            <h4 class="card-title">
              Selected Container Detail
            </h4>
            <p>Informasi Detil Container</p>
          </div>
          <div class="col-12">
            <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
              <thead>
                <tr>
                  <th>Container No</th>
                  <th>Vessel Name</th>
                  <th>Size</th>
                  <th>Type</th>
                  <th>CTR Status</th>
                  <th>CTR Intern Status</th>
                  <th>Gross</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($containers as $container) { ?>
                  <tr>
                    <td><?= $container->container_no ?></td>
                    <td><?= $container->vessel_name ?></td>
                    <td><?= $container->ctr_size ?></td>
                    <td><?= $container->ctr_type ?></td>
                    <td><?= $container->ctr_status ?></td>
                    <td><?= $container->ctr_intern_status ?></td>
                    <td><?= $container->gross ?></td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
        <div class="row mt-3">
          <?php
          $i = 0;
          foreach ($billingTotal as $value) { ?>
            <?php foreach ($value->billingName->name as $data) { ?>
              <div class="col-12">
                <h4 class="card-title">
                  Pranota Summary <?= $data ?>
                </h4>
                <p>Dengan Data Container <b><?= $value->billingName->container->container_no ?></b> type <b><?= $value->billingName->container->ctr_type ?></b>, size <b><?= $value->billingName->container->ctr_size ?></b>, status <b><?= $value->billingName->container->ctr_status ?></b></p>
              </div>
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns display ">
                      <thead>
                        <tr>
                          <th>Keterangan</th>
                          <th>Ukuran</th>
                          <th>Type</th>
                          <th>Status</th>
                          <th>Hari</th>
                          <th>Tarif Satuan</th>
                          <th>Amount</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($data == "OSK" || $data == "OSK(OSK246)" || $data == "OSK(OSK48)" || $data == "OSK(OSK212)" ? $value->table->titleTableOSK : $value->table->titleTableOS as $table) { ?>
                          <tr>
                            <td><?= $table ?></td>
                            <td><?= $value->billingName->container->ctr_size ?></td>
                            <td><?= $value->billingName->container->ctr_type ?></td>
                            <td><?= $value->billingName->container->ctr_status ?></td>
                            <?php if ($table == "Lift On / Off Full") { ?>
                              <td>0 Hari</td>
                              <td>Rp. <?= rupiah($value->tarif->lift_full) ?>,00 ~</td>
                              <td>Rp. <?= rupiah($value->liftFull) ?>,00 ~</td>
                            <?php } else if ($table == "Lift On / Off Empty") { ?>
                              <td>0 Hari</td>
                              <td>Rp. <?= rupiah($value->tarif->lift_empty) ?>,00 ~</td>
                              <td>Rp. <?= rupiah($value->liftEmpty) ?>,00 ~</td>
                            <?php } else if ($table == "Lift On / Off MT") { ?>
                              <td>0 Hari</td>
                              <td>Rp. <?= rupiah($value->tarif->lift_off_mt) ?>,00 ~</td>
                              <td>Rp. <?= rupiah($value->liftFull) ?>,00 ~</td>
                            <?php } else if ($table == "Pass Truck Out") { ?>
                              <td>0 Hari</td>
                              <td>Rp. <?= rupiah($value->tarif->pass_truck) ?>,00 ~</td>
                              <td>Rp. <?= rupiah($value->passTruckOut) ?>,00 ~</td>
                            <?php } else if ($table == "Pass Truck In") { ?>
                              <td>0 Hari</td>
                              <td>Rp. <?= rupiah($value->tarif->pass_truck) ?>,00 ~</td>
                              <td>Rp. <?= rupiah($value->passTruckIn) ?>,00 ~</td>
                            <?php } else if ($table == "Pass Truck Keluar") { ?>
                              <td>0 Hari</td>
                              <td>Rp. <?= rupiah($value->tarif->pass_truck) ?>,00 ~</td>
                              <td>Rp. <?= rupiah($value->passTruckOut) ?>,00 ~</td>
                            <?php } else if ($table == "Pass Truck Masuk") { ?>
                              <td>0 Hari</td>
                              <td>Rp. <?= rupiah($value->tarif->pass_truck) ?>,00 ~</td>
                              <td>Rp. <?= rupiah($value->passTruckIn) ?>,00 ~</td>
                            <?php } else if ($table == "Pass Truck") { ?>
                              <td>0 Hari</td>
                              <td>Rp. <?= rupiah($value->tarif->pass_truck) ?>,00 ~</td>
                              <td>Rp. <?= rupiah($value->passTruck ?? $value->passTruckIn) ?>,00 ~</td>
                            <?php } else if ($table == "Administrasi") { ?>
                              <td>0 Hari</td>
                              <td>Rp. <?= rupiah($value->tarif->administrasi) ?>,00 ~</td>
                              <td>Rp. <?= rupiah($value->administration) ?>,00 ~</td>
                            <?php } else if ($table == "Pemindahan Petikemas Antar Blok") { ?>
                              <td>0 Hari</td>
                              <td>Rp. <?= rupiah($value->tarif->pemindahan) ?>,00 ~</td>
                              <td>Rp. <?= rupiah($value->pemindahan) ?>,00 ~</td>
                            <?php } else if ($table == "Paket Stripping") { ?>
                              <td>0 Hari</td>
                              <td>Rp. <?= rupiah($value->tarif->paket_stripping) ?>,00 ~</td>
                              <td>Rp. <?= rupiah($value->paketStripping) ?>,00 ~</td>
                            <?php } else if ($table == "Penumpukan Masa 1") { ?>
                              <td><?= $value->differentDays[$i]->masa1 ?> Hari</td>
                              <td>Rp. <?= rupiah($value->tarif->masa1) ?>,00 ~</td>
                              <td>Rp. <?= rupiah($value->penumpukanMasa1) ?>,00 ~</td>
                            <?php } else if ($table == "Penumpukan Masa 2") { ?>
                              <td><?= $value->differentDays[$i]->masa2 ?> Hari</td>
                              <td>Rp. <?= rupiah($value->tarif->masa2) ?>,00 ~</td>
                              <td>Rp. <?= rupiah($value->penumpukanMasa2) ?>,00 ~</td>
                            <?php } else if ($table == "Penumpukan Masa 3") { ?>
                              <td><?= $value->differentDays[$i]->masa3 ?> Hari</td>
                              <td>Rp. <?= rupiah($value->tarif->masa3) ?>,00 ~</td>
                              <td>Rp. <?= rupiah($value->penumpukanMasa3) ?>,00 ~</td>
                            <?php } else if ($table == "JPB Extruck") { ?>
                              <td>0 Hari</td>
                              <td>Rp. <?= rupiah($value->tarif->jpbExtruck) ?>,00 ~</td>
                              <td>Rp. <?= rupiah($value->jpbExtruck) ?>,00 ~</td>
                            <?php } else if ($table == "Handling Charge") { ?>
                              <td>0 Hari</td>
                              <td>Rp. <?= rupiah($value->tarif->handlingCharge) ?>,00 ~</td>
                              <td>Rp. <?= rupiah($value->handlingCharge) ?>,00 ~</td>
                            <?php } else if ($table == "Paket Stuffing") { ?>
                              <td>0 Hari</td>
                              <td>Rp. <?= rupiah($value->tarif->paketStuffing) ?>,00 ~</td>
                              <td>Rp. <?= rupiah($value->paketStuffing) ?>,00 ~</td>
                            <?php } else if ($table == "Cargo Dooring") { ?>
                              <td>0 Hari</td>
                              <td>Rp. <?= rupiah($value->tarif->cargoDooring) ?>,00 ~</td>
                              <td>Rp. <?= rupiah($value->cargoDooring) ?>,00 ~</td>
                            <?php } else if ($table == "Sewa Crane") { ?>
                              <td>0 Hari</td>
                              <td>Rp. <?= rupiah($value->tarif->sewaCrane) ?>,00 ~</td>
                              <td>Rp. <?= rupiah($value->sewaCrane) ?>,00 ~</td>

                            <?php } else { ?>
                              <td>Data Tidak Ditemukan!</td>
                              <td>Data Tidak Ditemukan!</td>
                              <td>Data Tidak Ditemukan!</td>
                            <?php } ?>
                          </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="col-12">
                <div class="card" style="border-radius:15px !important;background-color:#435ebe !important;">
                  <div class="card-body">
                    <div class="row text-white p-3">
                      <div class="col-6">
                        <h1 class="lead text-white">
                          Total Summary <?= $data ?>
                        </h1>
                        <h4 class="text-white">Total Amount :</h4>
                        <h4 class="text-white">Tax 11% :</h4>
                        <h4 class="text-white">Grand Total :</h4>
                      </div>
                      <div class="col-6 mt-4" style="text-align:right;">
                        <!-- <h1 style="opacity: 0%;">.</h1> -->
                        <h4 class="text-white">Rp. <?= $data == "OSK" || $data == "OSK(OSK246)" || $data == "OSK(OSK48)" || $data == "OSK(OSK212)" ? rupiah($value->initialOSK) : rupiah($value->initialOS) ?>,00 ~</h4>
                        <h4 class="text-white">Rp. <?= $data == "OSK" || $data == "OSK(OSK246)" || $data == "OSK(OSK48)" || $data == "OSK(OSK212)" ? rupiah($value->taxOSK) : rupiah($value->taxOS) ?>,00 ~</h4>
                        <h4 style="color:#ff5265;">Rp. <?= $data == "OSK" || $data == "OSK(OSK246)" || $data == "OSK(OSK48)" || $data == "OSK(OSK212)" ? rupiah($grandTotal[$i]->totalOSK) : rupiah($grandTotal[$i]->totalOS) ?>,00 ~</h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <?php

              } ?>
          <?php $i++;
          } ?>
        </div>
        <div class="row mt-3">
          <div class="col-12 text-right">
            <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> Submit</button>
            <button class="btn btn-primary text-white opacity-50" data-toggle="tooltip" data-placement="top" title="Still on Development!">
              <a><i class="fa fa-pen"></i> Edit</a>
            </button>
            <!-- <a type="button" class="btn btn-primary" style="opacity: 50%;"><i class="fa fa-pen "></i> Edit</a> -->
            <a onclick="cancelAddCustomer();" type="button" class="btn btn-warning"><i class="fa fa-close"></i> Batal</a>
          </div>
        </div>

      </div>

    </div>
  </form>
</div>

@endsection