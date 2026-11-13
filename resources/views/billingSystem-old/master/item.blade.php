@extends ('partial.invoice.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <!-- <p>Input </p> -->

</div>
<div class="page-content">
    <div class="card">
        <div class="card-header">
            <button class="btn btn-warning addOS" data-bs-toggle="modal" data-bs-target="#osModal">Add Item</button>
        </div>
        <div class="card-body">
            <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Kode Zahir</th>
                        <th>Count By</th>
                        <th>Generate Cth Size</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    <tr>
                        <td>{{$item->name}}</td>
                        <td>{{$item->kode}}</td>
                        <td>{{$item->count_by}}</td>
                        <td>{{$item->size}}</td>
                        <td>
                            <div class="row">
                                <!-- <div class="col-3">
                                    <button class="btn btn-outline-warning editItemBTN" id="editItemBTN" data-id="{{ $item->id }}">Edit</button>
                                </div> -->
                                <div class="col-3">
                                    <form action="{{ route('invoice-master-itemDelete') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $item->id }}">
                                        <button class="btn btn-outline-danger" type="submit">Delete</button>
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
                <h4 class="modal-title" id="myModalLabel33">Master Item Form </h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"> <i data-feather="x"></i> </button>
            </div>
            <form action="{{ route('invoice-master-itemCreate')}}" method="post">
                @csrf
                <div class="modal-body">
                    <label>Name</label>
                    <div class="form-group">
                        <input type="text" placeholder="" name="name" class="form-control">
                    </div>
                    <label>Kode Zahir </label>
                    <div class="form-group">
                        <input type="text" name="kode" class="form-control">
                    </div>
                    <label>Count By </label>
                    <div class="form-group">
                        <select class="js-example-basic-single form-select select2" style="width: 100%;" name="count_by" >
                            <option disabled selected value>Pilih Satu!</option>
                            <option value="C">Container</option>
                            <option value="T">Time</option>
                            <option value="O">Once</option>
                            <option value="H">Hour</option>
                        </select>
                    </div>
                    <label>Genereate By Ctr Size </label>
                    <div class="form-group">
                        <select class="js-example-basic-single form-select select2" style="width: 100%;" name="size" >
                            <option disabled selected value>Pilih Satu!</option>
                            <option value="Y">Yes</option>
                            <option value="N">No</option>
                        </select>
                    </div>
                    <label>Periode Massa</label>
                    <div class="form-group">
                        <select class="js-example-basic-single form-select select2" style="width: 100%;" name="massa" id="" >
                            <option disabled selected value>Pilih Satu!</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
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
</div>

<div class="modal fade text-left" id="itemEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
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
                    <label>Massa </label>
                    <div class="form-group">
                        <select class="form-select" style="width: 100%;" name="" id="" >
                            <option disabled selected value>Pilih Satu!</option>
                            <option value="Y">2</option>
                            <option value="N">3</option>
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
</div>
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
@endsection