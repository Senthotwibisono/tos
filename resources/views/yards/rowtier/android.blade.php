@extends('partial.android')
@section('custom_style')
<style>
 @media (max-width: 768px) {
        /* Adjust styles for smaller screens, e.g., smartphones */
        .col-md {
            flex: 0 0 calc(100% - 20px); /* Full width on smaller screens */
            margin: 10px;
        }
    }
</style>
@endsection
@section('content')
<div class="page-title">
	<div class="container py-2">
		<div class="form-inline gap-2 d-flex align-items-center">
			<label for="block_no" class="col-auto col-form-label">Block </label>
			<div class="col-sm-1">
				<select class="form-control select-single" id="block_no" name="block_no"> 
                    <option value="" disabeled selected values>Pilih Satu!</option>
                    @foreach($lt_block as $lw_block)
					<option value="{{$lw_block->yard_block}}">{{$lw_block->yard_block}}</option>
					@endforeach
				</select> 
			</div>
			<label for="slot_no" class="col-auto col-form-label">Slot</label>
			<div class="col-sm-1">
				<select class="form-control" id="slot_no" name="slot_no">
                    <option value="" disabled selected values>Pilih Satu!</option>
				</select>
			</div>
			<div class="col-sm-2"></div>
			<div class="d-grid gap-2 d-md-flex justify-content-md-end">
				<button class="btn btn-primary me-md-2" type="button">Cetak per block</button>
				<button class="btn btn-primary" type="button">Cetak per vessel</button>
			</div>
		</div>
	</div>
</div>
<div class="page-body" >
    {!! $content !!}
</div>


@endsection
@section('custom_js')
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
                url: '{{ route('rowtier.get_rowtierAndroid') }}',
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
                url: '{{ route('rowtier.get_rowtierAndroid') }}',
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


<script>
 $(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
   

    $(document).on('click', '.cont', function() {
        let id = $(this).data('id');
        $.ajax({
            type: 'GET',
            url: '/yard/viewCont-' + id,
            cache: false,
            data: {
                container_key: id
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                $('#detailCont').modal('show');
                $('#detailCont #contNo').val(response.data.container_no);
                $('#detailCont #Ves').val(response.data.ves_name);
                $('#detailCont #iso').val(response.data.iso_code);
                $('#detailCont #size').val(response.data.ctr_size);
                $('#detailCont #status').val(response.data.ctr_status);
                $('#detailCont #type').val(response.data.ctr_type);
                $('#detailCont #gross').val(response.data.gross);
                $('#detailCont #commo').val(response.data.commodity_name);
               
            },
            error: function(data) {
                console.log('error:', data);
            }
        });
    });
});
</script>

<script>
    $(function(){
        $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

        $(function(){
            $('#block_no'). on('change', function(){
                let block_no = $('#block_no').val();

                $.ajax({
                    type: 'POST',
                    url: '/getSlot',
                    data : {block_no : block_no},
                    cache: false,
                    
                    success: function(msg){
                        $('#slot_no').html(msg);
                   
                    },
                    error: function(data){
                        console.log('error:',data)
                    },
                })
            })
        })
    });
</script>
@endsection