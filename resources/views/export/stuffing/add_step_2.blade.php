@extends ('partial.invoice.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
</div>
<div class="page content mb-5">
  <section class="row">
    <form action="/export/stuffing/add/storestep2" method="POST" enctype="multipart/form-data">
      @CSRF

      <div class="card">
        <div class="card-header">
          <!-- <h3>Delivery Form Data</h3> -->
          <h4>Step 2 Data Stuffing Pranota</h4>
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
            <div class="col-4">
              <div class="form-group">
                <label for="">Disc Date</label>
                <input type="text" class="form-control" value="<?= $ccdelivery->findContainer[0]->disc_date ?>" readonly>
              </div>
            </div>
            <?php if ($ccdelivery->deliveryForm->isExtended == "1") { ?>
              <input type="hidden" value="1" name="isExtended">
              <div class="col-4">
                <label for="">Extended Expired Date</label>
                <input type="text" class="form-control" placeholder="Select Hour" value="<?= $ccdelivery->deliveryForm->extended_exp_date ?>" readonly>
              </div>
            <?php } else { ?>
              <input type="hidden" value="0" name="isExtended">
            <?php } ?>
            <!-- <div class="col-4">
              <label for="">Time</label>
              <input type="text" class="form-control" placeholder="Select Hour" value="<?= $ccdelivery->deliveryForm->time ?>" readonly>
            </div> -->
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
            <!-- <div class="col-12 col-md-4">
              <div class="form-group">
                <label for="">Do Number</label>
                <input type="text" class="form-control" placeholder="Do Number" value="<?= $ccdelivery->deliveryForm->do_number ?>" readonly>
              </div>
            </div> -->
            <div class="col-12 col-md-4">
              <div class="form-group">
                <label for="">Do Expired</label>
                <input type="text" class="form-control" placeholder="Do Expired" value="<?= $ccdelivery->deliveryForm->do_exp_date ?>" readonly>
              </div>
            </div>
            <!-- <div class="col-12 col-md-4">
              <div class="form-group">
                <label for="">Booking Number</label>
                <input type="text" class="form-control" placeholder="Bill Of Loading Number" value="<?= $ccdelivery->deliveryForm->booking_no ?? "" ?>" readonly>
              </div>
            </div> -->
            <div class="col-12 col-md-4">
              <div class="form-group">
                <label>Order Service</label>
                <input type="text" class="form-control" value="<?= $ccdelivery->deliveryForm->orderService ?>" readonly>
              </div>
            </div>
          </div>

          <div class="row mt-5">
            <div class="col-12">
              <h5>Selected Container</h5>
              <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
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
                  <?php foreach ($ccdelivery->deliveryForm->containers as $data) { ?>
                    <tr>
                      <td><?= $data->container_no ?></td>
                      <td><?= $data->vessel_name ?></td>
                      <td><?= $data->ctr_size ?></td>
                      <td><?= $data->ctr_type ?></td>
                      <td><?= $data->ctr_status ?></td>
                      <td><?= $data->ctr_intern_status ?></td>
                      <td><?= $data->gross ?></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>

          <div class="row mt-3">
            <?php
            $i = 0;
            $index = 0;
            $k = 2;
            foreach ($ccdelivery->tarifCheck as $data) {
            ?>
              <?php
              $data1 = array_slice($ccdelivery->tarifCheck, 0, $index); // Elements before the index
              $data2 = array_slice($ccdelivery->tarifCheck, $index); // Elements at and after the index

              ?>
              <?php
              foreach ($data2 as $data_arr) { ?>
                <!-- update for prod -->
                <input type="hidden" name="orderService" value="stuffing">
                <input type="hidden" name="active_to" value="<?= $ccdelivery->deliveryForm->exp_date ?>">

                <input type="hidden" name="data1[<?= $index ?>][title]" value="<?= $menuinv[0] ?>">
                <input type="hidden" name="data2[<?= $index ?>][title]" value="<?= $menuinv2[1] ?>">
                <input type="hidden" name="data3[<?= $index ?>][title]" value="Penumpukan Masa 1">
                <input type="hidden" name="data4[<?= $index ?>][title]" value="Penumpukan Masa 2">
                <input type="hidden" name="data5[<?= $index ?>][title]" value="Penumpukan Masa 3">
                <input type="hidden" name="data7[<?= $index ?>][title]" value="Lift Off Full">

                <input type="hidden" name="data1[<?= $index ?>][jumlah]" value="<?= $ccdelivery->findContainer[$i]->jml_cont ?>">
                <input type="hidden" name="data2[<?= $index ?>][jumlah]" value="<?= $ccdelivery->findContainer[$i]->jml_cont ?>">
                <input type="hidden" name="data3[<?= $index ?>][jumlah]" value="<?= $ccdelivery->findContainer[$i]->jml_cont ?>">
                <input type="hidden" name="data4[<?= $index ?>][jumlah]" value="<?= $ccdelivery->findContainer[$i]->jml_cont ?>">
                <input type="hidden" name="data5[<?= $index ?>][jumlah]" value="<?= $ccdelivery->findContainer[$i]->jml_cont ?>">
                <input type="hidden" name="data7[<?= $index ?>][jumlah]" value="<?= $ccdelivery->findContainer[$i]->jml_cont ?>">

                <input type="hidden" name="data1[<?= $index ?>][size]" value="<?= $ccdelivery->findContainer[$i]->ctr_size ?>">
                <input type="hidden" name="data2[<?= $index ?>][size]" value="<?= $ccdelivery->findContainer[$i]->ctr_size ?>">
                <input type="hidden" name="data3[<?= $index ?>][size]" value="<?= $ccdelivery->findContainer[$i]->ctr_size ?>">
                <input type="hidden" name="data4[<?= $index ?>][size]" value="<?= $ccdelivery->findContainer[$i]->ctr_size ?>">
                <input type="hidden" name="data5[<?= $index ?>][size]" value="<?= $ccdelivery->findContainer[$i]->ctr_size ?>">
                <input type="hidden" name="data7[<?= $index ?>][size]" value="<?= $ccdelivery->findContainer[$i]->ctr_size ?>">

                <input type="hidden" name="data1[<?= $index ?>][type]" value="<?= $ccdelivery->findContainer[$i]->ctr_type ?>">
                <input type="hidden" name="data2[<?= $index ?>][type]" value="<?= $ccdelivery->findContainer[$i]->ctr_type ?>">
                <input type="hidden" name="data3[<?= $index ?>][type]" value="<?= $ccdelivery->findContainer[$i]->ctr_type ?>">
                <input type="hidden" name="data4[<?= $index ?>][type]" value="<?= $ccdelivery->findContainer[$i]->ctr_type ?>">
                <input type="hidden" name="data5[<?= $index ?>][type]" value="<?= $ccdelivery->findContainer[$i]->ctr_type ?>">
                <input type="hidden" name="data7[<?= $index ?>][type]" value="<?= $ccdelivery->findContainer[$i]->ctr_type ?>">

                <input type="hidden" name="data1[<?= $index ?>][status]" value="<?= $ccdelivery->findContainer[$i]->ctr_status ?>">
                <input type="hidden" name="data2[<?= $index ?>][status]" value="<?= $ccdelivery->findContainer[$i]->ctr_status ?>">
                <input type="hidden" name="data3[<?= $index ?>][status]" value="<?= $ccdelivery->findContainer[$i]->ctr_status ?>">
                <input type="hidden" name="data4[<?= $index ?>][status]" value="<?= $ccdelivery->findContainer[$i]->ctr_status ?>">
                <input type="hidden" name="data5[<?= $index ?>][status]" value="<?= $ccdelivery->findContainer[$i]->ctr_status ?>">
                <input type="hidden" name="data7[<?= $index ?>][status]" value="<?= $ccdelivery->findContainer[$i]->ctr_status ?>">

                <input type="hidden" name="data1[<?= $index ?>][hari]" value="0">
                <input type="hidden" name="data2[<?= $index ?>][hari]" value="0">
                <input type="hidden" name="data3[<?= $index ?>][hari]" value="<?= $ccdelivery->diffInDays[$i]->masa1 ?>">
                <input type="hidden" name="data4[<?= $index ?>][hari]" value="<?= $ccdelivery->diffInDays[$i]->masa2 ?? "" ?>">
                <input type="hidden" name="data5[<?= $index ?>][hari]" value="<?= $ccdelivery->diffInDays[$i]->masa3 ?? "" ?>">
                <input type="hidden" name="data7[<?= $index ?>][hari]" value="0">

                <input type="hidden" name="data1[<?= $index ?>][tarif]" value="<?= $data->lift_off ?>">
                <input type="hidden" name="data2[<?= $index ?>][tarif]" value="<?= $data->pass_truck ?>">
                <input type="hidden" name="data3[<?= $index ?>][tarif]" value="<?= $data->masa1 ?>">
                <input type="hidden" name="data4[<?= $index ?>][tarif]" value="<?= $data->masa2 ?? "" ?>">
                <input type="hidden" name="data5[<?= $index ?>][tarif]" value="<?= $data->masa3 ?? "" ?>">
                <input type="hidden" name="data7[<?= $index ?>][tarif]" value="<?= $data->lift_on ?>">

                <input type="hidden" name="data1[<?= $index ?>][amount]" value="<?= $ccdelivery->grandTotal[$i]->liftOff ?>">
                <input type="hidden" name="data2[<?= $index ?>][amount]" value="<?= $ccdelivery->grandTotal[$i]->passTruck ?>">
                <input type="hidden" name="data3[<?= $index ?>][amount]" value="<?= $ccdelivery->grandTotal[$i]->penumpukanMasa1 ?>">
                <input type="hidden" name="data4[<?= $index ?>][amount]" value="<?= $ccdelivery->grandTotal[$i]->penumpukanMasa2 ?? "" ?>">
                <input type="hidden" name="data5[<?= $index ?>][amount]" value="<?= $ccdelivery->grandTotal[$i]->penumpukanMasa3 ?? "" ?>">
                <input type="hidden" name="data7[<?= $index ?>][amount]" value="<?= $ccdelivery->grandTotal[$i]->liftOn ?>">

              <?php } ?>
              <div class="col-12">
                <h5>INVOICE 1</h5>
              </div>
              <div class="col-12">
                <h5>Tarif Container dengan ukuran <?= $data->size ?></h5>
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



                    <tr>

                      <td><?= $menuinv[0] ?></td>
                      <td><?= $ccdelivery->findContainer[$i]->jml_cont ?></td>
                      <td><?= $ccdelivery->findContainer[$i]->ctr_size ?></td>
                      <td><?= $ccdelivery->findContainer[$i]->ctr_type ?></td>
                      <td><?= $ccdelivery->findContainer[$i]->ctr_status ?></td>

                      <?php if ($menuinv[0] == "Lift On Empty") {  ?>
                        <td>0 Hari</td>
                        <td>Rp. <?= rupiah($data->lift_on) ?></td>
                        <td>Rp. <?= rupiah($ccdelivery->grandTotal[$i]->liftOn) ?></td>

                      <?php }  ?>

                    </tr>
                  </tbody>
                </table>
                <h4>Total : Rp. <?= rupiah($ccdelivery->grandTotal[$i]->amountBeforeTaxInvoice1) ?>,00 ~</h4>
                <br>
                <br>
                <br>
              </div>

              <div class="col-12">
                <h5>INVOICE 2</h5>
              </div>
              <div class="col-12">
                <h5>Tarif Container dengan ukuran <?= $data->size ?></h5>
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


                    <?php
                    // $i = 0;
                    foreach ($menuinv2 as $value) { ?>
                      <tr>

                        <td><?= $value ?></td>
                        <td><?= $ccdelivery->findContainer[$i]->jml_cont ?></td>
                        <td><?= $ccdelivery->findContainer[$i]->ctr_size ?></td>
                        <td><?= $ccdelivery->findContainer[$i]->ctr_type ?></td>
                        <td><?= $ccdelivery->findContainer[$i]->ctr_status ?></td>

                        <?php if ($value == "Lift Off Full") {  ?>
                          <td>0 Hari</td>
                          <td>Rp. <?= rupiah($data->lift_off) ?></td>
                          <td>Rp. <?= rupiah($ccdelivery->grandTotal[$i]->liftOff) ?></td>
                        <?php } else if ($value == "Pass Truck") {  ?>
                          <td>0 Hari</td>
                          <td>Rp. <?= rupiah($data->pass_truck) ?> x 2 (In & Out)</td>
                          <td>Rp. <?= rupiah($ccdelivery->grandTotal[$i]->passTruck) ?> </td>
                        <?php }  ?>

                        <?php if ($value == "Penumpukan Masa 1" && $ccdelivery->grandTotal[$i]->penumpukanMasa1 != 0) {  ?>
                          <td><?= $ccdelivery->diffInDays[$i]->masa1 ?> Hari</td>
                          <td>Rp. <?= rupiah($data->masa1) ?></td>
                          <td>Rp. <?= rupiah($ccdelivery->grandTotal[$i]->penumpukanMasa1) ?></td>
                        <?php } ?>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
                <h4>Total : Rp. <?= rupiah($ccdelivery->grandTotal[$i]->amountBeforeTaxInvoice2) ?>,00 ~</h4>
                <br>
                <br>
                <br>
              </div>
            <?php
              $i++;
              $index++; // Increment the index variable for each iteration
              $k++; // Increment the index variable for each iteration

            } ?>
          </div>

          <div class="row mt-3">
            <div class="col-12">
              <div class="card" style="border-radius:15px !important;background-color:#435ebe !important;">
                <div class="card-body">
                  <div class="row text-white p-3">
                    <div class="col-6">
                      <h4 class="text-white bold">TOTAL AMOUNT INVOICE 1</h4>
                      <h5 class="text-white">Administration :</h5>
                      <h5 class="text-white">Tax 11% :</h5>
                      <h5 class="text-white">Grand Total :</h5>
                    </div>
                    <div class="col-6" style="text-align:right;">
                      <h4 class="text-white bold">Rp. <?= rupiah($ccdelivery->finalGrandTotal[0]->totalAmountBeforeTaxInvoice1) ?>,00 ~</h4>
                      <h5 class="text-white">Rp. <?= rupiah($ccdelivery->grandTotal[0]->administration) ?>,00 ~</h5>
                      <h5 class="text-white">Rp. <?= rupiah($ccdelivery->finalGrandTotal[0]->totalAmountWithTaxInvoice1) ?>,00 ~</h5>
                      <h4 class="bold" style="color:#ff5265;">Rp. <?= rupiah($ccdelivery->finalGrandTotal[0]->totalAmountAfterTaxInvoice1) ?>,00 ~</h4>


                      <input type="hidden" name="data6[customer]" value="<?= $ccdelivery->deliveryForm->customer->customer_name ?>">
                      <input type="hidden" name="data6[deliveryid]" value="<?= $ccdelivery->deliveryForm->id ?>">
                      <input type="hidden" name="data6[totalamount]" value="<?= $ccdelivery->finalGrandTotal[0]->totalAmountBeforeTaxInvoice1 ?>">
                      <input type="hidden" name="data6[admin]" value="<?= $ccdelivery->grandTotal[0]->administration ?>">
                      <input type="hidden" name="data6[tax]" value="<?= $ccdelivery->finalGrandTotal[0]->totalAmountWithTaxInvoice1 ?>">
                      <input type="hidden" name="data6[grandtotal]" value="<?= $ccdelivery->finalGrandTotal[0]->totalAmountAfterTaxInvoice1 ?>">


                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 mt-3">
              <div class="card" style="border-radius:15px !important;background-color:#435ebe !important;">
                <div class="card-body">
                  <div class="row text-white p-3">
                    <div class="col-6">
                      <h4 class="text-white bold">TOTAL AMOUNT INVOICE 2</h4>
                      <h5 class="text-white">Administration :</h5>
                      <h5 class="text-white">Tax 11% :</h5>
                      <h5 class="text-white">Grand Total :</h5>
                    </div>
                    <div class="col-6" style="text-align:right;">
                      <h4 class="text-white bold">Rp. <?= rupiah($ccdelivery->finalGrandTotal[0]->totalAmountBeforeTaxInvoice2) ?>,00 ~</h4>
                      <h5 class="text-white">Rp. 0,00 ~</h5>
                      <h5 class="text-white">Rp. <?= rupiah($ccdelivery->finalGrandTotal[0]->totalAmountWithTaxInvoice2) ?>,00 ~</h5>
                      <h4 class="bold" style="color:#ff5265;">Rp. <?= rupiah($ccdelivery->finalGrandTotal[0]->totalAmountAfterTaxInvoice2) ?>,00 ~</h4>


                      <input type="hidden" name="data8[customer]" value="<?= $ccdelivery->deliveryForm->customer->customer_name ?>">
                      <input type="hidden" name="data8[deliveryid]" value="<?= $ccdelivery->deliveryForm->id ?>">
                      <input type="hidden" name="data8[totalamount]" value="<?= $ccdelivery->finalGrandTotal[0]->totalAmountBeforeTaxInvoice2 ?>">
                      <input type="hidden" name="data8[admin]" value="0">
                      <input type="hidden" name="data8[tax]" value="<?= $ccdelivery->finalGrandTotal[0]->totalAmountWithTaxInvoice2 ?>">
                      <input type="hidden" name="data8[grandtotal]" value="<?= $ccdelivery->finalGrandTotal[0]->totalAmountAfterTaxInvoice2 ?>">


                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row mt-3">

          </div>
          <div class="row mt-5">
            <div class="col-12 text-right">
              <button type="submit" class="btn btn btn-success">Submit</button>
              <a href="/invoice/add/update_step1?id=<?= $ccdelivery->deliveryForm->id ?>" class="btn btn btn-warning">Edit</a>
              <a onclick="canceladdCustomer();" class="btn btn btn-secondary">Cancel</a>
            </div>
          </div>
        </div>
      </div>
    </form>

  </section>
</div>

@endsection