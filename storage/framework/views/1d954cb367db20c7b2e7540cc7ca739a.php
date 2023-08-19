
<?php $__env->startSection('custom_styles'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="page-heading">
  <div class="page-title">
    <div class="row">
      <div class="col-12 col-md-6 order-md-1 order-last">
        <h3>Delivery Gate In</h3>
      </div>

      <div class="col-12 col-md-6 order-md-2 order-first">
        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
          <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Delivery Gate In</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <section class="section">
    <div class="card" id="load_ini">
      <div class="card-header">
        <button class="btn icon icon-left btn-outline-info" data-bs-toggle="modal" data-bs-target="#success">
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
              <th>Truck No</th>
              <th>Truck In</th>
            </tr>
          </thead>
          <tbody>
            <?php $__currentLoopData = $formattedData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <td><?php echo e($d['container_no']); ?></td>
              <td><?php echo e($d['truck_no']); ?></td>
              <td><?php echo e($d['truck_in_date']); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
        </table>
      </div>
    </div>
  </section>
</div>

<!-- Modal Update Status -->
<div class="modal fade text-left" id="success" role="dialog" aria-labelledby="myModalLabel110" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header bg-info">
        <h5 class="modal-title white" id="myModalLabel110">Delivery Gate In</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><i data-feather="x"></i></button>
      </div>
      <div class="modal-body">
        <!-- form -->
        <div class="form-body" id="place_cont">
          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <label for="first-name-vertical">Choose Container Number</label>
                <select class="choices form-select" id="key" name="container_key" required>
                  <option disabled selected value="">Select Container</option>
                  <?php $__currentLoopData = $containerKeys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $containerKey => $containerNo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($containerKey); ?>"><?php echo e($containerNo); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <input type="hidden" id="container_no" class="form-control" name="container_no">
                <input type="hidden" value="<?php echo e($currentDateTimeString); ?>" name="truck_in_date" class="form-control" readonly>
              </div>
              <?php echo e(csrf_field()); ?>

            </div>
        <div class="col-12">
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label for="first-name-vertical">Gross</label>
                <input type="text" id="gross" class="form-control" name="gross" readonly>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label for="first-name-vertical">Iso Code</label>
                <select class="form-select" id="iso_code" name="iso_code">
                                <option value="-">-</option>
                                <?php $__currentLoopData = $isocode; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $iso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($iso->iso_code); ?>"><?php echo e($iso->iso_code); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                              </select>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label for="first-name-vertical">Seal No</label>
                <input type="text" id="seal_no" class="form-control" name="seal_no" required>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label for="first-name-vertical">B/L No</label>
                <input type="text"  id="bl_no" name="bl_no" class="form-control" readonly>
              </div>
            </div>
            <hr>
              <div class="col-6">
              <div class="form-group">
                <label for="first-name-vertical">Size</label>
                <input type="text" id="size" class="form-control" name="ctr_size" readonly>
              
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label for="first-name-vertical">Type</label>
                <input type="text" id="type" class="form-control" name="ctr_type" required>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label for="first-name-vertical">Status</label>
                <select class="form-select" id="stat " name="ctr_status">
                  <option value="FCL">FCL</option>
                  <option value="MTY">MTY</option>
                </select>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label for="first-name-vertical">Truck Number</label>
                <input type="text" id="tayo" class="form-control" name="truck_no" required>
              </div>
            </div>
            
          <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Lihat Data Transaksi</button>
               <div class="collapse" id="collapseExample">
                  <br>
                   <div class="col-12">
                    <div class="row">
                        <div class="col-6">
                          <fieldset>                        
                              <div class="input-group">
                                      <div class="col-4">
                                      <label class="input-group-text">Size</label >
                                      </div>
                                      <div class="col-4">
                                      <label class="input-group-text">Type</label>
                                      </div>
                                      <div class="col-4">
                                      <label class="input-group-text">Status</label>
                                      </div>
                              </div>
                              <div class="input-group">
                                  
                                  <input type="text" id="sz" class="form-control" disabled>
                                  <input type="text" id="tp" class="form-control" disabled>
                                  <input type="text" id="st" class="form-control" disabled>
                              </div>
                          </fieldset>
                        </div>
                        <div class="col-6">
                          <fieldset>                        
                              <div class="input-group">
                                      <div class="col-6">
                                      <label class="input-group-text">Vessel</label>
                                      </div>
                                      <div class="col-6">
                                      <label class="input-group-text">Voy</label>
                                      </div>
                              </div>
                              <div class="input-group">
                                  
                                  <input type="text" id="vessel" class="form-control" disabled>
                                  <input type="text" id="voy" class="form-control" disabled>
                                  
                              </div>
                          </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                          <fieldset>                        
                              <div class="input-group">
                                      <div class="col-4">
                                      <label class="input-group-text">IMO</label>
                                      </div>
                                      <div class="col-4">
                                      <label class="input-group-text">Gross</label>
                                      </div>
                                      <div class="col-4">
                                      <label class="input-group-text">Class</label>
                                      </div>
                              </div>
                              <div class="input-group">
                                  
                                  <input type="text" id="imo" class="form-control" disabled>
                                  <input type="text" id="jmlh" class="form-control" disabled>
                                  <input type="text" id="class" class="form-control" disabled>
                              </div>
                          </fieldset>
                        </div>
                        <div class="col-6">
                          <fieldset>                        
                              <div class="input-group">
                                      <div class="col-6">
                                      <label class="input-group-text">POD</label>
                                      </div>
                                      <div class="col-6">
                                      <label class="input-group-text">Seal</label>
                                      </div>
                              </div>
                              <div class="input-group">
                                  
                                  <input type="text" id="pod"  class="form-control" disabled>
                                  <input type="text" id="seal"  class="form-control" disabled>
                              </div>
                          </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                          <fieldset>                        
                              <div class="input-group">
                                      <div class="col-4">
                                      <label class="input-group-text">O.H</label>
                                      </div>
                                      <div class="col-4">
                                      <label class="input-group-text">O.L</label>
                                      </div>
                                      <div class="col-4">
                                      <label class="input-group-text">O.W</label>
                                      </div>
                              </div>
                              <div class="input-group">
                                  
                                  <input type="text" id="oh" class="form-control" disabled>
                                  <input type="text" id="ol" class="form-control" disabled>
                                  <input type="text" id="ow" class="form-control" disabled>
                              </div>
                          </fieldset>
                        </div>
                        <div class="col-6">
                          <fieldset>                        
                              <div class="input-group">
                                      <div class="col-12">
                                      <label for="first-name-vertical">Date In</label>
                                      </div>
                              </div>
                              <div class="input-group">    
                                  <input type="datetime-local" value="<?php echo e($currentDateTimeString); ?>" id="datein" name="truck_in_date" class="form-control" readonly>
                              </div>
                          </fieldset>
                        </div>
                    </div>
                   </div>

               </div>
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





<?php $__env->stopSection(); ?>
<?php $__env->startSection('custom_js'); ?>
<script src="<?php echo e(asset('vendor/components/jquery/jquery.min.js')); ?>"></script>
<script src="<?php echo e(asset('select2/dist/js/select2.full.min.js')); ?>"></script>
<script src="<?php echo e(asset('dist/assets/extensions/sweetalert2/sweetalert2.min.js')); ?>"></script>
<script src="<?php echo e(asset('dist/assets/js/pages/sweetalert2.js')); ?>"></script>



<script>
  $(document).ready(function() {
    $('.container').select2({
      dropdownParent: '#success',
    });
  });
  $(document).on('click', '.update_status', function(e) {
    e.preventDefault();
    var container_key = $('#key').val();
    var container_no = $('#container_no').val();
    var truck_no = $('#tayo').val();
    var truck_in_date = $('#datein').val();
    var gross =$('#gross').val();
    var iso_code =$('#iso_code').val();
    var bl_no =$('#bl_no').val();
    var seal_no =$('#seal_no').val();
    var ctr_type =$('#type').val();
    var ctr_size =$('#size').val();
    var ctr_status =$('#stat').val();
    var data = {
      'container_key': $('#key').val(),
      'container_no': $('#container_no').val(),
      'truck_no': $('#tayo').val(),
      'truck_in_date': $('#datein').val(),
      'gross' : $('#gross').val(),
      'iso_code' : $('#iso_code').val(),
      'bl_no' : $('#bl_no').val(),
      'seal_no' : $('#seal_no').val(),
      'ctr_type' : $('#type').val(),
      'ctr_size' : $('#size').val(),
      'ctr_status' : $('#stat').val(),


    }
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    Swal.fire({
      title: 'Are you Sure?',
      text: "Truck " + truck_no + " will bring container" + container_no + "? ",
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
          url: '/gati-rec',
          data: data,
          cache: false,
          dataType: 'json',
          success: function(response) {
            console.log(response);
            if (response.success) {
              Swal.fire('Saved!', '', 'success')
              $('#load_ini').load(window.location.href + ' #load_ini');
              $('#place_cont').load(window.location.href + ' #place_cont', function() {
                $(document).ready(function() {
                  let choices = document.querySelectorAll('.choices');
                  let initChoice;
                  for (let i = 0; i < choices.length; i++) {
                    if (choices[i].classList.contains("multiple-remove")) {
                      initChoice = new Choices(choices[i], {
                        delimiter: ',',
                        editItems: true,
                        maxItemCount: -1,
                        removeItemButton: true,
                      });
                    } else {
                      initChoice = new Choices(choices[i]);
                    }
                  }
                  $('.container').select2({
                    dropdownParent: '#success',
                  });
                  $(document).ready(function() {
                    $('#key').on('change', function() {
                      let id = $(this).val();
                      $.ajax({
                        type: 'POST',
                        url: '/gati-data_container-rec',
                        data: {
                          container_key: id
                        },
                        success: function(response) {                      
                           $('#container_no').val(response.container_no);
                           $('#gross').val(response.gross);
                           $('#iso_code').val(response.iso_code);
                           $('#bl_no').val(response.bl_no);
                           $('#seal_no').val(response.seal_no);
                           $('#type').val(response.type);
                           $('#size').val(response.size);
                           $('#stat').val(response.stat);
                           $('#tp').val(response.type);
                           $('#sz').val(response.size);
                           $('#st').val(response.stat);
                           $('#vessel').val(response.vessel);
                           $('#voy').val(response.voy);
                           $('#imo').val(response.imo);
                           $('#jmlh').val(response.gross);
                           $('#class').val(response.class);
                           $('#pod').val(response.pod);
                           $('#seal').val(response.seal);
                           $('#oh').val(response.oh);
                           $('#ow').val(response.ow);
                           $('#ol').val(response.ol);
                        },
                        error: function(data) {
                          console.log('error:', data);
                        },
                      });
                    });
                  });
                  // $
                });

                $('#load_ini').load(window.location.href + ' #load_ini');
              });
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: response.message,
              });
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
          url: '/gati-data_container-rec',
          data: {
            container_key: id
          },
          success: function(response) {          
            
            $('#container_no').val(response.container_no);
            $('#gross').val(response.gross);
            $('#iso_code').val(response.iso_code);
            $('#bl_no').val(response.bl_no);
            $('#seal_no').val(response.seal_no);
            $('#type').val(response.type);
            $('#size').val(response.size);
            $('#stat').val(response.stat);
            $('#tp').val(response.type);
            $('#sz').val(response.size);
            $('#st').val(response.stat);
            $('#vessel').val(response.vessel);
            $('#voy').val(response.voy);
            $('#imo').val(response.imo);
            $('#jmlh').val(response.gross);
            $('#class').val(response.class);
            $('#pod').val(response.pod);
            $('#seal').val(response.seal);
            $('#oh').val(response.oh);
            $('#ow').val(response.ow);
            $('#ol').val(response.ol);
            
          },
          error: function(data) {
            console.log('error:', data);
          },
        });
      });
    });

    $(document).ready(function() {
      $('#iso_code').on('change', function() {
        let id = $(this).val();
        $.ajax({
          type: 'POST',
          url: '/gati-iso-rec',
          data: {
            iso_code: id
          },
          success: function(response) {

            $('#type').val(response.type);
            $('#size').val(response.size);
 

          },
          error: function(data) {
            console.log('error:', data);
          },
        });

      });
    });

    function openModal2() {
  document.getElementById('modal2').style.display = 'block';
}

function closeModal2() {
  document.getElementById('modal2').style.display = 'none';
}

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

<?php $__env->stopSection(); ?>
<?php echo $__env->make('partial.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\tos\resources\views/gate/recive/main.blade.php ENDPATH**/ ?>