<?php $__env->startSection('custom_style'); ?>
<style>
    span.select2-container {
        z-index: 10050;
    }
</style>
<?php $__env->stopSection(); ?>
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
                    <?php echo $__env->make('stuffing.modal.modal-stuffing-luar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
               </div>
          </div>
      </div>
    </div>
  </section>
  <?php echo $__env->make('stuffing.modal.modal-stuffing-dalam', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>






<!-- Modal Update Status -->

    <?php $__env->stopSection(); ?>
    <?php $__env->startSection('custom_js'); ?>
    <script src="<?php echo e(asset('vendor/components/jquery/jquery.min.js')); ?>"></script>
    <script src="<?php echo e(asset('select2/dist/js/select2.full.min.js')); ?>"></script>
    <script src="<?php echo e(asset('dist/assets/extensions/sweetalert2/sweetalert2.min.js')); ?>"></script>
    <script src="<?php echo e(asset('dist/assets/js/pages/sweetalert2.js')); ?>"></script>
    <script>new simpleDatatables.DataTable('#tableStuffingLuar');</script>

    <script>

      $(document).on('click', '.update_status', function(e) {
        e.preventDefault();
        var ro_no = $('#nomor_ro').val();
        var ves_id = $('#VesselDalam').val();
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
        var ro_id_gati = $('#id_truck').val();
        var alat = $('#alat').val();
        var data = {
          'ro_no' : $('#nomor_ro').val(),
          'ves_id' : $('#VesselDalam').val(),
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
          'ro_id_gati' : $('#truck').val(),
          'alat' : $('#alat').val(),

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
                $('#status').val(response.status);
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
          $('#VesselDalam').on('change', function() {
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




<!-- Stuffing Luar -->
<script>
  $(document).on('click', '.accept-stuffing-luar', function(e) {
        e.preventDefault();
        var ro_no = $('#rnomor_ro_luar').val();
        var container_key = $('#key_luar').val();
        var container_no = $('#container_no_luar').val();
        var truck_no = $('#truck_luar').val();
        var ro_id_gati = $('#id_truck_luar').val();
        var alat = $('#alat_luar').val();
        var data = {
          'ro_no' : $('#nomor_ro_luar').val(),
          'container_key': $('#key_luar').val(),
          'truck_no' : $('#truck_luar').val(),
          'ro_id_gati' : $('#id_truck_luar').val(),
          'alat' : $('#alat_luar').val(),

        }
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        Swal.fire({
          title: 'Are you Sure?',
          text: "?",
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



      $(document).on('click', '.ConfirmOut', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        Swal.fire({
          title: 'Are you Sure?',
          text: "?",
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
              url: '/stuffing-confirm-out',
              data: { ro_id_gati: id },
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
               
              },
            });

          } else if (result.isDenied) {
            Swal.fire('Changes are not saved', '', 'info')
          }


        })

      });
</script>



    <script>
    $(function() {

      function destroyChoices(selectElement) {
        if (selectElement.data('choices')) {
            selectElement.data('choices').destroy();
        }
    }
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('click', '.stuffingDalam', function() {
        let id = $(this).data('id');
        $.ajax({
            type: 'GET',
            url: '/stuffing/stuffingDalam/modal-' + id,
            cache: false,
            data: {
                ro_no: id
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
            $('#stuffingDalamModal').modal('show');
            $("#stuffingDalamModal #nomor_ro").val(response.roData.ro_no);
            $("#stuffingDalamModal #nama-kapal").val(response.roData.ves_name);
            $("#stuffingDalamModal #kode-kapal").val(response.roData.ves_code);
            $("#stuffingDalamModal #nomor-voyage").val(response.roData.voy_no);
            $("#stuffingDalamModal #VesselDalam").val(response.roData.ves_id);

            var selectTruck = $("#truck");
            selectTruck.empty(); // Hapus semua opsi yang ada sebelumnya

            // Ambil daftar truck dari respons
            var truckList = response.roGateData;

            for (var i = 0; i < truckList.length; i++) {
                var truck = truckList[i].truck_no;
                var truckId = truckList[i].ro_id_gati;
                selectTruck.append($('<option>', {
                    value: truckId,
                    text: truck,
                }));
            }
            },
            error: function(data) {
                console.log('error:', data);
            }
        });
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

   $(document).on('click', '.stuffingLuar', function() {
      let id = $(this).data('id');
      $.ajax({
         type: 'GET',
         url: '/stuffing/stuffingLuar/modal-' + id,
         cache: false,
         data: {
            ro_id_gati: id
         },
         dataType: 'json',
         success: function(response) {
            console.log(response);
            $('#modalStuffingLuar').modal('show');
            $("#modalStuffingLuar #nomor_ro_luar").val(response.data.ro_no);
            $("#modalStuffingLuar #truck_luar").val(response.data.truck_no);
            $("#modalStuffingLuar #id_truck_luar").val(response.data.ro_id_gati);
         },
         error: function(data) {
            console.log('error:', data);
         }
      });
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
   

    $(document).on('click', '.detail-cont-stuffing', function() {
        let id = $(this).data('id');
        $.ajax({
            type: 'GET',
            url: '/stuffing/detailCont-' + id,
            cache: false,
            data: {
                ro_no: id
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                $('#detail-stuffing').modal('show');
                var tableBody = $('#detail-stuffing #tableDetail tbody');
                tableBody.empty();
                    if (response.data.cont === 0) {
                        var newRow = $('<tr>');
                        newRow.append('<td colspan="3">No Container Available</td>');
                        tableBody.append(newRow);
                    } else {
                        response.data.forEach(function(detail_cont) {
                            var newRow = $('<tr>');
                            newRow.append('<td>' + detail_cont.ro_no + '</td>');
                            newRow.append('<td>' + detail_cont.container_no + '</td>');
                         // Tombol Edit
                         var viewButton = $('<a>', {
                             href: 'javascript:void(0)',
                             class: 'btn btn-outline-primary view-detail',
                             'data-id': detail_cont.container_key,
                             html: '<i class="fa-solid fa-eye"></i>'
                         });

                         newRow.append($('<td>').append(viewButton));
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

<!-- Container View -->
<script>
 $(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
   

    $(document).on('click', '.view-detail', function() {
        let id = $(this).data('id');
        $.ajax({
            type: 'GET',
            url: '/stuffing/viewCont-' + id,
            cache: false,
            data: {
                container_key: id
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                $('#detail-container').modal('show');
                var tableBody = $('#detail-container #tableView tbody');
                tableBody.empty();
                    if (response.data.view_cont === 0) {
                        var newRow = $('<tr>');
                        newRow.append('<td colspan="3">No Container Available</td>');
                        tableBody.append(newRow);
                    } else {
                        response.data.forEach(function(view_cont) {
                            var newRow = $('<tr>');
                            newRow.append('<td>' + view_cont.container_no + '</td>');
                            newRow.append('<td>' + view_cont.iso_code + '</td>');
                            newRow.append('<td>' + view_cont.ves_name + '</td>');
                            newRow.append('<td>' + view_cont.voy_no + '</td>');
                            newRow.append('<td>' + view_cont.ctr_size + '</td>');
                            newRow.append('<td>' + view_cont.ctr_type + '</td>');
                         // Tombol Edit
                            tableBody.append(newRow);
                        });
                        new simpleDatatables.DataTable('#tableView');
                      }
            },
            error: function(data) {
                console.log('error:', data);
            }
        });
    });
});
</script>


<!-- Stuffing Luar -->
<script>
 $(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
   

    $(document).on('click', '.ContLuar', function() {
        let id = $(this).data('id');
        $.ajax({
            type: 'GET',
            url: '/stuffing/detailContLuar-' + id,
            cache: false,
            data: {
                ro_id_gati: id
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                $('#contLuar').modal('show');
                var tableBody = $('#contLuar #tableDetailLuar tbody');
                tableBody.empty();
                    if (response.data.cont === 0) {
                        var newRow = $('<tr>');
                        newRow.append('<td colspan="3">No Container Available</td>');
                        tableBody.append(newRow);
                    } else {
                        response.data.forEach(function(detail_cont) {
                            var newRow = $('<tr>');
                            newRow.append('<td>' + detail_cont.ro_no + '</td>');
                            newRow.append('<td>' + detail_cont.container_no + '</td>');
                         // Tombol Edit
                         var viewButton = $('<a>', {
                             href: 'javascript:void(0)',
                             class: 'btn btn-outline-primary ContainerLuar',
                             'data-id': detail_cont.container_key,
                             html: '<i class="fa-solid fa-eye"></i>'
                         });

                         newRow.append($('<td>').append(viewButton));
                            tableBody.append(newRow);
                        });
                        new simpleDatatables.DataTable('#tableDetailLuar');
                      }
            },
            error: function(data) {
                console.log('error:', data);
            }
        });
    });
});
</script>

<!-- Container View -->
<script>
 $(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
   

    $(document).on('click', '.ContainerLuar', function() {
        let id = $(this).data('id');
        $.ajax({
            type: 'GET',
            url: '/stuffing/viewCont-' + id,
            cache: false,
            data: {
                container_key: id
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                $('#ContainerStuffingLuar').modal('show');
                var tableBody = $('#ContainerStuffingLuar #tableContainerLuar tbody');
                tableBody.empty();
                    if (response.data.view_cont === 0) {
                        var newRow = $('<tr>');
                        newRow.append('<td colspan="3">No Container Available</td>');
                        tableBody.append(newRow);
                    } else {
                        response.data.forEach(function(view_cont) {
                            var newRow = $('<tr>');
                            newRow.append('<td>' + view_cont.container_no + '</td>');
                            newRow.append('<td>' + view_cont.iso_code + '</td>');
                            newRow.append('<td>' + view_cont.ves_name + '</td>');
                            newRow.append('<td>' + view_cont.voy_no + '</td>');
                            newRow.append('<td>' + view_cont.ctr_size + '</td>');
                            newRow.append('<td>' + view_cont.ctr_type + '</td>');
                         // Tombol Edit
                            tableBody.append(newRow);
                        });
                        new simpleDatatables.DataTable('#tableContainerLuar');
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
<?php echo $__env->make('partial.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\tos\resources\views/stuffing/main.blade.php ENDPATH**/ ?>