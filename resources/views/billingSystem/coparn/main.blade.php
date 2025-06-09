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
                <a href="{{ route('uploadSingle')}}" type="button" class="btn btn-primary">
                  Create Single Coparn Document
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
          <h3 class="card-title">
            Table Coparn Online
          </h3>
          <p>Lorem Ipsum</p>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12">
              <table class="table table-hover table-striped" id="tableCoparn">
                <thead>
                  <tr>
                    <th>Container No</th>
                    <th>Booking No</th>
                    <th>Vessel</th>
                    <th>Edit</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

</div>

@endsection

@section('custom_js')
<script>
  $(document).ready(function () {
    $('#tableCoparn').DataTable({
      processing: true,
      serverSide: true,
      scrollY: '50vh',
      ajax: '{{route('coparn.masterData')}}',
      columns: [
        {data:'container_no', name:'container_no', className:'text-center'},
        {data:'booking_no', name:'booking_no', className:'text-center'},
        {data:'ves_name', name:'ves_name', className:'text-center'},
        {data:'edit', name:'edit', className:'text-center'},
      ]
    });
  });
</script>

@endsection