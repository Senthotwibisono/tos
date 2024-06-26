@extends('partial.main')
@section('custom_styles')
<style>
    .border {
        border: 2px solid #D3D3D3;
        /* Warna abu-abu muda (#D3D3D3) */
        border-radius: 10px;
        /* Membuat border menjadi rounded dengan radius 10px */
        padding: 10px;
        /* Tambahkan padding agar konten tidak terlalu dekat dengan border */
    }
</style>
@endsection
@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Placement to Yard</h3>
            </div>

            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">Yard Placement</li>
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
                    </svg> Confirmed</button>
            </div>
            <div class="card-body">
                <div class="list-group list-group-horizontal-sm mb-1 text-center" role="tablist">
                    <a class="list-group-item list-group-item-action active" id="list-sunday-list" data-bs-toggle="list" href="#import" role="tab">Import</a>
                    <a class="list-group-item list-group-item-action" id="list-monday-list" data-bs-toggle="list" href="#export" role="tab">Export</a>
                    <a class="list-group-item list-group-item-action" id="list-tuesday-list" data-bs-toggle="list" href="#exstrip" role="tab">Relokasi Pelindo</a>
                    <a class="list-group-item list-group-item-action" id="list-mty-list" data-bs-toggle="list" href="#mty" role="tab">Empty/Ex-stripping</a>
                </div>
                <div class="tab-content text-justify" id="load_ini">
                    <div class="tab-pane fade show active" id="import" role="tabpanel" aria-labelledby="list-sunday-list">
                        <div class="col-12 border">
                            <div class="card-body">
                                <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
                                    <thead>
                                        <tr>
                                            <th>Container No</th>
                                            <th>Type</th>
                                            <th>Blok</th>
                                            <th>Slot</th>
                                            <th>Row</th>
                                            <th>Tier</th>
                                            <th>Placemented At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($confirmed as $d)
                                        @if($d->ctr_intern_status === '03' && ($d->order_service != 'sp2icon' || $d->order_service != 'sp2icon') && $d->ctr_status != 'mty' )
                                        <tr>
                                            <td>{{$d->container_no}}</td>
                                            <td>{{$d->ctr_type}}</td>
                                            <td>{{$d->yard_block}}</td>
                                            <td>{{$d->yard_slot}}</td>
                                            <td>{{$d->yard_row}}</td>
                                            <td>{{$d->yard_tier}}</td>
                                            <td>{{$d->update_time}}</td>
                                        </tr>
                                        @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="export" role="tabpanel" aria-labelledby="list-monday-list">
                        <div class="col-12 border">
                            <div class="card-body">

                                <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table2">
                                    <thead>
                                        <tr>
                                            <th>Container No</th>
                                            <th>Type</th>
                                            <th>Blok</th>
                                            <th>Slot</th>
                                            <th>Row</th>
                                            <th>Tier</th>
                                            <th>Placemented At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($confirmed as $d)
                                        @if($d->ctr_intern_status === '51' || $d->ctr_intern_status === '53')
                                        <tr>
                                            <td>{{$d->container_no}}</td>
                                            <td>{{$d->ctr_type}}</td>
                                            <td>{{$d->yard_block}}</td>
                                            <td>{{$d->yard_slot}}</td>
                                            <td>{{$d->yard_row}}</td>
                                            <td>{{$d->yard_tier}}</td>
                                            <td>{{$d->update_time}}</td>
                                        </tr>
                                        @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                    <div class="tab-pane fade" id="exstrip" role="tabpanel" aria-labelledby="list-tuesday-list">
                        <div class="col-12 border">
                            <div class="card-body">
                                <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table3">
                                    <thead>
                                        <tr>
                                            <th>Container No</th>
                                            <th>Type</th>
                                            <th>Blok</th>
                                            <th>Slot</th>
                                            <th>Row</th>
                                            <th>Tier</th>
                                            <th>Placemented At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($confirmed as $d)
                                        @if($d->ctr_intern_status === '03' && ($d->order_service === 'sp2icon' || $d->order_service === 'sppsrelokasipelindo') )
                                        <tr>
                                            <td>{{$d->container_no}}</td>
                                            <td>{{$d->ctr_type}}</td>
                                            <td>{{$d->yard_block}}</td>
                                            <td>{{$d->yard_slot}}</td>
                                            <td>{{$d->yard_row}}</td>
                                            <td>{{$d->yard_tier}}</td>
                                            <td>{{$d->update_time}}</td>
                                        </tr>
                                        @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="mty" role="tabpanel" aria-labelledby="list-mty-list">
                        <div class="col-12 border">
                            <div class="card-body">
                                <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table4">
                                    <thead>
                                        <tr>
                                            <th>Container No</th>
                                            <th>Type</th>
                                            <th>Blok</th>
                                            <th>Slot</th>
                                            <th>Row</th>
                                            <th>Tier</th>
                                            <th>action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($confirmed as $d)
                                        @if($d->ctr_intern_status === '04' && $d->ctr_status === 'MTY' )
                                        <tr>
                                            <td>{{$d->container_no}}</td>
                                            <td>{{$d->ctr_type}}</td>
                                            <td>{{$d->yard_block}}</td>
                                            <td>{{$d->yard_slot}}</td>
                                            <td>{{$d->yard_row}}</td>
                                            <td>{{$d->yard_tier}}</td>
                                            <td>{{$d->update_time}}</td>
                                            <td><button type="button" class="btn btn-outline-success changed-to-exp-mty" data-bs-toggle="modal" data-id="{{$d['container_key']}}">Change</button></td>
                                        </tr>
                                        @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>

<!-- Modal Update Status -->
<div class="modal fade text-left" id="success" role="dialog" aria-labelledby="myModalLabel110" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title white" id="myModalLabel110">Placement</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><i data-feather="x"></i></button>
            </div>
            <div class="modal-body">
                <!-- form -->
                <div class="form-body" id="place_cont">
                    <div class="row">

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
                                <select id="key" class="form-control" style="font-size: 16px; width: 75%;">
                                    <!-- Existing options or a default placeholder option -->
                                    <option value="">Select a container</option>
                                </select>
                            </div>
                            {{ csrf_field()}}

                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Alat</label>
                                <select class="choices form-select" id="alat" required>
                                    <option value="">Pilih Alata</option>
                                    @foreach($alat as $alt)
                                    <option value="{{$alt->id}}">{{$alt->name}}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" id="container_no" class="form-control" name="container_no">
                                <input type="hidden" id="container_key" class="form-control" name="container_key">
                            </div>
                            {{ csrf_field()}}
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
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">Truck</label>
                                        <select class="choices form-select" id="truckAlat">
                                            <option disabeled selected value>Pilih Satu!</option>
                                            @foreach($truck as $trc)
                                                <option value="{{$trc->id}}">{{$trc->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">Supir</label>
                                        <select id="supir" class="choices form-select">
                                            <option disabeled selected value>Pilih Satu!</option>
                                            @foreach($supir as $spr)
                                            <option value="{{$spr->id}}">{{$spr->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="first-name-vertical">Type</label>
                                        <input type="text" id="tipe" class="form-control" name="ctr_type" disabled>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="first-name-vertical">Size</label>
                                        <input type="text" id="size" class="form-control" name="ctr_size" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Commodity</label>
                                <input type="text" id="coname" class="form-control" name="commodity_name" disabled>
                            </div>
                        </div>

                        <h4>Yard Planning</h4>
                        <div class="col-12" style="border:1px solid blue;">
                            <div class="row">

                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="first-name-vertical">Blok</label>
                                        <select class="choices form-select" id="block" name="yard_block" required>
                                            <option value="">-</option>
                                            @foreach($yard_block as $block)
                                            <option value="{{$block}}">{{$block}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="first-name-vertical">Slot</label>
                                        <select class="choices form-select" id="slot" name="yard_slot" required>
                                            <option value="">-</option>
                                            @foreach($yard_slot as $slot)
                                            <option value="{{$slot}}">{{$slot}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="first-name-vertical">Row</label>
                                        <select class="choices form-select" id="row" name="yard_row" required>
                                            <option value="">-</option>
                                            @foreach($yard_row as $row)
                                            <option value="{{$row}}">{{$row}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="first-name-vertical">Tier</label>
                                        <select class="choices form-select" id="tier" name="yard_tier" required>
                                            <option value="">-</option>
                                            @foreach($yard_tier as $tier)
                                            <option value="{{$tier}}">{{$tier}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="first-name-vertical">Planner Place</label>
                                <input type="text" id="user" class="form-control" value="{{ Auth::user()->name }}" name="user_id" readonly>
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

<div class="modal fade text-left" id="changed-mty" role="dialog" aria-labelledby="myModalLabel110" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success">
        <h5 class="modal-title white" id="myModalLabel110">Changing Service</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><i data-feather="x"></i></button>
      </div>
          <div class="modal-body">
            <!-- form -->
                <div class="row">
                    <div class="col-12">
                      <div class="form-group">
                        <label for="first-name-vertical">Container</label>
                        <input type="text" id="contKey" class="form-control" name="container_key" readonly>
                        <input type="text" id="contNo" class="form-control" name="container_no" readonly>
                        <input type="text" id="JobId" class="form-control"  readonly>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-4">
                        <div class="form-group">
                            <label for="">Size</label>
                            <input type="text" class="form-control" id="size" readonly>
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                            <label for="">Type</label>
                            <input type="text" class="form-control" id="type" readonly>
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                            <label for="">Status</label>
                            <input type="text" class="form-control" id="status" readonly>
                        </div>
                      </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="">Proccess</label>
                            <input type="text" class="form-control" id="service" readonly>
                        </div>
                    </div>
                </div>
                <hr>
                <br>
                <div class="col-12" id="vessel-select" style="display: none;">
                  <div class="form-group">
                    <label for="first-name-vertical">Choose Vessel</label>
                    <select class="choices form-select" id="Vessel" name="ves_id" required>
                        <option value="">Select Vessel</option>
                       @foreach($vessel as $ves)
                       <option value="{{$ves->ves_id}}">{{$ves->ves_name}}</option>
                       @endforeach
                    </select>
                  </div>
                            <div class="col-12">
                             <div class="form-group">
                                <label for="first-name-vertical">Vessel Name</label>
                                <input type="text" id="nama-kapal" class="form-control" readonly>
                              </div>
                            </div>
                            <div class="col-12">
                              <div class="form-group">
                                <label for="first-name-vertical">Vessel Code</label>
                                <input type="text" id="kode-kapal" class="form-control" readonly>
                              </div>
                            </div>
                            <div class="col-12">
                              <div class="form-group">
                                <label for="first-name-vertical">Voy No</label>
                                <input type="text" id="nomor-voyage" class="form-control" name="ctr_type" readonly>
                               
                              </div>
                            </div>
                        
                </div>
              
                        <div class="col-12" style="border:1px solid blue;" id="yard-select" style="display: none;">
                            <div class="row">
                                <h4>Yard Planning</h4>
                                <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Alat</label>
                                <select class="choices form-select" id="alatMTY" required>
                                    <option value="">Pilih Alata</option>
                                    @foreach($alat as $alt)
                                    <option value="{{$alt->id}}">{{$alt->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{ csrf_field()}}
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Op Alat</label>
                                <input type="text" id="operatorMTY" class="form-control" required>
                            </div>
                        </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="first-name-vertical">Blok</label>
                                        <select class="choices form-select" id="blockMTY" name="yard_block" required>
                                            <option value="">-</option>
                                            @foreach($yard_block as $block)
                                            <option value="{{$block}}">{{$block}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="first-name-vertical">Slot</label>
                                        <select class="choices form-select" id="slotMTY" name="yard_slot" required>
                                            <option value="">-</option>
                                            @foreach($yard_slot as $slot)
                                            <option value="{{$slot}}">{{$slot}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="first-name-vertical">Row</label>
                                        <select class="choices form-select" id="rowMTY" name="yard_row" required>
                                            <option value="">-</option>
                                            @foreach($yard_row as $row)
                                            <option value="{{$row}}">{{$row}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="first-name-vertical">Tier</label>
                                        <select class="choices form-select" id="tierMTY" name="yard_tier" required>
                                            <option value="">-</option>
                                            @foreach($yard_tier as $tier)
                                            <option value="{{$tier}}">{{$tier}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal"> <i class="bx bx-x d-block d-sm-none"></i><span class="d-none d-sm-block">Close</span></button>
            <button type="submit" class="btn btn-success ml-1 updateToExpMty"><i class="bx bx-check d-block d-sm-none"></i><span class="d-none d-sm-block">Confirm</span></button>
          </div>
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
    new simpleDatatables.DataTable('#table2');
    new simpleDatatables.DataTable('#table3');
    new simpleDatatables.DataTable('#table4');
</script>
<script>
    
    // Ambil elemen "Proccess" dan "Choose Vessel"
    const proccessSelect = document.getElementById("service");
    const vesselSelect = document.getElementById("vessel-select");
    const yardSelect = document.getElementById("yard-select");

    function toggleVesselSelect(displayValue) {
    vesselSelect.style.display = displayValue;
}

function toggleYardSelect(displayValue) {
    yardSelect.style.display = displayValue;
}

</script>
<script>
    // In your Javascript (external .js resource or <script> tag)
    $(document).ready(function() {
        $('.container').select2({
            dropdownParent: '#success',
        });
        $('.block').select2({
            dropdownParent: '#success',
        });
        $('.slot').select2({
            dropdownParent: '#success',
        });
        $('.yard_row').select2({
            dropdownParent: '#success',
        });
        $('.tier').select2({
            dropdownParent: '#success',
        });
    });
    $(document).on('click', '.update_status', function(e) {
        e.preventDefault();
        var container_key = $('#key').val();
        var container_no = $('#container_no').val();
        var yard_block = $('#block').val();
        var yard_slot = $('#slot').val();
        var yard_raw = $('#raw').val();
        var yard_tier = $('#tier').val();
        var alat = $('#alat').val();
        var operator = $('#operator').val();
        var supir = $('#supir').val();
        var truck = $('#truckAlat').val();
        if (!alat) {
        // If any of the required fields are empty, show an error message and return
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            text: 'Nomor Alat Belum Diisi, cek kembali Ya !!',
        });
        return;
        }
        if (!operator) {
        // If any of the required fields are empty, show an error message and return
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            text: 'Operator Belum Diisi, cek kembali Ya !!',
        });
        return;
        }

        // if (!supir) {
        // Swal.fire({
        //     icon: 'error',
        //     title: 'Validation Error',
        //     text: 'Supir Belum Diisi, cek kembali Ya !!',
        // });
        // return;
        // }

        // if (!truck) {
        // Swal.fire({
        //     icon: 'error',
        //     title: 'Validation Error',
        //     text: 'Truck Belum Diisi, cek kembali Ya !!',
        // });
        // return;
        // }
        var data = {
            'container_key': $('#key').val(),
            'container_no': $('#container_no').val(),
            'yard_block': $('#block').val(),
            'yard_slot': $('#slot').val(),
            'yard_row': $('#row').val(),
            'yard_tier': $('#tier').val(),
            'user_id': $('#user').val(),
            'alat': $('#alat').val(),
            'operator': $('#operator').val(),
            'supir': $('#supir').val(),
            'truck': $('#truckAlat').val(),

        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        Swal.fire({
            title: 'Are you Sure?',
            text: "Container " + container_no + " will be placed at Block " + yard_block + " Slot " + yard_slot + " Raw " + yard_raw + " and Tier " + yard_tier,
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
                    url: '/placement',
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
            $('#key').on('change', function() {
                let id = $(this).val();
                $.ajax({
                    type: 'POST',
                    url: '/container-tipe',
                    data: {
                        container_key: id
                    },
                    success: function(response) {
                        $('#container_key').val(response.container_key);
                        $('#container_no').val(response.container_no);
                        $('#tipe').val(response.tipe);
                        $('#size').val(response.size);
                        $('#coname').val(response.coname);
                    },
                    error: function(data) {
                        console.log('error:', data);
                    },
                });
            });
        });
        $(document).ready(function() {
          $('#Vessel').on('change', function() {
            let id = $(this).val();
            $.ajax({
              type: 'POST',
              url: '/get-vessel-in-stuffing',
              data: {
                ves_id: id
              },
              success: function(response) {

                $('#nama-kapal').val(response.ves_name);
                $('#kode-kapal').val(response.ves_code);
                $('#nomor-voyage').val(response.voy_no);
              },
              error: function(data) {
                console.log('error:', data);
              },
            });
          });
        });
        // $(function(){
        //         $('#block'). on('change', function(){
        //             let yard_block = $('#block').val();

        //             $.ajax({
        //                 type: 'POST',
        //             url: '/get-slot',
        //                 data : {yard_block : yard_block},
        //                 cache: false,

        //                 success: function(msg){
        //                     $('#slot').html(msg);

        //                 },
        //                 error: function(data){
        //                     console.log('error:',data)
        //                 },
        //             })               
        //         })
        //     })
    });
</script>

<script>
 $(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
   

    $(document).on('click', '.changed-to-exp-mty', function() {
        let id = $(this).data('id');
        $.ajax({
            type: 'POST',
            url: '/placement/changedToMty-' + id,
        
            data: {
                container_key: id
            },
          
            success: function(response) {
                let res = JSON.parse(response);
                let job = res.data.containers[0].findContainer;
                let delivery = res.data.containers[0].deliveryForm;
    
                console.log(response);
                $('#changed-mty').modal('show');
                $("#changed-mty #contNo").val(job.container_no);
                $("#changed-mty #contKey").val(job.container_key);
                $("#changed-mty #size").val(job.ctr_size);
                $("#changed-mty #type").val(job.ctr_type);
                $("#changed-mty #status").val(job.ctr_status);
                $("#changed-mty #service").val(job.orderService);
                $("#changed-mty #JobId").val(job.id);
                toggleVesselSelect(job.orderService === "mtiks" ? "none" : "block");
                toggleYardSelect(job.orderService === "mtiks" ? "none" : "block");
    
            },
            error: function(data) {
                console.log('error:', data);
            }
        });
    });

    $(document).on('click', '.updateToExpMty', function(e) {
        e.preventDefault();
        var container_key = $('#contKey').val();
        var order_service = $('#service').val();
        var ves_id = $('#Vessel').val();
        var ves_name = $('#nama-kapal').val();
        var ves_code = $('#kode-kapal').val();
        var voy_no = $('#nomor-voyage').val();
        var yard_block = $('#blockMTY').val();
        var yard_slot = $('#slotMTY').val();
        var yard_raw = $('#rawMTY').val();
        var yard_tier = $('#tierMTY').val();
        var alat = $('#alatMTY').val();
        var operator = $('#operatorMTY').val();
        var id = $('#JobId').val();
        var container_key = $('#container_key').val();
        if (!alat) {
        // If any of the required fields are empty, show an error message and return
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            text: 'Nomor Alat Belum Diisi, cek kembali Ya !!',
        });
        return;
        }
        if (!operator) {
        // If any of the required fields are empty, show an error message and return
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            text: 'Operator Belum Diisi, cek kembali Ya !!',
        });
        return;
        }
        var data = {
            'container_key': $('#contKey').val(),
            'order_service': $('#service').val(),
            'ves_id': $('#Vessel').val(),
            'ves_name': $('#nama-kapal').val(),
            'ves_code': $('#kode-kapal').val(),
            'voy_no': $('#nomor-voyage').val(),
            'yard_block': $('#blockMTY').val(),
            'yard_slot': $('#slotMTY').val(),
            'yard_row': $('#rowMTY').val(),
            'yard_tier': $('#tieMTYr').val(),
            'alat': $('#alatMTY').val(),
            'operator': $('#operatorMTY').val(),
            'id': $('#JobId').val(),
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        Swal.fire({
            title: 'Are you Sure?',
            text: "Changed to Export MTY?",
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
                    url: '/placement/changed-status',
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

});
</script>
<script>
     $(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(function() {
        const selectContainer = new Choices(document.querySelector('#key'), {
            // Opsi dan pengaturan Choices.js sesuai kebutuhan
        });

        $("#id_kapal").change(function() {
            let ves_id = $('#id_kapal').val();

            $.ajax({
                type: 'POST',
                url: '/get-con-plc',
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