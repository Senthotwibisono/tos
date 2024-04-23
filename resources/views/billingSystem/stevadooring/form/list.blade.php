@extends ('partial.invoice.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Menu Untuk Stevadooring Form</p>

</div>
<div class="page-content">
  <section class="row">
    <div class="col-12 mb-3">
      <a href="{{ route('stevadooringForm')}}" type="button" class="btn btn-success">
        <i class="fa-solid fa-plus"></i>
        Tambah Stevadooring Form
      </a>
    </div>

  </section>

  <section class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Stevadooring Form Data Table</h4>
          <p>Tabel Form Stevadooring</p>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12">
              <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Customer</th>
                    <th>Vessel Name</th>
                    <th>Voy Out</th>
                    <th>Voy In</th>
                    <th>Arrival Date</th>
                    <th>Deparature Date</th>
                    <th>Open Stack Date</th>
                    <th>Clossing Date</th>
                    <th>Created At</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($inv as $header)
                  <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$header->cust_name}}</td>
                    <td>{{$header->ves_name}}</td>
                    <td>{{$header->voy_out}}</td>
                    <td>{{$header->voy_in}}</td>
                    <td>{{$header->arrival_date}}</td>
                    <td>{{$header->deparature_date}}</td>
                    <td>{{$header->open_stack_date}}</td>
                    <td>{{$header->clossing_date}}</td>
                    <td>{{$header->created_at}}</td>
                    <td>
                      <a class="btn btn-success text-white" href="/billing/stevadooring/edit-invoice/{{$header->id}}"><i class="fa fa-pen"></i></a>
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