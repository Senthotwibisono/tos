
<?php $__env->startSection('content'); ?>
<div class="page-heading">
  <div class="page-title">
    <div class="row">
      <div class="col-12 col-md-6 order-md-1 order-last">
        <h3>Placement to Yard</h3>
      </div>

      <div class="col-12 col-md-6 order-md-2 order-first">
        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
          <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Yard Placement</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <section class="section">
    <div class="card" id="load_ini">
      <div class="card-header">
        <button class="btn icon icon-left btn-success" data-bs-toggle="modal" data-bs-target="#success">
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
            <?php $__currentLoopData = $formattedData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <td><?php echo e($d['container_no']); ?></td>
              <td><?php echo e($d['ctr_type']); ?></td>
              <td><?php echo e($d['yard_block']); ?></td>
              <td><?php echo e($d['yard_slot']); ?></td>
              <td><?php echo e($d['yard_row']); ?></td>
              <td><?php echo e($d['yard_tier']); ?></td>
              <td><?php echo e($d['update_time']); ?></td>
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
      <div class="modal-header bg-success">
        <h5 class="modal-title white" id="myModalLabel110">Placement</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><i data-feather="x"></i></button>
      </div>
      <div class="modal-body">
        <!-- form -->
        <div class="form-body" id="place_cont">
          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <label for="first-name-vertical">Choose Container Number</label>
                <select style="width:100%;" class="form-control container" id="key" name="container_key" required>
                  <option value="">Select Container</option>
                  <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <option value="<?php echo e($data->container_key); ?>"><?php echo e($data->container_no); ?></option>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <input type="text" id="container_no" class="form-control" name="container_no">
              </div>
              <?php echo e(csrf_field()); ?>

            </div>
            <div class="col-12">
              <div class="form-group">
                <label for="first-name-vertical">Type</label>
                <input type="text" id="tipe" class="form-control" name="ctr_type" disabled>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label for="first-name-vertical">Commodity</label>
                <input type="text" id="coname" class="form-control" name="commodity_name" disabled>
              </div>
            </div>

            <h4>Yard Planning</h4>
            <div class="col-12" style="border:1px solid blue;">
              <div class="row">

                <div class="col-6">
                  <div class="form-group">
                    <label for="first-name-vertical">Blok</label>
                    <select style="width:100%;" class="form-control block" id="block" name="yard_block" required>
                      <option value="">-</option>
                      <?php $__currentLoopData = $yard_block; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $block): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <option value="<?php echo e($block); ?>"><?php echo e($block); ?></option>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label for="first-name-vertical">Slot</label>
                    <select style="width:100%;" class="form-control slot" id="slot" name="yard_slot" required>
                      <option value="">-</option>
                      <?php $__currentLoopData = $yard_slot; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <option value="<?php echo e($slot); ?>"><?php echo e($slot); ?></option>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label for="first-name-vertical">Row</label>
                    <select style="width:100%;" class="form-control yard_row" id="row" name="yard_row" required>
                      <option value="">-</option>
                      <?php $__currentLoopData = $yard_row; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <option value="<?php echo e($row); ?>"><?php echo e($row); ?></option>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label for="first-name-vertical">Tier</label>
                    <select style="width:100%;" class="form-control tier" id="tier" name="yard_tier" required>
                      <option value="">-</option>
                      <?php $__currentLoopData = $yard_tier; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <option value="<?php echo e($tier); ?>"><?php echo e($tier); ?></option>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                  </div>
                </div>

              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label for="first-name-vertical">Planner Place</label>
                <input type="text" id="user" class="form-control" value="<?php echo e(Auth::user()->name); ?>" name="user_id" readonly>
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
  // In your Javascript (external .js resource or <script> tag)
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
  });
  $(document).on('click', '.update_status', function(e) {
    e.preventDefault();
    var container_key = $('#key').val();
    var container_no = $('#container_no').val();
    var yard_block = $('#block').val();
    var yard_slot = $('#slot').val();
    var yard_raw = $('#raw').val();
    var yard_tier = $('#tier').val();
    var data = {
      'container_key': $('#key').val(),
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
      text: "Container " + container_no + " will be placed at Block " + yard_block + " Slot " + yard_slot + " Raw " + yard_raw + " and Tier " + yard_tier,
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
          url: '/placement',
          data: data,
          cache: false,
          dataType: 'json',
          success: function(response) {
            console.log(response);
            Swal.fire('Saved!', '', 'success')

            if (response.success) {
              $('#load_ini').load(window.location.href + ' #load_ini');
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
                        url: '/container-tipe',
                        data: {
                          container_key: id
                        },
                        success: function(response) {

                          $('#container_no').val(response.container_no);
                          $('#tipe').val(response.tipe);
                          $('#coname').val(response.coname);
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
              Swal.fire('Error', response.message, 'error');
            }
          },
          error: function(data) {
            console.log('error:', data);
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
          url: '/container-tipe',
          data: {
            container_key: id
          },
          success: function(response) {

            $('#container_no').val(response.container_no);
            $('#tipe').val(response.tipe);
            $('#coname').val(response.coname);
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

<?php $__env->stopSection(); ?>
<?php echo $__env->make('partial.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Fdw Files\CTOS\dev\frontend\tos-dev-local\resources\views/yard/place/main.blade.php ENDPATH**/ ?>