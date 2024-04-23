@extends('partial.invoice.main')

@section('content')
<div class="page-heading">
  <h3>{{$title}}</h3>
</div>
<div class="page-content">
    <div class="card">
        <div class="card-header">
            
        </div>
        <div class="card-body">
            <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>Ves Code</th>
                        <th>Vessel</th>
                        <th>Voy Out</th>
                        <th>Arrival Date</th>
                        <th>Deparature Date</th>
                        <th>Open Stuck Date</th>
                        <th>Clossing Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                   @foreach($realisasiBongkarMuat as $rbm)
                    <tr>
                        <th>{{$loop->iteration}}</th>
                        <th>{{$rbm->ves_code}}</th>
                        <th>{{$rbm->ves_name}}</th>
                        <th>{{$rbm->voy_out}}</th>
                        <th>{{$rbm->arrival_date}}</th>
                        <th>{{$rbm->deparature_date}}</th>
                        <th>{{$rbm->open_stack_date}}</th>
                        <th>{{$rbm->clossing_date}}</th>
                        <th>
                            <a href="/billing/stevadooring/RBM-detali/{{$rbm->id}}" class="btn btn-outline-warning">Detail</a>
                        </th>
                    </tr>
                   @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <h4>Add Vessel</h4>
            <form action="{{ route('index-stevadooring-RBM_Create')}}" method="get">
                <div class="row">
                    <div class="col-8">
                        <div class="form-group">
                            <label for="">Choose Vessel</label>
                            <select name="ves_id"  class="js-example-basic-single form-select select2">
                                <option disabeled selected values>Pilih Satu</option>
                                @foreach($ves as $kapal)
                                    <option value="{{$kapal->ves_id}}">{{$kapal->ves_name}}--{{$kapal->voy_out}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <label for="">Add Vessel</label>
                            <br>
                            <button type="submit" class="btn btn-outline-info"><i class="fa fa-magnifying-glass"></i></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection