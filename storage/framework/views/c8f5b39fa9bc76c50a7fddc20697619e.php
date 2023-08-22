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
              <a href="/"><img class="logoicon" src="<?php echo e(asset('logo/ICON2.png')); ?>" alt="Logo"></a>
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
  <script src="<?php echo e(asset('dist/assets/extensions/choices.js/public/assets/scripts/choices.js')); ?>"></script>
  <script src="<?php echo e(asset('dist/assets/js/pages/form-element-select.js')); ?>"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  <script>new simpleDatatables.DataTable('#table1bc');</script>
  <script>new simpleDatatables.DataTable('#table1pea');</script>
  <script>new simpleDatatables.DataTable('#table2');</script>
  <script>new simpleDatatables.DataTable('#table2Pea');</script>
  <script>new simpleDatatables.DataTable('#table3');</script>
  <script>new simpleDatatables.DataTable('#tableLain');</script>

  <!-- select 2 js  -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <!-- flatpickr js -->
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

  <!-- Dropify Inject -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js" integrity="sha512-8QFTrG0oeOiyWo/VM9Y8kgxdlCryqhIxVeRpWSezdRRAvarxVtwLnGroJgnVW9/XBRduxO/z1GblzPrMQoeuew==" crossorigin="anonymous"></script>

  <!-- date-range-picker  -->
  <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>

  <!-- moment.js  -->
  <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>


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
        title: 'Success',
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
<script>
  $(document).ready(function() {
    $('table.display').DataTable();
  });
</script>

<script>
  function formatRupiah(angka, prefix) {
    var number_string = angka.replace(/[^,\d]/g, '').toString(),
      split = number_string.split(','),
      sisa = split[0].length % 3,
      rupiah = split[0].substr(0, sisa),
      ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    // tambahkan titik jika yang di input sudah menjadi angka ribuan
    if (ribuan) {
      separator = sisa ? '.' : '';
      rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
  }
</script>

<script>
  $('.dropify').dropify({
    messages: {
      'default': 'Drag and drop a file here or click',
      'replace': 'Drag and drop or click to replace',
      'remove': 'Remove',
      'error': 'Ooops, something wrong happended.'
    }
  });
</script>

<?php echo $__env->make('partial.invoice.js.js_customer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


</html><?php /**PATH C:\xampp\htdocs\tos\resources\views/partial/invoice/main.blade.php ENDPATH**/ ?>