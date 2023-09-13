<div class="col-12">
  <div class="card">
    <div class="card-header">
    </div>
    <div class="card-body">
    <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="tableStuffingLuar">
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
              @foreach($ro_gate_luar as $in)
              <tr>
                <td>{{$in->truck_no}}</td>
                <td>{{$in->ro_no}}</td>
                <td>
                    @if(isset($container_truck_luar[$in->ro_id_gati]))
                      {{ $container_truck_luar[$in->ro_id_gati] }}
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
                  @elseif($in->status === '4')
                  Container In a Truck
                  @elseif($in->status === '5')
                  In Proccess
                  @elseif($in->status === '6')
                  In Proccess
                  @elseif($in->status === '7')
                  In Proccess
                  @elseif($in->status === '8')
                  Waiting container loaded in a vessel
                  @else
                  @endif
                </td>
                <td>
                      @if($in->status === "1")
                      <button type="button" class="btn icon icon-left btn-outline-info stuffingLuar" data-bs-toggle="modal" data-id="{{$in->ro_id_gati}}"><i class="fa-solid fa-circle-plus"></i></button>
                      @endif
                      @if($in->status === "4")      
                      <button type="button" class="btn icon icon-left btn-outline-info stuffingLuar" data-bs-toggle="modal" data-id="{{$in->ro_id_gati}}"><i class="fa-solid fa-circle-plus"></i></button>
                      <!-- Button trigger modal -->
                      <button type="button" class="btn btn-icon-left btn-outline-success ContLuar" data-bs-toggle="modal" data-id="{{$in->ro_id_gati}}"><i class="fa-solid fa-eye"></i></button>
                      @include('stuffing.modal.detail-cont-luar')

                      <button type="button" class="btn btn-icon-left btn-outline-warning ConfirmOut"  data-id="{{$in->ro_id_gati}}"><i class="fa-regular fa-circle-check"></i></button>
                      @endif
                      @if($in->status === "5")
                      <button type="button" class="btn btn-icon-left btn-outline-success ContLuar" data-bs-toggle="modal" data-id="{{$in->ro_id_gati}}"><i class="fa-solid fa-eye"></i></button>
                      @include('stuffing.modal.detail-cont-luar')
                      @endif
                      @if($in->status === "6")
                      <button type="button" class="btn btn-icon-left btn-outline-success ContLuar" data-bs-toggle="modal" data-id="{{$in->ro_id_gati}}"><i class="fa-solid fa-eye"></i></button>
                      @include('stuffing.modal.detail-cont-luar')
                      @endif
                      @if($in->status === "7")
                      <a href="/stuffing/luar/placeCont-{{$in->ro_id_gati}}" type="button" class="btn btn-icon-left btn-outline-warning"><i class="fa-solid fa-location-dot"></i></i></a>
                      <button type="button" class="btn btn-icon-left btn-outline-success ContLuar" data-bs-toggle="modal" data-id="{{$in->ro_id_gati}}"><i class="fa-solid fa-eye"></i></button>
                      @include('stuffing.modal.detail-cont-luar')
                      <button type="button" class="btn btn-icon-left btn-outline-warning ConfirmOut"  data-id="{{$in->ro_id_gati}}"><i class="fa-regular fa-circle-check"></i></button>
                      @endif
                      @if($in->status === "8")
                      
                      <button type="button" class="btn btn-icon-left btn-outline-success ContLuar" data-bs-toggle="modal" data-id="{{$in->ro_id_gati}}"><i class="fa-solid fa-eye"></i></button>
                      @include('stuffing.modal.detail-cont-luar')
                      @endif
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
    </div>
  </div>
</div>
