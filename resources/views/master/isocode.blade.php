@extends('partial.main')

@section('content')
  
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>ISO Code Master</h3>
                <p class="text-subtitle text-muted">A sortable, searchable, paginated table without dependencies thanks to simple-datatables</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">DataTable</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
               
            
                <button class="btn btn-info btn-sm" id="btn-isocode"><i class="fa fa-file"></i> Create Port</button>
            
             </div>
            <div class="card-body">
                <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
                    <thead>
                        <tr>
                            <th>ISO Code</th>
                            <th>Size</th>
                            <th>Type</th> 
                            <th>Weight</th>     
                            <th>Height</th>           
                            <th>Description</th>  
                            <th>User Name</th>
                            <th>Update Time</th>            
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($isocode as $iso)
                        <tr>
                            <td>{{$iso->iso_code}}</td>
                            <td>{{$iso->iso_size}}</td>
                            <td>{{$iso->iso_type}}</td>
                            <td>{{$iso->iso_weight}}</td>
                            <td>{{$iso->iso_height}}</td>
                            <td>{{$iso->iso_descr}}</td>
                            <td>{{$iso->user_id}}</td>
                            <td>{{$iso->update_time}}</td>
                          
                            <td>
                            <form action="/master/delete_isocode={{$iso->iso_code}}" method="POST">
                               @csrf
                                @method('DELETE')
                                <button type="submit" class="btn icon btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus record ini?')"> <i class="bi bi-x"></i></button>                             
                                <a href="javascript:void(0)" class="btn btn-primary edit-modal" data-id="{{ $iso->iso_code }}" ><i class="bi bi-pencil"></i></a>

                            </form>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </section>
</div>

<div id="create-isocode-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Please Insert All Field</h4>
            </div>
            <form id="create-port-form" class="form-horizontal" action="/master/isocode_store" method="POST" enctype="multipart/form-data">
                @CSRF
                
                <div class="modal-body"> 
                    <div class="row">
                        <div class="col-md-12">
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">ISO Code</label>
                                <div class="col-sm-6">
                                    <input type="text"  class="form-control" name="iso_code" id="iso_code"  required />
                                </div>
                            </div>                            
                            <div class="form-group">
                                        <label for="-id-column">Size</label>
                                        <select class="form-select" id="iso_size" name="iso_size" required>
                                            <option value=""></option>
                                            <option value="20">20</option>                                             
                                            <option value="40">40</option>
                                            <option value="45">45</option>
                                        </select>
                            </div>
                            <div class="form-group">
                                        <label for="-id-column">Type</label>
                                        <select class="form-select" id="iso_type" name="iso_type" required>
                                            <option value=""></option>
                                            <option value="DRY">DRY</option>                                             
                                            <option value="HQ">HQ</option>
                                            <option value="FLT">FLT</option>
                                            <option value="RFR">RFR</option>
                                            <option value="OVD">OVD</option>
                                            <option value="CSH">CSH</option>
                                            <option value="TNK">TNK</option>
                                            
                                        </select>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Weight</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="iso_weight" id="iso_weight" required/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Height</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="iso_height" id="iso_height" required/>
                                </div>
                            </div>

                            
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Description </label>
                                <div class="col-sm-6">
                                    <textarea class="form-control" name="descr" id="descr"></textarea>
                                </div>
                            </div>

                            <input type="hidden" id="-column" class="form-control" value ="{{ Auth::user()->name }}" placeholder="{{ Auth::user()->name }}" name="user_id" required readonly>


                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                
                  <button type="submit" class="btn btn-primary">Create ISO Code</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div id="edit-isocode-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Please Insert All Field</h4>
            </div>
            <form id="edit-isocode-form" class="form-horizontal" action="/master/isocode_edit_store" method="POST" enctype="multipart/form-data">
             @csrf
            
                
                <div class="modal-body"> 
                    <div class="row">
                        <div class="col-md-12">
                            
                        <div class="form-group">
                                <label class="col-sm-3 control-label">ISO Code</label>
                                <div class="col-sm-6">
                                    <input type="text"  class="form-control" name="iso_code" id="iso_code"  readonly />
                                </div>
                            </div>                            
                            <div class="form-group">
                                        <label for="-id-column">Size</label>
                                        <select class="form-select" id="iso_size" name="iso_size" required>
                                            <option value=""></option>
                                            <option value="20">20</option>                                             
                                            <option value="40">40</option>
                                            <option value="45">45</option>
                                        </select>
                            </div>
                            <div class="form-group">
                                        <label for="-id-column">Type</label>
                                        <select class="form-select" id="iso_type" name="iso_type" required>
                                            <option value=""></option>
                                            <option value="DRY">DRY</option>                                             
                                            <option value="HQ">HQ</option>
                                            <option value="FLT">FLT</option>
                                            <option value="RFR">RFR</option>
                                            <option value="OVD">OVD</option>
                                            <option value="CSH">CSH</option>
                                            <option value="TNK">TNK</option>
                                            
                                        </select>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Weight</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="iso_weight" id="iso_weight" required/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Height</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="iso_height" id="iso_height" required/>
                                </div>
                            </div>

                            
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Description </label>
                                <div class="col-sm-6">
                                    <textarea class="form-control" name="descr" id="descr"></textarea>
                                </div>
                            </div>


                            <input type="hidden" id="-column" class="form-control" value ="{{ Auth::user()->name }}" placeholder="{{ Auth::user()->name }}" name="user_id" required readonly>


                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                
                  <button type="submit" class="btn btn-primary">Update ISO Code</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->






@endsection


@section('custom_js')



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>

<script>

    
 $(document).ready(function()
    {
        $('#btn-isocode').on("click", function(){
        
            $('#create-isocode-modal').modal('show');
        
        });
       
        $('#iso_code').blur(function() {

            //alert("SAASASSASsa");
            var id = $("#iso_code").val();
            $.ajax({
               type: 'GET',
               url: '/master/edit_isocode',
               cache: false,
               data : {iso_code : id},
                dataType : 'json',
     
           success: function(response) {
            //alert(response.message);  
            if(response.message=='Data Tidak Ditemukan')
            {
             
            }else{alert('Data sudah pernah di dimasukkan/duplicate data');
                $("#iso_code").val('');
            }
          }
        });
        
        });    
        
 });

 $(document).on('click', '.edit-modal', function() {
   let id = $(this).data('id');
      $.ajax({
               type: 'GET',
               url: '/master/edit_isocode',
               cache: false,
               data : {iso_code : id},
                dataType : 'json',
     
      success: function(response) {
       
         $('#edit-isocode-modal').modal('show');
         $("#edit-isocode-modal #iso_code").val(response.data.iso_code);
         $("#edit-isocode-modal #iso_size").val(response.data.iso_size);
         $("#edit-isocode-modal #iso_type").val(response.data.iso_type);
         $("#edit-isocode-modal #iso_weight").val(response.data.iso_weight);
         $("#edit-isocode-modal #iso_height").val(response.data.iso_height);
         $("#edit-isocode-modal #descr").val(response.data.descr);

         // fill form fields with record data
     },
                error: function(xhr, status, error) {
                    // Code untuk menangani error jika terjadi

                    alert(response);
                }

    

   });
});
</script>  

@endsection