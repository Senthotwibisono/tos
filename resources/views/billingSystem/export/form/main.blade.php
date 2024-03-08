@extends ('partial.invoice.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Menu Untuk Delivery Form</p>

</div>
<div class="page-content">
  <section class="row">
    <div class="col-12 mb-3">
      <a href="{{ route('deliveryFormExport')}}" type="button" class="btn btn-success">
        <i class="fa-solid fa-plus"></i>
        Tambah Delivery Form
      </a>
    </div>

  </section>

  <section class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Delivery Form Data Table</h4>
          <p>Tabel Form Delivery</p>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12">
              <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Customer</th>
                    <th>Do Number</th>
                    <th>Order Service</th>
                    <th>Expired Date</th>
                    <th>Bill Of Loading Number</th>
                    <th>Created At</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  
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