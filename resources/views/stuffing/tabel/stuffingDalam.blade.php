<div class="col-12">
  <div class="card">
   
    <div class="card-body">
    <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
            <thead>
              <tr>
                <th>R.O Number</th>
                <th>Shipper</th>
                <th>Kapasitas 20</th>
                <th>Kapasitas 40</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($roDalam as $in)
              <tr>
                <td>{{$in->ro_no}}</td>
                <td>{{$in->shipper}}</td>
                <td>{{$in->ctr_20}}</td>
                <td>{{$in->ctr_40}}</td>
                <td>
                      <a href="javascript:void(0)"class="btn icon icon-left btn-outline-info stuffingDalam" data-id="{{$in->ro_no}}"><i class="fa-solid fa-circle-plus"></i></a>
                      <button type="button" class="btn btn-outline-success detail-cont-stuffing" data-bs-toggle="modal" data-id="{{$in->ro_no}}">
                      <i class="fa-solid fa-eye"></i>
                      </button>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
    </div>
  </div>
</div>


@include('stuffing.modal.detail-cont')


