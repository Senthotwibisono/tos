<div class="col-12">
  <div class="card">
    <div class="card-header">
    <button class="btn icon icon-left btn-success" data-bs-toggle="modal" data-bs-target="#success">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit">
              <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
              <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
            </svg> Confirmed</button>
    </div>
    <div class="card-body">
    <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
            <thead>
              <tr>
                <th>Container No</th>
                <th>R.O Number</th>
                <th>Type</th>
                <th>Blok</th>
                <th>Slot</th>
                <th>Row</th>
                <th>Tier</th>
                <th>Placemented At</th>
              </tr>
            </thead>
            <tbody>
              @foreach($formattedData as $d)
              <tr>
                <td>{{$d['container_no']}}</td>
                <td>{{$d['ro_no']}}</td>
                <td>{{$d['ctr_type']}}</td>
                <td>{{$d['yard_block']}}</td>
                <td>{{$d['yard_slot']}}</td>
                <td>{{$d['yard_row']}}</td>
                <td>{{$d['yard_tier']}}</td>
                <td>{{$d['update_time']}}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
    </div>
  </div>
</div>
