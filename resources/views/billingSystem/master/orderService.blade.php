@extends ('partial.invoice.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <!-- <p>Input </p> -->

</div>
<div class="page-content">
    <div class="card">
        <div class="card-header">
            <button class="btn btn-warning addOS" data-bs-toggle="modal" data-bs-target="#osModal">Buat Order Service</button>
        </div>
        <div class="card-body">
            <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Export/Import</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($os as $orderService)
                    <tr>
                        <td>{{$orderService->name}}</td>
                        <td>{{$orderService->ie}}</td>
                        <td>
                            <div class="row">
                                <div class="col-3">
                                    <a class="btn btn-outline-warning" href="/invoice/master/osDetail{{$orderService->id}}">Edit</a>
                                </div>
                                <div class="col-3">
                                    <form id="deleteForm-{{ $orderService->id }}" action="{{ route('invoice-master-osDelete') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $orderService->id }}">
                                        <button class="btn btn-outline-danger delete-btn" type="button" data-id="{{ $orderService->id }}">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade text-left" id="osModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel33">Order Service Form </h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"> <i data-feather="x"></i> </button>
            </div>
            <form action="{{ route('orderService')}}" method="post">
                @csrf
                <div class="modal-body">
                        <div class="col sm-3">
                            <label>Order Service Name </label>
                            <div class="form-group">
                                <input type="text" placeholder="" name="name" class="form-control">
                            </div>
                        </div>
                    <div class="row mb-3">
                        <div class="col sm-3">
                            <label>Type </label>
                            <div class="form-group">
                                <select class="form-select js-example-basic-single" name="ie" id="" style="width: 100%;">
                                <option disabeled selected value>Pilih Satu !!</option>
                                    <option value="I">Import</option>
                                    <option value="E">Export</option>
                                    <option value="X">Extend</option>
                                </select>
                            </div>
                        </div>
                        <div class="col sm-3">
                            <label>Order </label>
                            <div class="form-group">
                                <select class="form-select js-example-basic-single" name="order" id="" style="width: 100%;">
                                    <option disabeled selected value>Pilih Satu !!</option>
                                    <option value="SP2">SP2/RC</option>
                                    <option value="SPPS">SPPS</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col sm-3">
                            <label>Return </label>
                            <div class="form-group">
                                <select class="form-select js-example-basic-single" name="return_yn" id="" style="width: 100%;">
                                    <option disabeled selected value>Pilih Satu !!</option>
                                    <option value="Y">Yes</option>
                                    <option value="N">No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col sm-3">
                            <label>Depo Return </label>
                            <div class="form-group">
                                <select class="form-select js-example-basic-single" name="depo_return" id="" style="width: 100%;">
                                    <option disabeled selected value>Pilih Satu !!</option>
                                    <option value="N">No Return</option>
                                    <option value="IKS">IKS</option>
                                    <option value="MKB">MKB</option>
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

<!-- <div class="modal fade text-left" id="itemEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel33">Master Item Form </h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"> <i data-feather="x"></i> </button>
            </div>
            <form action="{{ route('invoice-master-itemUpdate')}}" method="post">
                @csrf
                <div class="modal-body">
                    <label>Name</label>
                    <div class="form-group">
                        <input type="text" placeholder="" name="name" id="name" class="form-control">
                        <input type="text" placeholder="" name="id" id="id" class="form-control">
                    </div>
                    <label>Kode Zahir </label>
                    <div class="form-group">
                        <input type="text" name="kode" id="kode" class="form-control">
                    </div>
                    <label>Count By </label>
                    <div class="form-group">
                        <select class="form-select" style="width: 100%;" name="count_by" id="count_by" >
                            <option disabled selected value>Pilih Satu!</option>
                            <option value="C">Container</option>
                            <option value="T">Time</option>
                            <option value="O">Once</option>
                        </select>
                    </div>
                    <label>Genereate By Ctr Size </label>
                    <div class="form-group">
                        <select class="form-select" style="width: 100%;" name="size" id="size" >
                            <option disabled selected value>Pilih Satu!</option>
                            <option value="Y">Yes</option>
                            <option value="N">No</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal"> <i class="bx bx-x d-block d-sm-none"></i> <span class="d-none d-sm-block">Close</span> </button>
                    <button type="submit" class="btn btn-primary ml-1" data-bs-dismiss="modal"> <i class="bx bx-check d-block d-sm-none"></i> <span class="d-none d-sm-block">Submit</span></button>
                </div>
            </form>
        </div>
    </div>
</div> -->
@endsection

@section('custom_js')
<script>
   $(document).on('click', '.editItemBTN', function() {
    let id = $(this).data('id');
    $.ajax({
      type: 'GET',
      url: '/invoice/master/item-' + id,
      cache: false,
      data: {
        id: id
      },
      dataType: 'json',

      success: function(response) {

        console.log(response);
        $('#itemEdit').modal('show');
        $("#itemEdit #id").val(response.data.id);
        $("#itemEdit #name").val(response.data.name);
        $("#itemEdit #kode").val(response.data.kode);
        $("#itemEdit #count_by").val(response.data.count_by);
        $("#itemEdit #size").val(response.data.size);
      },
      error: function(data) {
        console.log('error:', data)
      }
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