@extends('partial.android')
@section('custom_styles')

<style>
  .text-green {
    color: green;
}

.text-red {
    color: red;
}
</style>

@endsection
@section('content')
<div class="page-heading">
  <div class="page-title">
    <div class="row">
      <div class="col-12 col-md-6 order-md-1 order-last">
        <h3>{{$title}}</h3>
      </div>
    </div>
  </div>

  <section>
    <div class="card">
      <div class="card-body">
        <table class="dataTable-wrapper dataTable-selector dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
          <thead>
            <tr>
              <th>Container No</th>
              <th>Size</th>
              <th>Kapal</th>
              <th>Service</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @foreach($containers as $container)
            <tr>
              <td>{{$container->container_no}}</td>
              <td>{{$container->ctr_size}}</td>
              <td>{{$container->Kapal->ves_name}} -- {{$container->Kapal->voy_out}}</td>
              <td>{{$container->Form->Service->name}}</td>
              <td>
                @if($container->Form->done == 'Y')
                  <p class="text-green">Invoice Sudah Terbit</p>
                @else
                  <p class="text-red">Invoice Belum Terbit</p>
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </section>
@endsection