<?php $__env->startSection('content'); ?>
  
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Vessel Master</h3>
               
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
               
            
                <button class="btn btn-info btn-sm" id="btn-vessel"><i class="fa fa-file"></i> Create vessel</button>
            
             </div>
            <div class="card-body">
                <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
                    <thead>
                        <tr>
                            <th>Vessel Code</th>
                            <th>Vessel Name</th>
                            <th>Agent</th>  
                            <th>Liner Name</th>
                            <th>Call Sign</th>
                            <th>Flag</th> 

                        </tr>
                    </thead>
                    <tbody>
                    <?php $__currentLoopData = $vessel_master; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vessel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($vessel->ves_code); ?></td>
                            <td><?php echo e($vessel->ves_name); ?></td>
                            <td><?php echo e($vessel->agent); ?></td>
                            <td><?php echo e($vessel->liner_name); ?></td>
                            <td><?php echo e($vessel->call_sign); ?></td>
                            <td><?php echo e($vessel->flag); ?></td>
                          
                            <td>
                            <form action="/master/delete_vessel=<?php echo e($vessel->ves_code); ?>" method="POST">
                               <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn icon btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus record ini?')"> <i class="bi bi-x"></i></button>                             
                                <a href="javascript:void(0)" class="btn btn-primary edit-modal" data-id="<?php echo e($vessel->ves_code); ?>" ><i class="bi bi-pencil"></i></a>

                            </form>

                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>

    </section>
</div>

<div id="create-vessel-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Please Insert All Field</h4>
            </div>
            <form id="create-vessel-form" class="form-horizontal" action="/master/vessel_store" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                
                <div class="modal-body"> 
                    <div class="row">
                        <div class="col-md-12">
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Vessel Code</label>
                                <div class="col-sm-6">
                                    <input type="text"  class="form-control" name="ves_code" id="ves_code"  required />
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Vessel Name</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="ves_name" id="ves_name" required />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Agent</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="agent" id="agent" required />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Liner </label>
                                <div class="col-sm-6">
                                <input type="text" class="form-control" name="liner" id="liner" required />
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Liner Name </label>`
                                <div class="col-sm-6">
                                <input type="text" class="form-control" name="liner_name" id="liner_name" required />
                                </div>
                            </div>
                             
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Flag </label>`
                                <div class="col-sm-6">
                                <input type="text" class="form-control" name="flag" id="flag" required />
                                </div>
                            </div>
                            <div class="form-group">
                                        <label for="-id-column">Ocean/Interisland</label>
                                            <select class="form-select" id="ves_service" name="ves_service" required>
                                            <option value="O">Ocean Going</option>                                             
                                            <option value="I">Inter Island</option>
                                            </select>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">G R T </label>`
                                <div class="col-sm-6">
                                <input type="text" class="form-control" name="grt" id="grt" required />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">B R T </label>`
                                <div class="col-sm-6">
                                <input type="text" class="form-control" name="brt" id="brt" required />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">L O A </label>`
                                <div class="col-sm-6">
                                <input type="text" class="form-control" name="loa" id="loa" required />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">D W T </label>`
                                <div class="col-sm-6">
                                <input type="text" class="form-control" name="dwt" id="dwt" required />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">N R T </label>`
                                <div class="col-sm-6">
                                <input type="text" class="form-control" name="nrt" id="nrt" required />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Draft </label>`
                                <div class="col-sm-6">
                                <input type="text" class="form-control" name="draft" id="draft" required />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Vessel_lenght </label>`
                                <div class="col-sm-6">
                                <input type="text" class="form-control" name="vlenght" id="vlenght" required />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Year Made </label>`
                                <div class="col-sm-6">
                                <input type="number" class="form-control" name="ymade" id="ymade" required />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Country Made </label>`
                                <div class="col-sm-6">
                                <input type="text" class="form-control" name="cmade" id="cmade" required />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Call Sign </label>`
                                <div class="col-sm-6">
                                <input type="text" class="form-control" name="callsign" id="callsign" required />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Lioyds No</label>`
                                <div class="col-sm-6">
                                <input type="text" class="form-control" name="lno" id="lno" required />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">ISPS Code </label>`
                                <div class="col-sm-6">
                                <input type="text" class="form-control" name="ispscode" id="ispscode" required />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">ISPS Date </label>`
                                <div class="col-sm-6">
                                <input type="date" class="form-control" name="ispsdate" id="ispsdate" required />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Remarks</label>`
                                <div class="col-sm-6">
                                <textarea class="form-control" name="remerks" id="remarks"></textarea>
                                </div>
                            </div>


                            <input type="hidden" id="-column" class="form-control" value ="<?php echo e(Auth::user()->id); ?>" placeholder="<?php echo e(Auth::user()->name); ?>" name="user_id" required readonly>


                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                
                  <button type="submit" class="btn btn-primary">Create vessel</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div id="edit-vessel-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Please Insert All Field</h4>
            </div>
            <form id="edit-vessel-form" class="form-horizontal" action="/master/vessel_edit_store" method="POST" enctype="multipart/form-data">
             <?php echo csrf_field(); ?>
            
                
                <div class="modal-body"> 
                    <div class="row">
                        <div class="col-md-12">
                            
                        <div class="form-group">
                                <label class="col-sm-3 control-label">Vessel Code</label>
                                <div class="col-sm-6">
                                    <input type="text"  class="form-control" name="ves_code" id="ves_code"  required />
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Vessel Name</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="ves_name" id="ves_name" required />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Agent</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="agent" id="agent" required />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Liner </label>
                                <div class="col-sm-6">
                                <input type="text" class="form-control" name="liner" id="liner" required />
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Liner Name </label>`
                                <div class="col-sm-6">
                                <input type="text" class="form-control" name="liner_name" id="liner_name" required />
                                </div>
                            </div>
                             
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Flag </label>`
                                <div class="col-sm-6">
                                <input type="text" class="form-control" name="flag" id="flag" required />
                                </div>
                            </div>
                            <div class="form-group">
                                        <label for="-id-column">Ocean/Interisland</label>
                                            <select class="form-select" id="ves_service" name="ves_service" required>
                                            <option value="O">Ocean Going</option>                                             
                                            <option value="I">Inter Island</option>
                                            </select>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">G R T </label>`
                                <div class="col-sm-6">
                                <input type="text" class="form-control" name="grt" id="grt" required />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">B R T </label>`
                                <div class="col-sm-6">
                                <input type="text" class="form-control" name="brt" id="brt" required />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">L O A </label>`
                                <div class="col-sm-6">
                                <input type="text" class="form-control" name="loa" id="loa" required />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">D W T </label>`
                                <div class="col-sm-6">
                                <input type="text" class="form-control" name="dwt" id="dwt" required />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">N R T </label>`
                                <div class="col-sm-6">
                                <input type="text" class="form-control" name="nrt" id="nrt" required />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Draft </label>`
                                <div class="col-sm-6">
                                <input type="text" class="form-control" name="draft" id="draft" required />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Vessel_lenght </label>`
                                <div class="col-sm-6">
                                <input type="text" class="form-control" name="vlenght" id="vlenght" required />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Year Made </label>`
                                <div class="col-sm-6">
                                <input type="number" class="form-control" name="ymade" id="ymade" required />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Country Made </label>`
                                <div class="col-sm-6">
                                <input type="text" class="form-control" name="cmade" id="cmade" required />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Call Sign </label>`
                                <div class="col-sm-6">
                                <input type="text" class="form-control" name="callsign" id="callsign" required />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Lioyds No</label>`
                                <div class="col-sm-6">
                                <input type="text" class="form-control" name="lno" id="lno" required />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">ISPS Code </label>`
                                <div class="col-sm-6">
                                <input type="text" class="form-control" name="ispscode" id="ispscode" required />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">ISPS Date </label>`
                                <div class="col-sm-6">
                                <input type="date" class="form-control" name="ispsdate" id="ispsdate" required />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Remarks</label>`
                                <div class="col-sm-6">
                                <textarea class="form-control" name="remerks" id="remarks"></textarea>
                                </div>
                            </div>

                            <input type="hidden" id="-column" class="form-control" value ="<?php echo e(Auth::user()->id); ?>" placeholder="<?php echo e(Auth::user()->name); ?>" name="user_id" required readonly>


                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                
                  <button type="submit" class="btn btn-primary">Update vessel</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->






<?php $__env->stopSection(); ?>


<?php $__env->startSection('custom_js'); ?>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>

<script>

    
 $(document).ready(function()
    {
        $('#btn-vessel').on("click", function(){
        
            $('#create-vessel-modal').modal('show');
        
        });
       
        $('#ves_code').blur(function() {

            //alert("SAASASSASsa");
            var id = $("#ves_code").val();
            $.ajax({
               type: 'GET',
               url: '/master/edit_vessel',
               cache: false,
               data : {ves_code : id},
                dataType : 'json',
     
           success: function(response) {
            //alert(response.message);  
            if(response.message=='Data Tidak Ditemukan')
            {
             
            }else{alert('Data sudah pernah di dimasukkan/duplicate data');
                $("#ves_code").val('');
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
               url: '/master/edit_vessel',
               cache: false,
               data : {ves_code : id},
                dataType : 'json',
     
      success: function(response) {
       
         $('#edit-vessel-modal').modal('show');
          $("#edit-vessel-modal #ves_code").val(response.data.ves_code);
         $("#edit-vessel-modal #ves_name").val(response.data.ves_name);
         $("#edit-vessel-modal #agent").val(response.data.agent);
         $("#edit-vessel-modal #liner").val(response.data.liner);
         $("#edit-vessel-modal #liner_name").val(response.data.liner_name);
         $("#edit-vessel-modal #flag").val(response.data.reg_flag);
         $("#edit-vessel-modal #ves_service").val(response.data.ocean_interisland);
         $("#edit-vessel-modal #grt").val(response.data.g_r_t);
         $("#edit-vessel-modal #brt").val(response.data.b_r_t);
         $("#edit-vessel-modal #loa").val(response.data.l_o_a);
         $("#edit-vessel-modal #dwt").val(response.data.d_w_t);
         $("#edit-vessel-modal #nrt").val(response.data.n_r_t);
         $("#edit-vessel-modal #draft").val(response.data.draft);
         $("#edit-vessel-modal #vlenght").val(response.data.ves_length);
         $("#edit-vessel-modal #ymade").val(response.data.year_made);
         $("#edit-vessel-modal #cmade").val(response.data.country_made);
         $("#edit-vessel-modal #callsign").val(response.data.call_sign);
         $("#edit-vessel-modal #lno").val(response.data.lloyds_no);
         $("#edit-vessel-modal #ispscode").val(response.data.isps_code);
         $("#edit-vessel-modal #ispsdate").val(response.data.isps_date);
         $("#edit-vessel-modal #remakrs").val(response.data.Remark);


         // fill form fields with record data
     },
                error: function(xhr, status, error) {
                    // Code untuk menangani error jika terjadi

                    alert(response);
                }

    

   });
});
</script>  

<?php $__env->stopSection(); ?>
<?php echo $__env->make('partial.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\tos\resources\views/master/vessel.blade.php ENDPATH**/ ?>