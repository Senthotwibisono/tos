<div class="col-12">
  <div class="card">
   
    <div class="card-body">
    <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
            <thead>
              <tr>
                <th>Truck No</th>
                <th>R.O Number</th>
                <th>Jumlah Container</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($ro_gate_dalam as $in)
              <tr>
                <td>{{$in->truck_no}}</td>
                <td>{{$in->ro_no}}</td>
                <td>
                    @if(isset($container_truck[$in->ro_id_gati]))
                      {{ $container_truck[$in->ro_id_gati] }}
                    @else
                        0 <!-- Menampilkan 0 jika indeks tidak ditemukan -->
                    @endif
                </td>
                <td>
                  @if($in->status === "1") 
                  Proccess in yard
                  @elseif($in->status === '2')
                  Waiting Truck Out
                  @elseif($in->status === '3')
                  Waiting container loaded in a vessel
                  @else
                  @endif
                </td>
                <td>
                @if($in->status === "1")
                      <a href="javascript:void(0)"class="btn icon icon-left btn-outline-info stuffingDalam" data-id="{{$in->ro_id_gati}}"><i class="fa-solid fa-circle-plus"></i></a>
                      <button type="button" class="btn btn-outline-success detail-cont-stuffing" data-bs-toggle="modal" data-id="{{$in->ro_id_gati}}">
                      <i class="fa-solid fa-eye"></i>
                      </button>
                      
                      @endif
                      @if($in->status === "2")
                      <a href="javascript:void(0)"class="btn icon icon-left btn-outline-info stuffingDalam" data-id="{{$in->ro_id_gati}}"><i class="fa-solid fa-circle-plus"></i></a>
                      
                      <!-- Button trigger modal -->
                      <button type="button" class="btn btn-outline-success detail-cont-stuffing" data-bs-toggle="modal" data-id="{{$in->ro_id_gati}}">
                      <i class="fa-solid fa-eye"></i>
                      </button>
                   
                      @endif
                      @if($in->status === "3")
                      <button type="button" class="btn btn-outline-success detail-cont-stuffing" data-bs-toggle="modal" data-id="{{$in->ro_id_gati}}">
                      <i class="fa-solid fa-eye"></i>
                      </button>
                 
                      @endif
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
    </div>
  </div>
</div>


@include('stuffing.modal.detail-cont')


