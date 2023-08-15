
<section class="section">
	<?php for($i = 7; $i >= 0; $i--): ?>
	<div
		class="row gx-1 d-flex justify-content-center align-items-center h-100">
		<?php for($j = 0; $j < 8; $j++): ?>
		<div class="col-md">
			<div class="card mb-1" style="color: #4B515D; border-radius: 15px;">
				<div class="card-body p-2">

					<div class="d-flex">
						<h6 class="flex-grow-1" id="<?php echo e($i.$j); ?>fr" name="<?php echo e($i.$j); ?>fr"><?php echo e(isset($lt_xy[$i][$j]) ? $lt_xy[$i][$j]->fr: ''); ?></h6>
						<h6 id="<?php echo e($i.$j); ?>to" name="<?php echo e($i.$j); ?>to"><?php echo e(isset($lt_xy[$i][$j]) ? $lt_xy[$i][$j]->to: ''); ?></h6>
					</div>

					<div class="d-flex flex-column text-center mt-2 mb-2">
						<h6 class=" mb-0 font-weight-bold" style="color: #1C2331;"><?php echo e(isset($lt_xy[$i][$j]) ? $lt_xy[$i][$j]->cnt: ''); ?></h6>
					</div>

					<div class="d-flex align-items-center">
						<div class="flex-grow-1" style="font-size: 1rem;">
							<div>
								<i class="fas fa-pallet fa-fw" style="color: #868B94;"></i> <span
									class="ms-1"><?php echo e(isset($lt_xy[$i][$j]) ? $lt_xy[$i][$j]->typ: ''); ?></span>
							</div>
							<div>
								<i class="fas fa-box fa-fw" style="color: #868B94;"></i> <span
									class="ms-1"><?php echo e(isset($lt_xy[$i][$j]) ? $lt_xy[$i][$j]->qty: ''); ?></span>
							</div>
						</div>
						<div>
							<div>
								<i class="fas fa-clipboard-list fa-fw" style="color: #868B94;"></i> <span
									class="ms-1"><strong><?php echo e(isset($lt_xy[$i][$j]) ? $lt_xy[$i][$j]->iso: ''); ?></strong></span>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
		<?php endfor; ?>
	</div>
	<?php endfor; ?>
</section><?php /**PATH C:\xampp\htdocs\tos\resources\views/yards/rowtier/rowtier.blade.php ENDPATH**/ ?>