<div class="modal fade text-left" id="pelindo-bayplan-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="text-center" id="myModalLabel17">Khusus Relokasi Pelindo</h4>
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

                    <form class="form" method="post" action='/planning/bayplan_pelindo'>
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
                                <select class="form-select" id="isocodePelindo" name="iso_code">
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
                                <select class="form-select" id="isosizePelindo" name="ctr_size" required readonly>
                                  <option value="-">-</option>
                                </select>
                              </div>
                            </div>
                            <div class="col-md-6 col-12">
                              <div class="form-group">
                                <label for="-floating">Type</label>
                                <select class="form-select" id="isotypePelindo" name="ctr_type" required readonly>
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
                            <h5>-</h5>
                            <div class="col-md-10 col-12">
                              <div class="form-group">
                                <label for="-id-column">CTR OPR</label>
                                <input type="text" id="-id-column" class="form-control" name="ctr_opr" placeholder="" required>
                              </div>
                            </div>
                            <div class="row">
                              
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