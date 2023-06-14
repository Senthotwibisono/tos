@extends('partial.main')

@section('content')
  
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Vessel Service</h3>
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
               
            
                <button class="btn btn-info btn-sm" id="btn-service"><i class="fa fa-file"></i> Create Vessel Service</button>
            
             </div>
            <div class="card-body">
                <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
                    <thead>
                        <tr>
                            <th>Service Code</th>
                            <th>Disch Port</th>
                            <th>User Name</th>   
                            <th>Update Time</th>                          
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($vessel_service as $service)
                        <tr>
                            <td>{{$service->service}}</td>
                            <td>{{$service->disch_port}}</td>
                            <td>{{$service->user_id}}</td>
                            <td>{{$service->update_time}}</td>
                          
                            <td>
                            <form action="/master/delete_service={{$service->service_id}}" method="POST">
                               @csrf
                                @method('DELETE')
                                <button type="submit" class="btn icon btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus record ini?')"> <i class="bi bi-x"></i></button>                             
                                <a href="javascript:void(0)" class="btn btn-primary edit-modal" data-id="{{ $service->service_id }}" ><i class="bi bi-pencil"></i></a>

                            </form>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </section>
</div>

<div id="create-service-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Please Insert All Field</h4>
            </div>
            <form id="create-service-form" class="form-horizontal" action="/master/service_store" method="POST" enctype="multipart/form-data">
                @CSRF
                
                <div class="modal-body"> 
                    <div class="row">
                        <div class="col-md-12">
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Service Code</label>
                                <div class="col-sm-6">
                                    <input type="text"  class="form-control" name="service_code" id="service_code"  required />
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label for="-id-column">Disch Port</label>
                                <select class="form-select" id="disch_port" name="disch_port" required>
                                  
                                  @foreach($port_master as $port)
                                    <option value="{{$port->port}}">{{$port->port}}</option>
                                  @endforeach
                                </select>
                            </div>
                            
                            <input type="hidden" id="-column" class="form-control" value ="{{ Auth::user()->name }}" placeholder="{{ Auth::user()->name }}" name="user_id" required readonly>


                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                
                  <button type="submit" class="btn btn-primary">Create Vessel Service</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div id="edit-service-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Please Insert All Field</h4>
            </div>
            <form id="edit-vessel-form" class="form-horizontal" action="/master/service_edit_store" method="POST" enctype="multipart/form-data">
             @csrf
            
                
                <div class="modal-body"> 
                    <div class="row">
                        <div class="col-md-12">
                            
                           <div class="form-group">
                                <label class="col-sm-3 control-label">Service Code</label>
                                <div class="col-sm-6">
                                    <input type="text"  class="form-control" name="service_code" id="service_code"  required />
                                    <input type="text"  class="form-control" name="service_id" id="service_id"  hidden />
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label for="-id-column">Disch Port</label>
                                <select class="form-select" id="disch_port" name="disch_port" required>
                                  
                                  @foreach($port_master as $port)
                                    <option value="{{$port->port}}">{{$port->port}}</option>
                                  @endforeach
                                </select>
                            </div>

                            <input type="hidden" id="-column" class="form-control" value ="{{ Auth::user()->id }}" placeholder="{{ Auth::user()->name }}" name="user_id" required readonly>


                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                
                  <button type="submit" class="btn btn-primary">Update Vessek Service</button>
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
        $('#btn-service').on("click", function(){
        
            $('#create-service-modal').modal('show');
        
        });
       
        $('#service_code').blur(function() {

            //alert("SAASASSASsa");
            var id = $("#service_code").val();
            $.ajax({
               type: 'GET',
               url: '/master/edit_service',
               cache: false,
               data : {port : id},
                dataType : 'json',
     
           success: function(response) {
            //alert(response.message);  
            if(response.message=='Data Tidak Ditemukan')
            {
              
            }else{alert('Data sudah pernah di dimasukkan/duplicate data');
                $("#service_code").val('');
            }
          }
        });
        
        });    
        
 });

 $(document).on('click', '.edit-modal', function() {
   let id = $(this).data('id');
      $.ajax({
               type: 'GET',
               url: '/master/edit_service',
               cache: false,
               data : {service_id : id},
                dataType : 'json',
     
      success: function(response) {
       
         $('#edit-service-modal').modal('show');
         $("#edit-service-modal #service_code").val(response.data.service);
         $("#edit-service-modal #disch_port").val(response.data.disch_port);
         $("#edit-service-modal #service_id").val(response.data.service_id);
       
       
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