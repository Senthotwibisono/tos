@extends ('partial.invoice.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Menu Untuk Delivery Form</p>

</div>
<div class="page-content">
  <section class="row">
    <div class="col-12 mb-3">
      <a href="{{ route('rental-repair-create-index')}}" type="button" class="btn btn-success">
        <i class="fa-solid fa-plus"></i>
        Buat Form
      </a>
    </div>

  </section>

  <section class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Rental & Repair Form Data Table</h4>
          <p>Tabel Form Rental & Repair</p>
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
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($formInvoiceImport as $form)
                  <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$form->customer->name ?? ''}}</td>
                    <td>{{$form->do_id}}</td>
                    <td>{{$form->service->name ?? ''}}</td>
                    <td>{{$form->expired_date ?? ''}}</td>
                    <td>
                      <div class="row">
                        <div class="col-4">
                          <a href="/renta&repair-create-edit-{{$form->id}}" class="btn btn-outline-warning">Edit</a>
                         </div>
                      </div>
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