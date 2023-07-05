@extends('partial.main')

@section('content')
  
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Receive Baplie</h3>
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
                
            <button id="upload-xls-file" type="button" class="btn btn-warning btn-sm"><i class="fa fa-plus"></i> Upload Baplie XLS File</button>
             <button id="upload-file" type="button" class="btn btn-info btn-sm"><i class="fa fa-plus"></i> Upload EDI Baplie TXT File</button>
                         
                
            </div>
            <div class="card-body">
                <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
                    <thead>
                        <tr>
                            <th>Vessel Name</th>
                            <th>Voyage No</th>
                            <th>Container No</th>
                            <th>Iso Code</th>
                            <th>Size</th>
                            <th>Type</th> 
                            <th>status </th>  
                            <th>Bay Row Tier</th>
                             

                        </tr>
                    </thead>
                    <tbody>
                    @foreach($item as $itm)
                        <tr>
                            <td>{{$itm->ves_name}}</td>
                            <td>{{$itm->voy_no}}</td>
                            <td>{{$itm->container_no}}</td>
                            <td>{{$itm->iso_code}}</td>
                            <td>{{$itm->ctr_size}}</td>
                            <td>{{$itm->ctr_type}}</td>
                            <td>{{$itm->ctr_status}}</td>
                            <td>{{$itm->bay_slot." ".$itm->bay_row.' '.$itm->bay_tier}}</td>
                            <td>
                            <form action="/edi/delete_itembayplan={{$itm->container_key}}" method="POST">
                               @csrf
                                @method('DELETE')
                                <button type="submit" class="btn icon btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus record ini? {{$itm->container_key}} ')"> <i class="bi bi-x"></i></button>                             
                                <a href="javascript:void(0)" class="btn btn-primary edit-modal" data-id="{{ $itm->container_key }}" ><i class="bi bi-pencil"></i></a>

                            </form>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </section>
</div>


<div id="upload-file-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Upload EDI Baplie TXT File</h4>
            </div>
            <form class="form-horizontal" action="/edi/receiveeditxt_store" method="POST" enctype="multipart/form-data">
            @csrf
                <div class="modal-body"> 
                    <div class="row">
                        <div class="col-md-12">
                            <input name="_token" type="hidden" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Vessel</label>
                               <div class="col-sm-8">
                                 <select class="form-control select2" name="ves_id" style="width: 100%;" tabindex="-1" aria-hidden="true" >
                                   <option value="">Choose Vessel</option>
                                    @foreach($vessel_voyage as $ves)
                                       <option value="{{ $ves->ves_id }}">{{ $ves->ves_name.' / '.$ves->voy_out }}</option>
                                    @endforeach
                                   </select>
                               </div>
                            </div>

                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">File</label>
                                <div class="col-sm-8">
                                    <input type="file" id="file-txt-input" name="filetxt" />
                                </div>
                            </div>
                            <input type="hidden" id="-column" class="form-control" value ="{{ Auth::user()->name }}" placeholder="{{ Auth::user()->name }}" name="user_id" required readonly>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="upload-xls-file-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Upload Baplie XLS File</h4>
            </div>
            <form class="form-horizontal" action="{{ route('upload.submit') }}" method="POST" enctype="multipart/form-data">
            @csrf
                <div class="modal-body"> 
                    <div class="row">
                        <div class="col-md-12">
                        <div class="form-group">
                                <label class="col-sm-3 control-label">Vessel</label>
                               <div class="col-sm-8">
                                 <select class="form-control select2" name="ves_id" style="width: 100%;" tabindex="-1" aria-hidden="true" >
                                   <option value="">Choose Vessel</option>
                                    @foreach($vessel_voyage as $ves)
                                        <option value="{{ $ves->ves_id }}">{{ $ves->ves_name.' / '.$ves->voy_out }}</option>
                                    @endforeach
                                   </select>
                               </div>
                        </div>

                            <input name="_token" type="hidden" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">File</label>
                                <div class="col-sm-8">
                                    <input type="file" name="excel" />
                                </div>
                            </div>
                            
                            <input type="hidden" id="user_id" class="form-control" value ="{{ Auth::user()->name }}" placeholder="{{ Auth::user()->name }}" name="user_id" required readonly>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                 <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Upload</button>

                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="edit-itembayplan-modal" class="modal fade" tabindex="-1" role="dialog">
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
                                <label class="col-sm-3 control-label">Vessel</label>
                               <div class="col-sm-8">
                                 <select class="form-control select2" name="ves_id" style="width: 100%;" tabindex="-1" aria-hidden="true" >
                                   @foreach($vessel_voyage as $ves)
                                       <option value="{{ $ves->ves_id }}">{{ $ves->ves_name}}.' / '.{{$ves->voy_no }}</option>
                                    @endforeach
                                   </select>
                               </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Container No</label>
                                <div class="col-sm-6">
                                    <input type="text"  class="form-control" name="container_no" id="container_no"  readonly />
                                </div>
                            </div>     

                            <div class="form-group">
                                <label class="col-sm-3 control-label">ISO Code</label>
                                <div class="col-sm-6">
                                    <input type="text"  class="form-control" name="iso_code" id="iso_code"  readonly />
                                </div>
                            </div>                            
                            <div class="form-group">
                                        <label for="-id-column">Size</label>
                                        <select class="form-select" id="ctr_size" name="ctr_size" required>
                                            <option value=""></option>
                                            <option value="20">20</option>                                             
                                            <option value="40">40</option>
                                            <option value="45">45</option>
                                        </select>
                            </div>
                            <div class="form-group">
                                        <label for="-id-column">Type</label>
                                        <select class="form-select" id="ctr_type" name="ctr_type" required>
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
                                        <label for="-id-column">Status</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" name="ctr_status" id="ctr_status" required/>
                                        </div>
                                     
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Gross</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="gross" id="gross" required/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Bay Location</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="bay_slot" id="bay_slot" required/>
                                    <input type="text" class="form-control" name="bay_row" id="bay_row" required/>
                                    <input type="text" class="form-control" name="bay_tier" id="bay_tier" required/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Load Port</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="load_port" id="load_port" required/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Disch Port</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="disch_port" id="disch_port" required/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Final Disch Port</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="fdisch_port" id="fdisch_port" required/>
                                </div>
                            </div>
                           


                            <input type="hidden" id="-column" class="form-control" value ="{{ Auth::user()->name }}" placeholder="{{ Auth::user()->name }}" name="user_id" required readonly>


                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                
                  <button type="submit" class="btn btn-primary">Update Item Bay Plan</button>
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

 $('#upload-file').on("click", function(){
        $('#upload-file-modal').modal('show');
    });
    $('#upload-xls-file').on("click", function(){
        $('#upload-xls-file-modal').modal('show');
 });

 $(document).on('click', '.edit-modal', function() {
   let id = $(this).data('id');
   //alert(id);
      $.ajax({
               type: 'GET',
               url: '/edi/edit_itembayplan',
               cache: false,
               data : {container_key : id},
                dataType : 'json',
     
      success: function(response) {
       

        
         $('#edit-itembayplan-modal').modal('show');
         $("#edit-itembayplan-modal #ves_id").val(response.data.ves_id);
         $("#edit-itembayplan-modal #container_no").val(response.data.container_no);
         $("#edit-itembayplan-modal #iso_code").val(response.data.iso_code);
         $("#edit-itembayplan-modal #ctr_size").val(response.data.ctr_size);
         $("#edit-itembayplan-modal #ctr_type").val(response.data.ctr_type);
         $("#edit-itembayplan-modal #ctr_status").val(response.data.ctr_status);
         $("#edit-itembayplan-modal #gross").val(response.data.gross);
         $("#edit-itembayplan-modal #bay_slot").val(response.data.bay_slot);
         $("#edit-itembayplan-modal #bay_row").val(response.data.bay_row);
         $("#edit-itembayplan-modal #bay_tier").val(response.data.bay_tier);
         $("#edit-itembayplan-modal #load_port").val(response.data.load_port);
         $("#edit-itembayplan-modal #disch_port").val(response.data.disch_port);
         $("#edit-itembayplan-modal #fdisch_port").val(response.data.fdisch_port);



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
