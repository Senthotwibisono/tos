@extends('partial.invoice.main')

@section('content')
<div class="page-heading">
  <h3>Tarif</h3>
</div>

<div class="page-content">
    <div class="card">
        <form action="{{ route('update-stevadooring-Tarif')}}" method="POST">
            @csrf
            <div class="card-body">
                <div class="row">
                    <h4>Tarif Stevadooring</h4>
                     <div class="card-footer">
                         <div class="row">
                             <p>Container Full</p>
                             <div class="col-3">
                                 <div class="form-group">
                                     <label for="">20</label>
                                     <input type="text" class="form-control" name="ctr_20_fcl" value="{{$tarif->ctr_20_fcl}}">
                                 </div>
                             </div>
                             <div class="col-3">
                                 <div class="form-group">
                                     <label for="">21</label>
                                     <input type="text" class="form-control" name="ctr_21_fcl" value="{{$tarif->ctr_21_fcl}}">
                                 </div>
                             </div>
                             <div class="col-3">
                                 <div class="form-group">
                                     <label for="">40</label>
                                     <input type="text" class="form-control" name="ctr_40_fcl" value="{{$tarif->ctr_40_fcl}}">
                                 </div>
                             </div>
                             <div class="col-3">
                                 <div class="form-group">
                                     <label for="">42</label>
                                     <input type="text" class="form-control" name="ctr_42_fcl" value="{{$tarif->ctr_42_fcl}}">
                                 </div>
                             </div>
                         </div>
                         <div class="row">
                             <p>Container Empty</p>
                             <div class="col-3">
                                 <div class="form-group">
                                     <label for="">20</label>
                                     <input type="text" class="form-control" name="ctr_20_mty" value="{{$tarif->ctr_20_mty}}">
                                 </div>
                             </div>
                             <div class="col-3">
                                 <div class="form-group">
                                     <label for="">21</label>
                                     <input type="text" class="form-control" name="ctr_21_mty" value="{{$tarif->ctr_21_mty}}">
                                 </div>
                             </div>
                             <div class="col-3">
                                 <div class="form-group">
                                     <label for="">40</label>
                                     <input type="text" class="form-control" name="ctr_40_mty" value="{{$tarif->ctr_40_mty}}">
                                 </div>
                             </div>
                             <div class="col-3">
                                 <div class="form-group">
                                     <label for="">42</label>
                                     <input type="text" class="form-control" name="ctr_42_mty" value="{{$tarif->ctr_42_mty}}">
                                 </div>
                             </div>
                         </div>
                     </div>
                </div>
                <div class="row">
                <h4>Tarif Shifting</h4>
                <div class="col-6">
                    <div class="card">
                        <div class="card-header text-center">
                            <strong>Crane Dermaga</strong>
                        </div>
                        <div class="card-body">
                            
                            <div class="row">
                                <div class="col-6">
                                    <div class="row">
                                        <p>Dengan Landing</p>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">20 Full</label>
                                                <input type="text" class="form-control" name="shift_20_fcl_d_l" value="{{$tarif->shift_20_fcl_d_l}}">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">20 Empty</label>
                                                <input type="text" class="form-control" name="shift_20_mty_d_l" value="{{$tarif->shift_20_mty_d_l}}">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">21 Full</label>
                                                <input type="text" class="form-control" name="shift_21_fcl_d_l" value="{{$tarif->shift_21_fcl_d_l}}">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">21 Empty</label>
                                                <input type="text" class="form-control" name="shift_21_mty_d_l" value="{{$tarif->shift_21_mty_d_l}}">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">40 Full</label>
                                                <input type="text" class="form-control" name="shift_40_fcl_d_l" value="{{$tarif->shift_40_fcl_d_l}}">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">40 Empty</label>
                                                <input type="text" class="form-control" name="shift_40_mty_d_l" value="{{$tarif->shift_40_mty_d_l}}">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">42 Full</label>
                                                <input type="text" class="form-control" name="shift_42_fcl_d_l" value="{{$tarif->shift_42_fcl_d_l}}">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">42 Empty</label>
                                                <input type="text" class="form-control" name="shift_42_mty_d_l" value="{{$tarif->shift_42_mty_d_l}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="row">
                                        <p>Tanpa Landing</p>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">20 Full</label>
                                                <input type="text" class="form-control" name="shift_20_fcl_d" value="{{$tarif->shift_20_fcl_d}}">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">20 Empty</label>
                                                <input type="text" class="form-control" name="shift_20_mty_d" value="{{$tarif->shift_20_mty_d}}">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">21 Full</label>
                                                <input type="text" class="form-control" name="shift_21_fcl_d" value="{{$tarif->shift_21_fcl_d}}">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">21 Empty</label>
                                                <input type="text" class="form-control" name="shift_21_mty_d" value="{{$tarif->shift_21_mty_d}}">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">40 Full</label>
                                                <input type="text" class="form-control" name="shift_40_fcl_d" value="{{$tarif->shift_40_fcl_d}}">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">40 Empty</label>
                                                <input type="text" class="form-control" name="shift_40_mty_d" value="{{$tarif->shift_40_mty_d}}">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">42 Full</label>
                                                <input type="text" class="form-control" name="shift_42_fcl_d" value="{{$tarif->shift_42_fcl_d}}">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">42 Empty</label>
                                                <input type="text" class="form-control" name="shift_42_mty_d" value="{{$tarif->shift_42_mty_d}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                        </div>
                    </div>
                </div>

                <div class="col-6">
                        <div class="card">
                            <div class="card-header text-center">
                                <strong>Crane Kapal</strong>
                            </div>
                            <div class="card-body">
                                
                                <div class="row">
                                    <div class="col-6">
                                        <div class="row">
                                            <p>Dengan Landing</p>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="">20 Full</label>
                                                    <input type="text" class="form-control" name="shift_20_fcl_k_l" value="{{$tarif->shift_20_fcl_k_l}}">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="">20 Empty</label>
                                                    <input type="text" class="form-control" name="shift_20_mty_k_l" value="{{$tarif->shift_20_mty_k_l}}">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="">21 Full</label>
                                                    <input type="text" class="form-control" name="shift_21_fcl_k_l" value="{{$tarif->shift_21_fcl_k_l}}">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="">21 Empty</label>
                                                    <input type="text" class="form-control" name="shift_21_mty_k_l" value="{{$tarif->shift_21_mty_k_l}}">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="">40 Full</label>
                                                    <input type="text" class="form-control" name="shift_40_fcl_k_l" value="{{$tarif->shift_40_fcl_k_l}}">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="">40 Empty</label>
                                                    <input type="text" class="form-control" name="shift_40_mty_k_l" value="{{$tarif->shift_40_mty_k_l}}">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="">42 Full</label>
                                                    <input type="text" class="form-control" name="shift_42_fcl_k_l" value="{{$tarif->shift_42_fcl_k_l}}">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="">42 Empty</label>
                                                    <input type="text" class="form-control" name="shift_42_mty_k_l" value="{{$tarif->shift_42_mty_k_l}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
        
                                    <div class="col-6">
                                        <div class="row">
                                            <p>Tanpa Landing</p>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="">20 Full</label>
                                                    <input type="text" class="form-control" name="shift_20_fcl_k" value="{{$tarif->shift_20_fcl_k}}">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="">20 Empty</label>
                                                    <input type="text" class="form-control" name="shift_20_mty_k" value="{{$tarif->shift_20_mty_k}}">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="">21 Full</label>
                                                    <input type="text" class="form-control" name="shift_21_fcl_k" value="{{$tarif->shift_21_fcl_k}}">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="">21 Empty</label>
                                                    <input type="text" class="form-control" name="shift_21_mty_k" value="{{$tarif->shift_21_mty_k}}">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="">40 Full</label>
                                                    <input type="text" class="form-control" name="shift_40_fcl_k" value="{{$tarif->shift_40_fcl_k}}">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="">40 Empty</label>
                                                    <input type="text" class="form-control" name="shift_40_mty_k" value="{{$tarif->shift_40_mty_k}}">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="">42 Full</label>
                                                    <input type="text" class="form-control" name="shift_42_fcl_k" value="{{$tarif->shift_42_fcl_k}}">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="">42 Empty</label>
                                                    <input type="text" class="form-control" name="shift_42_mty_k" value="{{$tarif->shift_42_mty_k}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <h4>Tarif Tambat Kapal</h4>
                    <div class="form-group">
                        <label for="">Jasa Tambat Kapal</label>
                        <input type="number" name="tambat_kapal" value="{{$tarif->tambat_kapal}}" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <h4>Tarif Tambat Tongkang</h4>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">Loose Cargo</label>
                                <input type="number" name="loose_cargo" value="{{$tarif->loose_cargo}}" class="form-control">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">Container</label>
                                <input type="number" name="ctr_tt" value="{{$tarif->ctr_tt}}" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <h4>Additional</h4>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">Admin</label>
                                <input type="number" name="admin" value="{{$tarif->admin}}" class="form-control">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">PPN</label>
                                <input type="number" name="pajak" value="{{$tarif->pajak}}" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-light-secondary" onclick="window.history.back();"><i class="bx bx-x d-block d-sm-none"></i><span class="d-none d-sm-block">Back</span></button>
                <button type="submit" class="btn btn-primary ml-1"> <i class="bx bx-check d-block d-sm-none"></i> <span class="d-none d-sm-block">Submit</span></button>
            </div>
        </form>
    </div>
</div>
@endsection