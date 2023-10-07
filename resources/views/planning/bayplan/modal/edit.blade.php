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
                                <option value="{{$vy->ves_id}}">{{str_pad($vy->ves_id,4,'0', STR_PAD_LEFT)}}-{{$vy->ves_code}} Tiba Pada {{$vy->eta_date}}</option>
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