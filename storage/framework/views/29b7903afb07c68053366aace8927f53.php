<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

<title>SPPS | <?= $title ?></title>

<link rel="stylesheet" href="<?php echo e(asset('dist/assets/css/main/app.css')); ?>">
<link rel="shortcut icon" href="<?php echo e(asset('logo/icon.png')); ?>" type="image/x-icon">
<link rel="shortcut icon" href="<?php echo e(asset('logo/icon.png')); ?>" type="image/png">

<link rel="stylesheet" href="<?php echo e(asset('dist/assets/css/shared/iconly.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('fontawesome/css/all.min.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('dist/assets/css/pages/datatables.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('dist/assets/extensions/simple-datatables/style.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('dist/assets/css/pages/simple-datatables.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('dist/assets/extensions/sweetalert2/sweetalert2.min.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('select2/dist/css/select2.min.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('query-ui/jquery-ui.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('query-ui/jquery-ui.min.css')); ?>">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

<!-- Select 2 js -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- flatpickr js  -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
  .logoicon {
    transform: scale(3);
  }

  .round-image-3 {
    width: 40px;
    /* Sesuaikan dengan lebar yang diinginkan */
    height: 40px;
    /* Sesuaikan dengan tinggi yang diinginkan */
    border-radius: 50%;
    overflow: hidden;
  }
</style><?php /**PATH E:\Fdw File Storage 1\CTOS\dev\frontend\tos-dev-local\resources\views/partial/spps/header.blade.php ENDPATH**/ ?>