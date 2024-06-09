<table class="table w-100 mt-10">
    <thead>
        <tr>
            <th colspan="12" class="text-center w-50">Baplei Muat Kapal {{$kapal->ves_name}} -- {{$kapal->voy_out}}</th>
        </tr>
        <tr>
            <th>POD</th>
            <th>POL</th>
            <th>stowage</th>
            <th>CONTAINER NO</th>
            <th>ISO CODE</th>
            <th>FULL/EMPTY(F/M)</th>
            <th>WEIGHT(ton)</th>
            <th>Temperature (Celcius)</th>
            <th>IMDG</th>
            <th>UNNO</th>
            <th>Over Height (CM)</th>
            <th>Over Size Left(CM)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($containers as $item)
            <tr>
                <td>{{$item->disch_port}}</td>
                <td>{{$item->load_port}}</td>
                <td>{{$item->bay_slot}}{{$item->bay_row}}{{$item->bay_tier}}</td>
                <td>{{$item->container_no}}</td>
                <td>{{$item->iso_code}}</td>
                <td>
                    @if($item->ctr_status == 'FCL')
                        Full
                    @else
                        Empty
                    @endif
                </td>
                <td>{{$item->gross}}</td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td>{{$item->over_height}}</td>
                <td>{{$item->over_length}}</td>
            </tr>
        @endforeach
    </tbody>
</table>