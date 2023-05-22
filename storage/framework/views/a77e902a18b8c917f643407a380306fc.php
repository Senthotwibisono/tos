<!DOCTYPE html>
<html lang="en">

<head>
  <?php echo $__env->make('partial.invoice.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

</head>

<body>
  <div id="app">
    <div id="main" class="layout-horizontal">
      <header class="mb-5">
        <div class="header-top">
          <div class="container">
            <div class="logo">
              <a href="/invoice"><img src="<?php echo e(asset('dist/assets/images/logo/logo.svg')); ?>" alt="Logo"></a>
            </div>
            <div class="header-top-right">

              <?php echo $__env->make('partial.invoice.authheader', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

              <!-- Burger button responsive -->
              <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
              </a>
            </div>
          </div>
        </div>
        <nav class="main-navbar">
          <?php echo $__env->make('partial.invoice.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </nav>

      </header>

      <div class="content-wrapper container">

        <?php echo $__env->yieldContent('content'); ?>

      </div>

      <!-- <footer>
        <?php echo $__env->make('partial.invoice.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
      </footer> -->
    </div>
  </div>
  <script src="<?php echo e(asset('dist/assets/js/bootstrap.js')); ?>"></script>
  <script src="<?php echo e(asset('dist/assets/js/app.js')); ?>"></script>
  <script src="<?php echo e(asset('dist/assets/js/pages/horizontal-layout.js')); ?>"></script>
  <script src="<?php echo e(asset('dist/assets/extensions/apexcharts/apexcharts.min.js')); ?>"></script>
  <script src="<?php echo e(asset('dist/assets/js/pages/dashboard.js')); ?>"></script>
  <script src="<?php echo e(asset('fontawesome/js/all.js')); ?>"></script>
  <script src="<?php echo e(asset('fontawesome/js/all.min.js')); ?>"></script>
  <script src="<?php echo e(asset('dist/assets/extensions/simple-datatables/umd/simple-datatables.js')); ?>"></script>
  <script src="<?php echo e(asset('dist/assets/js/pages/simple-datatables.js')); ?>"></script>
  <script src="<?php echo e(asset('vendor/components/jquery/jquery.min.js')); ?>"></script>
  <script src="<?php echo e(asset('dist/assets/extensions/sweetalert2/sweetalert2.min.js')); ?>"></script>
  <script src="<?php echo e(asset('dist/assets/js/pages/sweetalert2.js')); ?>"></script>

  <!-- select 2 js  -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <!-- flatpickr js -->
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <?php echo $__env->yieldContent('custom_js'); ?>

  <?php if(\Session::has('success')): ?>
  <script type="text/javascript">
    // Add CSRF token to the headers
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var successMessage = "<?php echo \Session::get('success'); ?>";

    if (successMessage) {
      Swal.fire({
        icon: 'success',
        title: 'bebas',
        text: successMessage,
      }).then(function() {
        // Make an AJAX request to unset session variable
        $.ajax({
          url: "<?php echo e(route('unset-session', ['key' => 'success'])); ?>",
          type: 'POST',
          success: function(response) {
            console.log('Success session unset');
            // <?php echo e(logger('Success session unset')); ?> -> call func logger in helper
          },
          error: function(error) {
            console.log('Error unsetting session', error);
          }
        });
      });
    }
  </script>
  <?php endif; ?>

</body>

<!-- <script>
  let jquery_datatable = $("#table1").DataTable()
  let jquery_datatable2 = $("#table2").DataTable()
  let jquery_datatable3 = $("#table3").DataTable()
  let jquery_datatable4 = $("#table4").DataTable()
</script> -->

<script>
  $(document).ready(function() {
    $('.js-example-basic-single').select2();
    $('.js-example-basic-multiple').select2();
    flatpickr('#expired', {
      "minDate": new Date()
    });
    flatpickr('#doexpired', {
      "minDate": new Date()
    });
    flatpickr('#hour', {
      noCalendar: true,
      enableTime: true,
      dateFormat: 'h:i K'
    });
  });
</script>

<?php echo $__env->make('partial.invoice.js.js_customer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


</html><?php /**PATH D:\Fdw Files\CTOS\dev\frontend\tos-dev-local\resources\views/partial/invoice/main.blade.php ENDPATH**/ ?>