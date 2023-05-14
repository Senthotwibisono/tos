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
            Customer Data Management
          </h4>
          <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12">
              <div class="btn-group mb-3" role="group" aria-label="Basic example">
                <a id="cust_add" type="button" class="btn btn-success">
                  Tambah Data Customer
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
          <h4 class="card-title">Customer Data Table</h4>
          <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
        </div>
        <div class="card-body">
          <div class="row mt-5">
            <div class="col-12">
              <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
                <thead>
                  <tr>
                    <th>id</th>
                    <th>Customer No</th>
                    <th>Customer Name</th>
                    <th>Phone</th>
                    <th>Fax</th>
                    <th>Address</th>
                    <th>NPWP</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php for ($i = 0; $i < 20; $i++) { ?>
                    <tr>
                      <td>1</td>
                      <td>432191</td>
                      <td>Andrea Tara</td>
                      <td>08782739981</td>
                      <td>16310</td>
                      <td>Komplek Bojongsoang Permata</td>
                      <td>66009288819983999</td>
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