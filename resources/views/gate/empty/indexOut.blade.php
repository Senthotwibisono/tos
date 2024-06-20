@extends('partial.android')
@section('content')
<div class="page-heading">
  <div class="page-title">
    <div class="row">
      <div class="col-12 col-md-6 order-md-1 order-last">
        <h3>{{$title}}</h3>
      </div>
    </div>
  </div>
</div>

<section>
    <div class="card">
        <div class="card-header">
            <h4>Form Input Gate Out Ambil Empty</h4>
        </div>
        <form action="{{ route('confirmOutGateMT')}}" method="post">
            @csrf
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <label for="">Truck No</label>
                    </div>
                    <div class="col-sm-1">:</div>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="truck_no" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <label for="">Truck Out Date</label>
                    </div>
                    <div class="col-sm-1">:</div>
                    <div class="col-sm-6">
                        <input type="datetime-local" class="form-control" name="truck_out_date" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <label for="">Container</label>
                    </div>
                    <div class="col-sm-1">:</div>
                    <div class="col-sm-6">
                        <select name="container_key" class="form-select choices">
                            <option value="" disabeled> Pilih Satu! </option>
                            @foreach($containers as $container)
                            <option value="{{$container->container_key}}">{{$container->container_no}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-header">
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <button class="btn btn-outline-success" type="submit">Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<section>
    <div class="card">
        <div class="card-header">
            <h4>Last Container Permited</h4>
        </div>
        <div class="card-body">
            <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Container No</th>
                  <th>Order Service</th>
                  <th>Truck No</th>
                  <th>Truck in Date</th>
                </tr>
              </thead>
              <tbody>
                @foreach($confirmed as $itm)
                <tr>
                  <td>{{$loop->iteration}}</td>
                  <td>{{$itm->container_no}}</td>
                  <td>{{$itm->service->name ?? ''}}</td>
                  <td>{{$itm->truck_no}}</td>
                  <td>{{$itm->truck_in_date}}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
        </div>
    </div>
</section>
@endsection