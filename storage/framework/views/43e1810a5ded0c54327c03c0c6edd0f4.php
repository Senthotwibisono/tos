<?php $__env->startSection('custom_style'); ?>

<link rel="stylesheet" href="<?php echo e(asset('/vendor/datatables.net/table-datatable-jquery.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('/vendor/datatables.net/dataTables.bootstrap5.min.css')); ?>">
<?php $__env->stopSection(); ?>
 <?php $__env->startSection('content'); ?>
<section class="section">
	<div class="row">
		<div class="col-12 col-lg-9">
			<div class="card">
				<div class="accordion" id="accordionExample">
					<div class="accordion-item">
						<h2 class="accordion-header" id="headingOne">
							<button class="accordion-button" type="button"
								data-bs-toggle="collapse" data-bs-target="#collapseOne"
								aria-expanded="true" aria-controls="collapseOne">Container
								Detail</button>
						</h2>
						<div id="collapseOne" class="accordion-collapse collapse show"
							aria-labelledby="headingOne" data-bs-parent="#accordionExample">
							<div class="accordion-body" id="lw_cont"></div>
						</div>
					</div>
					<div class="accordion-item">
						<h2 class="accordion-header" id="headingTwo">
							<button class="accordion-button collapsed" type="button"
								data-bs-toggle="collapse" data-bs-target="#collapseTwo"
								aria-expanded="false" aria-controls="collapseTwo">History</button>
						</h2>
						<div id="collapseTwo" class="accordion-collapse collapse"
							aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
							<div class="accordion-body">
								<div class="card">
									<div class="table-responsive col-12">
										<table class="table" id="tblhist"
											data-url="<?php echo e(route('reports.hist.get_cont_hist')); ?>">
											<thead>
												<tr>
													<th>Operation</th>
													<th>Status</th>
													<th>Internal Stat</th>
												</tr>
											</thead>
											<tbody>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="accordion-item">
						<h2 class="accordion-header" id="headingThree">
							<button class="accordion-button collapsed" type="button"
								data-bs-toggle="collapse" data-bs-target="#collapseThree"
								aria-expanded="false" aria-controls="collapseThree">Job Order</button>
						</h2>
						<div id="collapseThree" class="accordion-collapse collapse"
							aria-labelledby="headingThree" data-bs-parent="#accordionExample">
							<div class="accordion-body">
								<div class="card">
									<div class="table-responsive col-12">
										<table class="table" id="tbljob"
											data-url="<?php echo e(route('reports.hist.get_cont_job')); ?>">
											<thead>
												<tr>
													<th>CTR_STATUS</th>
													<th>CTPS_YN</th>
													<th>COMMODITY_CODE</th>
													<th>COMMODITY_NAME</th>
													<th>STACK_DATE</th>
												</tr>
											</thead>
											<tbody>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-lg-3">
			<div class="card">
				<div class="card-body py-4 px-4">
					<div class="d-flex align-items-center">
						<div class="name ms-3">
							<h3 class="font-bold" id="lv_cont"></h3>
							<h5 class="text-muted mb-0" id="lv_ves"></h5>
						</div>
					</div>
				</div>
			</div>
			<div class="card border-0 shadow rounded">
				<div class="card-content pb-4">
					<!-- 					<div class="table-responsive"> -->
					<table class="table table-hover table-striped table-sm"
						id="tblcont">
						<thead>
							<tr>
								<th>Container</th>
								<th>Vessel</th>
								<th>Sts</th>
							</tr>
						</thead>
						<tbody>
							<?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
							<tr>
								<td>
									<div class="d-flex align-items-center">
										<a class="cont_no text-decoration-none"
											data-url="<?php echo e(route('reports.hist.get_cont')); ?>"
											data-param="id=<?php echo e($item->container_key); ?>"
											href="#?id=<?php echo e($item->container_key); ?>" id=""> <i
											class="bi bi-file-post-fill"></i>
										</a> <?php echo e($item->container_no); ?>

									</div>
								</td>
								<td><?php echo e($item->ves_id); ?></td>
								<td><?php echo e($item->ctr_intern_status); ?></td>
							</tr>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
							<tr>
								<td class="text-center text-mute" colspan="4">Data is not
									available</td>
							</tr>
							<?php endif; ?>

						</tbody>
					</table>
					<!-- 					</div> -->
				</div>
			</div>

		</div>
	</div>
</section>
<?php $__env->stopSection(); ?> <?php $__env->startSection('custom_js'); ?>
<!-- Page level plugins -->
<!-- <script src="vendor/chart.js/Chart.min.js"></script> -->

<!-- Page level custom scripts -->
<!-- <script src="js/demo/chart-area-demo.js"></script> -->
<!-- <script src="js/demo/chart-pie-demo.js"></script> -->
<script src="<?php echo e(asset('dist/assets/extensions/jquery/jquery.min.js')); ?>"></script>

<script src="<?php echo e(asset('/vendor/datatables.net/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('/vendor/datatables.net/dataTables.bootstrap5.min.js')); ?>"></script>

<script src="<?php echo e(asset('dist/assets/js/pages/new/datatables.js')); ?>"></script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\tos\resources\views/reports/hist/index.blade.php ENDPATH**/ ?>