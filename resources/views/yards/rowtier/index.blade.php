@extends('partial.main') @section('content')
<div class="page-title">
	<div class="container py-2">
		<div class="form-inline gap-2 d-flex align-items-center">
			<label for="block_no" class="col-auto col-form-label">Block </label>
			<div class="col-sm-1">
				<select class="form-control select-single" id="block_no"
					name="block_no"> @foreach($lt_block as $lw_block)
					<option value="{{$lw_block->yard_block}}">{{$lw_block->yard_block}}</option>
					@endforeach
				</select> <input type="hidden" id="block_key" name="block_key"
					class="form-control">
			</div>
			<label for="slot_no" class="col-auto col-form-label">Slot</label>
			<div class="col-sm-1">
				<select class="form-control select-single" id="slot_no"
					name="slot_no"> @foreach($lt_slot as $lw_slot)
					<option value="{{$lw_slot->yard_slot}}">{{$lw_slot->yard_slot}}</option>
					@endforeach
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
<div class="page-body">{!! $content !!}</div>
@endsection @section('custom_js')
<script src="{{asset('vendor/components/jquery/jquery.min.js')}}"></script>
<script src="{{asset('select2/dist/js/select2.full.min.js')}}"></script>
<script
	src="{{asset('dist/assets/extensions/sweetalert2/sweetalert2.min.js')}}"></script>
<script src="{{asset('dist/assets/js/pages/sweetalert2.js')}}"></script>
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
                url: '{{ route('rowtier.get_rowtier') }}',
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
                url: '{{ route('rowtier.get_rowtier') }}',
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
@endsection
