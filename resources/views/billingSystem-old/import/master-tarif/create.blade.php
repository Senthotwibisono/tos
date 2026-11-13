@extends('partial.invoice.main')

@section('content')
<div class="page-heading">
  <h3>Tarif {{$MasterTarif->os_name}}</h3>
</div>

<div class="page-content">
    <div class="card">
        <div class="card-header">
          <p>Container : {{$MasterTarif->ctr_size}} -- {{$MasterTarif->ctr_status}}</p>
        </div>
        <hr>
        <form action="{{ route('invoice-master-tarifDetail') }}" method="POST">
            @csrf
            <div class="cord">
                <input type="hidden" name="tarif_id" value="{{$MasterTarif->id}}">
                <div class="row">
                    @foreach($masterTarifDetail as $detail)
                    <div class="col-6">
                        <div class="form-group">
                            <label for="">{{$detail->master_item_name}}</label>
                            <input type="text" class="form-control" name="tarif[{{$detail->id}}]" value="{{$detail->tarif}}">
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="row">
                    <div class="form-group">
                        <label for="">Pajak</label>
                        <input type="number" class="form-control" name="pajak" value="{{$MasterTarif->pajak}}">
                    </div>
                </div>
            </div>
            <div class="card-footer">
               <a href="{{ route('invoice-master-tarifImport')}}" class="btn btn-light-secondary"><i class="bx bx-x d-block d-sm-none"></i><span class="d-none d-sm-block">Back</span></a>
                <button type="submit" class="btn btn-primary ml-1"> <i class="bx bx-check d-block d-sm-none"></i> <span class="d-none d-sm-block">Submit</span></button>
            </div>
        </form>
    </div>
</div>
@endsection