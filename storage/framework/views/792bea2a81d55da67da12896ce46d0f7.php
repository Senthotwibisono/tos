
<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"> -->


<?php $__env->startSection('content'); ?>

<div class="page-heading text-center">
  <h3><?= $title ?></h3>
  <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>

</div>


<div class="page-content">
  <div class="row">
    <div class="col-6">
      <div class="row">
        <div class="col-12">


          <div class="card">
            <div class="card-content">
              <img class="card-img-top img-fluid " src="<?php echo e(asset('/images/container2.jpg')); ?>" alt="Card image cap" style="height: 20rem" />
              <div class="card-body">
                <h4 class="card-title">Billing System Delivery Invoice</h4>
                <p class="card-text">
                  Jelly-o sesame snaps cheesecake topping. Cupcake
                  fruitcake macaroon donut pastry.
                </p>
                <a href="/invoice" class="btn btn-primary block">Go To Menu</a>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12">
          <div class="row">
            <div class="col-6">
              <div class="card">
                <div class="card-content">
                  <img src="<?php echo e(asset('/images/container1.jpg')); ?>" class="card-img-top img-fluid">
                  <div class="card-body">
                    <h4 class="card-title">Customer Management</h4>
                    <p class="card-text">
                      Jelly-o sesame snaps cheesecake topping. Cupcake
                      fruitcake macaroon donut pastry.
                    </p>
                    <a href="/invoice/customer" class="btn btn-primary block">Go To Menu</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-6">
              <div class="card">
                <div class="card-content">
                  <img src="<?php echo e(asset('/images/container1.jpg')); ?>" class="card-img-top img-fluid">
                  <div class="card-body">
                    <h4 class="card-title">Master Tarif SP2</h4>
                    <p class="card-text">
                      Jelly-o sesame snaps cheesecake topping. Cupcake
                      fruitcake macaroon donut pastry.
                    </p>
                    <a href="/invoice/mastertarif" class="btn btn-primary block">Go To Menu</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
    <div class="col-6">
      <div class="row">
        <div class="col-6">
          <div class="card">
            <div class="card-content">
              <img src="<?php echo e(asset('/images/container1.jpg')); ?>" class="card-img-top img-fluid">
              <div class="card-body">
                <h4 class="card-title">Billing System Delivery Import</h4>
                <p class="card-text">
                  Jelly-o sesame snaps cheesecake topping. Cupcake
                  fruitcake macaroon donut pastry.
                </p>
                <a href="/invoice/delivery" class="btn btn-primary block">Go To Menu</a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-6">
          <div class="card">
            <div class="card-content">
              <img src="<?php echo e(asset('/images/container1.jpg')); ?>" class="card-img-top img-fluid">
              <div class="card-body">
                <h4 class="card-title">Billing System Delivery Import Extend</h4>
                <p class="card-text">
                  Jelly-o sesame snaps cheesecake topping. Cupcake
                  fruitcake macaroon donut pastry.
                </p>
                <a href="/invoice/add/extend" class="btn btn-primary block">Go To Menu</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-content">
              <img src="<?php echo e(asset('/images/container1.jpg')); ?>" class="card-img-top img-fluid">
              <div class="card-body">
                <h4 class="card-title">Billing System Delivery Export</h4>
                <p class="card-text">
                  Jelly-o sesame snaps cheesecake topping. Cupcake
                  fruitcake macaroon donut pastry.
                </p>
                <a href="/export" class="btn btn-primary block">Go To Menu</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('partial.invoice.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Fdw File Storage 1\CTOS\dev\frontend\tos-dev-local\resources\views/invoice/menu.blade.php ENDPATH**/ ?>