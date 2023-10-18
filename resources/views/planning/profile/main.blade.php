@extends('partial.main')

@section('content')
<div class="page-heading">
  <title>Ship Profile</title>
  <div class="container">
    <div class="card mt-5">
      <div class="card-header">
        <div class="col-12 col-md-6 order-md-1 order-last">
          <h3>Ship Profile</h3>
          <p class="text-subtitle text-muted"></p>
        </div>
      </div>
      <hr>
      <div class="card-body">
        <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
          <thead>
            <tr>
              <th>Ves. Name</th>
              <th>Ves. Code</th>
              <th>Liner. Name</th>
              <th>Agent</th>
              <th>Liner</th>
              <th>User Id</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($vessel_master as $vmaster)
            <tr>
              <td>{{$vmaster->ves_name}}</td>
              <td>{{$vmaster->ves_code}}</td>
              <td>{{$vmaster->liner_name}}</td>
              <td>{{$vmaster->agent}}</td>
              <td>{{$vmaster->liner}}</td>
              <td>{{$vmaster->user_id}}</td>
              <td>
                <!-- Add an action button here -->
                <!-- <button type="button" class="btn btn-primary select-kapal-btn" data-bs-toggle="modal" data-bs-target="#exampleModalCenter" data-ves-name="{{$vmaster->ves_name}}" data-ves-code="{{$vmaster->ves_code}}">
                  Select
                </button> -->
                <!-- <input type="hidden" id="vesCodeInput" name="ves_code" value=""> -->
                <a href="/planning/grid?ves_code=<?= $vmaster->ves_code ?>&ves_name=<?= $vmaster->ves_name ?>" class="btn btn-primary"><i class="fa fa-eye"></i></a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection