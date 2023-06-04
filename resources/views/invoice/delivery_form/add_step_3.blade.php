@extends ('partial.invoice.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
</div>
<div class="page content mb-5">
  <section class="row">
    <form action="/invoice/add/storestep3" method="POST" enctype="multipart/form-data">
      @CSRF

      <div class="card">
        <div class="card-header">
          <!-- <h3>Delivery Form Data</h3> -->
          <h4>Step 3 Data Delivery Pranota</h4>
          <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-4">
              <div class="form-group">
                <label for="">Expired Date</label>
                <input type="text" class="form-control" placeholder="09/05/2023" value="<?= $ccdelivery->deliveryForm->exp_date ?>" readonly>
              </div>
            </div>
            <div class="col-2">
              <label for="">Time</label>
              <input type="text" class="form-control" placeholder="Select Hour" value="<?= $ccdelivery->deliveryForm->time ?>" readonly>
            </div>
            <div class="col-4">
              <label for="">Customer</label>
              <div class="form-group">
                <input type="text" class="form-control" value="<?= $ccdelivery->deliveryForm->customer->customer_name ?>" readonly>
              </div>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-12">
              <h5>Information Shipping Agent</h5>
              <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
            </div>
            <div class="col-12 col-md-4">
              <div class="form-group">
                <label for="">Do Number</label>
                <input type="text" class="form-control" placeholder="Do Number" value="<?= $ccdelivery->deliveryForm->do_number ?>" readonly>
              </div>
            </div>
            <div class="col-12 col-md-4">
              <div class="form-group">
                <label for="">Do Expired</label>
                <input type="text" class="form-control" placeholder="Do Expired" value="<?= $ccdelivery->deliveryForm->do_exp_date ?>" readonly>
              </div>
            </div>
            <div class="col-12 col-md-4">
              <div class="form-group">
                <label for="">Bill of Loading Number</label>
                <input type="text" class="form-control" placeholder="Bill Of Loading Number" value="<?= $ccdelivery->deliveryForm->boln ?>" readonly>
              </div>
            </div>
          </div>

          <div class="row mt-3">
            <?php
            $i = 0;
            foreach ($ccdelivery->tarifCheck as $data) { ?>
              <div class="col-12">
                <h5>Container dengan ukuran <?= $data->size ?></h5>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
              </div>
              <div class="col-12">
                <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns display ">
                  <thead>
                    <tr>
                      <th>Keterangan</th>
                      <th>Jumlah Container</th>
                      <th>Ukuran</th>
                      <th>Type</th>
                      <th>Status</th>
                      <th>Hari</th>
                      <th>Tarif Satuan</th>
                      <th>Amount</th>
                    </tr>
                  </thead>
                  <tbody>

                    <?php foreach ($menuinv as $value) { ?>

                      <?php
                      $total_bfr_grand = 0;
                      $total_grand = 0;
                      $total_tax = 0;
                      $administration = 30000;
                      foreach ($ccdelivery->tarifCheck as $calcdata) { ?>
                        <?php
                        $cr_amount[$i] = $calcdata->cost_recovery * $ccdelivery->findContainer[$i]->jml_cont;
                        $lo_amount[$i] = $calcdata->lift_on * $ccdelivery->findContainer[$i]->jml_cont;
                        $lof_amount[$i] = $calcdata->lift_off * $ccdelivery->findContainer[$i]->jml_cont;
                        $pm1_amount[$i] = $calcdata->masa1 * $ccdelivery->findContainer[$i]->jml_cont * 1;
                        $pm2_amount[$i] = $calcdata->masa2 * $ccdelivery->findContainer[$i]->jml_cont * $ccdelivery->diffInDays[$i]->masa2;
                        $pm3_amount[$i] = $calcdata->masa3 * $ccdelivery->findContainer[$i]->jml_cont * $ccdelivery->diffInDays[$i]->masa3;

                        $total[$i] = $lo_amount[$i] + $lof_amount[$i] + $pm1_amount[$i] + $pm2_amount[$i] + $pm3_amount[$i];
                        $tax[$i] = $total[$i] * 11 / 100;
                        $grand_total[$i] = $tax[$i] + $total[$i];
                        // dd(rupiah($grand_total));
                        $total_grand += $grand_total[$i] + $administration;
                        $total_bfr_grand += $total[$i];
                        $total_tax += $tax[$i];
                        ?>
                      <?php } ?>
                      <?php
                      // dd("DATA GRAND TOTAL data PERTAMA", rupiah($grand_total[0]), "DATA GRAND TOTAL", rupiah($total_grand)); 
                      ?>
                      <tr>
                        <th><?= $value ?></th>
                        <th><?= $ccdelivery->findContainer[$i]->jml_cont ?></th>
                        <th><?= $ccdelivery->findContainer[$i]->ctr_size ?></th>
                        <th><?= $ccdelivery->findContainer[$i]->ctr_type ?></th>
                        <th><?= $ccdelivery->findContainer[$i]->ctr_status ?></th>


                        <?php if ($value == "Cost Recovery") { ?>
                          <th>0 Hari</th>
                          <th>Rp. <?= rupiah($data->cost_recovery) ?></th>
                          <th>Rp. <?= rupiah($data->cost_recovery * $ccdelivery->findContainer[$i]->jml_cont) ?></th>
                        <?php } else if ($value == "Lift On") {  ?>
                          <th>0 Hari</th>
                          <th>Rp. <?= rupiah($data->lift_on) ?></th>
                          <th>Rp. <?= rupiah($data->lift_on * $ccdelivery->findContainer[$i]->jml_cont) ?></th>
                        <?php } else if ($value == "Lift Off") {  ?>
                          <th>0 Hari</th>
                          <th>Rp. <?= rupiah($data->lift_off) ?></th>
                          <th>Rp. <?= rupiah($data->lift_off * $ccdelivery->findContainer[$i]->jml_cont) ?></th>
                        <?php } else if ($value == "Penumpukan Masa 1") {  ?>
                          <th><?= $ccdelivery->diffInDays[$i]->masa1 ?> Hari</th>
                          <th>Rp. <?= rupiah($data->masa1) ?></th>
                          <th>Rp. <?= rupiah($data->masa1 * $ccdelivery->findContainer[$i]->jml_cont * 1) ?></th>
                        <?php } else if ($value == "Penumpukan Masa 2") {  ?>
                          <th><?= $ccdelivery->diffInDays[$i]->masa2 ?> Hari</th>
                          <th>Rp. <?= rupiah($data->masa2) ?></th>
                          <th>Rp. <?= rupiah($data->masa2 * $ccdelivery->findContainer[$i]->jml_cont * $ccdelivery->diffInDays[$i]->masa2) ?></th>
                        <?php } else if ($value == "Penumpukan Masa 3") {  ?>
                          <th><?= $ccdelivery->diffInDays[$i]->masa3 ?> Hari</th>
                          <th>Rp. <?= rupiah($data->masa3) ?></th>
                          <th>Rp. <?= rupiah($data->masa3 * $ccdelivery->findContainer[$i]->jml_cont * $ccdelivery->diffInDays[$i]->masa3) ?></th>

                        <?php } else { ?>
                          <th>0</th>
                          <th>Rp. 0</th>
                          <th>Rp. 0</th>
                        <?php } ?>




                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            <?php
              $i = $i + 1;
            } ?>

          </div>

          <div class="row mt-3">
            <style>
            </style>
            <div class="col-12">
              <div class="card" style="border-radius:15px !important;background-color:#435ebe !important;">
                <div class="card-body">
                  <div class="row text-white p-3">
                    <div class="col-6">
                      <h4 class="text-white bold">TOTAL AMOUNT</h4>
                      <h5 class="text-white">Administration :</h5>
                      <h5 class="text-white">Tax 10% :</h5>
                      <h5 class="text-white">Grand Total :</h5>
                    </div>
                    <div class="col-6" style="text-align:right;">
                      <h4 class="text-white bold">Rp. <?= rupiah($total_bfr_grand) ?>,00 ~</h4>
                      <h5 class="text-white">Rp. <?= rupiah($administration) ?>,00 ~</h5>
                      <h5 class="text-white">Rp. <?= rupiah($total_tax) ?>,00 ~</h5>
                      <h4 class="bold" style="color:#ff5265;">Rp. <?= rupiah($total_grand) ?>,00 ~</h4>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row mt-5">
            <div class="col-12 text-right">
              <button type="submit" class="btn btn btn-success">Submit</button>
              <a href="/invoice/add/step1" class="btn btn btn-warning">Edit</a>
              <a onclick="canceladdCustomer();" class="btn btn btn-secondary">Cancel</a>
            </div>
          </div>
        </div>
      </div>
    </form>

  </section>
</div>

@endsection