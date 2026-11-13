@extends ('partial.invoice.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Menu untuk Management Data Customer</p>

</div>
<div class="page-content">
  <section class="row">
    <div class="col-12 text-center">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">
            Customer Data Management
          </h4>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12">
              <div class="btn-group mb-3" role="group" aria-label="Basic example">
                <a href="/invoice/customer/register/createIndex" type="button" class="btn btn-success">
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
          <p>Tabel Data Akun Customer</p>
        </div>
        <div class="card-body">
          <div class="row mt-5">
            <div class="col-12">
              <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
                <thead>
                  <tr>
                    <th>no</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Customer Count</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach($custsAccount as $cust)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$cust->name}}</td>
                        <td>{{$cust->email}}</td>
                        <td>{{$cust->customerCount()}}</td>
                        <td>
                            <a href="/invoice/customer/register/editIndex{{$cust->id}}" class="btn btn-outline-warning"><i class="fa fa-pencil"></i></a>
                        </td>
                    </tr>
                    @endforeach
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