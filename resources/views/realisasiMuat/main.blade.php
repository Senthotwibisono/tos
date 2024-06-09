@extends('partial.main')
@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>

</div>
<section>
    <div class="card">
        <div class="card-header">
            <h4>Vessel List</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
                        <thead>
                            <tr>
                                <th>Ves. Name</th>
                                <th>Ves. Code</th>
                                <th>Voy Out</th>
                                <th>Voy In</th>
                                <th>Agent</th>
                                <th>Liner</th>
                                <th>Berth. Date</th>
                                <th>Dep. Date</th>
                                <th>action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kapal as $ves)
                            <tr>
                                <td>{{$ves->ves_name}}</td>
                                <td>{{$ves->ves_code}}</td>                            
                                <td>{{$ves->voy_out}}</td>
                                <td>{{$ves->voy_in}}</td>
                                <td>{{$ves->agent}}</td>
                                <td>{{$ves->liner}}</td>
                                <td>{{$ves->berthing_date}}</td>
                                <td>{{$ves->etd_date}}</td>
                                <td>
                                    <a href="javascript:void(0)" onclick="openWindow('/planning/detail/realisasiMuat/{{$ves->ves_id}}')" class="btn btn-sm btn-info"><i class="fa fa-eye"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@section('custom_js')
<script>
    function openWindow(url) {
        window.open(url, '_blank', 'width=1000,height=1000');
    }
</script>
@endsection