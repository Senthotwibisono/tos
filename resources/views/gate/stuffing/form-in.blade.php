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
                        <h6 >Dokumen R.O</h6>
                    </div>
                    <div class="col-1">
                        :
                    </div>
                    <div class="col-6">
                       <select name="" id="roId" class="form-select choices">
                        <option value="" disabeled selected values>Pilih Satu !</option>
                        @foreach($ro_Gate as $ro)
                        <option value="{{$ro->ro_id}}">{{$ro->ro_no}}</option>
                        @endforeach
                       </select>
                       <input type="hidden" class="form-control" id="ro" readonly>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-4">
                        <h6 >Jenis Stuffing</h6>
                    </div>
                    <div class="col-1">
                        :
                    </div>
                    <div class="col-6">
                        <input type="text" class="form-control" id="service" readonly>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-4">
                        <h6 >Nomor Truck</h6>
                    </div>
                    <div class="col-1">
                        :
                    </div>
                    <div class="col-6">
                        <input type="text" id="tayo" class="form-control">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-4">
                        <h6 >Jumlah Container dalam dokumen R.O</h6>
                    </div>
                    <div class="col-1">
                        :
                    </div>
                    <div class="col-6">
                        <input type="number" id="cont" class="form-control"readonly>
                    </div>
                </div>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-outline-primary ml-1 confirm-gate-stuffing"><i class="bx bx-check d-block d-sm-none"></i><span class="d-none d-sm-block">Permit</span>
                </button>
            </div>    
        </div>
    </div>