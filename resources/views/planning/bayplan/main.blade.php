@extends('partial.main')
@section('content')
<div class="page-heading">
  <div class="page-title">
    <div class="row">
      <div class="col-12 col-md-6 order-md-1 order-last">
        <h3>Bay Plan Import</h3>
        <p class="text-subtitle text-muted"></p>
      </div>

      <div class="col-12 col-md-6 order-md-2 order-first">
        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
          <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Bay Import</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <section class="section">
    <div class="card">
      <div class="card-header">
        <button class="btn icon icon-left btn-success" id="btn-bayplan"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit">
            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
          </svg> Create Container</button>
      </div>
      <div class="card-body">
        @if ($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        @endif

        <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
          <thead>
            <tr>
              <th>Vessel Id</th>
              <th>Vessel Name</th>
              <th>Voyage In</th>
              <th>Container No</th>
              <th>Size</th>
              <th>Type</th>
              <th>Status</th>
              <th>Gross</th>
              <th>Slot</th>
              <th>Row</th>
              <th>Tier</th>
              <th>Load Port</th>
              <th>Origin Port</th>
              <th>Last Update</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($formattedData as $d)
            <tr>
              <td>{{ str_pad($d['ves_id'],4,'0', STR_PAD_LEFT)}}</td>
              <td>{{$d['ves_name']}}</td>
              <td>{{$d['voy_no']}}</td>
              <td>{{$d['container_no']}}</td>
              <td>{{$d['ctr_size']}}</td>
              <td>{{$d['ctr_type']}}</td>
              <td>{{$d['ctr_status']}}</td>
              <td>{{$d['gross']}}</td>
              <td>{{$d['bay_slot']}}</td>
              <td>{{$d['bay_row']}}</td>
              <td>{{$d['bay_tier']}}</td>
              <td>{{$d['load_port']}}</td>
              <td>{{$d['org_port']}}</td>
              <td>{{$d['update_time']}}</td>
              <td>
                <div class="btn-group">
                  <a href="javascript:void(0)" class="btn btn-primary edit-modal" data-id="{{ $d['container_key'] }}"><i class="bi bi-pencil"></i></a>

                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </section>
</div>
<div class="modal fade text-left" id="create-bayplan-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="text-center" id="myModalLabel17">Bay Plan Import</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <i data-feather="x"></i>
        </button>
      </div>
      <div class="modal-body">
        <section id="multiple-column-form">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-content">
                  <div class="card-body">

                    <form class="form" method="post" action='/planning/bayplan_import'>
                      @CSRF

                      <div class="row">
                        <div class="col-md-4 border-right">
                          <div class="row" style="border-right: 2px solid blue ;">
                            <h5>Container Fill</h5>
                            <div class="col-md-12 col-12">
                              <div class="form-group">
                                <label for="first-name-column">Container No.</label>
                                <input type="text" id="-floating" class="form-control" name="container_no" placeholder="">
                                <input type="hidden" id="container_key" name="container_key">
                              </div>
                            </div>

                            <div class="col-md-6 col-12">
                              <div class="form-group">
                                <label for="-column">Iso Code</label>
                                <select class="form-select" id="isocode" name="iso_code">
                                  <option value="-">-</option>
                                  @foreach($isocode as $iso)
                                  <option value="{{$iso->iso_code}}">{{$iso->iso_code}}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>
                            <div class="col-md-6 col-12">
                              <div class="form-group">
                                <label for="-floating">Size</label>
                                <select class="form-select" id="isosize" name="ctr_size" required readonly>
                                  <option value="-">-</option>
                                </select>
                              </div>
                            </div>
                            <div class="col-md-6 col-12">
                              <div class="form-group">
                                <label for="-floating">Type</label>
                                <select class="form-select" id="isotype" name="ctr_type" required readonly>
                                  <option value="-">-</option>
                                </select>
                              </div>
                            </div>
                            <div class="col-md-6 col-12">
                              <div class="form-group">
                                <label for="-column">Status</label>
                                <select class="form-select" id="" name="ctr_status" required readonly>
                                  <option value="FCL">FCL</option>
                                  <option value="MTY">MTY</option>
                                </select>
                              </div>
                            </div>
                            <div class="col-md-6 col-12">
                              <div class="form-group">
                                <label for="-id-column">Gross</label>
                                <input type="text" class="form-control" name="gross" required>
                              </div>
                            </div>
                            <div class="col-md-6 col-12">
                              <div class="form-group">
                                <label for="-id-column">Gross Class</label>
                                <select class="form-select" name="gross_class" required readonly>
                                  <option value="S">S</option>
                                  <option value="M">M</option>
                                  <option value="L">L</option>
                                  <option value="X">XL</option>
                                </select>
                              </div>
                            </div>
                            <div class="col-md-10 col-12">
                              <div class="form-group">
                                <label for="-id-column">B/L No</label>
                                <input type="text" id="-id-column" class="form-control" name="bl_no" placeholder="">
                              </div>
                            </div>
                            <div class="col-md-12 col-12">
                              <div class="form-group">
                                <label for="-id-column">Seal No</label>
                                <input type="text" id="-id-column" class="form-control" name="seal_no" placeholder="">
                              </div>
                            </div>
                            <div class="col-md-6 col-12">
                              <div class="form-group">
                                <label for="-id-column">Commodity Name</label>
                                <input type="text" id="-id-column" class="form-control" name="commodity_name" placeholder="" required>
                                <input type="hidden" id="-id-column" class="form-control" value="01" name="ctr_intern_status" placeholder="" required>
                                <input type="hidden" id="-id-column" class="form-control" value="I" name="ctr_i_e_t" placeholder="" required>
                                <input type="hidden" id="-id-column" class="form-control" value="{{ Auth::user()->name }}" name="user_id" placeholder="" required>
                                <label for="-id-column">Imo Code : </label>
                                <select class="form-select" id="imo" name="imo_code" required>
                                  <option value="-">-</option>
                                  @foreach($imocode as $imo)
                                  <option value="{{$imo->imo_code}}">{{$imo->imo_code}}</option>
                                  @endforeach
                                </select>
                                <label for="-id-column">Dangerous : </label>
                                <select class="form-select" id="" name="dangerous_yn" required readonly>
                                  <option value="N">N</option>
                                  <option value="Y">Y</option>
                                </select>
                                <label for="-id-column">Dangerous Label : </label>
                                <select class="form-select" id="" name="dangerous_label_yn" required readonly>
                                  <option value="N">N</option>
                                  <option value="Y">Y</option>
                                </select>
                              </div>
                            </div>
                            <div class="col-md-6 col-12">
                              <div class="form-group">
                                <label for="-id-column">O Height</label>
                                <input type="text" id="-id-column" class="form-control" name="over_height" placeholder="">
                                <label for="-id-column">O Weight</label>
                                <input type="text" id="-id-column" class="form-control" name="over_weight" placeholder="">
                                <label for="-id-column">O Length</label>
                                <input type="text" id="-id-column" class="form-control" name="over_length" placeholder="">
                                <label for="-id-column">Child. Temp</label>
                                <input type="text" id="-id-column" class="form-control" name="chilled_temp" placeholder="">
                              </div>
                            </div>

                          </div>
                        </div>
                        <div class="col-md-8">
                          <div class="row">
                            <h5>Vessel Fill</h5>
                            <div class="col-md-6 col-12">
                              <div class="form-group">
                                <label for="first-name-column">Vessel Id.</label>
                                <select class="form-select" id="vesid" name="ves_id">
                                  <option value="-">-</option>
                                  @foreach($vessel_import as $vi)
                                  <option value="{{$vi->ves_id}}">{{str_pad($vi->ves_id,4,'0', STR_PAD_LEFT)}}-{{$vi->ves_code}} Tiba Pada {{$vi->eta_date}}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>

                            <div class="col-md-6 col-12">
                              <div class="form-group">
                                <label for="-column">Vessel Code</label>
                                <select class="form-select" id="vescode" name="ves_code">
                                  <option value="-">-</option>

                                </select>
                              </div>
                            </div>
                            <div class="col-md-12 col-12">
                              <div class="form-group">
                                <label for="-floating">Vessel Name</label>
                                <select class="form-select" id="vesname" name="ves_name" required readonly>
                                  <option value="-">-</option>
                                </select>
                              </div>
                            </div>
                            <div class="col-md-6 col-12">
                              <div class="form-group">
                                <label for="-column">Voyage Out</label>
                                <select class="form-select" id="voy" name="voy_no" required readonly>
                                  <option value="-">-</option>
                                </select>
                              </div>
                            </div>
                            <div class="col-md-6 col-12">
                              <div class="form-group">
                                <label for="-id-column">Agent</label>
                                <select class="form-select" id="agent" name="agent" required readonly>
                                  <option value="-">-</option>
                                </select>
                              </div>
                            </div>
                            <div class="col-md-10 col-12">
                              <div class="form-group">
                                <label for="-id-column">CTR OPR</label>
                                <input type="text" id="-id-column" class="form-control" name="ctr_opr" placeholder="" required>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-6">
                                <div class="row">
                                  <h4>Plan of Bay</h4>
                                  <div class="col-md-12 col-12">
                                    <div class="form-group">
                                      <label for="-id-column">Disc. Seq</label>
                                      <input type="text" id="-id-column" class="form-control" name="disc_load_seq" placeholder="" required>
                                    </div>
                                  </div>
                                  <div class="col-md-12 col-12">
                                    <div class="form-group">
                                      <label for="-id-column">Bay</label>
                                      <input type="text" id="-id-column" class="form-control" name="bay_slot" placeholder="" required>
                                    </div>
                                  </div>
                                  <div class="col-md-12 col-12">
                                    <div class="form-group">
                                      <label for="-id-column">Raw</label>
                                      <input type="text" id="-id-column" class="form-control" name="bay_row" placeholder="" required>
                                    </div>
                                  </div>
                                  <div class="col-md-12 col-12">
                                    <div class="form-group">
                                      <label for="-id-column">Tier</label>
                                      <input type="text" id="-id-column" class="form-control" name="bay_tier" placeholder="" required>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-6">
                                <div class="row">
                                  <h4>Port Pelabuhan</h4>
                                  <div class="col-md-12 col-12">
                                    <div class="form-group">
                                      <label for="-id-column">Load Port</label>
                                      <select class="form-select" id="" name="load_port" required>
                                        @foreach($port_master as $pm)
                                        <option value="{{$pm->un_port}}">{{$pm->port}}</option>
                                        @endforeach
                                      </select>
                                    </div>
                                  </div>
                                  <div class="col-md-12 col-12">
                                    <div class="form-group">
                                      <label for="-id-column">Disc Port</label>
                                      <select class="form-select" id="" name="disch_port" required>
                                        @foreach($port_master as $pm)
                                        <option value="{{$pm->un_port}}">{{$pm->port}}</option>
                                        @endforeach
                                      </select>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-10 col-12">
                              <div class="form-group">
                                <label for="-id-column">Disc/Tran/Shif</label>
                                <select class="form-select" id="" name="disc_load_trans_shift" required readonly>
                                  <option value="DISC">DISC</option>
                                  <option value="TRAN">TRAN</option>
                                  <option value="SHIF">SHIF</option>
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Entry</button>
                      </div>
                    </form>


                  </div>
                </div>
              </div>
            </div>
          </div>

        </section>
      </div>
    </div>
  </div>
</div>

<div class="modal fade text-left" id="editBayplanImport" tabindex="-1" role="dialog" aria-labelledby="editBayplanImport" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="text-center" id="myModalLabel17">Bay Plan Import</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <i data-feather="x"></i>
        </button>
      </div>
      <div class="modal-body">
        <section id="multiple-column-form">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-content">
                  <div class="card-body">



                    <div class="row">
                      <div class="col-md-4 border-right">
                        <div class="row" style="border-right: 2px solid blue ;">
                          <h5>Container Fill</h5>
                          <div class="col-md-12 col-12">
                            <div class="form-group">
                              <label for="first-name-column">Container No.</label>
                              <input type="text" id="edit_container_no" class="form-control" name="container_no" placeholder="">
                              <input type="hidden" id="edit_container_key" class="form-control" name="container_key" placeholder="">
                            </div>
                          </div>

                          <div class="col-md-6 col-12">
                            <div class="form-group">
                              <label for="-column">Iso Code</label>
                              <select class="form-select" id="isocode_edit" name="iso_code">
                                <option value="-">-</option>
                                @foreach($isocode as $iso)
                                <option value="{{$iso->iso_code}}">{{$iso->iso_code}}</option>
                                @endforeach
                              </select>
                            </div>
                          </div>
                          <div class="col-md-6 col-12">
                            <div class="form-group">
                              <label for="-floating">Size</label>
                              <input type="text" id="isosize_edit" class="form-control" name="ctr_size" readonly>
                            </div>
                          </div>
                          <div class="col-md-6 col-12">
                            <div class="form-group">
                              <label for="-floating">Type</label>
                              <input type="text" id="isotype_edit" class="form-control" name="ctr_type" readonly>
                            </div>
                          </div>
                          <div class="col-md-6 col-12">
                            <div class="form-group">
                              <label for="-column">Status</label>
                              <select class="form-select" id="stat_edit" name="ctr_status" required readonly>
                                <option value="FCL">FCL</option>
                                <option value="MTY">MTY</option>
                              </select>
                            </div>
                          </div>
                          <div class="col-md-6 col-12">
                            <div class="form-group">
                              <label for="-id-column">Gross</label>
                              <input type="text" id="gross" class="form-control" name="gross" required>
                            </div>
                          </div>
                          <div class="col-md-6 col-12">
                            <div class="form-group">
                              <label for="-id-column">Gross Class</label>
                              <select class="form-select" id="gclass" name="gross_class" required readonly>
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="L">L</option>
                                <option value="X">XL</option>
                              </select>
                            </div>
                          </div>
                          <div class="col-md-10 col-12">
                            <div class="form-group">
                              <label for="-id-column">B/L No</label>
                              <input type="text" id="bl" class="form-control" name="bl_no" placeholder="" required>
                            </div>
                          </div>
                          <div class="col-md-12 col-12">
                            <div class="form-group">
                              <label for="-id-column">Seal No</label>
                              <input type="text" id="sl" class="form-control" name="seal_no" placeholder="" required>
                            </div>
                          </div>
                          <div class="col-md-6 col-12">
                            <div class="form-group">
                              <label for="-id-column">Commodity Name</label>
                              <input type="text" id="commodity_name_edit" class="form-control" name="commodity_name" placeholder="" required>
                              <label for="-id-column">Imo Code : </label>
                              <select class="form-select" id="imo_edit" name="imo_code" required>
                                <option value="-">-</option>
                                @foreach($imocode as $imo)
                                <option value="{{$imo->imo_code}}">{{$imo->imo_code}}</option>
                                @endforeach
                              </select>
                              <label for="-id-column">Dangerous : </label>
                              <select class="form-select" id="dangerous_yn_edit" name="dangerous_yn" required readonly>
                                <option value="N">N</option>
                                <option value="Y">Y</option>
                              </select>
                              <label for="-id-column">Dangerous Label : </label>
                              <select class="form-select" id="dangerlab" name="dangerous_label_yn" required readonly>
                                <option value="N">N</option>
                                <option value="Y">Y</option>
                              </select>
                            </div>
                          </div>
                          <div class="col-md-6 col-12">
                            <div class="form-group">
                              <label for="-id-column">O Height</label>
                              <input type="text" id="height" class="form-control" name="over_height" placeholder="" required>
                              <label for="-id-column">O Weight</label>
                              <input type="text" id="weight" class="form-control" name="over_weight" placeholder="" required>
                              <label for="-id-column">O Length</label>
                              <input type="text" id="length" class="form-control" name="over_length" placeholder="" required>
                              <label for="-id-column">Child. Temp</label>
                              <input type="text" id="child" class="form-control" name="chilled_temp" placeholder="" required>
                            </div>
                          </div>

                        </div>
                      </div>
                      <div class="col-md-8">
                        <div class="row">
                          <h5>Vessel Fill</h5>
                          <div class="col-md-6 col-12">
                            <div class="form-group">
                              <label for="first-name-column">Vessel Id.</label>
                              <select class="form-select" id="vesid_edit" name="ves_id">
                                <option value="-">-</option>
                                @foreach($vessel_voyage as $vy)
                                <option value="{{$vy->ves_id}}">{{str_pad($vi->ves_id,4,'0', STR_PAD_LEFT)}}-{{$vi->ves_code}} Tiba Pada {{$vi->eta_date}}</option>
                                @endforeach
                              </select>
                            </div>
                          </div>

                          <div class="col-md-6 col-12">
                            <div class="form-group">
                              <label for="-column">Vessel Code</label>
                              <input type="text" id="vescode_edit" class="form-control" name="ves_code" readonly>
                            </div>
                          </div>
                          <div class="col-md-12 col-12">
                            <div class="form-group">
                              <label for="-floating">Vessel Name</label>
                              <input type="text" id="vesname_edit" class="form-control" name="ves_name" readonly>
                            </div>
                          </div>
                          <div class="col-md-6 col-12">
                            <div class="form-group">
                              <label for="-column">Voyage Out</label>
                              <input type="text" id="voy_edit" class="form-control" name="voy_no" readonly>
                            </div>
                          </div>
                          <div class="col-md-6 col-12">
                            <div class="form-group">
                              <label for="-id-column">Agent</label>
                              <input type="text" id="agent_edit" class="form-control" name="agent" readonly>
                            </div>
                          </div>
                          <div class="col-md-10 col-12">
                            <div class="form-group">
                              <label for="-id-column">CTR OPR</label>
                              <input type="text" id="opr_edit" class="form-control" name="ctr_opr" required>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-6" style="border:1px solid blue;">
                              <div class="row">
                                <h4>Plan of Bay</h4>
                                <div class="col-md-12 col-12">
                                  <div class="form-group">
                                    <label for="-id-column">Disc. Seq</label>
                                    <input type="text" id="seq_edit" class="form-control" name="disc_load_seq" placeholder="" required>
                                  </div>
                                </div>
                                <div class="col-md-12 col-12">
                                  <div class="form-group">
                                    <label for="-id-column">Bay</label>
                                    <input type="text" id="slot_edit" class="form-control" name="bay_slot" placeholder="" required>
                                  </div>
                                </div>
                                <div class="col-md-12 col-12">
                                  <div class="form-group">
                                    <label for="-id-column">Row</label>
                                    <input type="text" id="row_edit" class="form-control" name="bay_row" placeholder="" required>
                                  </div>
                                </div>
                                <div class="col-md-12 col-12">
                                  <div class="form-group">
                                    <label for="-id-column">Tier</label>
                                    <input type="text" id="tier_edit" class="form-control" name="bay_tier" placeholder="" required>
                                    <input type="hidden" id="user_update" class="form-control" value="{{ Auth::user()->name }}" name="user_id" placeholder="" required>

                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="col-6" style="border:1px solid blue;">
                              <div class="row">
                                <h4>Port Pelabuhan</h4>
                                <div class="col-md-12 col-12">
                                  <div class="form-group">
                                    <label for="-id-column">Load Port</label>
                                    <select class="form-select" id="loadport_edit" name="load_port" required>
                                      @foreach($port_master as $pm)
                                      <option value="{{$pm->un_port}}">{{$pm->port}}</option>
                                      @endforeach
                                    </select>
                                  </div>
                                </div>
                                <div class="col-md-12 col-12">
                                  <div class="form-group">
                                    <label for="-id-column">Disc Port</label>
                                    <select class="form-select" id="dischport_edit" name="disch_port" required>
                                      @foreach($port_master as $pm)
                                      <option value="{{$pm->un_port}}">{{$pm->port}}</option>
                                      @endforeach
                                    </select>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-10 col-12">
                            <div class="form-group">
                              <label for="-id-column">Disc/Tran/Shif</label>
                              <select class="form-select" id="dlts" name="disc_load_trans_shift" required readonly>
                                <option value="DISC">DISC</option>
                                <option value="TRAN">TRAN</option>
                                <option value="SHIF">SHIF</option>
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-primary update_item">Update</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </section>
      </div>
    </div>
  </div>
</div>


@endsection
@section('custom_js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="{{asset('dist/assets/extensions/sweetalert2/sweetalert2.min.js')}}"></script>
<script src="{{asset('dist/assets/js/pages/sweetalert2.js')}}"></script>

<script>
  @if(session('success'))
  Swal.fire({
    icon: 'success',
    title: 'Success',
    text: "{{ session('success') }}"
  });
  @endif
</script>

<script>
  $(document).ready(function() {
    $('#btn-bayplan').on("click", function() {

      $('#create-bayplan-modal').modal('show');


    });

    $('#').blur(function() {





    });

  });



  $(document).on('click', '.edit-modal', function() {
    let id = $(this).data('id');
    $.ajax({
      type: 'GET',
      url: '/planning/edit_bayplanimport_' + container_key,
      cache: false,
      data: {
        container_key: id
      },
      dataType: 'json',

      success: function(response) {

        console.log(response);
        $('#editBayplanImport').modal('show');
        $("#editBayplanImport #edit_container_key").val(response.data.container_key);
        $("#editBayplanImport #edit_container_no").val(response.data.container_no);
        $("#editBayplanImport #isocode_edit").val(response.data.iso_code);
        $("#editBayplanImport #isosize_edit").val(response.data.ctr_size);
        $("#editBayplanImport #isotype_edit").val(response.data.ctr_type);
        $("#editBayplanImport #stat").val(response.data.ctr_status);
        $("#editBayplanImport #gross").val(response.data.gross);
        $("#editBayplanImport #gclass").val(response.data.gross_class);
        $("#editBayplanImport #bl").val(response.data.bl_no);
        $("#editBayplanImport #sl").val(response.data.seal_no);
        $("#editBayplanImport #commodity_name_edit").val(response.data.commodity_name);
        $("#editBayplanImport #imo_edit").val(response.data.imo_code);
        $("#editBayplanImport #dangerous_yn").val(response.data.dangerous_yn);
        $("#editBayplanImport #dangerlab").val(response.data.dangerous_label_yn);
        $("#editBayplanImport #height").val(response.data.over_height);
        $("#editBayplanImport #weight").val(response.data.over_weight);
        $("#editBayplanImport #length").val(response.data.over_length);
        $("#editBayplanImport #child").val(response.data.chilled_temp);
        $("#editBayplanImport #vesid_edit").val(response.data.ves_id);
        $("#editBayplanImport #vescode_edit").val(response.data.ves_code);
        $("#editBayplanImport #vesname_edit").val(response.data.ves_name);
        $("#editBayplanImport #voy_edit").val(response.data.voy_no);
        $("#editBayplanImport #agent_edit").val(response.data.agent);
        $("#editBayplanImport #opr_edit").val(response.data.ctr_opr);
        $("#editBayplanImport #seq_edit").val(response.data.disc_load_seq);
        $("#editBayplanImport #slot_edit").val(response.data.bay_slot);
        $("#editBayplanImport #row_edit").val(response.data.bay_row);
        $("#editBayplanImport #tier_edit").val(response.data.bay_tier);
        $("#editBayplanImport #loadport_edit").val(response.data.load_port);
        $("#editBayplanImport #dischport_edit").val(response.data.disch_port);
        $("#editBayplanImport #dlts").val(response.data.disc_load_trans_shift);

      },
      error: function(data) {
        console.log('error:', data)


      }



    });

  });

  $(document).on('click', '.update_item', function(e) {
    e.preventDefault();
    var container_key = $('#edit_container_key').val();

    var data = {
      'container_key': $('#edit_container_key').val(),
      'container_no': $('#edit_container_no').val(),
      'ves_id': $('#vesid_edit').val(),
      'ves_code': $('#vescode_edit').val(),
      'ves_name': $('#vesname_edit').val(),
      'voy_no': $('#voy_edit').val(),
      'ctr_size': $('#isosize_edit').val(),
      'ctr_type': $('#isotype_edit').val(),
      'ctr_status': $('#stat_edit').val(),
      'disc_load_trans_shift': $('#dlts').val(),
      'gross': $('#gross').val(),
      'gross_class': $('#gclass').val(),
      'over_height': $('#height').val(),
      'over_weight': $('#weight').val(),
      'over_length': $('#length').val(),
      'commodity_name': $('#commodity_name_edit').val(),
      'load_port': $('#loadport_edit').val(),
      'disch_port': $('#dischport_edit').val(),
      'agent': $('#agent_edit').val(),
      'chilled_temp': $('#child').val(),
      'imo_code': $('#imo_edit').val(),
      'dangerous_yn': $('#dangerous_yn_edit').val(),
      'dangerous_label_yn': $('#dangerlab').val(),
      'bl_no': $('#bl').val(),
      'seal_no': $('#sl').val(),
      'disc_load_seq': $('#seq_edit').val(),
      'bay_slot': $('#slot_edit').val(),
      'bay_row': $('#row_edit').val(),
      'bay_tier': $('#tier_edit').val(),
      'iso_code': $('#isocode_edit').val(),
      'ctr_opr': $('#opr_edit').val(),
      'user_id': $('#user_update').val(),
    }
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    Swal.fire({
      icon: 'question',
      title: 'Do you want to save the changes?',
      showDenyButton: false,
      showCancelButton: true,
      confirmButtonText: 'Save',
      denyButtonText: `Don't save`,
    }).then((result) => {
      if (result.isConfirmed) {


        $.ajax({
          type: 'POST',
          url: '/planning/update_bayplanimport',
          data: data,
          cache: false,
          dataType: 'json',
          success: function(response) {
            Swal.fire({
              icon: 'success',
              title: 'Saved',
              html: 'Update Successfully',
            }).then((result) => {
              if (result.isConfirmed) {
                location.reload();
              }
            });
            console.log(response.message);
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
        Swal.fire('Changes are not saved', '', 'info');
      }
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

    $(document).ready(function() {
      $('#isocode_edit').on('change', function() {
        let id = $(this).val();
        $.ajax({
          type: 'POST',
          url: '/get-iso-type',
          data: {
            iso_code: id
          },
          success: function(response) {

            $('#isotype_edit').val(response.isotype_edit);
            $('#isosize_edit').val(response.isosize_edit);

          },
          error: function(data) {
            console.log('error:', data);
          },
        });

      });
    });
    $(document).ready(function() {
      $('#vesid_edit').on('change', function() {
        let id = $(this).val();
        $.ajax({
          type: 'POST',
          url: '/get-ves-name',
          data: {
            ves_id: id
          },
          success: function(response) {

            $('#vesname_edit').val(response.vesname_edit);
            $('#vescode_edit').val(response.vescode_edit);
            $('#voy_edit').val(response.voy_edit);
            $('#agent_edit').val(response.agent_edit);

          },
          error: function(data) {
            console.log('error:', data);
          },
        });
      });
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
      $('#isocode').on('change', function() {
        let iso_code = $('#isocode').val();

        $.ajax({
          type: 'POST',
          url: '/getsize',
          data: {
            iso_code: iso_code
          },
          cache: false,

          success: function(msg) {
            $('#isosize').html(msg);

          },
          error: function(data) {
            console.log('error:', data)
          },
        })
        $.ajax({
          type: 'POST',
          url: '/gettype',
          data: {
            iso_code: iso_code
          },
          cache: false,

          success: function(msg) {
            $('#isotype').html(msg);

          },
          error: function(data) {
            console.log('error:', data)
          },
        })
      })
    })
    $(function() {
      $('#vesid').on('change', function() {
        let ves_id = $('#vesid').val();

        $.ajax({
          type: 'POST',
          url: '/getcode',
          data: {
            ves_id: ves_id
          },
          cache: false,

          success: function(msg) {
            $('#vescode').html(msg);

          },
          error: function(data) {
            console.log('error:', data)
          },
        })
        $.ajax({
          type: 'POST',
          url: '/getname',
          data: {
            ves_id: ves_id
          },
          cache: false,

          success: function(msg) {
            $('#vesname').html(msg);

          },
          error: function(data) {
            console.log('error:', data)
          },
        })
        $.ajax({
          type: 'POST',
          url: '/getvoy',
          data: {
            ves_id: ves_id
          },
          cache: false,

          success: function(msg) {
            $('#voy').html(msg);

          },
          error: function(data) {
            console.log('error:', data)
          },
        })
        $.ajax({
          type: 'POST',
          url: '/getagent',
          data: {
            ves_id: ves_id
          },
          cache: false,

          success: function(msg) {
            $('#agent').html(msg);

          },
          error: function(data) {
            console.log('error:', data)
          },
        })
      })
    })
  });
</script>
@endsection