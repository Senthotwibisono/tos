@extends('partial.invoice.main')

@section('content')
<div class="page-heading">
  <h3>{{$title}}</h3>
</div>

<div class="page-content">
    <div class="card">
        <div class="card-header">

        </div>
        <form action="{{ route('index-stevadooring-RBM_Post')}}" method="post">
            @csrf
        <div class="card-body">
            <div class="row">
                <h4>Vessel Information</h4>
                <div class="col-3">
                    <div class="form-group">
                        <label for="">Vessel</label>
                        <input type="text" class="form-control" name="ves_name" value="{{$kapal->ves_name}}" readonly>
                        <input type="hidden" class="form-control" name="ves_id" value="{{$kapal->ves_id}}">
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="">Voy Out</label>
                        <input type="text" class="form-control" value="{{$kapal->voy_out}}" readonly>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="">Ves Code</label>
                        <input type="text" class="form-control" value="{{$kapal->ves_code}}" readonly>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="">Agent</label>
                        <input type="text" class="form-control" value="{{$kapal->agent}}" readonly>
                    </div>
                </div>
            </div>
            <hr>

            <div class="row">
                <h4>Vessel Schedule</h4>
                <div class="col-3">
                    <div class="form-group">
                        <label for="">Arrival Date</label>
                        <input type="text" class="form-control" name="arrival_date" value="{{$kapal->arrival_date}}" readonly>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="">Deparature Date</label>
                        <input type="text" class="form-control" name="deparature_date" value="{{$kapal->deparature_date}}" readonly>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="">Open Stack Date</label>
                        <input type="text" class="form-control" value="{{$kapal->open_stack_date}}" readonly>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="">Clossing Date</label>
                        <input type="text" class="form-control" value="{{$kapal->clossing_date}}" readonly>
                    </div>
                </div>
            </div>
            <hr>

            <div class="row">
                <h4>Container Info</h4>

                <!-- <div class="col-6">
                    <div class="card">
                        <div class="card-header">
                            Container Import
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">20 Empty</label>
                                        <input type="text"  class="form-control">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">20 Full</label>
                                        <input type="text"  class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">21 Empty</label>
                                        <input type="text"  class="form-control">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">21 Full</label>
                                        <input type="text"  class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">40 Empty</label>
                                        <input type="text"  class="form-control">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">40 Full</label>
                                        <input type="text"  class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">42 Empty</label>
                                        <input type="text"  class="form-control">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">42 Full</label>
                                        <input type="text"  class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->

                <!-- <div class="col-6">
                    <div class="card">
                        <div class="card-header">
                            Container Export
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">20 Empty</label>
                                        <input type="text"  class="form-control">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">20 Full</label>
                                        <input type="text"  class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">21 Empty</label>
                                        <input type="text"  class="form-control">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">21 Full</label>
                                        <input type="text"  class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">40 Empty</label>
                                        <input type="text"  class="form-control">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">40 Full</label>
                                        <input type="text"  class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">42 Empty</label>
                                        <input type="text"  class="form-control">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">42 Full</label>
                                        <input type="text"  class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->

                 <div class="card-footer">
                     <p>Total</p>
                     <div class="row">
                         <p>Container Full</p>
                         <div class="col-3">
                             <div class="form-group">
                                 <label for="">20</label>
                                 <input type="text" class="form-control" name="ctr_20_fcl">
                             </div>
                         </div>
                         <div class="col-3">
                             <div class="form-group">
                                 <label for="">21</label>
                                 <input type="text" class="form-control" name="ctr_21_fcl">
                             </div>
                         </div>
                         <div class="col-3">
                             <div class="form-group">
                                 <label for="">40</label>
                                 <input type="text" class="form-control" name="ctr_40_fcl">
                             </div>
                         </div>
                         <div class="col-3">
                             <div class="form-group">
                                 <label for="">42</label>
                                 <input type="text" class="form-control" name="ctr_42_fcl">
                             </div>
                         </div>
                     </div>
                     <div class="row">
                         <p>Container Empty</p>
                         <div class="col-3">
                             <div class="form-group">
                                 <label for="">20</label>
                                 <input type="text" class="form-control" name="ctr_20_mty">
                             </div>
                         </div>
                         <div class="col-3">
                             <div class="form-group">
                                 <label for="">21</label>
                                 <input type="text" class="form-control" name="ctr_21_mty">
                             </div>
                         </div>
                         <div class="col-3">
                             <div class="form-group">
                                 <label for="">40</label>
                                 <input type="text" class="form-control" name="ctr_40_mty">
                             </div>
                         </div>
                         <div class="col-3">
                             <div class="form-group">
                                 <label for="">42</label>
                                 <input type="text" class="form-control" name="ctr_42_mty">
                             </div>
                         </div>
                     </div>
                 </div>

            </div>
            <div class="row">
                <h4>Shifting</h4>
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
                                                <input type="text" class="form-control" name="shift_20_fcl_d_l">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">20 Empty</label>
                                                <input type="text" class="form-control" name="shift_20_mty_d_l">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">21 Full</label>
                                                <input type="text" class="form-control" name="shift_21_fcl_d_l">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">21 Empty</label>
                                                <input type="text" class="form-control" name="shift_21_mty_d_l">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">40 Full</label>
                                                <input type="text" class="form-control" name="shift_40_fcl_d_l">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">40 Empty</label>
                                                <input type="text" class="form-control" name="shift_40_mty_d_l">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">42 Full</label>
                                                <input type="text" class="form-control" name="shift_42_fcl_d_l">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">42 Empty</label>
                                                <input type="text" class="form-control" name="shift_42_mty_d_l">
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
                                                <input type="text" class="form-control" name="shift_20_fcl_d">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">20 Empty</label>
                                                <input type="text" class="form-control" name="shift_20_mty_d">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">21 Full</label>
                                                <input type="text" class="form-control" name="shift_21_fcl_d">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">21 Empty</label>
                                                <input type="text" class="form-control" name="shift_21_mty_d">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">40 Full</label>
                                                <input type="text" class="form-control" name="shift_40_fcl_d">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">40 Empty</label>
                                                <input type="text" class="form-control" name="shift_40_mty_d">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">42 Full</label>
                                                <input type="text" class="form-control" name="shift_42_fcl_d">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">42 Empty</label>
                                                <input type="text" class="form-control" name="shift_42_mty_d">
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
                                                <input type="text" class="form-control" name="shift_20_fcl_k_l">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">20 Empty</label>
                                                <input type="text" class="form-control" name="shift_20_mty_k_l">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">21 Full</label>
                                                <input type="text" class="form-control" name="shift_21_fcl_k_l">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">21 Empty</label>
                                                <input type="text" class="form-control" name="shift_21_mty_k_l">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">40 Full</label>
                                                <input type="text" class="form-control" name="shift_40_fcl_k_l">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">40 Empty</label>
                                                <input type="text" class="form-control" name="shift_40_mty_k_l">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">42 Full</label>
                                                <input type="text" class="form-control" name="shift_42_fcl_k_l">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">42 Empty</label>
                                                <input type="text" class="form-control" name="shift_42_mty_k_l">
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
                                                <input type="text" class="form-control" name="shift_20_fcl_k">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">20 Empty</label>
                                                <input type="text" class="form-control" name="shift_20_mty_k">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">21 Full</label>
                                                <input type="text" class="form-control" name="shift_21_fcl_k">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">21 Empty</label>
                                                <input type="text" class="form-control" name="shift_21_mty_k">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">40 Full</label>
                                                <input type="text" class="form-control" name="shift_40_fcl_k">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">40 Empty</label>
                                                <input type="text" class="form-control" name="shift_40_mty_k">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">42 Full</label>
                                                <input type="text" class="form-control" name="shift_42_fcl_k">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">42 Empty</label>
                                                <input type="text" class="form-control" name="shift_42_mty_k">
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
                <h4>Additional Info</h4>
                <div class="col-3">
                    <div class="form-group">
                        <label for="">Loose Cargo</label>
                        <input type="number" class="form-control" name="loose_cargo" placeholder="isi dengan 0 jika kosong" required>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="">Container Tambat Tongkang</label>
                        <input type="number" class="form-control" name="ctr_tt" placeholder="isi dengan 0 jika kosong" required>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="">GT Kapal</label>
                        <input type="number" class="form-control" name="gt_kapal" placeholder="isi dengan 0 jika kosong" required>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="">Etmal</label>
                        <input type="number" class="form-control" name="etmal" placeholder="isi dengan 0 jika kosong" required>
                    </div>
                </div>
            </div>

        </div>
        <div class="card-footer">
            <div class="col-12 text-right">
                <button type="submit" class="btn btn-success">Submit</button>
                <button type="button" class="btn btn-light-secondary" onclick="window.history.back();"><i class="bx bx-x d-block d-sm-none"></i><span class="d-none d-sm-block">Back</span></button>
            </div>
        </div>
        </form>
    </div>
</div>

@endsection