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
            Data Management
          </h4>
          <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12">
              <div class="btn-group mb-3" role="group" aria-label="Basic example">
                <a href="/invoice/add/step1" type="button" class="btn btn-success">
                  Tambah Data Delivery
                </a>
                <a href=""></a>
                <a href="/invoice/container" type="button" class="btn btn-primary">
                  Lihat Data Container
                </a>
                <a href="/invoice/customer" type="button" class="btn btn-info">
                  Lihat Data Customer
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
          <h4 class="card-title">Invoice Data Table</h4>
          <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12 col-md-4">
              <div class="form-group">
                <label for="">Performa No</label>
                <input type="text" class="form-control" placeholder="Performa No">
              </div>
            </div>
            <div class="col-12 col-md-4">
              <div class="form-group">
                <label for=""> From Date</label>
                <input type="text" class="form-control" placeholder="Start">
              </div>
            </div>
            <div class="col-12 col-md-3">
              <div class="form-group">
                <label for="">To Date</label>
                <input type="text" class="form-control" placeholder="To">
              </div>
            </div>
            <div class="col m-auto">
              <div class="align-items-center mt-2">
                <a href="" class="btn block btn-primary"><i class="fa fa-search"></i></a>
              </div>
            </div>

          </div>
          <div class="row mt-5">
            <div class="col-12">
              <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
                <thead>
                  <tr>
                    <th>Performa No</th>
                    <th>Vessel</th>
                    <th>Customer</th>
                    <th>Service</th>
                    <th>Status</th>
                    <th>Pranota</th>
                    <th>Invoice</th>
                    <th>Job</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php for ($i = 0; $i < 20; $i++) { ?>
                    <tr>
                      <td>1</td>
                      <td>Vessel 1</td>
                      <td>Customer 1</td>
                      <td>Service 1</td>
                      <td><span class="badge bg-danger text-white">Not Paid</span></td>
                      <td><a type="button" class="btn btn-sm btn-warning text-white"><i class="fa fa-file"></i></a></td>
                      <td><a type="button" class="btn btn-sm btn-primary text-white disabled"><i class="fa fa-file"></i></a></td>
                      <td><a type="button" class="btn btn-sm btn-info text-white"><i class="fa fa-file"></i></a></td>
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

@endsection