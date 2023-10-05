<div class="col-12">
  <div class="card">
  <div class="card-header">
        <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#alatttt">Add</button>
    </div>
    <div class="card-body">
    <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
            <thead>
              <tr>
                <th>NO</th>
                <th>Kategori</th>
                <th>Nama Alat</th>
                <th>Created At</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
             @foreach($alat as $alt)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$alt->category}}</td>
                    <td>{{$alt->name}}</td>
                    <td>{{$alt->created_at}}</td>
                    <td>{{$alt->id}}</td>
                </tr>
             @endforeach
            </tbody>
          </table>
    </div>
  </div>
</div>

<div class="modal fade" id="alatttt" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-success">
        <h5 class="modal-title" id="exampleModalLabel">Tambah  Alat</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
                   <div class="row">
                    <div class="col-3">
                        <label for="first-name-vertical">Kategori</label>
                    </div>
                    <div class="col-7">
                        <select class="choices form-control" type="text" id="kategori_id" name="category">
                            <option value="">Pilih Category</option>
                            @foreach($category as $cat)
                                <option value="{{$cat->name}}">{{$cat->name}}</option>
                            @endforeach
                        </select>
                    </div>
                   </div>
                   <br>
                   <div class="row">
                    <div class="col-3">
                        <label for="first-name-vertical">Alat</label>
                    </div>
                    <div class="col-7">
                        <input class="form-control" type="text" id="nama_alat" name="name">
                    </div>
                   </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success addAlat">Add</button>
      </div>
    </div>
  </div>
</div>




