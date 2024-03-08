@extends('partial.invoice.main')

@section('content')
<div class="page-heading">
  <h3>Tarif {{$masterTarif->os_name}}</h3>
</div>

<div class="page-content">
    <div class="card">
        <div class="card-header">
           <h6>Buat Data</h6>
        </div>
        <hr>
        <form action="/billing/import/master-tarif/update-MT" method="POST">
            @csrf
            <div class="card-body">
                        <label>Ukuran Container </label>
                        <div class="form-group">
                            <select name="ctr_size" class="js-example-basic-single form-select select2"> 
                                <option disable selected value>Pilih Satu!</option>
                                <option value="20" {{$masterTarif->ctr_size == '20' ? 'selected' : ''}}>20</option>
                                <option value="21" {{$masterTarif->ctr_size == '21' ? 'selected' : ''}}>21</option>
                                <option value="40" {{$masterTarif->ctr_size == '40' ? 'selected' : ''}}>40</option>
                                <option value="42" {{$masterTarif->ctr_size == '42' ? 'selected' : ''}}>42</option>
                            </select>
                        </div>
                <div class="row">
                <h6>Tarif Lift On Lift Of</h6>
                    <div class="col-4">
                        <label>Order Service Name </label>
                        <div class="form-group">
                            <input type="text" value="{{$masterTarif->os_name}}" name="os_name" class="form-control" readonly>
                            <input type="hidden" value="{{$masterTarif->os_id}}" name="os_id" class="form-control">
                            <input type="hidden" value="{{$masterTarif->id}}" name="id" class="form-control">
                        </div>
                    </div>
                    <div class="col-4">
                        <label>Lift ON/OFF Full </label>
                        <div class="form-group">
                            <input type="number"  name="lolo_full" value="{{$masterTarif->lolo_full}}" class="form-control">
                        </div>
                    </div>
                    <div class="col-4">
                        <label>Lift ON/OFF Empty </label>
                        <div class="form-group">
                            <input type="number"  name="lolo_empty" value="{{$masterTarif->lolo_empty}}" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <h6>Tarif Penumpukan</h6>
                    <div class="col-4">
                        <label>Massa 1 </label>
                        <div class="form-group">
                            <input type="number" name="m1" value="{{$masterTarif->m1}}" class="form-control">
                        </div>
                    </div>
                    <div class="col-4">
                        <label>Massa 2</label>
                        <div class="form-group">
                            <input type="number"  name="m2" value="{{$masterTarif->m2}}" class="form-control">
                        </div>
                    </div>
                    <div class="col-4">
                        <label>Massa 3</label>
                        <div class="form-group">
                            <input type="number"  name="m3" value="{{$masterTarif->m3}}" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <h6>Tarif Stripping</h6>
                    <div class="col-6">
                        <label>Paket Stripping </label>
                        <div class="form-group">
                            <input type="number" name="paket_stripping" value="{{$masterTarif->paket_stripping}}" class="form-control">
                        </div>
                    </div>
                    <div class="col-6">
                        <label>Pemindahan Petikemas</label>
                        <div class="form-group">
                            <input type="number"  name="pemindahan_petikemas" value="{{$masterTarif->pemindahan_petikemas}}" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <h6>Tarif Additional</h6>
                    <div class="col-3">
                        <label>Pass Truck Masuk </label>
                        <div class="form-group">
                            <input type="number" name="pass_truck_masuk" value="{{$masterTarif->pass_truck_masuk}}" class="form-control">
                        </div>
                    </div>
                    <div class="col-3">
                        <label>Pass Truck Keluar</label>
                        <div class="form-group">
                            <input type="number"  name="pass_truck_keluar" value="{{$masterTarif->pass_truck_keluar}}" class="form-control">
                        </div>
                    </div>
                    <div class="col-3">
                        <label>PPN</label>
                        <div class="form-group">
                            <input type="number"  name="pajak" value="{{$masterTarif->pajak}}" class="form-control">
                        </div>
                    </div>
                    <div class="col-3">
                        <label>Admin</label>
                        <div class="form-group">
                            <input type="number"  name="admin" value="{{$masterTarif->admin}}" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-light-secondary" onclick="window.history.back();"><i class="bx bx-x d-block d-sm-none"></i><span class="d-none d-sm-block">Back</span></button>
                <button type="submit" class="btn btn-primary ml-1"> <i class="bx bx-check d-block d-sm-none"></i> <span class="d-none d-sm-block">Submit</span></button>
            </div>
        </form>
    </div>
</div>
@endsection