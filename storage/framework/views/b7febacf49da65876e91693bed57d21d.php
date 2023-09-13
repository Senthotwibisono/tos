<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"> -->


<?php $__env->startSection('content'); ?>

<div class="page-heading text-center">
  <h3>Billing System</h3>
  <p>Pilihan Menu Billing System</p>

</div>


<div class="page-content">
  <div class="row">
    <div class="col-6">
      <div class="row">
        <div class="col-12">


          <div class="card">
            <div class="card-content">
              <img class="card-img-top img-fluid " src="<?php echo e(asset('/images/invoice.jpg')); ?>" alt="Card image cap" style="height: 20rem" />
              <div class="card-body">
                <h4 class="card-title">Delivery Invoice</h4>
                <p class="card-text">
                  Menu untuk Delivery Invoice
                </p>
                <br>
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
                  <img src="<?php echo e(asset('/images/costumer.jpg')); ?>" class="card-img-top img-fluid" alt="card image cap" style="height: 20rem"/>
                  <div class="card-body">
                    <h4 class="card-title">Customer Management</h4>
                    <p class="card-text">
                      Menu untuk Customer Management
                    </p>
                    <a href="/invoice/customer" class="btn btn-primary block">Go To Menu</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-6">
              <div class="card">
                <div class="card-content">
                  <img src="<?php echo e(asset('/images/tarif.jpg')); ?>" class="card-img-top img-fluid" alt="card image cap" style="height: 20rem"/>
                  <div class="card-body">
                    <h4 class="card-title">Master Tarif SP2</h4>
                    <p class="card-text">
                      Menu untuk Master Tarif SP2
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
              <img src="<?php echo e(asset('/images/import.jpg')); ?>" class="card-img-top img-fluid" alt="card image cap" style="height: 20rem"/>
              <div class="card-body">
                <h4 class="card-title">Delivery Import</h4>
                <p class="card-text">
                  Menu untuk Delivery Import
                </p>
                <br>
                <a href="/invoice/delivery" class="btn btn-primary block">Go To Menu</a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-6">
          <div class="card">
            <div class="card-content">
              <img src="<?php echo e(asset('/images/extend.jpg')); ?>" class="card-img-top img-fluid" alt="card image cap" style="height: 20rem"/>
              <div class="card-body">
                <h4 class="card-title">Delivery Import Extend</h4>
                <p class="card-text">
                  Menu untuk Delivery import (Extend)
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
              <img src="<?php echo e(asset('/images/receiving.jpg')); ?>" class="card-img-top img-fluid" alt="card image cap" style="height: 20rem"/>
              <div class="card-body">
                <h4 class="card-title">Receiving</h4>
                <p class="card-text">
                  Menu untuk Receiving
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
<?php echo $__env->make('partial.invoice.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\tos\resources\views/invoice/menu.blade.php ENDPATH**/ ?>