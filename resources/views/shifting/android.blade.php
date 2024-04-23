@extends('partial.android')

@section('content')
<div class="page-heading">
  <h3>{{$title}}</h3>
</div>

<section>
    <div class="card">
        <div class="card-header">
        <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#inlineForm">Add Shifting</button>
        </div>
        <div class="card-body">
            <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>Vessel</th>
                        <th>Container No</th>
                        <th>BF || RF || TF</th>
                        <th>BT || RT || TT</th>
                        <th>Landing</th>
                        <th>Crane Use</th>
                        <th>Crane Code</th>
                        <th>Operator</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($shifted as $load)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$load->ves_name}} || {{$load->voy_no}}</td>
                        <td>{{$load->container_no}}</td>
                        <td>{{$load->bay_from}} || {{$load->slot_from}} || {{$load->row_from}}</td>
                        <td>{{$load->bay_to}} || {{$load->slot_to}} || {{$load->row_to}}</td>
                        <td>{{$load->landing}}</td>
                        <td>
                            @if($load->crane_d_k == 'D')
                                Crane Dermaga
                            @else
                                Crane Kapal
                            @endif
                        </td>
                        <td>{{$load->alat}}</td>
                        <td>{{$load->operator}}</td>
                        <td>{{$load->shifting_time}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- modal -->
<div class="modal fade text-left" id="inlineForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel33">Shifting Form </h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"> <i data-feather="x"></i> </button>
            </div>
            <form action="/post-shifting" method="POST">
                @CSRF
                <div class="modal-body">
                    <label>Vessel </label>
                    <div class="form-group">
                        <select class="choices form-select" id="id_kapal" name="ves_id" required>
                            <option value="">Select Vessel</option>
                            @foreach($vessel_voyage as $voy)
                            <option value="{{$voy->ves_id}}">{{$voy->ves_name}}--{{$voy->voy_out}}</option>
                            @endforeach
                        </select>
                    </div>
                    <label>Container </label>
                    <div class="form-group">
                        <select id="container_key" name="container_key" class="form-control" style="font-size: 16px; width: 75%;">
                             <!-- Existing options or a default placeholder option -->
                             <option value="">Select a container</option>
                         </select>
                    </div>
                    <label>Crane Use </label>
                    <div class="form-group">
                        <select class="choices form-select" name="crane_d_k" required>
                            <option disabeled selected value>Choose One</option>
                            <option value="D">Dermaga</option>
                            <option value="K">Kapal</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label>Alat </label>
                            <div class="form-group">
                                <select class="choices form-control" name="id_alat" id="no_alat">
                                    <option value="" disabledselected>Pilih Alat</option>
                                    @foreach($alat as $alt)
                                        <option value="{{$alt->id}}">{{$alt->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <label>Operator </label>
                            <div class="form-group">
                                <select class="choices form-select" name="id_operator">
                                    <option disabeled selected value>Pilih Satu!</option>
                                    @foreach($operator as $opr)
                                    <option value="{{$opr->id}}">{{$opr->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                       <div class="col-4">
                            <label>Bay </label>
                            <div class="form-group">
                               <input type="text" name="bay_to" class="form-control">
                            </div>
                        </div>
                        <div class="col-4">
                            <label>Row </label>
                            <div class="form-group">
                               <input type="text" name="row_to" class="form-control">
                            </div>
                        </div>
                        <div class="col-4">
                            <label>Tier </label>
                            <div class="form-group">
                               <input type="text" name="tier_to" class="form-control">
                            </div>
                       </div>
                    </div>
                    <label>With Landing </label>
                    <div class="form-group">
                        <select class="choices form-select" name="landing" required>
                            <option disabeled selected value>Choose One</option>
                            <option value="Y">Y</option>
                            <option value="N">N</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal"> <i class="bx bx-x d-block d-sm-none"></i> <span class="d-none d-sm-block">Close</span> </button>
                    <button type="submit" class="btn btn-primary ml-1" data-bs-dismiss="modal"> <i class="bx bx-check d-block d-sm-none"></i> <span class="d-none d-sm-block">Submit</span></button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('custom_js')
<script>
     $(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(function() {
        const selectContainer = new Choices(document.querySelector('#container_key'), {
            // Opsi dan pengaturan Choices.js sesuai kebutuhan
        });

        $("#id_kapal").change(function() {
            let ves_id = $('#id_kapal').val();

            $.ajax({
                type: 'POST',
                url: '/get-con-shift',
                data: {
                    ves_id: ves_id
                },
                cache: false,

                success: function(msg) {
                    let res = msg;
                    var len = res.length;
                    var choicesArray = []; // Array untuk menyimpan pilihan-pilihan baru
                    for (let i = 0; i < len; i++) {
                        let id = res[i].value;
                        let nama = res[i].text;
                        choicesArray.push({ value: id, label: nama }); // Tambahkan pilihan baru ke dalam array
                    }
                    selectContainer.clearChoices(); // Hapus pilihan-pilihan saat ini
                    selectContainer.setChoices(choicesArray, 'value', 'label', false); // Atur pilihan-pilihan baru
                },
                error: function(data) {
                    console.log('error:', data)
                },
            });
        });
    });
});
</script>
@endsection