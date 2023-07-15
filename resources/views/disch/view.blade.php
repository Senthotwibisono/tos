@extends('partial.main')
@section('custom_styles')
<style>
    .kotak {
            width: 110px;
            height: 40px;
            background-color: #F5F5F5;
            margin: 06px;
            float: left;
            text-align: center;
            line-height: 40px;
            border-radius: 8px; /* Mengatur radius sudut kotak */
            border: 2px solid #00BFFF; /* Mengatur border dengan warna biru laut */
            font-size: 12px;
            
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
</style>
@endsection

@section('content')
<div class="container">
    <div class="card mt-5">
        <div class="card-header">
          
             <div class="row">
                    <div class="col-lg-4 mb-1">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="">Choose Ves Id</span>
                                <select class="form-select" id="vesid" name="ves_id">
                                    <option value="-">-</option>
                                    @foreach($item as $itm)
                                        
                                    <option value="{{ $itm->ves_id }}">{{ $itm->ves_name }} - {{ $itm->voy_no }}</option>
                                    @endforeach
                                </select>
                        </div>
                    </div>
                    <div class="col-lg-2 mb-1">
                        <div class="input-group mb-3">
                            <span class="input-group-text">Ves Code</span>
                            <input type="text" class="form-control" id="code"  name="ves_code" disabled>
                        </div>
                    </div>
                    <div class="col-lg-3 mb-1">
                        <div class="input-group mb-3">
                            <span class="input-group-text">Ves Name</span>
                            <input type="text" class="form-control" id="name" name="ves_name"  disabled>
                        </div>
                    </div>
                    <div class="col-lg-2 mb-1">
                         <div class="input-group mb-3">
                             <span class="input-group-text" id="basic-addon2">Choose Bay</span>
                             <select class="form-select" id="bay" name="bay_slot">
                                    <option value="-">-</option>
                                </select>
                         </div>
                    </div>
                    <div class="col-lg-1 mb-1">
                        <a href="#" class="btn icon btn-info search"><i class="bi bi-search"></i></i></a>
                    </div>
            </div>
           
        </div>
        <hr>
        <div class="card-body">
        @php
        $row = 15;
        $tier = 13;
        $evenNumbers = [94, 92, 90, 88, 86, 84, 82, 80, 14, 12, 10, 8, 6, 4, 2];
        $numberIndex = 0;
    @endphp
    <table class="table table-hover" id="">
                        <thead>
                            <tr>
                                <th></th>
                                <th>12</th>
                                <th>10</th>
                                <th>08</th>
                                <th>06</th>
                                <th>04</th>
                                <th>02</th>
                                <th>00</th>
                                <th>01</th>
                                <th>03</th>
                                <th>05</th>
                                <th>07</th>
                                <th>09</th>
                                <th>11</th>
                            </tr>
                        </thead>
                        <tbody>          
                        @for ($i = $row - 1; $i >= 0; $i--)
        <div class="row-container">
            <div class="tier-label left-label">{{$evenNumbers[$numberIndex]}}</div>

            @for ($j = 0; $j < $tier; $j++)
                <div class="kotak" id=""></div>
            @endfor

            <div class="tier-label right-label">{{$evenNumbers[$numberIndex]}}</div>

            @php
                $numberIndex++;
            @endphp
        </div>
    @endfor
                        </tbody>
                    </table>

   
            

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

    $(document).on('click', '.search', function(e) {
        // Memperbarui konten pada card-body ketika form berubah
        e.preventDefault();
            var data ={
                'ves_id': $('#vesid').val(),
                'bay_slot': $('#bay').val(),
            }
            $.ajax({
                url: "/get-container",
                type: "GET",
                data: data,
                success: function (response) {
                    updateContainerBoxes(response.item);
                }
            });
        

        // Memperbarui kotak-kotak container
        function updateContainerBoxes(item) {
            $('.kotak').removeClass('filled');
            $('.kotak').text('');

            item.forEach(function (item) {
                var row = item.bay_tier;
                var tier = item.bay_row;
                var containerNo = item.container_no;
                        switch (tier) {
                            case '00':
                                tier = 8;
                                break;
                            case '01':
                                tier = 9;
                                break;
                            case '03':
                                tier = 10;
                                break;
                            case '05':
                                tier = 11;
                                break;
                            case '07':
                                tier = 12;
                                break;
                                case '09':
                                tier = 13;
                                break;
                                case '11':
                                tier = 14;
                                break;
                                // genap
                            case '02':
                                tier = 7;
                                break;
                            case '04':
                                tier = 6;
                                break;
                            case '06':
                                tier = 5;
                                break;
                            case '08':
                                tier = 4;
                                break;
                                case '10':
                                tier = 3;
                                break;
                                case '12':
                                tier = 2;
                                break;
                            default:
                                
                            break;
                    }
                    switch (row) {
                            case '02':
                                row = 15;
                                break;
                            case '04':
                                row = 14;
                                break;
                            case '06':
                                row = 13;
                                break;
                            case '08':
                                row = 12;
                                break;
                            case '10':
                                row = 11;
                                break;
                                case '12':
                                    row = 10;
                                break;
                                case '14':
                                    row = 9;
                                break;
                                // 80 ke atas
                                case '80':
                                row = 8;
                                break;
                            case '82':
                                row = 7;
                                break;
                            case '84':
                                row = 6;
                                break;
                            case '86':
                                row = 5;
                                break;
                            case '88':
                                row = 4;
                                break;
                                case '90':
                                    row = 3;
                                break;
                                case '92':
                                    row = 2;
                                break;
                                case '94':
                                    row = 1;
                                break;
                            default:
                                
                            break;
                    }

                var containerBox = $('.row-container:nth-child(' + row + ') .kotak:nth-child(' + tier + ')');
                containerBox.text(containerNo);
                containerBox.addClass('filled');
            });
        }
    });

</script>

@endsection
