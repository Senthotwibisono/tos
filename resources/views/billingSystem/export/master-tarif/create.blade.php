@extends('partial.invoice.main')

@section('content')
<div class="page-heading">
  <h3>Tarif {{$orderService->name}}</h3>
</div>

<div class="page-content">
    <div class="card">
        <div class="card-header">
           <h6>Buat Data</h6>
        </div>
        <hr>
        <form action="{{ route('storeMTexport') }}" method="POST">
            @csrf
            <div class="card-body">
                        <label>Ukuran Container </label>
                        <div class="form-group">
                            <select name="ctr_size" class="js-example-basic-single form-select select2">
                                <option disable selected value>Pilih Satu!</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="40">40</option>
                                <option value="42">42</option>
                            </select>
                        </div>
                <div class="row">
                <h6>Tarif Lift On Lift Of</h6>
                    <div class="col-4">
                        <label>Order Service Name </label>
                        <div class="form-group">
                            <input type="text" value="{{$orderService->name}}" name="os_name" class="form-control" readonly>
                            <input type="hidden" value="{{$orderService->id}}" name="os_id" class="form-control">
                        </div>
                    </div>
                    <div class="col-4">
                        <label>Lift ON/OFF Full </label>
                        <div class="form-group">
                            <input type="number"  name="lolo_full" class="form-control">
                        </div>
                    </div>
                    <div class="col-4">
                        <label>Lift ON/OFF Empty </label>
                        <div class="form-group">
                            <input type="number"  name="lolo_empty" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <h6>Tarif Penumpukan</h6>
                    <div class="col-4">
                        <label>Massa 1 </label>
                        <div class="form-group">
                            <input type="number" name="m1" class="form-control">
                        </div>
                    </div>
                    <div class="col-4">
                        <label>Massa 2</label>
                        <div class="form-group">
                            <input type="number"  name="m2" class="form-control">
                        </div>
                    </div>
                    <div class="col-4">
                        <label>Massa 3</label>
                        <div class="form-group">
                            <input type="number"  name="m3" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <h6>Tarif Stuffing</h6>
                    <div class="col-3">
                        <label>Paket Stuffing </label>
                        <div class="form-group">
                            <input type="number" name="paket_stuffing" class="form-control">
                        </div>
                    </div>
                    <div class="col-3">
                        <label>Cargo Dooring</label>
                        <div class="form-group">
                            <input type="number"  name="cargo_dooring" class="form-control">
                        </div>
                    </div>
                    <div class="col-3">
                        <label>JPB Ex-truck</label>
                        <div class="form-group">
                            <input type="number"  name="jpb_extruck" class="form-control">
                        </div>
                    </div>
                    <div class="col-3">
                        <label>Sewa Crane</label>
                        <div class="form-group">
                            <input type="number"  name="sewa_crane" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <h6>Tarif Additional</h6>
                    <div class="col-3">
                        <label>Pass Truck Masuk </label>
                        <div class="form-group">
                            <input type="number" name="pass_truck_masuk" class="form-control">
                        </div>
                    </div>
                    <div class="col-3">
                        <label>Pass Truck Keluar</label>
                        <div class="form-group">
                            <input type="number"  name="pass_truck_keluar" class="form-control">
                        </div>
                    </div>
                    <div class="col-3">
                        <label>PPN</label>
                        <div class="form-group">
                            <input type="number"  name="pajak" class="form-control">
                        </div>
                    </div>
                    <div class="col-3">
                        <label>Admin</label>
                        <div class="form-group">
                            <input type="number"  name="admin" class="form-control">
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