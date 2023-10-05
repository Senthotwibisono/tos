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
                            <th>NO Dok</th>
                            <th>Jenis Dok</th>
                            <th>Keterangan</th>
                            <th>Alasan Segel</th>
                            <th>File</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                      @foreach($segel as $seg)
                      <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$seg->container_no}}</td>
                        <td>{{$seg->no_dok}}</td>
                        <td>{{$seg->jenis_dok}}</td>
                        <td>{{$seg->keterangan}}</td>
                        <td>{{$seg->alasan_segel}}</td>
                        <td>
                              <button type="button" class="btn btn-outline-success DokHolding" data-bs-toggle="modal" data-id="{{$seg->file}}">
                            <i class="fa-solid fa-eye"></i>
                            </button>
                        </td>
                        <td>
                        <button class="btn icon icon-left btn-success release" data-id="{{$seg->container_key}}"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>Release</button>
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                </table>
    </div>
</div>
<div class="modal fade" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalScrollableTitle"></h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                 <iframe id="document-iframe" src="" frameborder="0" width="100%" height="500"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Close</span>
                </button>
                <button type="button" class="btn btn-primary ml-1" data-bs-dismiss="modal">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Accept</span>
                </button>
            </div>
        </div>
    </div>
</div>
<!--  -->

@endsection

@section('custom_js')
<script>
    const dokButtons = document.querySelectorAll('.DokHolding');
    const iframe = document.getElementById('document-iframe'); // Get the iframe element by its ID

    dokButtons.forEach(button => {
        button.addEventListener('click', function() {
            const selectedFile = this.getAttribute('data-id'); // Get the file name from the button's data-id attribute
            iframe.src = "{{ route('show-document', ['file' => ':file']) }}".replace(':file', selectedFile);

            // Open the modal
            const modal = new bootstrap.Modal(document.getElementById('exampleModalScrollable'));
            modal.show();
        });
    });
</script>

<script>
$(document).on('click', '.release', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        Swal.fire({
          title: 'Are you Sure Release this Container?',
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
              url: '/release-cont-p2',
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