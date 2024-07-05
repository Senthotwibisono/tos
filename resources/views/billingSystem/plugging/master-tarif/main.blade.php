@extends('partial.invoice.main')

@section('content')
<div class="page-heading">
  <h3><?= $title ?></h3>
</div>

<div class="page-content">
    <div class="card">
        <div class="card-body">
            @foreach($orderService as $os)
            <div class="card">
                <div class="card-header">
                    <h6>Order Service : {{$os->name}}</h6>
                    <button class="btn btn-outline-success addOS" data-id="{{$os->id}}"><i class="fa fa-plus"></i>add Tarif</button>
                </div>
                <div class="card-body">
                    <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table{{$loop->iteration}}">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Ctr Size</th>
                                <th>Ctr Status</th>
                                <th>Action</th>  
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($masterTarif as $mt)
                                @if($mt->os_id == $os->id)
                                   <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$mt->ctr_size}}</td>
                                        <td>{{$mt->ctr_status}}</td>
                                        <td>
                                            <div class="row">
                                                <div class="col-3">
                                                    <a href="/plugging/master-tarifDetail-{{$mt->id}}" class="btn btn-outline-warning">Edit</a>
                                                </div>
                                                <div class="col-3">
                                                    <form id="deleteForm-{{ $mt->id }}" action="{{ route('invoice-master-tarifDelete') }}" method="post">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $mt->id }}">
                                                        <button class="btn btn-outline-danger delete-btn" type="button" data-id="{{ $mt->id }}">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                   </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <hr>
            </div>
            @endforeach
        </div>
    </div>
</div>




<!-- modal Order Service -->
<div class="modal fade text-left" id="osModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel33">Tarif Form </h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"> <i data-feather="x"></i> </button>
            </div>
            <form action="{{ route('plugging-tarif-create-first')}}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="os_id" id="os_id">
                        <input type="hidden" name="os_name" id="os_name">
                        <div class="col-6">
                            <label>Container Size </label>
                            <div class="form-group">
                                <select name="ctr_size" class="js-example-basic-single form-select select2">
                                    <option disable selected value>Pilih Satu!</option>
                                    <option value="20">20</option>
                                    <option value="21">21</option>
                                    <option value="40">40</option>
                                    <option value="42">42</option>
                                </select>
                            </div>
                        </div>
                       <div class="col-6">
                         <label>Container Status </label>
                         <div class="form-group">
                             <select name="ctr_status" class="js-example-basic-single form-select select2">
                                 <option disable selected value>Pilih Satu!</option>
                                 <option value="FCL">FCL</option>
                                 <option value="MTY">MTY</option>
                             </select>
                         </div>
                       </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal"> <i class="bx bx-x d-block d-sm-none"></i> <span class="d-none d-sm-block">Close</span> </button>
                    <button type="submit" class="btn btn-primary ml-1" data-bs-dismiss="modal"> <i class="bx bx-check d-block d-sm-none"></i> <span class="d-none d-sm-block">Submit</span></button>
                </div>
            </form>
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

    $(document).on('click', '.addOS', function() {
        let id = $(this).data('id');
        $.ajax({
            type: 'GET',
            url: '/invoice/master/tarif-modalMT',
            cache: false,
            data: {id: id},
            dataType: 'json',
            success: function(response) {
                console.log(response);
                $('#osModal').modal('show');
                $('#osModal #os_id').val(response.data.id);
                $('#osModal #os_name').val(response.data.name);
            },
            error: function(data) {
            console.log('error:', data);
         }
        });
    });
});
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Select all delete buttons
        const deleteButtons = document.querySelectorAll('.delete-btn');
        
        // Add event listener to each delete button
        deleteButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                
                const id = this.getAttribute('data-id');
                const form = document.getElementById('deleteForm-' + id);
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endsection