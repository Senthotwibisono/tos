<div class="modal fade text-left" id="modalStuffingLuar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel130" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header bg-info">
        <h5 class="modal-title white" id="myModalLabel130">Info Modal</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <i data-feather="x"></i>
        </button>
      </div>
      <div class="modal-body">
        <!-- content -->
        <div class="col-12">
          <div class="form-group">
            <label for="first-name-vertical">Choose R.O Number</label>
            <input type="text" id="nomor_ro_luar" class="form-control" name="ro_no" readonly>
            <input type="text" id="id_truck_luar" class="form-control" name="ro_id_gate" readonly>
          </div>
        </div>
        <div class="col-12">
          <div class="form-group">
            <label for="first-name-vertical">Truck No</label>
            <input type="text" id="truck_luar" class="form-control" name="truck_no" readonly>
          </div>
        </div>


        <div class="col-12">
          <div class="form-group">
            <label for="first-name-vertical">Choose Container Number</label>
            <select id="key_luar" class="choices form-control" required>
              <option value="">Select Container</option>
              @foreach($items as $item)
              <option value="{{$item->container_key}}">{{$item->container_no}}</option>
              @endforeach
            </select>

          </div>
          {{ csrf_field()}}
        </div>
        <div class="col-12">
          <div class="form-group">
            <label for="first-name-vertical">Alat</label>
            <select class="choices form-select" id="alat_luar" required>
              <option value="">Pilih Alata</option>
              @foreach($alat as $alt)
              <option value="{{$alt->id}}">{{$alt->name}}</option>
              @endforeach
            </select>
          </div>
          {{ csrf_field()}}
        </div>
        <!-- end content -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light-secondary d-block d-sm-none" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-info ml-1 d-block d-sm-none accept-stuffing-luar" data-bs-dismiss="modal">Accept</button>
        <button type="button" class="btn btn-light-secondary d-none d-sm-block"> <i class="bx bx-x"></i> Close</button>
        <button type="button" class="btn btn-info ml-1 d-none d-sm-block accept-stuffing-luar"><i class="bx bx-check"></i> Accept</button>

      </div>
    </div>
  </div>
</div>
</div>