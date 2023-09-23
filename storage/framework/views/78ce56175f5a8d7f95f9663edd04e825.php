

<?php $__env->startSection('content'); ?>
  
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
                            <th>Ves Id</th>
                            <th>Ves Name</th>
                            <th>Ves Code</th>
                            <th>Voy In</th>
                            <th>Voy Out</th>
                            <th>Arrival Date</th>
                            <th>Estimate Deparature Date</th>
                            <th>Closing Time</th>
                            <th>Owner</th>
                            <th>Agent</th> 
                            <th>Action</th>  
                        </tr>
                    </thead>
                    <tbody>
                    <?php $__currentLoopData = $vessel_voyage; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $voy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($voy->ves_id); ?></td>
                            <td><?php echo e($voy->ves_name); ?></td>
                            <td><?php echo e($voy->ves_code); ?></td>
                            <td><?php echo e($voy->voy_in); ?></td>
                            <td><?php echo e($voy->voy_out); ?></td>
                            <td><?php echo e($voy->arrival_date); ?></td>
                            <td><?php echo e($voy->etd_date); ?></td>
                            <td><?php echo e($voy->clossing_date); ?></td>
                            <td><?php echo e($voy->voyage_owner); ?></td>
                            <td><?php echo e($voy->agent); ?></td> 
                            <td>
                            <a href="javascript:void(0)"class="btn icon icon-left btn-outline-info detail-cont-edi" data-id="<?php echo e($voy->ves_id); ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg> Detail</a>
                            </td>  
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                <!-- <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
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
                    <?php $__currentLoopData = $item; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($itm->ves_name); ?></td>
                            <td><?php echo e($itm->voy_no); ?></td>
                            <td><?php echo e($itm->container_no); ?></td>
                            <td><?php echo e($itm->iso_code); ?></td>
                            <td><?php echo e($itm->ctr_size); ?></td>
                            <td><?php echo e($itm->ctr_type); ?></td>
                            <td><?php echo e($itm->ctr_status); ?></td>
                            <td><?php echo e($itm->bay_slot." ".$itm->bay_row.' '.$itm->bay_tier); ?></td>
                            <td>
                            <form action="/edi/delete_itembayplan=<?php echo e($itm->container_key); ?>" method="POST">
                               <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn icon btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus record ini? <?php echo e($itm->container_key); ?> ')"> <i class="bi bi-x"></i></button>                             
                                <a href="javascript:void(0)" class="btn btn-primary edit-modal" data-id="<?php echo e($itm->container_key); ?>" ><i class="bi bi-pencil"></i></a>
                            </form>

                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table> -->
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
            <?php echo csrf_field(); ?>
                <div class="modal-body"> 
                    <div class="row">
                        <div class="col-md-12">
                            <input name="_token" type="hidden" value="<?php echo e(csrf_token()); ?>">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Vessel</label>
                               <div class="col-sm-8">
                                 <select class="form-control select2" name="ves_id" style="width: 100%;" tabindex="-1" aria-hidden="true" >
                                   <option value="">Choose Vessel</option>
                                    <?php $__currentLoopData = $vessel_voyage; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ves): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                       <option value="<?php echo e($ves->ves_id); ?>"><?php echo e($ves->ves_name.' / '.$ves->voy_out); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                   </select>
                               </div>
                            </div>

                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">File</label>
                                <div class="col-sm-8">
                                    <input type="file" id="file-txt-input" name="filetxt" />
                                </div>
                            </div>
                            <input type="hidden" id="-column" class="form-control" value ="<?php echo e(Auth::user()->name); ?>" placeholder="<?php echo e(Auth::user()->name); ?>" name="user_id" required readonly>

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
            <form class="form-horizontal" action="<?php echo e(route('upload.submit')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
                <div class="modal-body"> 
                    <div class="row">
                        <div class="col-md-12">
                        <div class="form-group">
                                <label class="col-sm-3 control-label">Vessel</label>
                               <div class="col-sm-8">
                                 <select class="form-control select2" name="ves_id" style="width: 100%;" tabindex="-1" aria-hidden="true" >
                                   <option value="">Choose Vessel</option>
                                    <?php $__currentLoopData = $vessel_voyage; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ves): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($ves->ves_id); ?>"><?php echo e($ves->ves_name.' / '.$ves->voy_out); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                   </select>
                               </div>
                        </div>

                            <input name="_token" type="hidden" value="<?php echo e(csrf_token()); ?>">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">File</label>
                                <div class="col-sm-8">
                                    <input type="file" name="excel" />
                                </div>
                            </div>
                            
                            <input type="hidden" id="user_id" class="form-control" value ="<?php echo e(Auth::user()->name); ?>" placeholder="<?php echo e(Auth::user()->name); ?>" name="user_id" required readonly>

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
             <?php echo csrf_field(); ?>
            
                
                <div class="modal-body"> 
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Vessel</label>
                               <div class="col-sm-8">
                                 <select class="form-control select2" name="ves_id" style="width: 100%;" tabindex="-1" aria-hidden="true" >
                                   <?php $__currentLoopData = $vessel_voyage; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ves): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                       <option value="<?php echo e($ves->ves_id); ?>"><?php echo e($ves->ves_name); ?>.' / '.<?php echo e($ves->voy_no); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                           


                            <input type="hidden" id="-column" class="form-control" value ="<?php echo e(Auth::user()->name); ?>" placeholder="<?php echo e(Auth::user()->name); ?>" name="user_id" required readonly>


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

<?php echo $__env->make('edi.detail-cont', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('custom_js'); ?>



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
       
        $('#detail-edi').modal('hide'); 
        
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

<script>
 $(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
   

    $(document).on('click', '.detail-cont-edi', function() {
        let id = $(this).data('id');
        $.ajax({
            type: 'GET',
            url: '/edi/detail-container-' + id,
            cache: false,
            data: {
                ves_id: id
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                $('#detail-edi').modal('show');
                var tableBody = $('#detail-edi #tableDetail tbody');
                tableBody.empty();
                    if (response.data.cont === 0) {
                        var newRow = $('<tr>');
                        newRow.append('<td colspan="3">No Container Available</td>');
                        tableBody.append(newRow);
                    } else {
                        response.data.forEach(function(cont) {
                            var newRow = $('<tr>');
                            newRow.append('<td>' + cont.container_no + '</td>');
                            newRow.append('<td>' + cont.iso_code + '</td>');
                            newRow.append('<td>' + cont.ctr_size + '</td>');
                            newRow.append('<td>' + cont.ctr_type + '</td>');
                            newRow.append('<td>' + cont.ctr_status + '</td>');
                            newRow.append('<td>' + cont.bay_slot + cont.bay_row + cont.bay_tier + '</td>');
                            var deleteForm = $('<form>', {
                             action: '/edi/delete_itembayplan=' + cont.container_key,
                             method: 'POST',
                             onsubmit: "return confirm('Apakah Anda yakin ingin menghapus record ini? " + cont.container_key + "')"
                         }).append(
                             $('<button>', {
                                 type: 'submit',
                                 class: 'btn icon btn-danger',
                                 html: '<i class="bi bi-x"></i>'
                             }).append(
                                 $('<input>', {
                                     type: 'hidden',
                                     name: '_token',
                                     value: $('meta[name="csrf-token"]').attr('content')
                                 }),
                                 $('<input>', {
                                     type: 'hidden',
                                     name: '_method',
                                     value: 'DELETE'
                                 })
                             )
                         );
                     
                         // Tombol Edit
                         var editButton = $('<a>', {
                             href: 'javascript:void(0)',
                             class: 'btn btn-primary edit-modal',
                             'data-id': cont.container_key,
                             html: '<i class="bi bi-pencil"></i>'
                         });

                         newRow.append($('<td>').append(deleteForm, editButton));
                            tableBody.append(newRow);
                        });
                        new simpleDatatables.DataTable('#tableDetail');
                    }
            },
            error: function(data) {
                console.log('error:', data);
            }
        });
    });
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('partial.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\tos\resources\views/edi/ediarrival.blade.php ENDPATH**/ ?>