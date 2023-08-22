<!-- Fonts -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

<!-- Vendors -->
<!-- <link rel="stylesheet" href="<?php echo e(asset('/vendors/perfect-scrollbar/perfect-scrollbar.css')); ?>"> -->
<link rel="stylesheet" href="<?php echo e(asset('dist/assets/extensions/bootstrap-icons/font/bootstrap-icons.css')); ?>">

<link rel="shortcut icon" href="<?php echo e(asset('logo/icon.png')); ?>" type="image/x-icon">
    <link rel="shortcut icon" href="<?php echo e(asset('logo/icon.png')); ?>" type="image/png">
<!-- Styles -->

<link rel="stylesheet" href="<?php echo e(asset('dist/assets/css/main/app.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('dist/assets/css/main/app-dark.css')); ?>">

<link rel="stylesheet" href="<?php echo e(asset('fontawesome/css/all.min.css')); ?>">

<style>
    .select2-container--default .select2-selection--single {
        border-radius:.3rem;
        font-size:1.25rem;
        min-height:calc(1.5em + 1rem + 2px);
        padding:.5rem 1rem;
        background-color: #010f1c;
  }

  .select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #fff;
  }
   
  .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: calc(2.5rem + 2px);
    background-color: #010f1c;
  }

  .logoicon {
    transform: scale(3);
}
.round-image-3 {
  width: 40px; /* Sesuaikan dengan lebar yang diinginkan */
  height: 40px; /* Sesuaikan dengan tinggi yang diinginkan */
  border-radius: 50%;
  overflow: hidden;
}

</style>
<!-- custom_style -->
<?php echo $__env->yieldContent('custom_style'); ?><?php /**PATH C:\xampp\htdocs\tos\resources\views/layouts/partials/styles.blade.php ENDPATH**/ ?>