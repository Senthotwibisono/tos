@extends('partial.main')

@section('content')
<div class="container">
    <h1>Grid View</h1>
    
    <table>
        <thead>
            <tr>
                <th>VES_CODE</th>
                <th>BAY1</th>
                <th>SIZE1</th>
                <th>BAY2</th>
                <th>SIZE2</th>
                <th>JOINSLOT</th>
                <th>WEIGHT_BALANCE_ON</th>
                <th>WEIGHT_BALANCE_UNDER</th>
                <th>START_ROW</th>
                <th>START_ROW_UNDER</th>
                <th>TIER</th>
                <th>TIER_UNDER</th>
                <th>MAX_ROW</th>
                <th>MAX_ROW_UNDER</th>
                <th>START_TIER</th>
                <th>START_TIER_UNDER</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($gridBoxData as $box)
                <tr>
                    <td>{{ $box->VES_CODE }}</td>
                    <td>{{ $box->BAY1 }}</td>
                    <td>{{ $box->SIZE1 }}</td>
                    <td>{{ $box->BAY2 }}</td>
                    <td>{{ $box->SIZE2 }}</td>
                    <td>{{ $box->JOINSLOT }}</td>
                    <td>{{ $box->WEIGHT_BALANCE_ON }}</td>
                    <td>{{ $box->WEIGHT_BALANCE_UNDER }}</td>
                    <td>{{ $box->START_ROW }}</td>
                    <td>{{ $box->START_ROW_UNDER }}</td>
                    <td>{{ $box->TIER }}</td>
                    <td>{{ $box->TIER_UNDER }}</td>
                    <td>{{ $box->MAX_ROW }}</td>
                    <td>{{ $box->MAX_ROW_UNDER }}</td>
                    <td>{{ $box->START_TIER }}</td>
                    <td>{{ $box->START_TIER_UNDER }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
