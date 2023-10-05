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
                             <a href="javascript:void(0)"class="btn icon icon-left btn-outline-danger hold" data-id="{{$items->container_key}}">Hold</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
    </div>
</div>

<div class="modal fade text-left" id="holdModal" role="dialog" aria-labelledby="myModalLabel110" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger">
        <h5 class="modal-title white" id="myModalLabel110">Holding Proccess</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><i data-feather="x"></i></button>
      </div>
      <div class="modal-body">
        <!-- form -->
                    <div class="col-12">
                      <div class="form-group">
                        <label for="">Container No</label>
                        <input type="text" class="form-control" id="contNo" readonly>
                        <input type="text" class="form-control" id="contKey" readonly>
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="form-group">
                        <label for="">No Dok</label>
                        <input type="text" class="form-control" id="dok">
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="form-group">
                        <label for="">Jenis Dok</label>
                        <input type="text" class="form-control" id="Jenisdok">
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="form-group">
                        <label for="">Alasan Segel</label>
                        <select name="" class="choices form-select" id="alasan">
                          <option value="" disabled selected values>Pilih Satu</option>
                          @foreach($alseg as $seg)
                          <option value="{{$seg->name}}">{{$seg->name}}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="form-group">
                        <label for="">Dokumen Segel</label>
                        <input type="file" class="form-control" id="file">
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="form-group">
                        <label for="">Keterangan</label>
                        <textarea name="" class="form-control" id="keterangan" cols="30" rows="5"></textarea>
                      </div>
                    </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal"> <i class="bx bx-x d-block d-sm-none"></i><span class="d-none d-sm-block">Close</span></button>
            <button type="submit" class="btn btn-danger ml-1 update_hold"><i class="bx bx-check d-block d-sm-none"></i><span class="d-none d-sm-block">Hold</span></button>
          </div>
        </div>
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
                $('#holdModal').modal('show');
                $('#holdModal #contKey').val(response.data.container_key);
                $('#holdModal #contNo').val(response.data.container_no);
              
                
                
            },
            error: function(data) {
                console.log('error:', data);
            }
        });
    });
});
    </script>
<script>
$(document).on('click', '.update_hold', function(e) {
        e.preventDefault();
        var container_key = $('#contKey').val();
        var fileInput = document.getElementById('file'); // Mengambil elemen input file
        var file = fileInput.files[0]; 
        var alasan_segel = $('#alasan').val();
        var keterangan = $('#keterangan').val();
        var formData = new FormData();
            formData.append('container_key', container_key);
            formData.append('alasan_segel', alasan_segel);
            formData.append('keterangan', keterangan);
            formData.append('file', file);
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
              url: '/hold-cont',
              data: formData,
                    cache: false,
                    contentType: false, // Set contentType ke false
                processData: false, // Set processData ke false
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
                console.log('error:', response);
              },
            });

          } else if (result.isDenied) {
            Swal.fire('Changes are not saved', '', 'info')
          }


        })

      });
</script>
@endsection