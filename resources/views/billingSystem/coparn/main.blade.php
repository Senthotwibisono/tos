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
          <h3 class="card-title">
            <?= $title ?>
          </h3>
          <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12">
              <div class="btn-group mb-3" role="group" aria-label="Basic example">
                <a href="{{ route('uploadView')}}" type="button" class="btn btn-success">
                  Upload Coparn Document With File
                </a>
                <button href="#" type="button" class="btn btn-primary" disabled>
                  Create Single Coparn Document
                </button>
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
          <h3 class="card-title">
            Table Coparn Online
          </h3>
          <p>Lorem Ipsum</p>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12">
              <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Container No</th>
                    <th>Booking No</th>
                    <th>Vessel</th>
                  </tr>
                </thead>
                <tbody>
                @foreach($container as $cont)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$cont->container_no}}</td>
                    <td>{{$cont->booking_no}}</td>
                    <td>{{$cont->ves_name}}</td>
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

@endsection