@extends('partial.main')
@section('custom_styles')

@endsection
@section('content')

<div class="page-title">
    <div class="row">
      <div class="col-12 col-md-6 order-md-1 order-last">
        <h3>{{$title}}</h3>
      </div>

      <div class="col-12 col-md-6 order-md-2 order-first">
        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
          <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">operator</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>


  <section class="section">
    <div class="card">
        <div class="card-header">
            <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#kategoriiii">Add</button>
        </div>
        <div class="card-body">
            <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
                <thead>
                    <tr>
                        <th></th>
                        <th>Role</th>
                        <th>Nmae</th> 
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($oper as $opr)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$opr->name}}</td>
                        @if($opr->role == "cc")
                        <td><strong>Disch/Load</strong></td>
                        @elseif($opr->role == "yard")
                        <td><strong>Yard (Placement-Container)</strong></td>
                        @else
                        <td><strong>Supir</strong></td>
                        @endif
                        <td>
                        <form action="/master/operator-delete={{$opr->id}}" method="POST">
                           @csrf
                            @method('DELETE')
                            <button type="submit" class="btn icon btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus record ini?')"> <i class="bi bi-x"></i></button>   
                            <button type="button" class="btn btn-primary edit-modal" data-bs-toggle="modal" data-id="{{ $opr->id }}"><i class="bi bi-pencil"></i></button>
                        </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
  </section>


  <!-- modal -->
<div class="modal fade" id="kategoriiii" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Operator</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        <form action="{{ route('operator-post')}}" method="post">
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="form-group">
                        <label for="">Role</label>
                        <select name="role" class="form-select choices">
                            <option disabeled selected value>Pilih Satu!</option>
                            <option value="cc">Disch/Load</option>
                            <option value="yard">Yard (Placement Container)</option>
                            <option value="truck">Supir</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Add</button>
            </div>
        </form>
    </div>
  </div>
</div>

<div class="modal fade" id="edit-opr" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="exampleModalLabel">Edit Operator</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        <form action="{{ route('operator-patch')}}" method="post">
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="form-group">
                        <label for="">Role</label>
                        <select name="role" id="role" class="form-select">
                            <option disabeled selected value>Pilih Satu!</option>
                            <option value="cc">Disch/Load</option>
                            <option value="yard">Yard (Placement Container)</option>
                            <option value="truck">Supir</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Name</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                        <input type="hidden" name="id" id="idOpr" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Add</button>
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

    $(document).on('click', '.edit-modal', function() {
        let id = $(this).data('id');
        $.ajax({
            type: 'GET',
            url: '/master/operator-edit-' + id,
            cache: false,
            data: {id: id},
            dataType: 'json',
            success: function(response) {
                console.log(response);
                $('#edit-opr').modal('show');
                $('#edit-opr #role').val(response.data.role);
                $('#edit-opr #name').val(response.data.name);
                $('#edit-opr #idOpr').val(response.data.id);


       
            },
            error: function(data) {
            console.log('error:', data);
         }
        });
    });
});

</script>
@endsection