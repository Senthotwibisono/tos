@extends('partial.android')
@section('content')
<div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Stripping</h3>
                </div>

                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active" aria-current="page">Stripping</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <button class="btn icon icon-left btn-success" data-bs-toggle="modal"data-bs-target="#success">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg> Confirmed</button>
                </div>
                <div class="card-body">
                    <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
                        <thead>
                            <tr>
                                <th>Container No</th>
                                <th>Type</th>
                                <th>Blok</th>
                                <th>Slot</th>
                                <th>Row</th>
                                <th>Tier</th>
                                <th>Placemented At</th>
                            </tr>
                        </thead>
                        <tbody>          
                        @foreach($formattedData as $d)
                        <tr>
                            <td>{{$d['container_no']}}</td>
                            <td>{{$d['ctr_type']}}</td>
                            <td>{{$d['yard_block']}}</td>
                            <td>{{$d['yard_slot']}}</td>
                            <td>{{$d['yard_row']}}</td>
                            <td>{{$d['yard_tier']}}</td>
                            <td>{{$d['update_time']}}</td>
                        </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal Update Status -->
            <div class="modal fade text-left" id="success"  role="dialog"aria-labelledby="myModalLabel110" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-success">
                            <h5 class="modal-title white" id="myModalLabel110">Stripping</h5>
                            <button type="button" class="close" data-bs-dismiss="modal"aria-label="Close"><i data-feather="x"></i></button>
                        </div>
                        <div class="modal-body">
                            <!-- form -->
                                <div class="form-body" id="place_cont">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="first-name-vertical">Choose Container Number</label>   
                                                <select class="choices form-select"  id="key" name="container_key" required>
                                                  <option value="">Select Container</option>
                                                  @foreach ($containerKeys as $container)
        <option value="{{ $container['value'] }}">{{ $container['text'] }}</option>
    @endforeach
                                                </select>
                                                <input type="hidden" id="container_no" class="form-control" name="container_no">                                             
                                            </div>
                                            {{ csrf_field()}}
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="first-name-vertical">Type</label>   
                                                <input type="text"  id="tipe" class="form-control" name="ctr_type"  disabled>                                               
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="first-name-vertical">Invoice</label>   
                                                <input type="text"  id="invoice" class="form-control" name="invoice_no"  disabled>                                               
                                            </div>
                                        </div>

                                        <h4>Current Yard</h4>
                                        <div class="col-12" style="border:1px solid blue;">
                                            <div class="row">
                                                
                                                <div class="col-3">
                                                    <div class="form-group">
                                                        <label for="first-name-vertical">Blok</label>    
                                                        <input type="text"  id="oldblock" class="form-control" name="yard_block"  disabled>                                                
                                                    </div>
                                                </div>
                                                <div class="col-3">
                                                        <div class="form-group">
                                                            <label for="first-name-vertical">Slot</label>   
                                                            <input type="text"  id="oldslot" class="form-control" name="yard_slot"  disabled>                                                
                                                        </div>
                                                    </div>
                                                   <div class="col-3">
                                                        <div class="form-group">
                                                            <label for="first-name-vertical">Row</label>   
                                                            <input type="text"  id="oldrow" class="form-control" name="yard_row"  disabled>                                                
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <label for="first-name-vertical">Tier</label>   
                                                            <input type="text"  id="oldtier" class="form-control" name="yard_tier"  disabled>                                               
                                                        </div>
                                                    </div>
                                                    
                                            </div>
                                        </div>
                                        <h4>Stripping Yard</h4>
                                        <div class="col-12" style="border:1px solid blue;">
                                            <div class="row">
                                                
                                                <div class="col-3">
                                                    <div class="form-group">
                                                        <label for="first-name-vertical">Blok</label>    
                                                        <select class="choices form-select"  id="block" name="yard_block" required>
                                                          <option value="">-</option>
                                                          @foreach($yard_block as $block)
                                                            <option value="{{$block}}">{{$block}}</option>
                                                            @endforeach
                                                        </select>                                              
                                                    </div>
                                                </div>
                                                <div class="col-3">
                                                        <div class="form-group">
                                                            <label for="first-name-vertical">Slot</label>   
                                                            <select class="choices form-select" id="slot" name="yard_slot" required>
                                                          <option value="">-</option>
                                                          @foreach($yard_slot as $slot)
                                                            <option value="{{$slot}}">{{$slot}}</option>
                                                            @endforeach
                                                        </select>                                                
                                                        </div>
                                                    </div>
                                                   <div class="col-3">
                                                        <div class="form-group">
                                                            <label for="first-name-vertical">Row</label>   
                                                            <select class="choices form-select" id="row" name="yard_row" required>
                                                          <option value="">-</option>
                                                          @foreach($yard_row as $row)
                                                            <option value="{{$row}}">{{$row}}</option>
                                                            @endforeach
                                                        </select>                                                
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <label for="first-name-vertical">Tier</label>   
                                                            <select class="choices form-select" id="tier" name="yard_tier" required>
                                                          <option value="">-</option>
                                                          @foreach($yard_tier as $tier)
                                                            <option value="{{$tier}}">{{$tier}}</option>
                                                            @endforeach
                                                        </select>                                               
                                                        </div>
                                                    </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="first-name-vertical">Planner Place</label>   
                                                <input type="text"  id="user" class="form-control" value="{{ Auth::user()->name }}" name="user_id" readonly>                                               
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal"> <i class="bx bx-x d-block d-sm-none"></i><span class="d-none d-sm-block">Close</span></button>
                            <button type="submit" class="btn btn-success ml-1 update_status"><i class="bx bx-check d-block d-sm-none"></i><span class="d-none d-sm-block">Confirm</span></button>
                        </div>
                    </div>
                </div>
            </div>
@endsection
@section('custom_js')
<script src="{{ asset('vendor/components/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{asset('dist/assets/extensions/sweetalert2/sweetalert2.min.js')}}"></script>    
<script src="{{asset('dist/assets/js/pages/sweetalert2.js')}}"></script>

<script>
// In your Javascript (external .js resource or <script> tag)
$(document).ready(function() {
    $('.container').select2({
        dropdownParent:'#success',
    });
    $('.block').select2({
        dropdownParent:'#success',
    });
    $('.slot').select2({
        dropdownParent:'#success',
    });
    $('.yard_row').select2({
        dropdownParent:'#success',
    });
    $('.tier').select2({
        dropdownParent:'#success',
    });
});
$(document).on('click', '.update_status', function(e){
    e.preventDefault();
    var container_key = $('#key').val();
    var container_no= $('#container_no').val();
    var yard_block= $('#block').val();
    var yard_slot= $('#slot').val();
    var yard_raw= $('#raw').val();
    var yard_tier= $('#tier').val();
    var data = {
       'container_key' : $('#key').val(),
       'container_no': $('#container_no').val(),
       'yard_block': $('#block').val(),
       'yard_slot': $('#slot').val(),
       'yard_row': $('#row').val(),
       'yard_tier': $('#tier').val(),
       'user_id': $('#user').val(),
        
    }
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
                      Swal.fire({
                      title: 'Are you Sure?',
                      text: "Container " +container_no+ " will be placed at Block " +yard_block+ " Slot " +yard_slot+ " Raw " +yard_raw+ " and Tier " +yard_tier,
                      icon: 'warning',
                      showDenyButton: false,
                      showCancelButton: true,
                      confirmButtonColor: '#3085d6',
                      confirmButtonText: 'Confirm',
                    }).then((result) => {
                      /* Read more about isConfirmed, isDenied below */
                      if (result.isConfirmed) {
                          
                          $.ajax({
                            type: 'POST',
                            url: '/stripping-place',
                            data: data,
                            cache: false,
                            dataType: 'json',
                            success: function(response) {
                                console.log(response);
                                if (response.success) {
                                    Swal.fire('Saved!', '', 'success')
                                    $('#place_cont').load(window.location.href + ' #place_cont', function() {
                                        $(document).ready(function() {
                                            $('.container').select2({
                                                dropdownParent: '#success',
                                            });
                                            $('.block').select2({
                                                dropdownParent: '#success',
                                            });
                                            $('.slot').select2({
                                                dropdownParent: '#success',
                                            });
                                            $('.yard_row').select2({
                                                dropdownParent: '#success',
                                            });
                                            $('.tier').select2({
                                                dropdownParent: '#success',
                                            });
                                            $(document).ready(function() {
                                                $('#key').on('change', function() {
                                                    let id = $(this).val();
                                                    $.ajax({
                                                        type: 'POST',
                                                        url: '/get-stripping',
                                                        data: { container_key : id },
                                                        success: function(response) {
                                                        
                                                                $('#container_no').val(response.container_no);
                                                                $('#tipe').val(response.tipe);
                                                                $('#invoice').val(response.invoice);
                                                                $('#oldblock').val(response.oldblock);
                                                                $('#oldslot').val(response.oldslot);
                                                                $('#oldrow').val(response.oldrow);
                                                                $('#oldtier').val(response.oldtier);
                                                            },
                                                        error: function(data) {
                                                            console.log('error:', data);
                                                        },
                                                                 });
                                                             });
                                                     });
                                        });
                                    
                                        $('#table1').load(window.location.href + ' #table1');
                                    });
                                } else {
                                    Swal.fire('Error', response.message, 'error');
                                }
                            },
                            error: function(response) {
                    var errors = response.responseJSON.errors;
                    if (errors) {
                        var errorMessage = '';
                        $.each(errors, function(key, value) {
                            errorMessage += value[0] + '<br>';
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            html: errorMessage,
                        });
                    } else {
                        console.log('error:', response);
                    }
                },
                        });
                        
                      } else if (result.isDenied) {
                        Swal.fire('Changes are not saved', '', 'info')                     
                      }
                      
                      
                    })
   
});


$(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function() {
            $('#key').on('change', function() {
                let id = $(this).val();
                $.ajax({
                    type: 'POST',
                    url: '/get-stripping',
                    data: { container_key : id },
                    success: function(response) {
                       
                            $('#container_no').val(response.container_no);
                            $('#tipe').val(response.tipe);
                            $('#invoice').val(response.invoice);
                            $('#oldblock').val(response.oldblock);
                            $('#oldslot').val(response.oldslot);
                            $('#oldrow').val(response.oldrow);
                            $('#oldtier').val(response.oldtier);
                        },
                    error: function(data) {
                        console.log('error:', data);
                    },
                });
            });
    });
    // $(function(){
    //         $('#block'). on('change', function(){
    //             let yard_block = $('#block').val();

    //             $.ajax({
    //                 type: 'POST',
    //             url: '/get-slot',
    //                 data : {yard_block : yard_block},
    //                 cache: false,
                    
    //                 success: function(msg){
    //                     $('#slot').html(msg);
                   
    //                 },
    //                 error: function(data){
    //                     console.log('error:',data)
    //                 },
    //             })               
    //         })
    //     })
});

</script>

@endsection