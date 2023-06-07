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
            $index = 0;
            foreach ($ccdelivery->tarifCheck as $data) {
            ?>
              <?php
              $data1 = array_slice($ccdelivery->tarifCheck, 0, $index); // Elements before the index
              $data2 = array_slice($ccdelivery->tarifCheck, $index); // Elements at and after the index
              ?>
              <?php
              foreach ($data2 as $data) { ?>

                <input type="hidden" name="data1[<?= $index ?>][title]" value="<?= $menuinv[0] ?>">
                <input type="hidden" name="data2[<?= $index ?>][title]" value="<?= $menuinv[1] ?>">
                <input type="hidden" name="data3[<?= $index ?>][title]" value="<?= $menuinv[2] ?>">
                <input type="hidden" name="data4[<?= $index ?>][title]" value="<?= $menuinv[3] ?>">
                <input type="hidden" name="data5[<?= $index ?>][title]" value="<?= $menuinv[4] ?>">
                <input type="hidden" name="data6[<?= $index ?>][title]" value="<?= $menuinv[5] ?>">

                <input type="hidden" name="data1[<?= $index ?>][jumlah]" value="<?= $ccdelivery->findContainer[$i]->jml_cont ?>">
                <input type="hidden" name="data2[<?= $index ?>][jumlah]" value="<?= $ccdelivery->findContainer[$i]->jml_cont ?>">
                <input type="hidden" name="data3[<?= $index ?>][jumlah]" value="<?= $ccdelivery->findContainer[$i]->jml_cont ?>">
                <input type="hidden" name="data4[<?= $index ?>][jumlah]" value="<?= $ccdelivery->findContainer[$i]->jml_cont ?>">
                <input type="hidden" name="data5[<?= $index ?>][jumlah]" value="<?= $ccdelivery->findContainer[$i]->jml_cont ?>">
                <input type="hidden" name="data6[<?= $index ?>][jumlah]" value="<?= $ccdelivery->findContainer[$i]->jml_cont ?>">

                <input type="hidden" name="data1[<?= $index ?>][size]" value="<?= $ccdelivery->findContainer[$i]->ctr_size ?>">
                <input type="hidden" name="data2[<?= $index ?>][size]" value="<?= $ccdelivery->findContainer[$i]->ctr_size ?>">
                <input type="hidden" name="data3[<?= $index ?>][size]" value="<?= $ccdelivery->findContainer[$i]->ctr_size ?>">
                <input type="hidden" name="data4[<?= $index ?>][size]" value="<?= $ccdelivery->findContainer[$i]->ctr_size ?>">
                <input type="hidden" name="data5[<?= $index ?>][size]" value="<?= $ccdelivery->findContainer[$i]->ctr_size ?>">
                <input type="hidden" name="data6[<?= $index ?>][size]" value="<?= $ccdelivery->findContainer[$i]->ctr_size ?>">

                <input type="hidden" name="data1[<?= $index ?>][type]" value="<?= $ccdelivery->findContainer[$i]->ctr_type ?>">
                <input type="hidden" name="data2[<?= $index ?>][type]" value="<?= $ccdelivery->findContainer[$i]->ctr_type ?>">
                <input type="hidden" name="data3[<?= $index ?>][type]" value="<?= $ccdelivery->findContainer[$i]->ctr_type ?>">
                <input type="hidden" name="data4[<?= $index ?>][type]" value="<?= $ccdelivery->findContainer[$i]->ctr_type ?>">
                <input type="hidden" name="data5[<?= $index ?>][type]" value="<?= $ccdelivery->findContainer[$i]->ctr_type ?>">
                <input type="hidden" name="data6[<?= $index ?>][type]" value="<?= $ccdelivery->findContainer[$i]->ctr_type ?>">

                <input type="hidden" name="data1[<?= $index ?>][status]" value="<?= $ccdelivery->findContainer[$i]->ctr_status ?>">
                <input type="hidden" name="data2[<?= $index ?>][status]" value="<?= $ccdelivery->findContainer[$i]->ctr_status ?>">
                <input type="hidden" name="data3[<?= $index ?>][status]" value="<?= $ccdelivery->findContainer[$i]->ctr_status ?>">
                <input type="hidden" name="data4[<?= $index ?>][status]" value="<?= $ccdelivery->findContainer[$i]->ctr_status ?>">
                <input type="hidden" name="data5[<?= $index ?>][status]" value="<?= $ccdelivery->findContainer[$i]->ctr_status ?>">
                <input type="hidden" name="data6[<?= $index ?>][status]" value="<?= $ccdelivery->findContainer[$i]->ctr_status ?>">

                <input type="hidden" name="data1[<?= $index ?>][hari]" value="0">
                <input type="hidden" name="data2[<?= $index ?>][hari]" value="0">
                <input type="hidden" name="data3[<?= $index ?>][hari]" value="0">
                <input type="hidden" name="data4[<?= $index ?>][hari]" value="<?= $ccdelivery->diffInDays[$i]->masa1 ?>">
                <input type="hidden" name="data5[<?= $index ?>][hari]" value="<?= $ccdelivery->diffInDays[$i]->masa2 ?>">
                <input type="hidden" name="data6[<?= $index ?>][hari]" value="<?= $ccdelivery->diffInDays[$i]->masa3 ?>">

                <input type="hidden" name="data1[<?= $index ?>][tarif]" value="<?= $data->cost_recovery ?>">
                <input type="hidden" name="data2[<?= $index ?>][tarif]" value="<?= $data->lift_on ?>">
                <input type="hidden" name="data3[<?= $index ?>][tarif]" value="<?= $data->lift_off ?>">
                <input type="hidden" name="data4[<?= $index ?>][tarif]" value="<?= $data->masa1 ?>">
                <input type="hidden" name="data5[<?= $index ?>][tarif]" value="<?= $data->masa2 ?>">
                <input type="hidden" name="data6[<?= $index ?>][tarif]" value="<?= $data->masa3 ?>">

                <input type="hidden" name="data1[<?= $index ?>][amount]" value="<?= $ccdelivery->grandTotal[$i]->costRecovery ?>">
                <input type="hidden" name="data2[<?= $index ?>][amount]" value="<?= $ccdelivery->grandTotal[$i]->liftOn ?>">
                <input type="hidden" name="data3[<?= $index ?>][amount]" value="<?= $ccdelivery->grandTotal[$i]->liftOff ?>">
                <input type="hidden" name="data4[<?= $index ?>][amount]" value="<?= $ccdelivery->grandTotal[$i]->penumpukanMasa1 ?>">
                <input type="hidden" name="data5[<?= $index ?>][amount]" value="<?= $ccdelivery->grandTotal[$i]->penumpukanMasa2 ?>">
                <input type="hidden" name="data6[<?= $index ?>][amount]" value="<?= $ccdelivery->grandTotal[$i]->penumpukanMasa3 ?>">

              <?php } ?>

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
                          <th>Rp. <?= rupiah($ccdelivery->grandTotal[$i]->costRecovery) ?></th>
                        <?php } else if ($value == "Lift On") {  ?>
                          <th>0 Hari</th>
                          <th>Rp. <?= rupiah($data->lift_on) ?></th>
                          <th>Rp. <?= rupiah($ccdelivery->grandTotal[$i]->liftOn) ?></th>
                        <?php } else if ($value == "Lift Off") {  ?>
                          <th>0 Hari</th>
                          <th>Rp. <?= rupiah($data->lift_off) ?></th>
                          <th>Rp. <?= rupiah($ccdelivery->grandTotal[$i]->liftOff) ?></th>
                        <?php } else if ($value == "Penumpukan Masa 1") {  ?>
                          <th><?= $ccdelivery->diffInDays[$i]->masa1 ?> Hari</th>
                          <th>Rp. <?= rupiah($data->masa1) ?></th>
                          <th>Rp. <?= rupiah($ccdelivery->grandTotal[$i]->penumpukanMasa1) ?></th>
                        <?php } else if ($value == "Penumpukan Masa 2") {  ?>
                          <th><?= $ccdelivery->diffInDays[$i]->masa2 ?> Hari</th>
                          <th>Rp. <?= rupiah($data->masa2) ?></th>
                          <th>Rp. <?= rupiah($ccdelivery->grandTotal[$i]->penumpukanMasa2) ?></th>
                        <?php } else if ($value == "Penumpukan Masa 3") {  ?>
                          <th><?= $ccdelivery->diffInDays[$i]->masa3 ?> Hari</th>
                          <th>Rp. <?= rupiah($data->masa3) ?></th>
                          <th>Rp. <?= rupiah($ccdelivery->grandTotal[$i]->penumpukanMasa3) ?></th>

                        <?php } else { ?>
                          <th>0</th>
                          <th>Rp. 0</th>
                          <th>Rp. 0</th>
                        <?php } ?>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
                <h4>Total : Rp. <?= rupiah($ccdelivery->grandTotal[$i]->amountBeforeTax) ?>,00 ~</h4>
                <br>
                <br>
                <br>
              </div>
            <?php
              $i = $i + 1;
              $index++; // Increment the index variable for each iteration

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
                      <h4 class="text-white bold">Rp. <?= rupiah($ccdelivery->finalGrandTotal[0]->totalAmountBeforeTax) ?>,00 ~</h4>
                      <h5 class="text-white">Rp. 30.000,00 ~</h5>
                      <h5 class="text-white">Rp. <?= rupiah($ccdelivery->finalGrandTotal[0]->totalAmountWithTax) ?>,00 ~</h5>
                      <h4 class="bold" style="color:#ff5265;">Rp. <?= rupiah($ccdelivery->finalGrandTotal[0]->totalAmountAfterTax) ?>,00 ~</h4>


                      <input type="hidden" name="data7[customer]" value="<?= $ccdelivery->deliveryForm->customer->customer_name ?>">
                      <input type="hidden" name="data7[deliveryid]" value="<?= $ccdelivery->deliveryForm->id ?>">
                      <input type="hidden" name="data7[totalamount]" value="<?= $ccdelivery->finalGrandTotal[0]->totalAmountBeforeTax ?>">
                      <input type="hidden" name="data7[admin]" value="30000">
                      <input type="hidden" name="data7[tax]" value="<?= $ccdelivery->finalGrandTotal[0]->totalAmountWithTax ?>">
                      <input type="hidden" name="data7[grandtotal]" value="<?= $ccdelivery->finalGrandTotal[0]->totalAmountAfterTax ?>">


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