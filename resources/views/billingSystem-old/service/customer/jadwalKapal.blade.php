@extends('partial.android')

@section('content')

<section>
    <div class="page-content">
        <div class="card">
            <div class="card-header">
                <div class="text-center">
                    <h4><b>Jadwal Kapal Tersedia</b></h4>
                </div>
            </div>
            <div class="card-body">
                <table id="table1">
                    <thead>
                        <tr>
                            <th>Vessel Name</th>
                            <th>Vessel Code</th>
                            <th>Agent</th>
                            <th>Owner</th>
                            <th>Voy In</th>
                            <th>Voy Out</th>
                            <th>Arrival Date</th>
                            <th>Clossing Time</th>
                            <th>Estimate Departure Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vessels as $ves)
                            <td>{{$ves->ves_name ?? '-'}}</td>
                            <td>{{$ves->ves_code ?? '-'}}</td>
                            <td>{{$ves->agent ?? '-'}}</td>
                            <td>{{$ves->voyage_owner ?? '-'}}</td>
                            <td>{{$ves->voy_in ?? '-'}}</td>
                            <td>{{$ves->voy_out ?? '-'}}</td>
                            <td>{{$ves->arrival_date ?? '-'}}</td>
                            <td>{{$ves->clossing_date ?? '-'}}</td>
                            <td>{{$ves->etd_date ?? '-'}}</td>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

@endsection