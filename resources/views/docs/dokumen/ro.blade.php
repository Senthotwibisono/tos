@extends('partial.main')

@section('custom_styles')

@endsection
@section('content')

<div class="page-heading">
  <div class="page-title">
    <div class="row">
      <div class="col-12 col-md-6 order-md-1 order-last">
        <h3>Dokumen RO</h3>
      </div>

      <div class="col-12 col-md-6 order-md-2 order-first">
        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
          <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Doc R.O</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>

 <section>
    <div class="card">
        <div class="card-header">
            <button id="upload-xls-file" type="button" class="btn btn-warning btn-sm"><i class="fa fa-plus"></i> Input Document</button>
        </div>
        <div class="card-body">
            <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
              <thead>
                  <tr>
                      <th>No</th>
                      <th>Nomor R.O</th>
                      <th>Shipper</th>
                      <th>Service</th>
                      <th>CTR 20</th>
                      <th>CTR 40</th>
                      <th>Vessel</th>
                      <th>Doks</th>
                      <th>Detail</th>
                  </tr>
              </thead>
              <tbody>
                @foreach($ro as $r)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$r->ro_no}}</td>
                    <td>{{$r->shipper}}</td>
                    <td>
                        @if($r->stuffing_service === 'in')
                            Stuffing Dalam
                        @endif
                        @if($r->stuffing_service === 'out')
                            Stuffing Luar
                        @endif
                    </td>
                    <td>{{$r->ctr_20}}</td>
                    <td>{{$r->ctr_40}}</td>
                    <td>{{$r->ves_name}} -- {{$r->voy_no}}</td>
                    <td><button type="button" class="btn btn-outline-primary DokHolding" data-bs-toggle="modal" data-id="{{$r->file}}">Doc</button></td>
                    <td>
                    <button type="button" class="btn btn-outline-success detail-ro" data-bs-toggle="modal" data-id="{{$r->ro_no}}"><i class="fa-solid fa-eye"></i></button>
                    <button type="button" class="btn btn-outline-warning edit-ro" data-bs-toggle="modal" data-id="{{$r->ro_id}}">Edit</i></button>
                    </td>
                </tr>
                @endforeach
              </tbody>
            </table>
        </div>
    </div>
 </section>


 <div class="modal fade text-left w-100" id="contRO" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h4 class="modal-title" id="myModalLabel16">Detail Container</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 border-right">
                    <div class="row" style="border-right: 2px solid blue;">
                        <h5>Container Fill</h5>
                        <div id="container-list">
                        <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="tableRO">
                    <thead>
                        <tr>
                            <th>R.O No</th>
                            <th>Container No</th>
                            <th>Truck No</th>
                                                     
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Close</span>
                </button>
            </div>
        </div>
    </div>
</div>

<div id="upload-xls-file-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Upload R.O Document</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
                <div class="modal-body"> 
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="">R.O Number</label>
                                <input type="text" class="form-control" id="roNo">
                            </div>
                            <div class="form-group">
                                <label for="">Shipper</label>
                                <input type="text" class="form-control" id="shipper">
                            </div>
                            <div class="form-group">
                                <label for="">Port of Discharge</label>
                                <select name="" id="pod" class="form-select choices">
                                    <option value="" disabeled selected values>Pilih Satu !</option>
                                    @foreach($port as $pod)
                                    <option value="{{$pod->port}}">{{$pod->port}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Load Terminal</label>
                                <select name="" id="stuffing" class="form-select choices">
                                    <option value="" disabeled selected values>Pilih Satu !</option>
                                    <option value="in">Stuffing Dalam</option>
                                    <option value="out">Stuffing Luar</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">CTR 20</label>
                                <input type="number" id="20" class="form-control">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">CTR 40</label>
                                <input type="number" id="40" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12">
                        <div class="form-group">
                                <label class="col-sm-3 control-label">Vessel</label>
                               <div class="col-sm-12">
                                 <select class="form-control choices" id="vessel" name="ves_id" style="width: 100%;" tabindex="-1" aria-hidden="true" >
                                   <option value="">Choose Vessel</option>
                                    @foreach($vessel_voyage as $ves)
                                        <option value="{{ $ves->ves_id }}">{{ $ves->ves_name.' / '.$ves->voy_out }}</option>
                                    @endforeach
                                   </select>
                               </div>
                        </div>
                            <input name="_token" type="hidden" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">File</label>
                                <div class="col-sm-8">
                                    <input type="file" id="file" name="pdf" />
                                </div>
                            </div>
                            <input type="hidden" id="user_id" class="form-control" value ="{{ Auth::user()->name }}" placeholder="{{ Auth::user()->name }}" name="user_id" required readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                 <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary gaskeun">Upload</button>

                </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- doks -->
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

<div id="edit-ro" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit R.O Document</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
                <div class="modal-body"> 
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="">R.O Number</label>
                                <input type="text" class="form-control" id="roNoEdit">
                                <input type="hidden" class="form-control" id="roIdEdit" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">Shipper</label>
                                <input type="text" class="form-control" id="shipperEdit">
                            </div>
                            <div class="form-group">
                                <label for="">Port of Discharge</label>
                                <select name="" id="podEdit" class="form-select">
                                    <option value="" disabeled selected values>Pilih Satu !</option>
                                    @foreach($port as $pod)
                                    <option value="{{$pod->port}}">{{$pod->port}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Load Terminal</label>
                                <select name="" id="stuffingEdit" class="form-select">
                                    <option value="" disabeled selected values>Pilih Satu !</option>
                                    <option value="in">Stuffing Dalam</option>
                                    <option value="out">Stuffing Luar</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">CTR 20</label>
                                <input type="number" id="20Edit" class="form-control">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">CTR 40</label>
                                <input type="number" id="40Edit" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12">
                        <div class="form-group">
                                <label class="col-sm-3 control-label">Vessel</label>
                               <div class="col-sm-12">
                                 <select class="form-select" id="vesselEdit" name="ves_id" style="width: 100%;" tabindex="-1" aria-hidden="true" >
                                   <option value="">Choose Vessel</option>
                                    @foreach($vessel_voyage as $ves)
                                        <option value="{{ $ves->ves_id }}">{{ $ves->ves_name.' / '.$ves->voy_out }}</option>
                                    @endforeach
                                   </select>
                               </div>
                        </div>
                            <input name="_token" type="hidden" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">File</label>
                                <div class="col-sm-8">
                                    <input type="file" id="fileEdit" name="pdf" />
                                </div>
                            </div>
                            <input type="hidden" id="user_id" class="form-control" value ="{{ Auth::user()->name }}" placeholder="{{ Auth::user()->name }}" name="user_id" required readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                 <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary editRo">Upload</button>

                </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endsection
@section('custom_js')
    
    <script src="{{ asset('vendor/components/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{asset('dist/assets/extensions/sweetalert2/sweetalert2.min.js')}}"></script>
    <script src="{{asset('dist/assets/js/pages/sweetalert2.js')}}"></script>


<script>
const dokButtons = document.querySelectorAll('.DokHolding');
const iframe = document.getElementById('document-iframe'); // Get the iframe element by its ID
dokButtons.forEach(button => {
    button.addEventListener('click', function() {
        const selectedFile = this.getAttribute('data-id'); // Get the file name from the button's data-id attribute
        iframe.src = "{{ route('show-document-ro', ['file' => ':file']) }}".replace(':file', selectedFile);
        // Open the modal
        const modal = new bootstrap.Modal(document.getElementById('exampleModalScrollable'));
        modal.show();
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
   

    $(document).on('click', '.detail-ro', function() {
        let id = $(this).data('id');
        $.ajax({
            type: 'GET',
            url: '/docs/dokumen/ro/detail-' + id,
            cache: false,
            data: {
                ro_no: id
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                $('#contRO').modal('show');
                var tableBody = $('#contRO #tableRO tbody');
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
                            newRow.append('<td>' + detail_cont.truck_no + '</td>');
                            tableBody.append(newRow);
                        });
                        new simpleDatatables.DataTable('#tableRO');
                      }
            },
            error: function(data) {
                console.log('error:', data);
            }
        });
    });


    $('#upload-xls-file').on("click", function(){
        $('#upload-xls-file-modal').modal('show');
 })
});
</script>

<script>
$(document).on('click', '.gaskeun', function(e) {
        e.preventDefault();
        var ro_no = $('#roNo').val();
        var stuffing_service = $('#stuffing').val();
        var shipper = $('#shipper').val();
        var pod = $('#pod').val();
        var ctr_20 = $('#20').val();
        var ctr_40 = $('#40').val();
        var ves_id = $('#vessel').val();
        var fileInput = document.getElementById('file'); // Mengambil elemen input file
        var file = fileInput.files[0]; 
        var formData = new FormData();
            formData.append('ro_no', ro_no);
            formData.append('stuffing_service', stuffing_service);
            formData.append('shipper', shipper);
            formData.append('pod', pod);
            formData.append('ctr_20', ctr_20);
            formData.append('ctr_40', ctr_40);
            formData.append('ves_id', ves_id);
            formData.append('file', file);
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        Swal.fire({
          title: 'Create this data ?',
          text: "Pastikan Data yang Dimasukkan Sudah Benar!!",
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
              url: '/docs/ro-pdf',
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

<script>
 $(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
   

    $(document).on('click', '.edit-ro', function() {
        let id = $(this).data('id');
        $.ajax({
            type: 'GET',
            url: '/docs/ro/editBy-' + id,
            cache: false,
            data: {
                ro_id: id
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                $('#edit-ro').modal('show');
                $("#edit-ro #roNoEdit").val(response.data.ro_no);
                $("#edit-ro #roIdEdit").val(response.data.ro_id);
                $("#edit-ro #shipperEdit").val(response.data.shipper);
                $("#edit-ro #20Edit").val(response.data.ctr_20);
                $("#edit-ro #40Edit").val(response.data.ctr_40);
                $("#edit-ro #podEdit").val(response.data.pod);
                $("#edit-ro #stuffingEdit").val(response.data.stuffing_service);
                $("#edit-ro #vesselEdit").val(response.data.ves_id);

                if ($("#vesselEdit").classList.contains('choices')) {
                $("#vesselEdit").choices('destroy');
            }

            if ($("#stuffingEdit").classList.contains('choices')) {
                $("#stuffingEdit").choices('destroy');
            }

            if ($("#podEdit").classList.contains('choices')) {
                $("#podEdit").choices('destroy');
            }

                var vesselSelect = new Choices("#vesselEdit", {
                removeItemButton: true, // Opsional, menambahkan tombol hapus
                });
                vesselSelect.setChoiceByValue(response.data.ves_id);

                var stuffingService = new Choices("#stuffingEdit", {
                removeItemButton: true, // Opsional, menambahkan tombol hapus
                });
                stuffingService.setChoiceByValue(response.data.stuffing_service);

                var podSelect = new Choices("#podEdit", {
                removeItemButton: true, // Opsional, menambahkan tombol hapus
                });
                podSelect.setChoiceByValue(response.data.pod);
                
                $("#edit-ro #fileEdit").val(response.data.file);
                
                
            },
            error: function(data) {
                console.log('error:', data);
            }
        });
    });

    $(document).on('click', '.editRo', function(e) {
        e.preventDefault();
        var ro_id = $('#roIdEdit').val();
        var ro_no = $('#roNoEdit').val();
        var stuffing_service = $('#stuffingEdit').val();
        var shipper = $('#shipperEdit').val();
        var pod = $('#podEdit').val();
        var ctr_20 = $('#20Edit').val();
        var ctr_40 = $('#40Edit').val();
        var ves_id = $('#vesselEdit').val();
        var fileInput = document.getElementById('fileEdit'); // Mengambil elemen input file
        var file = fileInput.files[0]; 
        var formData = new FormData();
            formData.append('ro_id', ro_id);
            formData.append('ro_no', ro_no);
            formData.append('stuffing_service', stuffing_service);
            formData.append('shipper', shipper);
            formData.append('pod', pod);
            formData.append('ctr_20', ctr_20);
            formData.append('ctr_40', ctr_40);
            formData.append('ves_id', ves_id);
            formData.append('file', file);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        Swal.fire({
            title: 'Update this data ?',
            text: "Pastikan Data yang Dimasukkan Sudah Benar!!",
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
                    url: '/docs/update-ro',
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

});
</script>
@endsection