@extends('partial.main')
@section('custom_styles')
<style>
.tier-container {
    display: flex;
    flex-wrap: fixed; /* Mengatur agar kontainer tier bisa terlipat jika ukurannya melebihi lebar kontainer induk */
    gap: 5px; /* Mengatur jarak antar kotak */
}
.kotak {
        height: 10vh;
        background-color: #F5F5F5;
        text-align: center;
        line-height: 150px;
        border-radius: 8px; /* Mengatur radius sudut kotak */
        border: 2px solid #00BFFF; /* Mengatur border dengan warna biru laut */
        font-size: 10px;
        flex: 1;
        margin: 0px;
       
    }
    .kotak.filled {
            background-color: red;
            color: #fff;
    }
    .row-container {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
    }
    .wide-body {
            width: 1500px; /* Atur lebar sesuai kebutuhan */
            margin: 0 auto; /* Untuk memusatkan card-body di tengah halaman */
    }
    .tier-label {
            width: 30px; /* Atur lebar sesuai kebutuhan */
            text-align: center;
            font-weight: bold;
    }
    
    .left-label {
            float: left;
    }
    
    .right-label {
            float: right;
    }
    
    .bay-label {
            text-align: center;
            font-weight: bold;
            margin-bottom: 10px;
    }
    .container-item {
        background-color: #fff; /* Warna default */
        border: 1px solid #000; /* Garis pembatas */
    }

    table tr,th, td
     {
        border: 1px solid #d2d2d2;
        border-collapse: collapse;
        padding: 7px 8px;
    }

    table tr, th, td {
        font-size: 15px;
    }

    table tr td {
        font-size: 13px;
    }

    table {
        border-collapse: collapse;
    }
</style>
@endsection

@section('content')
<div class="">
    <div class="card mt-5">
        <div class="card-header">
          
             <div class="row">
                    <div class="col-lg-4 mb-1">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="">Choose Ves Id</span>
                                <select class="form-select" id="vesid" name="ves_id">
                                    <option value="-">-</option>
                                    @foreach($ves as $kapal)
                                    <option value="{{ $kapal->ves_id }}" {{ $kapal->ves_id == $vessel ? 'selected' : '' }} >{{ $kapal->ves_name }} - {{ $kapal->voy_out }}</option>
                                    @endforeach
                                </select>
                        </div>
                    </div>
                    <div class="col-lg-2 mb-1">
                        <div class="input-group mb-3">
                            <span class="input-group-text">Ves Code</span>
                            <input type="text" class="form-control" id="code" name="ves_code" value="{{ $vessel != null ? $selectedVes->ves_code : '' }}" {{ $vessel != null ? 'disabled' : 'disabled' }}>
                        </div>
                    </div>
                    <div class="col-lg-3 mb-1">
                        <div class="input-group mb-3">
                            <span class="input-group-text">Ves Name</span>
                            <input type="text" class="form-control" id="name" name="ves_name" value="{{ $vessel != null ? $selectedVes->ves_name : '' }}" {{ $vessel != null ? 'disabled' : 'disabled' }}>
                        </div>
                    </div>
                    @if($vessel != null)
                    <div class="col-lg-2 mb-1">
                         <div class="input-group mb-3">
                             <span class="input-group-text" id="basic-addon2">Choose Bay</span>
                             <select class="form-select" id="bay" name="bay_slot">
                                   <option disabeled selected value>Silahkan Pilih Bay</option>
                                   @foreach($optionBay as $bayVes)
                                   <option value="{{$bayVes->BAY1}}" {{ $bayVes->BAY1 == $bay ? 'selected' : '' }}>{{$bayVes->BAY1}}</option>
                                   @endforeach
                             </select>
                         </div>
                    </div>
                    @endif
                    <div class="col-lg-1 mb-1">
                        <a href="#" class="btn icon btn-info search"><i class="bi bi-search"></i></i></a>
                    </div>
            </div>
            @if($vessel != null)
            <div class="row">
                <div class="col-2">
                    <form action="{{ route('dischCetakKapal')}}" method="get" target="_blank">
                        <input type="hidden" class="form-control"  name="ves_id" value="{{$vessel}}">
                        <button type="submit" class="btn btn-outline-primary">Cetak Perkapal</button>
                    </form>
                </div>
                @if($bay != null)
                <div class="col-2">
                    <form action="{{ route('dischCetakBay')}}" method="get" target="_blank">
                        <input type="hidden" class="form-control"  name="ves_id" value="{{$vessel}}">
                        <input type="hidden" class="form-control"  name="bay_slot" value="{{$bay}}">
                        <button type="submit" class="btn btn-outline-primary">Cetak Per Bay</button>
                    </form>
                </div>
                @endif
            </div>
            @endif
           
        </div>
        <hr>
        <div class="card-body">
            @if($bay != null && $onDeck != null && $underDeck != null)
            <div class="card" id="onDeck">
                <h4><strong>On Deck</strong></h4>
               <div class="row">
               @php
                    $rows = $onDeck->unique('bay_row')->sortByDesc('bay_row');
                    $tiers = $onDeck->unique('bay_tier')->sortByDesc('bay_tier');
                @endphp
                 <table>
                 <tr>
                    <th></th>
                        @foreach($rows as $row)
                        
                        <th class="text-center">
                            @if($row->bay_row % 2 == 0)
                            <p>{{$row->bay_row }}</p>    
                            @endif
                        </th>
                        @endforeach
                        @php
                            $reversedRows =  $onDeck->unique('bay_row')->sortBy('bay_row');
                        @endphp
                        @foreach($reversedRows as $row)
                        <th class="text-center">
                            @if($row->bay_row % 2 != 0)
                            <p>{{$row->bay_row}}</p>    
                            @endif
                        </th>
                        @endforeach  
                    </tr>
                    @foreach($tiers as $tier)
                   
                    
                    <tr>
                        <td ><h6>{{ $tier->bay_tier }}</h6></td>
                        @foreach($rows as $row)
                        @php
                            $box = $onDeck->where('bay_row', $row->bay_row)->where('bay_tier', $tier->bay_tier)->first();
                        @endphp
                        <td class="text-center">
                            @if($box && $row->bay_row % 2 == 0)
                            <div class="kotak{{($box->container_key != null && $box->ctr_i_e_t == 'I') ? ' filled' : ''}}">
                                @if($box->container_key != null && $box->ctr_i_e_t == "I")
                                    <p><Strong>{{$box->container_no}}</Strong> <br>
                                        {{$box->ctr_size}} -- {{$box->ctr_weight}}</p>
                                @endif
                                R<strong>{{ $row->bay_row }}</strong> T<strong>{{ $tier->bay_tier }}</strong> 
                            </div>
                            @endif
                        </td>
                        @endforeach
                        @php
                            $reversedRows = $onDeck->unique('bay_row')->sortBy('bay_row');
                        @endphp
                        @foreach($reversedRows as $row)
                        @php
                            $box = $onDeck->where('bay_row', $row->bay_row)->where('bay_tier', $tier->bay_tier)->first();
                        @endphp
                            <td class="text-center">
                                @if($box && $row->bay_row % 2 != 0)
                                <div class="kotak{{($box->container_key != null && $box->ctr_i_e_t == 'I') ? ' filled' : ''}}">
                                @if($box->container_key != null && $box->ctr_i_e_t == "I")
                                    <p><Strong>{{$box->container_no}}</Strong> <br>
                                        {{$box->ctr_size}} -- {{$box->ctr_weight}}</p>
                                @endif
                                    R<strong>{{ $row->bay_row }}</strong> T<strong>{{ $tier->bay_tier }}</strong> 
                                </div>
                                @endif
                            </td>
                        @endforeach
                        <td><h6>{{ $tier->bay_tier}}</h6></td>
                    </tr>
          
                    @endforeach
                 </table>
               </div>
            </div>


            <div class="card" id="underDeck">
            <h4><strong>Under Deck</strong></h4>
               <div class="row">
               @php
                    $rows = $underDeck->unique('bay_row')->sortByDesc('bay_row');
                    $tiers = $underDeck->unique('bay_tier')->sortByDesc('bay_tier');
                @endphp
                 <table>
                 <tr>
                    <th></th>
                        @foreach($rows as $row)
                        
                        <th class="text-center">
                            @if($row->bay_row % 2 == 0)
                            <p>{{$row->bay_row }}</p>    
                            @endif
                        </th>
                        @endforeach
                        @php
                            $reversedRows =  $underDeck->unique('bay_row')->sortBy('bay_row');
                        @endphp
                        @foreach($reversedRows as $row)
                        <th class="text-center">
                            @if($row->bay_row % 2 != 0)
                            <p>{{$row->bay_row}}</p>    
                            @endif
                        </th>
                        @endforeach  
                    </tr>
                    @foreach($tiers as $tier)
                   
                    
                    <tr>
                        <td ><h6>{{ $tier->bay_tier }}</h6></td>
                        @foreach($rows as $row)
                        @php
                            $box = $underDeck->where('bay_row', $row->bay_row)->where('bay_tier', $tier->bay_tier)->first();
                        @endphp
                        <td>
                            @if($box && $row->bay_row % 2 == 0)
                            <div class="kotak{{($box->container_key != null && $box->ctr_i_e_t == 'I') ? ' filled' : ''}}">
                                @if($box->container_key != null && $box->ctr_i_e_t == "I")
                                    <p><Strong>{{$box->container_no}}</Strong> <br>
                                        {{$box->ctr_size}} -- {{$box->ctr_weight}}</p>
                                @endif
                                    R<strong>{{ $row->bay_row }}</strong> T<strong>{{ $tier->bay_tier }}</strong> 
                                </div>
                            @endif
                        </td>
                        @endforeach
                        @php
                            $reversedRows = $underDeck->unique('bay_row')->sortBy('bay_row');
                        @endphp
                        @foreach($reversedRows as $row)
                        @php
                            $box = $underDeck->where('bay_row', $row->bay_row)->where('bay_tier', $tier->bay_tier)->first();
                        @endphp
                            <td>
                                @if($box && $row->bay_row % 2 != 0)
                                <div class="kotak{{($box->container_key != null && $box->ctr_i_e_t == 'I') ? ' filled' : ''}}">
                                    @if($box->container_key != null && $box->ctr_i_e_t == "I")
                                        <p><Strong>{{$box->container_no}}</Strong> <br>
                                            {{$box->ctr_size}} -- {{$box->ctr_weight}}</p>
                                    @endif
                                    R<strong>{{ $row->bay_row }}</strong> T<strong>{{ $tier->bay_tier }}</strong> 
                                </div>
                                @endif
                            </td>
                        @endforeach
                        <td><h6>{{ $tier->bay_tier}}</h6></td>
                    </tr>
          
                    @endforeach
                 </table>
               </div>
            </div>
            @else
                <h4><strong>Silahkan Pilih Bay Terlebih Dahulu</strong></h4> 
            @endif
        </div>
    </div>
</div>

@endsection

@section('custom_js')
<script src="{{ asset('vendor/components/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{asset('dist/assets/extensions/sweetalert2/sweetalert2.min.js')}}"></script>    
<script src="{{asset('dist/assets/js/pages/sweetalert2.js')}}"></script>

<script>
    $(document).ready(function () {
        $('.search').click(function () {
            var vessel = $('#vesid').val();
      

            // Now you can use startDate and endDate in your logic or navigation
            window.location.href = "/disch/view-vessel/"  + vessel;
        });

    });

</script>
<script>
    $(document).ready(function () {
        $('#bay').change(function () {
            var vessel = $('#vesid').val();
            var bay = $('#bay').val();
      
            // Redirect to the desired URL with the selected vessel value
            window.location.href = "/disch/view-vessel/"  + vessel + "/" + bay;
        });
    });
</script>


<script>
// In your Javascript (external .js resource or <script> tag)
// $(document).ready(function() {
//     $('.vesid').select2();
// });

$(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function() {
            $('#vesid').on('change', function() {
                let id = $(this).val();
                $.ajax({
                    type: 'POST',
                    url: '/get-ves',
                    data: { ves_id : id },
                    success: function(response) {
                       
                            $('#name').val(response.name);
                            $('#code').val(response.code);
                            
                        },
                    error: function(data) {
                        console.log('error:', data);
                    },
                });
            });
    });
});

$(function(){
        $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$(function(){
            $('#vesid'). on('change', function(){
                let ves_id = $('#vesid').val();

                $.ajax({
                    type: 'POST',
                    url: '/get-bay',
                    data : {ves_id : ves_id},
                    cache: false,
                    
                    success: function(msg){
                        $('#bay').html(msg);
                   
                    },
                    error: function(data){
                        console.log('error:',data)
                    },
                })                
            })
        })
    });
</script>

@endsection
