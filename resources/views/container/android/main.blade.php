@extends('partial.android')
@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>

</div>
<section>
    <div class="row">
        <div class="col-2">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon blue mb-2">
                                <i class="fa-solid fa-ship"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">
                                Veesel Count
                            </h6>
                            <h6 class="font-extrabold mb-0">
                                {{$jumlahKapal}}
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-2">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon green mb-2">
                                <i class="fa-solid fa-ship"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">
                                Vessel {{$bulan}}
                            </h6>
                            <h6 class="font-extrabold mb-0">
                                {{$kapalBulan}}
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon blue mb-2">
                                <i class="fa-solid fa-box"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">
                                Container Count
                            </h6>
                            <h6 class="font-extrabold mb-0">
                                {{$container}}
                            </h6>
                            <a href="{{ route('container-report-all-android')}}"><p>see all containers...</p></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-2">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon red mb-2">
                                <i class="fa-solid fa-box"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">
                                Import
                            </h6>
                            <h6 class="font-extrabold mb-0">
                                {{$import}}
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-2">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon purple mb-2">
                                <i class="fa-solid fa-box"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">
                                Export
                            </h6>
                            <h6 class="font-extrabold mb-0">
                                {{$export}}
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                                    <a href="/container/sortByVes-android/{{$ves->ves_id}}" class="btn btn-sm btn-info"><i class="fa fa-pencil"></i></a>
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
<!-- <script>
    $(document).ready(function() {
        $('.dataTable-wrapper').each(function() {
            $(this).DataTable();
        });
    });
</script> -->
@endsection