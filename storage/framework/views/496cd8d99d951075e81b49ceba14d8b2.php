<script src="<?php echo e(asset('dist/assets/js/app.js')); ?>"></script>
<script src="<?php echo e(asset('dist/assets/js/bootstrap.js')); ?>"></script>

<!-- <script src="<?php echo e(asset('/js/main.js')); ?>"></script> -->

<!-- custom_js -->

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

<?php echo $__env->yieldContent('custom_js'); ?><?php /**PATH C:\xampp\htdocs\tos\resources\views/layouts/partials/scripts.blade.php ENDPATH**/ ?>