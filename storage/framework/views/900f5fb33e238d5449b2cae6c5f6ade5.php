<?php $__env->startSection('content'); ?>

  <div class="page-title">
    <div class="row">
      <div class="col-12 col-md-6 order-md-1 order-last">
        <h3>Stuffing</h3>
      </div>

      <div class="col-12 col-md-6 order-md-2 order-first">
        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
          <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Stuffing</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>


  <section class="section">
    <div class="card">
      <div class="card-body">
          <div class="list-group list-group-horizontal-sm mb-1 text-center" role="tablist">
              <a class="list-group-item list-group-item-action active" id="list-dalam-list" data-bs-toggle="list" href="#dalam" role="tab">Stuffing Dalam</a>
              <a class="list-group-item list-group-item-action" id="list-luar-list" data-bs-toggle="list" href="#luar" role="tab">Stuffing Luar</a>
                                                  
          </div>
          <div class="tab-content text-justify" id="load_ini">
               <div class="tab-pane fade show active" id="dalam" role="tabpanel" aria-labelledby="list-dalam-list">
                   <?php echo $__env->make('stuffing.tabel.stuffingDalam', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
               </div>
               <div class="tab-pane fade" id="luar" role="tabpanel"aria-labelledby="list-luar-list">
                    <?php echo $__env->make('stuffing.tabel.stuffingLuar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
               </div>
          </div>
      </div>
    </div>
  </section>
<?php echo $__env->make('stuffing.modal.modal-stuffing-dalam', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('stuffing.modal.modal-stuffing-luar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<!-- Modal Update Status -->

    <?php $__env->stopSection(); ?>
    <?php $__env->startSection('custom_js'); ?>
    <script src="<?php echo e(asset('vendor/components/jquery/jquery.min.js')); ?>"></script>
    <script src="<?php echo e(asset('select2/dist/js/select2.full.min.js')); ?>"></script>
    <script src="<?php echo e(asset('dist/assets/extensions/sweetalert2/sweetalert2.min.js')); ?>"></script>
    <script src="<?php echo e(asset('dist/assets/js/pages/sweetalert2.js')); ?>"></script>
    <script>new simpleDatatables.DataTable('#tableStuffingLuar');</script>

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
        var ro_no = $('#ro').val();
        var ves_id = $('#Vessel').val();
        var ves_name = $('#nama-kapal').val();
        var ves_code = $('#kode-kapal').val();
        var voy_no = $('#nomor-voyage').val();
        var container_key = $('#key').val();
        var container_no = $('#container_no').val();
        var yard_block = $('#block').val();
        var yard_slot = $('#slot').val();
        var yard_raw = $('#raw').val();
        var yard_tier = $('#tier').val();
        var truck_no = $('#truck').val();
        var data = {
          'ro_no' : $('#ro').val(),
          'ves_id' : $('#Vessel').val(),
          'ves_name' : $('#nama-kapal').val(),
          'ves_code' : $('#kode-kapal').val(),
          'voy_no' : $('#nomor-voyage').val(),
          'container_key': $('#key').val(),
          'container_no': $('#container_no').val(),
          'yard_block': $('#block').val(),
          'yard_slot': $('#slot').val(),
          'yard_row': $('#row').val(),
          'yard_tier': $('#tier').val(),
          'user_id': $('#user').val(),
          'truck_no' : $('#truck').val(),

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
              url: '/stuffing-place',
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
              url: '/get-stuffing',
              data: {
                container_key: id
              },
              success: function(response) {

                $('#container_key').val(response.container_key);
                $('#container_no').val(response.container_no);
                $('#tipe').val(response.tipe);
                $('#invoice').val(response.invoice);
                $('#oldblock').val(response.oldblock);
                $('#oldslot').val(response.oldslot);
                $('#oldrow').val(response.oldrow);
                $('#oldtier').val(response.oldtier);
              },
              error: function(data) {
                console.log('error:', data);
              },
            });
          });
        });

        $(document).ready(function() {
          $('#Vessel').on('change', function() {
            let id = $(this).val();
            $.ajax({
              type: 'POST',
              url: '/get-vessel-in-stuffing',
              data: {
                ves_id: id
              },
              success: function(response) {

                $('#nama-kapal').val(response.ves_name);
                $('#kode-kapal').val(response.ves_code);
                $('#nomor-voyage').val(response.voy_no);
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
<?php echo $__env->make('partial.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\tos\resources\views/stuffing/main.blade.php ENDPATH**/ ?>