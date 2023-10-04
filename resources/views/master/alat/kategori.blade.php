<div class="col-12">
  <div class="card">
    <div class="card-header">
        <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#kategoriiii">Add</button>
    </div>

    <div class="card-body">
    <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="kategori">
            <thead>
              <tr>
                <th>NO</th>
                <th>Kategori</th>
                <th>Created At</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
            @foreach($category as $ctg)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$ctg->name}}</td>
                    <td>{{$ctg->created_at}}</td>
                    <td>{{$ctg->id}}</td>
                </tr>
             @endforeach
            </tbody>
          </table>
    </div>
  </div>
</div>

 <div class="modal fade" id="kategoriiii" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-success">
        <h5 class="modal-title" id="exampleModalLabel">Tambah Kategori Alat</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
                   <div class="row">
                    <div class="col-3">
                        <label for="first-name-vertical">Kategori</label>
                    </div>
                    <div class="col-7">
                        <input class="form-control" type="text" id="kategori_name" name="name">
                    </div>
                   </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success addCategory">Add</button>
      </div>
    </div>
  </div>
</div>




