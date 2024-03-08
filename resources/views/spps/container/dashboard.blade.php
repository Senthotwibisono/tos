@extends ('partial.invoice.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>

</div>
<div class="page-content">
  <section class="row">
    <div class="col-12 text-center">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">
            Container Data Management
          </h4>
          <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12">
              <div class="btn-group mb-3" role="group" aria-label="Basic example">
                <a href="/invoice/container/add" type="button" class="btn btn-success">
                  Tambah Data Container
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </section>

  <section class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Container Data Table</h4>
          <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
        </div>
        <div class="card-body">
          <div class="row mt-5">
            <div class="col-12">
              <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
                <thead>
                  <tr>
                    <th>id</th>
                    <th>Container No</th>
                    <th>Vessel Name</th>
                    <th>ctr_status</th>
                    <th>ctr_intern_status</th>
                    <th>size</th>
                    <th>type</th>
                    <th>gross</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($container as $data) { ?>
                    <tr>
                      <td><?= $data->id ?></td>
                      <td><?= $data->container_no ?></td>
                      <td><?= $data->vessel_name ?></td>
                      <td><?= $data->ctr_status ?></td>
                      <td><?= $data->ctr_intern_status ?></td>
                      <td><?= $data->ctr_size ?></td>
                      <td><?= $data->ctr_type ?></td>
                      <td><?= $data->gross ?></td>
                      <td><a href="" type="button" class="btn btn-sm btn-success"><i class="fa fa-pencil"></i></a></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

@include('invoice.modal.modal')


@endsection