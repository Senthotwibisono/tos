<div class="card">
        <div class="card-header">
            <h6>Masukkan Data dengan Benar!!</h6>
            <div>
            <span>Data akan diproses secara otomatis pada tahap selanjutnya</span>
            </div>
            <hr style="border:1px solid red;">
        </div>
        <div class="card-body">
        <div class="row">
                    <div class="col-4">
                        <h6 >Nomor Truck</h6>
                    </div>
                    <div class="col-1">
                        :
                    </div>
                    <div class="col-6">
                        <select class="choices form-control" name="truck_id" id="tayo_full">
                            <option value="">Selecet Truck</option>
                            @foreach($full as $truck)
                            <option value="{{$truck->ro_id_gati}}">{{$truck->truck_no}}</option>
                            @endforeach
                        </select>
                        
                    </div>
                </div>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-outline-primary ml-1 confirm-gate-stuffing-luar"><i class="bx bx-check d-block d-sm-none"></i><span class="d-none d-sm-block">Permit</span>
                </button>
            </div>    
        </div>
    </div>