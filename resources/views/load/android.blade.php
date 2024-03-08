@extends('partial.ANDROID')
@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Confirm Load</h3>
            </div>

            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">Confirmed Load</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <button class="btn icon icon-left btn-success" data-bs-toggle="modal" data-bs-target="#success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg> Load</button>
            </div>
            <div class="card-body">
                <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>Vessel</th>
                            <th>Container No</th>
                            <th>BS || BR || BT</th>
                            <th>Crane Code</th>
                            <th>Operator</th>
                            <th>Load</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($formattedData as $d)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$d['ves_name']}} || {{$d['voy_no']}}</td>
                            <td>{{$d['container_no']}}</td>
                            <td>{{$d['bay_slot']}} || {{$d['bay_row']}} || {{$d['bay_tier']}}</td>
                            <td>{{$d['cc_tt_no']}}</td>
                            <td>{{$d['cc_tt_oper']}}</td>
                            <td>{{$d['disc_date']}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<!-- Modal Update Status -->
<div class="modal fade text-left" id="success" role="dialog" aria-labelledby="myModalLabel110" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title white" id="myModalLabel110">Confirm Load</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><i data-feather="x"></i></button>
            </div>
            <div class="modal-body">
                <!-- form -->

                <div class="form-body" id="modal-update">
                    <div class="row">
                    <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">No Alat</label>
                                <select class="choices form-control" name="cc_tt_no" id="no_alat">
                                    <option value="" disabledselected>Pilih Alat</option>
                                    @foreach($alat as $alt)
                                        <option value="{{$alt->id}}">{{$alt->name}}</option>
                                    @endforeach
                                </select>
                                <!-- <input type="text" id="no_alat" class="form-control" name="cc_tt_no" required> -->
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Op Alat</label>
                                <select class="choices form-select" id="operator">
                                    <option disabeled selected value>Pilih Satu!</option>
                                    @foreach($operator as $opr)
                                    <option value="{{$opr->id}}">{{$opr->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Choose Vessel</label>
                                <select class="choices form-select" id="id_kapal" name="ves_id" required>
                                    <option value="">Select Vessel</option>
                                    @foreach($vessel_voyage as $voy)
                                    <option value="{{$voy->ves_id}}">{{$voy->ves_name}}--{{$voy->voy_out}}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{ csrf_field()}}

                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Choose Container Number</label>
                                <br>
                                <!-- <select class="choices form-select" id="container_key" name="container_key" required>
                  <option value="-">-</option>
                </select> -->
                                <select id="container_key" class="form-control" style="font-size: 16px; width: 75%;">
                                    <!-- Existing options or a default placeholder option -->
                                    <option value="">Select a container</option>
                                </select>
                            </div>
                            {{ csrf_field()}}

                        </div>
                        <div class="col-6" style="border:1px solid blue;">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="first-name-vertical">Kapal</label>
                                        <input type="text" id="name" class="form-control" name="ves_name" readonly>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="first-name-vertical">Slot</label>
                                        <input type="text" id="slot" class="form-control" name="bay_slot">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="first-name-vertical">Row</label>
                                        <input type="text" id="row" class="form-control" name="bay_row" >
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="first-name-vertical">Tier</label>
                                        <input type="text" id="tier" class="form-control" name="bay_tier">
                                        <input type="hidden" id="container_key" name="container_key" class="form-control" required>
                                        <input type="hidden" id="container_no" name="container_no" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="first-name-vertical">Op Lapangan</label>
                                <input type="text" id="user" class="form-control" value="{{ Auth::user()->name }}" name="wharf_yard_oa" readonly>
                                <input type="datetime-local" id="tanggal" class="form-control" value="{{ $currentDateTimeString }}" name="load_date" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal"> <i class="bx bx-x d-block d-sm-none"></i><span class="d-none d-sm-block">Close</span></button>
                <button type="submit" class="btn btn-success ml-1 update_status"><i class="bx bx-check d-block d-sm-none"></i><span class="d-none d-sm-block">Confirm</span></button>
            </div>
        </div>
    </div>
</div>
<style>
    span.select2-container {
        z-index: 10050;
    }
</style>
@endsection
@section('custom_js')
<script src="{{ asset('vendor/components/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{asset('dist/assets/extensions/sweetalert2/sweetalert2.min.js')}}"></script>
<script src="{{asset('dist/assets/js/pages/sweetalert2.js')}}"></script>

<script>
    // $('.select2').select2();
</script>
<script>
    // In your Javascript (external .js resource or <script> tag)
    // $('.container').select2({
    //   dropdownParent: '#success',
    // });
    // $("#container_key").select2({
    //     dropdownParent: '#success',

    //     // dropdownParent: "#modal-container"
    // });
   
    $(document).ready(function() {});


    $(document).on('click', '.update_status', function(e) {
        e.preventDefault();
        var container_key = $('#container_key').val();
        var alat = $('#no_alat').val();
        var oper = $('#operator').val();
        if (!alat) {
        // If any of the required fields are empty, show an error message and return
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            text: 'Nomor Alat Belum Diisi, cek kembali Ya !!',
        });
        return;
        }
        if (!oper) {
        // If any of the required fields are empty, show an error message and return
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            text: 'Operator Belum Diisi, cek kembali Ya !!',
        });
        return;
        }
        var data = {
            'container_key': $('#container_key').val(),
            'container_no': $('#container_no').val(),
            'operator': $('#operator').val(),
            'cc_tt_no': $('#no_alat').val(),
            'wharf_yard_oa': $('#user').val(),
            'load_date': $('#tanggal').val(),
            'ves_name': $('#name').val(),
            'bay_slot': $('#slot').val(),
            'bay_row': $('#row').val(),
            'bay_tier': $('#tier').val(),

        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        Swal.fire({
            title: 'Are you Sure?',
            text: "Container will be updated",
            icon: 'warning',
            showDenyButton: false,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Confirm',
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {

                $.ajax({
                    type: 'POST',
                    url: '/confirm-load',
                    data: data,
                    cache: false,
                    dataType: 'json',
                    success: function(response) {
                console.log(response);
                if (response.success) {
                  Swal.fire('Saved!', '', 'success')
                  .then(() => {
                            // Memuat ulang halaman setelah berhasil menyimpan data
                            window.location.reload();
                        });
                } else {
                  Swal.fire('Error', response.message, 'error');
                }
              },
                    error: function(response) {
                        var errors = response.responseJSON.errors;
                        if (errors) {
                            var errorMessage = '';
                            $.each(errors, function(key, value) {
                                errorMessage += value[0] + '<br>';
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                html: errorMessage,
                            });
                        } else {
                            console.log('error:', response);
                        }
                    },
                });

            } else if (result.isDenied) {
                Swal.fire('Changes are not saved', '', 'info')
            }


        })

    });


    $(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function() {
            $('#container_key').on('change', function() {
                let id = $(this).val();
                $.ajax({
                    type: 'POST',
                    url: '/get-container-key-load',
                    data: {
                        container_key: id
                    },
                    success: function(response) {

                        $('#container_no').val(response.container_no);
                        $('#name').val(response.name);
                        $('#slot').val(response.slot);
                        $('#row').val(response.row);
                        $('#tier').val(response.tier);
                        $('#container_key').val(response.container_key);
                        $('#ves_id').val(response.ves_id);
                        $('#voy_no').val(response.voy_no);
                        $('#ctr_status').val(response.ctr_status);
                        $('#ctr_type').val(response.ctr_type);
                        $('#ctr_opr').val(response.ctr_opr);
                        $('#ctr_size').val(response.ctr_size);
                        $('#disc_load_trans_shift').val(response.disc_load_trans_shift);
                        $('#load_port').val(response.load_port);
                        $('#disch_port').val(response.disch_port);
                        $('#gross').val(response.gross);
                        $('#iso_code').val(response.iso_code);

                    },
                    error: function(data) {
                        console.log('error:', data);
                    },
                });
            });
        });
    });

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
                url: '/get-con-load',
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

<script>
    $("#select_province").change(function() {
        var province_id = $(this).val();
        $.ajax({
            url: 'https://dev.farizdotid.com/api/daerahindonesia/kota?id_provinsi=' + province_id,
            type: 'get',
            success: function(response) {
                var len = response.kota_kabupaten.length;
                $("#select_city").empty();
                $("#select_kecamatan").empty();
                $("#select_kelurahan").empty();
                $("#select_city").append("<option disabled default selected> Pilih Kota </option>");
                for (let i = 0; i < len; i++) {
                    let id = response.kota_kabupaten[i].id;
                    let nama = response.kota_kabupaten[i].nama;
                    $("#select_city").append("<option value='" + id + "'>" + nama + "</option>");
                }
            }
        });
    });
</script>

@endsection