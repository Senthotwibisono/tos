@extends('partial.main')

@section('content')
  
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Port Master</h3>
                
            </div>
            <!-- <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">DataTable</li>
                    </ol>
                </nav>
            </div> -->
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
               
            
                <button class="btn btn-info btn-sm" id="btn-port"><i class="fa fa-file"></i> Create Port</button>
            
             </div>
            <div class="card-body">
                <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
                    <thead>
                        <tr>
                            <th>Port</th>
                            <th>Country Name</th>
                            <th>Description</th>                          
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($port_master as $port)
                        <tr>
                            <td>{{$port->port}}</td>
                            <td>{{$port->country_name}}</td>
                            <td>{{$port->descr}}</td>
                          
                            <td>
                            <form action="/master/delete_port={{$port->port}}" method="POST">
                               @csrf
                                @method('DELETE')
                                <button type="submit" class="btn icon btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus record ini?')"> <i class="bi bi-x"></i></button>                             
                                <a href="javascript:void(0)" class="btn btn-primary edit-modal" data-id="{{ $port->port }}" ><i class="bi bi-pencil"></i></a>

                            </form>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </section>
</div>

<div id="create-port-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Please Insert All Field</h4>
            </div>
            <form id="create-port-form" class="form-horizontal" action="/master/port_store" method="POST" enctype="multipart/form-data">
                @CSRF
                
                <div class="modal-body"> 
                    <div class="row">
                        <div class="col-md-12">
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Port</label>
                                <div class="col-sm-6">
                                    <input type="text"  class="form-control" name="port" id="port"  required />
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
                
                  <button type="submit" class="btn btn-primary">Create Port</button>
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
            <form id="edit-port-form" class="form-horizontal" action="/master/port_edit_store" method="POST" enctype="multipart/form-data">
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






@endsection


@section('custom_js')



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>

<script>

    
 $(document).ready(function()
    {
        $('#btn-port').on("click", function(){
        
            $('#create-port-modal').modal('show');
        
        });
       
        $('#port').blur(function() {

            //alert("SAASASSASsa");
            var id = $("#port").val();
            $.ajax({
               type: 'GET',
               url: '/master/edit_port',
               cache: false,
               data : {port : id},
                dataType : 'json',
     
           success: function(response) {
            //alert(response.message);  
            if(response.message=='Data Tidak Ditemukan')
            {
               var un_port = $("#port").val();
               var un_country = $("#port").val();
  
               //alert($("#port").val());
              $("#un_port").val(un_port.substring(2,7));
              $("#un_country").val(un_country.substring(0,2));
            }else{alert('Data sudah pernah di dimasukkan/duplicate data');
                $("#port").val('');
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

@endsection