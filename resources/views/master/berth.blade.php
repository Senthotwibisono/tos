@extends('partial.main')

@section('content')
  
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Berth Master</h3>

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
               
            
                <button class="btn btn-info btn-sm" id="btn-berth"><i class="fa fa-file"></i> Create Berth</button>
            
             </div>
            <div class="card-body">
                <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
                    <thead>
                        <tr>
                            <th>Bert No</th>
                            <th>From Length</th>
                            <th>To length</th> 
                            <th>Ocean/Inter </th>                          
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($berth as $berth)
                        <tr>
                            <td>{{$berth->berth_no}}</td>
                            <td>{{$berth->from_length}}</td>
                            <td>{{$berth->to_length}}</td>
                            <td>{{$berth->ocean_interisland}}</td>
                            <td>
                            <form action="/master/delete_berth={{$berth->berth_no}}" method="POST">
                               @csrf
                                @method('DELETE')
                                <button type="submit" class="btn icon btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus record ini?')"> <i class="bi bi-x"></i></button>                             
                                <a href="javascript:void(0)" class="btn btn-primary edit-modal" data-id="{{ $berth->berth_no }}" ><i class="bi bi-pencil"></i></a>

                            </form>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </section>
</div>

<div id="create-berth-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Please Insert All Field</h4>
            </div>
            <form id="create-berth-form" class="form-horizontal" action="/master/berth_store" method="POST" enctype="multipart/form-data">
                @CSRF
                
                <div class="modal-body"> 
                    <div class="row">
                        <div class="col-md-12">
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Berth No</label>
                                <div class="col-sm-6">
                                    <input type="text"  class="form-control" name="berth_no" id="berth_no"  required />
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">From Length</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="flength" id="flength" required />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">To Length</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="tlength" id="tlength" required/>
                                </div>
                            </div>
                            <div class="form-group">
                                        <label for="-id-column">Berth Code</label>
                                            <select class="form-select" id="bcode" name="bcode" required>
                                            <option value="06">06-KON</option>                                             
                                            <option value="07">07-TPK</option>
                                            <option value="08">08-TPK</option>
                                            </select>
                            </div>
                            
                            <div class="form-group">
                                        <label for="-id-column">Ocean/Interisland</label>
                                            <select class="form-select" id="ves_service" name="ves_service" required>
                                            <option value="O">Ocean Going</option>                                             
                                            <option value="I">Inter Island</option>
                                            </select>
                            </div>
                            
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Contruct Type </label>
                                <div class="col-sm-6">
                                <input type="text" class="form-control" name="ctypr" id="ctype" required/>
                                </div>
                            </div>
                        
                            <input type="hidden" id="-column" class="form-control" value ="{{ Auth::user()->id }}" placeholder="{{ Auth::user()->name }}" name="user_id" required readonly>

                        
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                
                  <button type="submit" class="btn btn-primary">Create Berth</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div id="edit-berth-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Please Insert All Field</h4>
            </div>
            <form id="edit-berth-form" class="form-horizontal" action="/master/berth_edit_store" method="POST" enctype="multipart/form-data">
             @csrf
            
                
                <div class="modal-body"> 
                    <div class="row">
                        <div class="col-md-12">
                            
                        <div class="form-group">
                                <label class="col-sm-3 control-label">Berth No</label>
                                <div class="col-sm-6">
                                    <input type="text"  class="form-control" name="berth_no" id="berth_no"  required />
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">From Length</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="flength" id="flength" required />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">To Length</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="tlength" id="tlength" required/>
                                </div>
                            </div>
                            <div class="form-group">
                                        <label for="-id-column">Berth Code</label>
                                            <select class="form-select" id="bcode" name="bcode" required>
                                            <option value="06">06-KON</option>                                             
                                            <option value="07">07-TPK</option>
                                            <option value="08">08-TPK</option>
                                            </select>
                            </div>
                            
                            <div class="form-group">
                                        <label for="-id-column">Ocean/Interisland</label>
                                            <select class="form-select" id="ves_service" name="ves_service" required>
                                            <option value="O">Ocean Going</option>                                             
                                            <option value="I">Inter Island</option>
                                            </select>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Contruct Type </label>
                                <div class="col-sm-6">
                                <input type="text" class="form-control" name="ctype" id="ctype" required/>
                                </div>
                            </div>
                        

                            <input type="hidden" id="-column" class="form-control" value ="{{ Auth::user()->id }}" placeholder="{{ Auth::user()->name }}" name="user_id" required readonly>


                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                
                  <button type="submit" class="btn btn-primary">Update Berth</button>
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
        $('#btn-berth').on("click", function(){
        
            $('#create-berth-modal').modal('show');
        
        });
       
        $('#berth_no').blur(function() {

            //alert("SAASASSASsa");
            var id = $("#berth_no").val();
            $.ajax({
               type: 'GET',
               url: '/master/edit_berth',
               cache: false,
               data : {berth_no : id},
                dataType : 'json',
     
           success: function(response) {
            //alert(response.message);  
            if(response.message=='Data Tidak Ditemukan')
            {
              
            }else{alert('Data sudah pernah di dimasukkan/duplicate data');
                $("#berth_no").val('');
            }
          }
        });
        
        });    
        
 });

 $(document).on('click', '.edit-modal', function() {
   let id = $(this).data('id');
   //alert(id);
      $.ajax({
               type: 'GET',
               url: '/master/edit_berth',
               cache: false,
               data : {berth_no : id},
                dataType : 'json',
     
      success: function(response) {
       

        
         $('#edit-berth-modal').modal('show');
         $("#edit-berth-modal #berth_no").val(response.data.berth_no);
         $("#edit-berth-modal #bcode").val(response.data.berth_code);
         $("#edit-berth-modal #ctype").val(response.data.consturct_type);
         $("#edit-berth-modal #flength").val(response.data.from_length);
         $("#edit-berth-modal #tlength").val(response.data.to_length);
         $("#edit-berth-modal #ves_service").val(response.data.ocean_interisland);

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