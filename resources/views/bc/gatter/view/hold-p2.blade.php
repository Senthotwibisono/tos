@extends('partial.main')
<style>
    .border {
        border: 1px solid transparent; /* Set border dulu ke transparan */
        border-image: linear-gradient(to right, rgba(128,128,128,0.5), transparent); /* Gunakan linear gradient untuk border dengan gradasi */
        border-image-slice: 1; /* Memastikan border image mencakup seluruh border */
    }
</style>
@section('custom_styles')
@endsection

@section('content')
<div class="card">
    <div class="card-header">

    </div>
    <div class="card-body">
    <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>Container No</th>
                            <th>Iso Code</th>
                            <th>Size</th>
                            <th>Type</th>
                            <th>Jenis Dok</th>
                            <th>HOLD/RELEASE</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($item as $items)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$items->container_no}}</td>
                            <td>{{$items->iso_code}}</td>
                            <td>{{$items->ctr_size}}</td>
                            <td>{{$items->ctr_type}}</td>
                            <td>{{$items->jenis_dok}}</td>
                            <td>
                                <span class="badge bg-success text-white">Release</span>
                            </td>
                            <td>
                            <button class="btn icon icon-left btn-danger hold" data-id="{{$items->container_key}}"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>Hold</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
    </div>
</div>

@endsection

@section('custom_js')
<script>
  $(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
   

    $(document).on('click', '.hold', function() {
        let id = $(this).data('id');
        $.ajax({
            type: 'GET',
            url: '/container-hold-p2-' + id,
            cache: false,
            data: {
                container_key: id
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                $('#hold').modal('show');
                $("#hold #nomor_ro").val(response.data.ro_no);
                $("#hold #truck").val(response.data.truck_no);
                $("#hold #id_truck").val(response.data.ro_id_gati);
                
                
            },
            error: function(data) {
                console.log('error:', data);
            }
        });
    });
});
    </script>
<script>
$(document).on('click', '.holdAction', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        Swal.fire({
          title: 'Are you Sure Hold this Container?',
          text: "",
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
              url: '/release-cont',
              data: { container_key: id },
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
@endsection