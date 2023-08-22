 <?php $__env->startSection('content'); ?>
<div class="page-title">
	<div class="container py-2">
		<div class="form-inline gap-2 d-flex align-items-center">
			<label for="block_no" class="col-auto col-form-label">Block </label>
			<div class="col-sm-1">
				<select class="form-control select-single" id="block_no"
					name="block_no"> <?php $__currentLoopData = $lt_block; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lw_block): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<option value="<?php echo e($lw_block->yard_block); ?>"><?php echo e($lw_block->yard_block); ?></option>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</select> <input type="hidden" id="block_key" name="block_key"
					class="form-control">
			</div>
			<label for="slot_no" class="col-auto col-form-label">Slot</label>
			<div class="col-sm-1">
				<select class="form-control select-single" id="slot_no"
					name="slot_no"> <?php $__currentLoopData = $lt_slot; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lw_slot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<option value="<?php echo e($lw_slot->yard_slot); ?>"><?php echo e($lw_slot->yard_slot); ?></option>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</select> <input type="hidden" id="slot_key" name="slot_key"
					class="form-control">
			</div>
			<div class="col-sm-2"></div>
			<div class="d-grid gap-2 d-md-flex justify-content-md-end">
				<button class="btn btn-primary me-md-2" type="button">Cetak per block</button>
				<button class="btn btn-primary" type="button">Cetak per vessel</button>
			</div>
		</div>
	</div>
</div>
<div class="page-body"><?php echo $content; ?></div>
<?php $__env->stopSection(); ?> <?php $__env->startSection('custom_js'); ?>
<script src="<?php echo e(asset('vendor/components/jquery/jquery.min.js')); ?>"></script>
<script src="<?php echo e(asset('select2/dist/js/select2.full.min.js')); ?>"></script>
<script
	src="<?php echo e(asset('dist/assets/extensions/sweetalert2/sweetalert2.min.js')); ?>"></script>
<script src="<?php echo e(asset('dist/assets/js/pages/sweetalert2.js')); ?>"></script>
<script>
$(document).ready(function() {
	$(".select-single").select2();
});

$(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).ready(function() {
    	$('#block_no').on('change', function() {
            let block_no = $(this).val();
            let slot_no  = $('#slot_no').val();
            $.ajax({
                type: 'POST',
                url: '<?php echo e(route('rowtier.get_rowtier')); ?>',
                data: { 
                   	block_no : block_no,
                   	slot_no  : slot_no 
                },
                success: function(response) {
                    $('.page-body').html(response);
                    $('.page-body').show();
                },
                error: function(data) {
                    console.log('error:', data);
                },
            });
        });
	});
    $(document).ready(function() {
    	$('#slot_no').on('change', function() {
    		let block_no = $('#block_no').val();
            let slot_no  = $(this).val();
            $.ajax({
                type: 'POST',
                url: '<?php echo e(route('rowtier.get_rowtier')); ?>',
                data: { 
                   	block_no : block_no,
                   	slot_no  : slot_no 
                },
                success: function(response) {
                    $('.page-body').html(response);
                    $('.page-body').show();
                },
                error: function(data) {
                    console.log('error:', data);
                },
            });
        });
	});
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('partial.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\tos\resources\views/yards/rowtier/index.blade.php ENDPATH**/ ?>