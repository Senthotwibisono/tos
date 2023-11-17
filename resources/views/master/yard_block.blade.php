@extends('partial.main')

@section('content')
  
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Yard Block Master</h3>
              
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
  
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
               
            
                <button class="btn btn-info btn-sm" id="btn-block"><i class="fa fa-file"></i> Create Yard Block</button>
            
             </div>
            <div class="card-body">
                <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
                    <thead>
                        <tr>
                            <th>Yard Block</th>
                            <th>Yard Slot</th>
                            <th>Yard Row</th>    
                            <th>Yard Tier</th>  
                            <th>Jmlh Container</th>
                            <th>Tambah Slot</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($blocks as $yard)
                        <tr>
                            <td>{{$yard->yard_block}}</td>                           
                            <td>{{$yslot[$yard->yard_block]}}</td>                           
                            <td>{{$yrow[$yard->yard_block]}}</td>                           
                            <td>{{$ytier[$yard->yard_block]}}</td>                           
                            <td>{{$cont[$yard->yard_block]}}</td>            
                            <td><button class="btn btn-outline-warning addSlot" data-id="{{$yard->yard_block}}">Tambah Slot</button></td>               
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </section>
</div>

<div id="create-block-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Please Insert All Field</h4>
            </div>
            <form id="create-block-form" class="form-horizontal" action="/master/block_store" method="POST" enctype="multipart/form-data">
                @CSRF
                
                <div class="modal-body"> 
                    <div class="row">
                        <div class="col-md-12">
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Yard Block</label>
                                <div class="col-sm-6">
                                    <input type="text"  class="form-control" name="yard_block" id="yard_block"  required />
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Max Slot</label>
                                <div class="col-sm-6">
                                    <input type="text"  class="form-control" name="yard_slot" id="yard_slot"  required />
                                </div>
                            </div>     
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Max Row</label>
                                <div class="col-sm-6">
                                    <input type="text"  class="form-control" name="yard_row" id="yard_row"  required />
                                </div>
                            </div>     
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Max Tier</label>
                                <div class="col-sm-6">
                                    <input type="text"  class="form-control" name="yard_tier" id="yard_tier"  required />
                                </div>
                            </div>     
                           
                            <input type="hidden" id="-column" class="form-control" value ="{{ Auth::user()->name }}" placeholder="{{ Auth::user()->name }}" name="user_id" required readonly>


                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                
                  <button type="submit" class="btn btn-primary">Create Block</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div id="edit-port-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Please Insert All Field</h4>
            </div>
            <form id="edit-block-form" class="form-horizontal" action="/master/block_edit_store" method="POST" enctype="multipart/form-data">
             @csrf
            
                
                <div class="modal-body"> 
                    <div class="row">
                        <div class="col-md-12">
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Port</label>
                                <div class="col-sm-6">
                                    <input type="text"  class="form-control" name="port" id="port"  readonly />
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">UN Country</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="un_country" id="un_country" readonly />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">UN Port</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="un_port" id="un_port" readonly/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Country Name </label>
                                <div class="col-sm-6">
                                    <textarea class="form-control" name="country_name" id="country_name"></textarea>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Description </label>
                                <div class="col-sm-6">
                                    <textarea class="form-control" name="descr" id="descr"></textarea>
                                </div>
                            </div>

                            <input type="hidden" id="-column" class="form-control" value ="{{ Auth::user()->id }}" placeholder="{{ Auth::user()->name }}" name="user_id" required readonly>


                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                
                  <button type="submit" class="btn btn-primary">Update Port</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade text-left w-100" id="slot" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h4 class="modal-title" id="myModalLabel16">Detail Container</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 border-right">
                    <div class="row" style="border-right: 2px solid blue;">
                    <div class="form-group">
                                <label class="col-sm-3 control-label">Yard Block</label>
                                <div class="col-sm-6">
                                    <input type="text"  class="form-control" name="yard_block" id="yard_blockAddSlot"  readonly />
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Max Slot</label>
                                <div class="col-sm-6">
                                    <input type="number"  class="form-control" name="yard_slot" id="yard_slotAddSlot"  required />
                                </div>
                            </div>     
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Max Row</label>
                                <div class="col-sm-6">
                                    <input type="number"  class="form-control" name="yard_row" id="yard_rowAddSlot"  required />
                                </div>
                            </div>     
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Max Tier</label>
                                <div class="col-sm-6">
                                    <input type="number"  class="form-control" name="yard_tier" id="yard_tierAddSlot"  required />
                                    <input type="hidden" id="user_idAddSlot" class="form-control" value ="{{ Auth::user()->name }}" placeholder="{{ Auth::user()->name }}" name="user_idAddSlot" required readonly>
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
                <button type="button" class="btn btn-primary ml-1 createSlot" data-bs-dismiss="modal">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Accept</span>
                </button>
            </div>
        </div>
    </div>
</div>




@endsection


@section('custom_js')



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>

<script>

    
 $(document).ready(function()
    {
        $('#btn-block').on("click", function(){
        
            $('#create-block-modal').modal('show');
        
        });
       
        $('#yard_block').blur(function() {

            //alert("SAASASSASsa");
            var id = $("#yard_block").val();
            $.ajax({
               type: 'GET',
               url: '/master/edit_block',
               cache: false,
               data : {yard_block : id},
                dataType : 'json',
     
           success: function(response) {
            //alert(response.message);  
            if(response.message=='Data Tidak Ditemukan')
            {
              
            }else{alert('Data sudah pernah di dimasukkan/duplicate data');
                $("#yard_block").val('');
            }
          }
        });
        
        });    
        
 });

 $(document).on('click', '.edit-modal', function() {
   let id = $(this).data('id');
      $.ajax({
               type: 'GET',
               url: '/master/edit_port',
               cache: false,
               data : {port : id},
                dataType : 'json',
     
      success: function(response) {
       
         $('#edit-port-modal').modal('show');
         $("#edit-port-modal #port").val(response.data.port);
         $("#edit-port-modal #un_port").val(response.data.un_port);
         $("#edit-port-modal #un_country").val(response.data.un_country);
         $("#edit-port-modal #country_name").val(response.data.country_name);
         $("#edit-port-modal #descr").val(response.data.descr);

         // fill form fields with record data
     },
                error: function(xhr, status, error) {
                    // Code untuk menangani error jika terjadi

                    alert(response);
                }

    

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

    $(document).on('click', '.addSlot', function() {
        let id = $(this).data('id');
        $.ajax({
            type: 'GET',
            url: '/master/block/addSlot-' + id,
            cache: false,
            data: {
                YARD_BLOCK: id
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                $('#slot').modal('show');
                $('#slot #yard_blockAddSlot').val(response.block);
            },
            error: function(data) {
                console.log('error:', data);
            }
        });
    });
});
</script>

<script>
   $(document).on('click', '.createSlot', function(e) {
        e.preventDefault();
        var yard_block = $('#yard_blockAddSlot').val();
        var yard_slot = $('#yard_slotAddSlot').val();
        var yard_row = $('#yard_rowAddSlot').val();
        var yard_tier = $('#yard_tierAddSlot').val();
        var user_id = $('#user_idAddSlot').val();
       
        var data = {
          'yard_block' : $('#yard_blockAddSlot').val(),
          'yard_slot' : $('#yard_slotAddSlot').val(),
          'yard_row' : $('#yard_rowAddSlot').val(),
          'yard_tier' : $('#yard_tierAddSlot').val(),
          'user_id' : $('#user_idAddSlot').val(),
          

        }
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        Swal.fire({
          title: 'Are you Sure?',
          text: "?",
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
              url: '/master/block/createSlot',
              data: data,
              cache: false,
              dataType: 'json',
              success: function(response) {
                console.log(response);
                if (response.success) {
                  Swal.fire('Saved!', '', 'success')
                  .then(() => {
                            // Memuat ulang halaman setelah berhasil menyimpan data
                            window.location.reload();
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
</script>

@endsection