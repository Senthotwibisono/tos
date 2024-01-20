
<section class="section">
	@for ($i = $tier - 1; $i >= 0; $i--)
	<div
		class="row gx-1 d-flex justify-content-center align-items-center h-100">
		@for ($j = 0; $j < $row; $j++)
		<div class="col-md">
			<div class="card mb-1" style="color: #4B515D; border-radius: 15px;">
				<div class="card-body p-2">

					<div class="d-flex">
						<h6 class="flex-grow-1" id="{{$i.$j}}fr" name="{{$i.$j}}fr">{{ isset($lt_xy[$i][$j]) ? $lt_xy[$i][$j]->fr: '' }}</h6>
						<h6 id="{{$i.$j}}to" name="{{$i.$j}}to">{{ isset($lt_xy[$i][$j]) ? $lt_xy[$i][$j]->to: '' }}</h6>
					</div>
					<!-- Container -->
					<div class="d-flex flex-column text-center mt-2 mb-2">
					<a href="javascript:void(0)"class="btn icon icon-left btn-outline-info cont" data-id="{{ isset($lt_xy[$i][$j]) ? $lt_xy[$i][$j]->key: '' }}"><h6 class=" mb-0 font-weight-bold" style="color: red;">{{ isset($lt_xy[$i][$j]) ? $lt_xy[$i][$j]->cnt: '' }}</h6></a>	
					<p style="color: white;">Row: {{ $row}}, Tier: {{$tier}}</p>
	
				</div>

					<div class="d-flex align-items-center" >
						<div class="flex-grow-1" style="font-size: 1rem;">
							<div>
								<i class="fas fa-pallet fa-fw" style="color: #868B94;"></i> <span
									class="ms-1">{{ isset($lt_xy[$i][$j]) ? $lt_xy[$i][$j]->typ: '' }}</span>
							</div>
							<div>
								<i class="fas fa-box fa-fw" style="color: #868B94;"></i> <span
									class="ms-1">{{ isset($lt_xy[$i][$j]) ? $lt_xy[$i][$j]->qty: '' }}</span>
							</div>
						</div>
						<div>
							<div>
								<i class="fas fa-clipboard-list fa-fw" style="color: #868B94;"></i> <span
									class="ms-1"><strong>{{ isset($lt_xy[$i][$j]) ? $lt_xy[$i][$j]->iso: '' }}</strong></span>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
		@endfor
	</div>
	@endfor
</section>


<div class="modal fade text-left w-100" id="detailCont" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title" id="myModalLabel16">Detail Container</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
				<div class="row">
					<div class="col-6">
						<div class="row">
							<div class="col-4">
								<label for="">Container No</label>
							</div>
							<div class="col-1">:</div>
							<div class="col-6">
								<input type="text" class="form-control" id="contNo" readonly>
							</div>
						</div>
					</div>
					<div class="col-6">
						<div class="row">
							<div class="col-4">
								<label for="">Vessel Name</label>
							</div>
							<div class="col-1">:</div>
							<div class="col-6">
								<input type="text" class="form-control" id="Ves" readonly>
							</div>
						</div>
					</div>
				</div>
				<br>
				<div class="row">
				<div class="col-3">
						<div class="row">
							<div class="col-4">
								<label for="">ISO Code</label>
							</div>
							<div class="col-1">:</div>
							<div class="col-6">
								<input type="text" class="form-control" id="iso" readonly>
							</div>
						</div>
					</div>
					<div class="col-3">
						<div class="row">
							<div class="col-4">
								<label for="">CTR Size</label>
							</div>
							<div class="col-1">:</div>
							<div class="col-6">
								<input type="text" class="form-control" id="size" readonly>
							</div>
						</div>
					</div>
					<div class="col-3">
						<div class="row">
							<div class="col-4">
								<label for="">CTR Status</label>
							</div>
							<div class="col-1">:</div>
							<div class="col-6">
								<input type="text" class="form-control" id="status" readonly>
							</div>
						</div>
					</div>
					<div class="col-3">
						<div class="row">
							<div class="col-4">
								<label for="">CTR Type</label>
							</div>
							<div class="col-1">:</div>
							<div class="col-6">
								<input type="text" class="form-control" id="type" readonly>
							</div>
						</div>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-6">
						<div class="row">
							<div class="col-4">
								<label for="">Gross</label>
							</div>
							<div class="col-1">:</div>
							<div class="col-6">
								<input type="text" class="form-control" id="gross" readonly>
							</div>
						</div>
					</div>
					<div class="col-6">
						<div class="row">
							<div class="col-4">
								<label for="">Commodity</label>
							</div>
							<div class="col-1">:</div>
							<div class="col-6">
								<input type="text" class="form-control" id="commo" readonly>
							</div>
						</div>
					</div>
				</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Close</span>
                </button>
            </div>
        </div>
    </div>
</div>