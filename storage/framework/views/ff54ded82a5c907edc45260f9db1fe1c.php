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
          <h3 class="card-title">
            <?= $title ?>
          </h3>
          <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12">
              <div class="btn-group mb-3" role="group" aria-label="Basic example">
                <a href="/do/create" type="button" class="btn btn-success">
                  Create Do Online
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
          <h3 class="card-title">
            Table DO Online
          </h3>
          <p>Lorem Ipsum</p>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12">
              <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Container No</th>
                    <th>Do No</th>
                    <th>BL No</th>
                    <th>Do Expired</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $i = 1;
                  foreach ($do as $data) { ?>
                    <?php if ($data->isActive == "1") { ?>

                      <tr>
                        <td><?= $i ?></td>
                        <td><?= $data->container_no ?></td>
                        <td><?= $data->do_no ?></td>
                        <td><?= $data->bl_no ?></td>
                        <td><?= $data->do_expired ?></td>

                      </tr>
                    <?php } ?>
                  <?php
                    $i++;
                  } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('partial.invoice.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\tos\resources\views/do/dashboard.blade.php ENDPATH**/ ?>