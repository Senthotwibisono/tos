@extends('partial.invoice.main')

@section('content')
<div class="page-heading">
  <h3><?= $title ?></h3>
</div>

<div class="page-content">
    <div class="card">
        <div class="card-header">
            <button class="btn btn-warning addOS" data-bs-toggle="modal" data-bs-target="#osModal">Buat Order Service</button>
        </div>
        <div class="card-body">
            @foreach($orderService as $os)
            <div class="card">
                <div class="card-header">
                    <h6>Order Service : {{$os->name}}</h6>
                    <a href="/billing/export/master-tarif-{{$os->id}}" class="btn btn-outline-success"><i class="fa fa-plus"></i>add Tarif</a>
                </div>
                <div class="card-body">
                    <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table{{$loop->iteration}}">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Ctr Size</th>
                                <th>Action</th>  
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($masterTarif as $mt)
                                @if($mt->os_id == $os->id)
                                   <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$mt->ctr_size}}</td>
                                        <td>
                                            <a href="/billing/export/master-tarif/edit-{{$mt->id}}" class="btn btn-outline-warning">Edit</a>
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
                <h4 class="modal-title" id="myModalLabel33">Order Service Form </h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"> <i data-feather="x"></i> </button>
            </div>
            <form action="{{ route('orderService')}}" method="post">
                @csrf
                <div class="modal-body">
                    <label>Order Service Name </label>
                    <div class="form-group">
                        <input type="text" placeholder="" name="name" class="form-control">
                    </div>
                    <label>Type: </label>
                    <div class="form-group">
                        <input type="text" name="ie" class="form-control" value="Export" readonly>
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