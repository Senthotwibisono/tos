


<?php $__env->startSection('content'); ?>

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>

</div>
<div class="page-content">
  <section class="row">
    <div class="col-12 text-center">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">
            Data Management
          </h4>
          <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12">
              <div class="btn-group mb-3" role="group" aria-label="Basic example">
                <a onclick="createTarif();" type="button" class="btn btn-success">
                  Tambah Master Tarif SP2
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </section>

  <section class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Master Tarif for SP2 Data Table</h4>
          <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
        </div>
        <div class="card-body">
          <div class="row mt-5">
            <div class="col-12">
              <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
                <thead>
                  <tr>
                    <th>Action</th>
                    <th>Lokasi Sandar</th>
                    <th>Type</th>
                    <th>Size</th>
                    <th>Status</th>
                    <th>Masa 1</th>
                    <th>Masa 2</th>
                    <th>Masa 3</th>
                    <th>Masa 4</th>
                    <th>Lift On</th>
                    <th>Lift Off</th>
                    <th>Pass Truck</th>
                    <th>Gate Pass Admin</th>
                    <th>Cost Recovery</th>
                    <th>Surcharge</th>
                    <th>Packet PLP</th>
                    <th>Behandle</th>
                    <th>Recooling</th>
                    <th>Monitoring</th>
                    <th>Administrasi</th>
                    <th>Created At</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($mastertarif as $value) { ?>
                    <tr>
                      <td><a type="button" onclick="tarifConfig(`<?= $value->id ?>`)" class="btn btn-sm btn-success"><i class="fa fa-pencil"></i></a></td>
                      <td><?= $value->lokasi_sandar ?></td>
                      <td><?= $value->type ?></td>
                      <td><?= $value->size ?></td>
                      <td><?= $value->status ?></td>
                      <td><?= $value->masa1 ?></td>
                      <td><?= $value->masa2 ?></td>
                      <td><?= $value->masa3 ?></td>
                      <td><?= $value->masa4 ?></td>
                      <td><?= $value->lift_on ?></td>
                      <td><?= $value->lift_off ?></td>
                      <td><?= $value->pass_truck ?></td>
                      <td><?= $value->gate_pass_admin ?></td>
                      <td><?= $value->cost_recovery ?></td>
                      <td><?= $value->surcharge ?></td>
                      <td><?= $value->packet_plp ?></td>
                      <td><?= $value->behandle ?></td>
                      <td><?= $value->recooling ?></td>
                      <td><?= $value->monitoring ?></td>
                      <td><?= $value->administrasi ?></td>
                      <td><?= DateFormat($value->createdAt) ?></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>


<!-- Edit Modal Single Data Table  -->
<div class="modal fade text-left modal-borderless" id="editModalTarif" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Data Master Tarif SP2</h5>
        <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
          <i data-feather="x"></i>
        </button>
      </div>
      <form action="#">
        <div class="modal-body" style="height:auto;">
          <div class="form-group">
            <label>Id</label>
            <input type="text" id="id" value="kosong" class="form-control" readonly>
          </div>
          <div class="form-group">
            <label for="">Lokasi Sandar</label>
            <input type="text" id="Lokasi_Sandar" class="form-control" value="kosong">
          </div>
          <div class="form-group">
            <label for="">Type</label>
            <input type="text" id="Type" class="form-control" value="kosong">
          </div>
          <div class="form-group">
            <label for="">Size</label>
            <input type="text" id="Size" class="form-control" value="kosong">
          </div>
          <div class="form-group">
            <label for="">Status</label>
            <input type="text" id="Status" class="form-control" value="kosong">
          </div>
          <div class="form-group">
            <label for="">Masa 1</label>
            <input type="text" id="Masa_1" class="form-control" value="kosong">
          </div>
          <div class="form-group">
            <label for="">Masa 2</label>
            <input type="text" id="Masa_2" class="form-control" value="kosong">
          </div>
          <div class="form-group">
            <label for="">Masa 3</label>
            <input type="text" id="Masa_3" class="form-control" value="kosong">
          </div>
          <div class="form-group">
            <label for="">Masa 4</label>
            <input type="text" id="Masa_4" class="form-control" value="kosong">
          </div>
          <div class="form-group">
            <label for="">Lift On</label>
            <input type="text" id="Lift_On" class="form-control" value="kosong">
          </div>
          <div class="form-group">
            <label for="">Lift Off</label>
            <input type="text" id="Lift_Off" class="form-control" value="kosong">
          </div>
          <div class="form-group">
            <label for="">Pass Truck</label>
            <input type="text" id="Pass_Truck" class="form-control" value="kosong">
          </div>
          <div class="form-group">
            <label for="">Gate Pass Admin</label>
            <input type="text" id="Gate_Pass_Admin" class="form-control" value="kosong">
          </div>
          <div class="form-group">
            <label for="">Cost Recovery</label>
            <input type="text" id="Cost_Recovery" class="form-control" value="kosong">
          </div>
          <div class="form-group">
            <label for="">Surcharge</label>
            <input type="text" id="Surcharge" class="form-control" value="kosong">
          </div>
          <div class="form-group">
            <label for="">Packet PLP</label>
            <input type="text" id="Packet_PLP" class="form-control" value="kosong">
          </div>
          <div class="form-group">
            <label for="">Behandle</label>
            <input type="text" id="Behandle" class="form-control" value="kosong">
          </div>
          <div class="form-group">
            <label for="">Recooling</label>
            <input type="text" id="Recooling" class="form-control" value="kosong">
          </div>
          <div class="form-group">
            <label for="">Monitoring</label>
            <input type="text" id="Monitoring" class="form-control" value="kosong">
          </div>
          <div class="form-group">
            <label for="">Administrasi</label>
            <input type="text" id="Administrasi" class="form-control" value="kosong">
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
            <i class="bx bx-x d-block d-sm-none"></i>
            <span class="d-none d-sm-block">Cancel</span>
          </button>
          <button id="editSubmit" type="button" class="btn btn-primary ml-1">
            Submit
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- end of Edit Modal Single Data Table  -->

<!-- Create Modal Single Data Table  -->
<div class="modal fade text-left modal-borderless" id="createModalTarif" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Create Data Master Tarif SP2</h5>
        <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
          <i data-feather="x"></i>
        </button>
      </div>
      <form action="#">
        <div class="modal-body" style="height:auto;">
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label for="">Lokasi Sandar</label>
                <input type="text" id="Lokasi_Sandar" class="form-control" placeholder="Enter Value Here..">
              </div>
              <div class="form-group">
                <label for="">Type</label>
                <input type="text" id="Type" class="form-control" placeholder="Enter Value Here..">
              </div>
              <div class="form-group">
                <label for="">Size</label>
                <input type="text" id="Size" class="form-control" placeholder="Enter Value Here..">
              </div>
              <div class="form-group">
                <label for="">Status</label>
                <input type="text" id="Status" class="form-control" placeholder="Enter Value Here..">
              </div>
              <div class="form-group">
                <label for="">Masa 1</label>
                <input type="text" id="Masa_1" class="form-control" placeholder="Enter Value Here..">
              </div>
              <div class="form-group">
                <label for="">Masa 2</label>
                <input type="text" id="Masa_2" class="form-control" placeholder="Enter Value Here..">
              </div>
              <div class="form-group">
                <label for="">Masa 3</label>
                <input type="text" id="Masa_3" class="form-control" placeholder="Enter Value Here..">
              </div>
              <div class="form-group">
                <label for="">Masa 4</label>
                <input type="text" id="Masa_4" class="form-control" placeholder="Enter Value Here..">
              </div>
              <div class="form-group">
                <label for="">Lift On</label>
                <input type="text" id="Lift_On" class="form-control" placeholder="Enter Value Here..">
              </div>
              <div class="form-group">
                <label for="">Lift Off</label>
                <input type="text" id="Lift_Off" class="form-control" placeholder="Enter Value Here..">
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label for="">Pass Truck</label>
                <input type="text" id="Pass_Truck" class="form-control" placeholder="Enter Value Here..">
              </div>

              <div class="form-group">
                <label for="">Gate Pass Admin</label>
                <input type="text" id="Gate_Pass_Admin" class="form-control" placeholder="Enter Value Here..">
              </div>
              <div class="form-group">
                <label for="">Cost Recovery</label>
                <input type="text" id="Cost_Recovery" class="form-control" placeholder="Enter Value Here..">
              </div>
              <div class="form-group">
                <label for="">Surcharge</label>
                <input type="text" id="Surcharge" class="form-control" placeholder="Enter Value Here..">
              </div>
              <div class="form-group">
                <label for="">Packet PLP</label>
                <input type="text" id="Packet_PLP" class="form-control" placeholder="Enter Value Here..">
              </div>
              <div class="form-group">
                <label for="">Behandle</label>
                <input type="text" id="Behandle" class="form-control" placeholder="Enter Value Here..">
              </div>
              <div class="form-group">
                <label for="">Recooling</label>
                <input type="text" id="Recooling" class="form-control" placeholder="Enter Value Here..">
              </div>
              <div class="form-group">
                <label for="">Monitoring</label>
                <input type="text" id="Monitoring" class="form-control" placeholder="Enter Value Here..">
              </div>
              <div class="form-group">
                <label for="">Administrasi</label>
                <input type="text" id="Administrasi" class="form-control" placeholder="Enter Value Here..">
              </div>
            </div>
          </div>


        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
            <i class="bx bx-x d-block d-sm-none"></i>
            <span class="d-none d-sm-block">Cancel</span>
          </button>
          <button id="createSubmit" type="button" class="btn btn-primary ml-1">
            Submit
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- end of Create Modal Single Data Table  -->

<?php $__env->stopSection(); ?>
<?php echo $__env->make('partial.invoice.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Fdw Files\CTOS\dev\frontend\tos-dev-local\resources\views/invoice/master_tarif/dashboard.blade.php ENDPATH**/ ?>