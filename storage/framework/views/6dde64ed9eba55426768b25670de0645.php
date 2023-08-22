
<?php $__env->startSection('custom_styles'); ?>
<style>
    .border {
        border: 2px solid #D3D3D3; /* Warna abu-abu muda (#D3D3D3) */
        border-radius: 10px; /* Membuat border menjadi rounded dengan radius 10px */
        padding: 10px; /* Tambahkan padding agar konten tidak terlalu dekat dengan border */
    }
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Dokumen BeaCukai</h3>
                <p class="text-subtitle text-muted"></p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">Vessel Schedule</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
               <h6>Request Dokumen Form Bea Cukai</h6>
            </div>
              
            <div class="card-body">
                            <div class="list-group list-group-horizontal-sm mb-1 text-center" role="tablist">
                                <a class="list-group-item list-group-item-action active" id="list-sunday-list" data-bs-toggle="list" href="#import" role="tab">Import</a>
                                <a class="list-group-item list-group-item-action" id="list-monday-list" data-bs-toggle="list" href="#export" role="tab">Export</a>
                                <a class="list-group-item list-group-item-action" id="list-tuesday-list" data-bs-toggle="list" href="#dokumenlain" role="tab">Dokumen Lainnya...</a>
                            </div>
                            <div class="tab-content text-justify" id="load_ini">
                    <div class="tab-pane fade show active" id="import" role="tabpanel" aria-labelledby="list-sunday-list">
                        <?php echo $__env->make('invoice.bc.table.import', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                    <div class="tab-pane fade" id="export" role="tabpanel"aria-labelledby="list-monday-list">
                    <?php echo $__env->make('invoice.bc.table.export', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                    <div class="tab-pane fade" id="dokumenlain" role="tabpanel"aria-labelledby="list-tuesday-list">
                    <?php echo $__env->make('invoice.bc.table.dok-lain', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>

<?php echo $__env->make('invoice.bc.modal-sppb', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('invoice.bc.modal-npe', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('invoice.bc.modal-peabn', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('invoice.bc.model-peabn-exp', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('invoice.bc.modal-lain', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom_js'); ?>
<!-- SPPB -->
<script>
  $(document).on('click', '.download', function(e) {
    e.preventDefault();
 
    var jenis_dok =  $('#kodeSPPB').val();
    var No_Sppb =  $('#no').val();
    var Tgl_Sppb = $('#tanggal').val();
    var NPWP_IMB = $('#npwp').val();
    var data = {
      'jenis_dok': $('#kodeSPPB').val(),
      'No_Sppb':  $('#no').val(),
      'Tgl_Sppb': $('#tanggal').val(),
      'NPWP_IMB': $('#npwp').val(),
    


    }
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    Swal.fire({
      title: 'Are you Sure?',
      text: "Ambil data untuk NPWP " + NPWP_IMB + " untuk data SPPIB -IMB ? ",
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
          url: '/download-sppb',
          data: data,
          cache: false,
          dataType: 'json',
          success: function(response) {
            console.log(response);
            if (response.success) {
              Swal.fire({
                icon: 'success',
                title: 'Success',
                text: response.message,
              })
              .then(() => {
                            // Memuat ulang halaman setelah berhasil menyimpan data
                            window.location.reload();
                        });
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Error',
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
</script>

<!-- NPE -->
<script>
      $(document).on('click', '.downloadNPE', function(e) {
    e.preventDefault();
 
    var No_PE =  $('#noNPE').val();
    var KD_KANTOR = $('#kntr').val();
    var NPWP_NPE = $('#npwpNPE').val();
    var data = {
      'container_key': $('#key').val(),
      'No_PE':  $('#noNPE').val(),
      'KD_KANTOR': $('#kntr').val(),
      'NPWP_NPE': $('#npwpNPE').val(),
    


    }
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    Swal.fire({
      title: 'Are you Sure?',
      text: "Ambil data untuk NPWP " + NPWP_NPE + " untuk data SPPIB -IMB ? ",
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
          url: '/download-npe',
          data: data,
          cache: false,
          dataType: 'json',
          success: function(response) {
            console.log(response);
            if (response.success) {
              Swal.fire({
                icon: 'success',
                title: 'Success',
                text: response.message,
              })
              .then(() => {
                            // Memuat ulang halaman setelah berhasil menyimpan data
                            window.location.reload();
                        });
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Error',
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
</script>

<!-- Pabean -->
<script>
  $(document).on('click', '.download-peaBN', function(e) {
    e.preventDefault();
 
    var kode_dok =  $('#kodePab').val();
    var no_dok =  $('#no_pabean').val();
    var tgl_dok = $('#tanggal_pabean').val();
    var data = {
      'kode_dok': $('#kodePab').val(),
      'no_dok':  $('#no_pabean').val(),
      'tgl_dok': $('#tanggal_pabean').val(),
     
    


    }
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    Swal.fire({
      title: 'Are you Sure?',
      text: "Ambil data untuk Dokumen Pebean " + no_dok + " untuk data import ? ",
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
          url: '/download-Pabean',
          data: data,
          cache: false,
          dataType: 'json',
          success: function(response) {
            console.log(response);
            if (response.success) {
              Swal.fire({
                icon: 'success',
                title: 'Success',
                text: response.message,
              })
              .then(() => {
                            // Memuat ulang halaman setelah berhasil menyimpan data
                            window.location.reload();
                        });
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Error',
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
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('partial.invoice.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\tos\resources\views/invoice/bc/req-bc-dok.blade.php ENDPATH**/ ?>