@extends('partial.main')
@section('custom_styles')
<style>
    .border {
        border: 1px solid transparent; /* Set border dulu ke transparan */
        border-image: linear-gradient(to right, rgba(128,128,128,0.5), transparent); /* Gunakan linear gradient untuk border dengan gradasi */
        border-image-slice: 1; /* Memastikan border image mencakup seluruh border */
    }
</style>
@endsection

@section('content')
<div class="page-heading">
    <h4>{{$title}}</h4>
</div>
<div class="card">
    <div class="card-header">
        <!-- <button class="btn btn-outline-danger addCont" data-bs-toggle="modal" data-bs-target="#success">Add Container</button> -->
        <a href="{{ route('addCont-batal-muat')}}" class="btn btn-outline-danger">Add Container</a>
    </div>
    <div class="card-body">
        <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>Container No</th>
                    <th>Batal dari Kapal</th>
                    <th>Posisi Container</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($canceled as $cont)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$cont->container_no}}</td>
                    <td>{{$cont->old_ves_name}} || {{$cont->old_voy_no}}</td>
                    @if($cont->cont->ctr_intern_status == '49')
                        <td>Belum Masuk IKS</td>
                    @elseif($cont->cont->ctr_intern_status == '51')
                        <td>Gate In Reciving</td>
                    @elseif($cont->cont->ctr_intern_status == '53')
                        <td>Yard at Blok <strong>{{$cont->cont->yard_block}}</strong> Slot <strong>{{$cont->cont->yard_slot}}</strong> Row <strong>{{$cont->cont->yard_row}}</strong> Tier <strong>{{$cont->cont->yard_tier}}</strong></td>
                    @else
                        <td> </td>
                    @endif
                    <td><button class="btn btn-outline-success Action" data-id="{{$cont->id}}">Action</button></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


<!-- Modal -->
<div class="modal fade text-left" id="success" role="dialog" aria-labelledby="myModalLabel110" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title white" id="myModalLabel110">Container Batal Muat</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><i data-feather="x"></i></button>
            </div>
            <form action="{{route('post-batal-muat')}}" method="post">
            @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Select Container</label>
                        <select name="container_key[]" id="" class="form-select choices multiple-remove" data-placeholder="Pilih Container!!" multiple="multiple">
                            @foreach($item as $cont)
                            <option value="{{$cont->container_key}}">{{$cont->container_no}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Alasan Batal Muat</label>
                        <textarea class="form-control" name="alasan_batal_muat" id="exampleFormControlTextarea1" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal"> <i class="bx bx-x d-block d-sm-none"></i><span class="d-none d-sm-block">Close</span></button>
                    <button type="submit" class="btn btn-success ml-1 update_status"><i class="bx bx-check d-block d-sm-none"></i><span class="d-none d-sm-block">Confirm</span></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade text-left" id="actionCont" role="dialog" aria-labelledby="myModalLabel110" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title white" id="myModalLabel110">Action Container</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><i data-feather="x"></i></button>
            </div>
            <form action="{{ route('update-batal-muat')}}" method="post">
            @csrf
                <div class="modal-body">
                   <div class="form-group">
                        <label for="">Container</label>
                        <input type="text" class="form-control" id="ctr_no">
                        <input type="hidden" name="key" class="form-control" id="ctr_key">
                        <input type="hidden" name="id" class="form-control" id="id">
                   </div>
                   <div class="form-group">
                        <label for="">Action</label>
                        <select name="ctr_action" id="ctr_action" class="choices form-select">
                            <option disabeled selected value>Pilih Satu!</option>
                            <option value="CONTINUE">Pilih Kapal Lain</option>
                            <option value="OUT">Keluar</option>
                        </select>
                   </div>

                        <div id="kapal" style="display:none;">
                               <div class="form-group">
                                 <label for="">Select Vessel</label>
                                <select  name="ves_id" id="vesStuffing" class="choices form-select" style="height: 150%;">
                                    <option value="" disabled selected>Pilih Salah Satu</option>
                                    @foreach($vessel as $ves)
                                        <option value="{{$ves->ves_id}}">{{$ves->ves_name}} || {{$ves->voy_out}}</option>
                                    @endforeach
                                </select>
                               </div>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal"> <i class="bx bx-x d-block d-sm-none"></i><span class="d-none d-sm-block">Close</span></button>
                    <button type="submit" class="btn btn-success ml-1 update_status"><i class="bx bx-check d-block d-sm-none"></i><span class="d-none d-sm-block">Confirm</span></button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('custom_js')
@if (\Session::has('success'))
  <script type="text/javascript">
    // Add CSRF token to the headers
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var successMessage = "{!! \Session::get('success') !!}";

    if (successMessage) {
      Swal.fire({
        icon: 'success',
        title: 'Success',
        text: successMessage,
      }).then(function() {
        // Make an AJAX request to unset session variable
        $.ajax({
          url: "{{ route('unset-session', ['key' => 'success']) }}",
          type: 'POST',
          success: function(response) {
            console.log('Success session unset');
            // {{logger('Success session unset')}} -> call func logger in helper
          },
          error: function(error) {
            console.log('Error unsetting session', error);
          }
        });
      });
    }
  </script>
  @endif

<script>
 $(function() {
   $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });

    $(document).on('click', '.Action', function() {
        let id = $(this).data('id');
        $.ajax({
            type: 'GET',
            url: '/batal-muat-action',
            cache: false,
            data: {id: id},
            dataType: 'json',
            success: function(response) {
                console.log(response);
                $('#actionCont').modal('show');
                $('#actionCont #id').val(response.data.id);
                $('#actionCont #ctr_no').val(response.data.container_no);
                $('#actionCont #ctr_key').val(response.data.container_key);


       
            },
            error: function(data) {
            console.log('error:', data);
         }
        });
    });
});

</script>

<script>
  document.getElementById('ctr_action').addEventListener('change', function() {
    var selectedValue = this.value;
    var ves = document.getElementById('kapal');    
    if (selectedValue == "CONTINUE") {
      ves.style.display = 'block';
    }else{
        ves.style.display = 'none';
        ves.required = false; // Remove the required attribute
    }

    $(document).on('click', '.update_status', function(e) {
      e.preventDefault();
      var kapal = $('#vesStuffing').val();
      if ($('#ctr_action').val() === "CONTINUE" && !kapal) {
        // If CONTINUE is selected but ves_id is empty, show an error message
        Swal.fire({
          icon: 'error',
          title: 'Validation Error',
          text: 'Anda Belum Memilih Kapal, cek kembali Ya !!',
        });
        return;
      }
      
      // If all validations pass, you can submit the form
      $('form').submit();
    });
  });
</script>
@endsection