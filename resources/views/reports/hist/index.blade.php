@extends('layouts.base')
@section('custom_style')
{{--<link rel="stylesheet" href="{{ asset('dist/assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">--}}
<link rel="stylesheet" href="{{ asset('/vendor/datatables.net/table-datatable-jquery.css') }}">
<link rel="stylesheet" href="{{ asset('/vendor/datatables.net/dataTables.bootstrap5.min.css') }}">
@endsection
@section('content')
<style>
	/* Custom styling for the smaller table */
	.smaller-table {
		font-size: 0.85rem;
	}

	/* Style the table rows */
	.smaller-table tbody tr:hover {
		background-color: #e9ecef;
	}
</style>
<section class="section">
	<div class="row">
		<div class="col-12 col-md-8">
			<div class="card">
				<div class="accordion" id="accordionExample">
					<div class="accordion-item">
						<h2 class="accordion-header" id="headingOne">
							<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Container Detail</button>
						</h2>
						<div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
							<div class="accordion-body" id="lw_cont"></div>
						</div>
					</div>
					<div class="accordion-item">
						<h2 class="accordion-header" id="headingTwo">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">History</button>
						</h2>
						<div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
							<div class="accordion-body">
								<div class="card">
									<div class="table-responsive col-12">
										<table class="table" id="tblhist" data-url="{{ route('reports.hist.get_cont_hist') }}">
											<thead>
												<tr>
													<th>Operation</th>
													<th>Status</th>
													<th>Internal Stat</th>
													<th>Yard Block</th>
													<th>Yard Slot</th>
													<th>Yard Row</th>
													<th>Yard Tier</th>
													<th>User</th>
													<th>Update</th>
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
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">Job Order</button>
						</h2>
						<div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
							<div class="accordion-body">
								<div class="card">
									<div class="table-responsive col-12">
										<table class="table" id="tbljob" data-url="{{ route('reports.hist.get_cont_job') }}">
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
		<div class="col-6 col-md-4">
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
			</div>
	</div>
	<div class="row">
			
			<div class="col-12">
				<table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
					<thead>
						<tr>
							<th></th>
							<th>Container</th>
							<th>Last Update</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($items as $item)
						<tr>
							<td>
								<div class="d-flex align-items-center">
									<a class="cont_no text-decoration-none" data-url="{{ route('reports.hist.get_cont') }}" data-param="id={{ $item->container_key }}" href="#?id={{ $item->container_key }}" id=""> <i class="bi bi-file-post-fill"></i>
								</div>
							</td>
							<td>
								{{$item->container_no}}
							</td>
							<td>
								{{$item->update_at}}
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
	</div>
</section>


<!-- Add pagination links -->




@endsection
@section('custom_js')
<!-- Page level plugins -->
<!-- <script src="vendor/chart.js/Chart.min.js"></script> -->

<!-- Page level custom scripts -->
<!-- <script src="js/demo/chart-area-demo.js"></script> -->
<!-- <script src="js/demo/chart-pie-demo.js"></script> -->
<script src="{{ asset('dist/assets/extensions/jquery/jquery.min.js') }}"></script>
{{--<script src="{{ asset('dist/assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.js') }}"></script>--}}
<script src="{{ asset('/vendor/datatables.net/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('/vendor/datatables.net/dataTables.bootstrap5.min.js') }}"></script>
{{--<script src="https://cdn.datatables.net/v/bs5/dt-1.12.1/datatables.min.js"></script>--}}
<script src="{{ asset('dist/assets/js/pages/new/datatables.js') }}"></script>
<script src="{{asset('dist/assets/extensions/simple-datatables/umd/simple-datatables.js')}}"></script>
    <script src="{{asset('dist/assets/js/pages/simple-datatables.js')}}"></script>

<script>
	  $('[aria-label="&laquo; Previous"]').hide();
	  $('svg[fill="currentColor"][viewBox="0 0 20 20"]').hide();
// 	  var divToHide = $('div.hidden.sm\\:flex-1.sm\\:flex.sm\\:items-center.sm\\:justify-between');
// divToHide.hide();
$(document).ready(function() {
    var dataTable = $('#tblcont2').DataTable({
        pagingType: 'full_numbers',
        lengthMenu: [10, 25, 50, 100],
        language: {
            search: '_INPUT_',
            searchPlaceholder: 'Search...'
        }
    });

    $('#search').on('keyup', function() {
        var searchValue = this.value;

        
        dataTable.ajax.url(searchContHistUrl + '?search[value]=' + searchValue).load();
        dataTable.ajax.url(searchContJobUrl + '?search[value]=' + searchValue).load();
    });
});

</script>
@endsection