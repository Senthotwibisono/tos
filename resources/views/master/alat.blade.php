@extends('partial.main')
@section('custom_style')

@endsection
@section('content')

<div class="page-title">
    <div class="row">
      <div class="col-12 col-md-6 order-md-1 order-last">
        <h3>Equipment</h3>
      </div>

      <div class="col-12 col-md-6 order-md-2 order-first">
        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
          <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Equipment</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>


  <section class="section">
    <div class="card">
      <div class="card-body">
          <div class="list-group list-group-horizontal-sm mb-1 text-center" role="tablist">
              <a class="list-group-item list-group-item-action" id="list-dalam-list" data-bs-toggle="list" href="#dalam" role="tab">Kategori</a>
              <a class="list-group-item list-group-item-action active" id="list-alat-list" data-bs-toggle="list" href="#alat" role="tab">Alat</a>
                                                  
          </div>
          <div class="tab-content text-justify" id="load_ini">
               <div class="tab-pane fade" id="dalam" role="tabpanel" aria-labelledby="list-dalam-list">
                        @include('master.alat.kategori')
               </div>
               <div class="tab-pane fade show active" id="alat" role="tabpanel"aria-labelledby="list-alat-list">
                        @include('master.alat.tabel-alat')
               </div>
          </div>
      </div>
    </div>
  </section>


@endsection
@section('custom_js')
<script>new simpleDatatables.DataTable('#kategori');</script>
<script>
     $(document).on('click', '.addCategory', function(e) {
    e.preventDefault();
 
    var name =  $('#kategori_name').val();
    var data = {
      'name': $('#kategori_name').val(),
    }
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    Swal.fire({
      title: 'Are you Sure?',
      text: "Tambah " + name + " untuk kategori alat ? ",
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
          url: '/kategori-alat',
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

<script>
     $(document).on('click', '.addAlat', function(e) {
    e.preventDefault();
 
    var name =  $('#nama_alat').val();
    var category =  $('#kategori_id').val();
    var data = {
      'name': $('#nama_alat').val(),
      'category': $('#kategori_id').val(),
    }
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    Swal.fire({
      title: 'Are you Sure?',
      text: "Tambah " + name + " untuk kategori alat ? ",
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
          url: '/add-alat',
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
@endsection