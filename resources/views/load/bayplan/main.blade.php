@extends('partial.main')
@section('custom_styles')
<style>
.box1{
width:600px;
height:600px;
background:gray;
border:solid 3px black;
}

.box2{
width:50%;
height:50%;
background:gray;
border:solid 3px black;
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
</div>

<section>
   <div class="card">
      <div class="card-body">
         <div class="">
            <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
               <thead>
                  <tr>
                     <th>No</th>
                     <th>Ves Name</th>
                     <th>Ves Code</th>
                     <th>Voy</th>
                     <th>Length</th>
                     <th>Arrival Date</th>
                     <th>Deparature Date</th>
                     <th>Open Stack Date</th>
                     <th>Clossing Date</th>
                     <th>Container in Progress</th>
                     <th>Container Loaded</th>
                     <th>Action</th>
                  </tr>
               </thead>
               <tbody>
                  @foreach($ves as $kapal)
                  <tr>
                     <td>{{$loop->iteration}}</td>
                     <td>{{$kapal->ves_name}}</td>
                     <td>{{$kapal->ves_code}}</td>
                     <td>{{$kapal->voy_out}}</td>
                     <td>{{$kapal->ves_length}}</td>
                     <td>{{$kapal->arrival_date}}</td>
                     <td>{{$kapal->etd_date}}</td>
                     <td>{{$kapal->open_stack_date}}</td>
                     <td>{{$kapal->clossing_date}}</td>
                     <td>{{$kapal->containersInProgress}}</td>
                     <td>{{$kapal->containersLoaded}}</td>
                     <td>
                        <a href="" class="btn btn-outline-info">Bay Plan</a>
                     </td>
                  </tr>
                  @endforeach
               </tbody>
            </table>
         </div>
      </div>
   </div>
</section>

@endsection