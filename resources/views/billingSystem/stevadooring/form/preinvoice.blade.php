@extends ('partial.invoice.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Review Data Pranota Form & Kalkulasi</p>
</div>
<div class="page content mb-5">
  <form action="{{ route('stevadooringDetailPost')}}" method="POST" enctype="multipart/form-data">
    @CSRF
    <input type="hidden" name="deliveryFormId" >
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-12">
            <h4 class="card-title">
              Customer Detail
            </h4>
            <p>Informasi Detil Customer</p>
          </div>
          <div class="col-4">
            <div class="form-group">
              <label for="">Customer</label>
              <input type="text" name="cust_name" class="form-control" readonly value="{{$inv->cust_name}}">
              <input type="hidden" name="id" class="form-control" readonly value="{{$inv->id}}">
            </div>
          </div>
          <div class="col-4">
            <div class="form-group">
              <label for="">NPWP</label>
              <input type="text" name="npwp" class="form-control" readonly value="{{$inv->npwp}}">
            </div>
          </div>
          <div class="col-4">
            <div class="form-group">
                <label for="">Address</label>
                <input type="text" name="alamat" class="form-control" readonly value="{{$inv->alamat}}">
            </div>
          </div>
        </div>

        <!-- Vessel Information -->
        <div class="row">
          <div class="col-12">
            <h4 class="card-title">
              Customer Detail
            </h4>
            <p>Informasi Detil Kapal</p>
          </div>
          <h4>Vessel Information</h4>
            <div class="col-3">
                <div class="form-group">
                    <label for="">Vessel</label>
                    <input type="text" class="form-control" name="ves_name" value="{{$inv->ves_name}}" readonly>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label for="">Ves Code</label>
                    <input type="text" class="form-control" value="{{$inv->ves_code}}" readonly>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label for="">Voy Out</label>
                    <input type="text" class="form-control" value="{{$inv->voy_out}}" readonly>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label for="">Ves Code</label>
                    <input type="text" class="form-control" value="{{$inv->ves_code}}" readonly>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label for="">Arrival Date</label>
                    <input type="text" class="form-control" name="arrival_date" value="{{$inv->arrival_date}}" readonly>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label for="">Deparature Date</label>
                    <input type="text" class="form-control" name="deparature_date" value="{{$inv->deparature_date}}" readonly>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label for="">Open Stack Date</label>
                    <input type="text" class="form-control" value="{{$inv->open_stack_date}}" readonly>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label for="">Clossing Date</label>
                    <input type="text" class="form-control" value="{{$inv->clossing_date}}" readonly>
                </div>
            </div>
        </div>

        <div class="row mt-3">
          <div class="col-12">
            <h4 class="card-title">
              Selected Invoice Detail
            </h4>
            <p>Informasi Detil Invoice</p>
          </div>
          <div class="col-12">
          @if($inv->tambat_tongkak == 'Y')
            <h4>Jasa Tambat Tongkang</h4>
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Item Name</th>
                  <th>Tarif Satuan</th>
                  <th>Jumlah</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
               <tr>
                <td>1</td>
                <td>Loose Cargo</td>
                <td>{{number_format($mt->loose_cargo, 0 , ',', '.')}}</td>
                <td>{{$rbm->loose_cargo}}</td>
                <td>{{number_format($mt->loose_cargo * $rbm->loose_cargo, 0 , ',', '.')}}</td>
               </tr>
               <tr>
                <td>2</td>
                <td>Container</td>
                <td>{{number_format($mt->ctr_tt, 0 , ',', '.')}}</td>
                <td>{{$rbm->ctr_tt}}</td>
                <td>{{number_format($mt->ctr_tt * $rbm->ctr_tt, 0 , ',', '.')}}</td>
               </tr>
              </tbody>
            </table>
            <strong>Total : Rp. {{number_format($t_tongkak,0 ,',', '.')}}</strong>
            <input type="hidden" name="tambat_tongkak_total" value="{{$t_tongkak}}">
            <hr>
            @else
            <input type="hidden" name="tambat_tongkak_total" value="0">
            @endif
            @if($inv->tambat_kapal == 'Y')
            <h4>Jasa Tambat Kapal</h4>
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Item Name</th>
                  <th>Tarif Satuan</th>
                  <th>GT Kapal</th>
                  <th>Etmal</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
               <tr>
                <td>1</td>
                <td>Jasa Tambat Kapal</td>
                <td>{{number_format($mt->tambat_kapal, 0 , ',', '.')}}</td>
                <td>{{$rbm->gt_kapal}}</td>
                <td>{{$rbm->etmal}}</td>
                <td>{{number_format($mt->tambat_kapal * $rbm->gt_kapal * $rbm->etmal, 0 , ',', '.')}}</td>
               </tr>
              </tbody>
            </table>
            <strong>Total : Rp. {{number_format($t_kapal,0 ,',', '.')}}</strong>
            <input type="hidden" name="tambat_kapal_total" value="{{$t_kapal}}">
            <hr>
            @else
            <input type="hidden" name="tambat_kapal_total" value="0">
            @endif
            @if($inv->stevadooring == 'Y')
            <h4>Stevadooring</h4>
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Container Size</th>
                  <th>Status</th>
                  <th>Tarif Satuan</th>
                  <th>Jumlah</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
               <tr>
                <td>1</td>
                <td>20</td>
                <td>Full</td>
                <td>{{number_format($mt->ctr_20_fcl, 0 , ',', '.')}}</td>
                <td>{{$rbm->ctr_20_fcl}}</td>
                <td>{{number_format($mt->ctr_20_fcl * $rbm->ctr_20_fcl, 0 , ',', '.')}}</td>
               </tr>
               <tr>
                <td>2</td>
                <td>20</td>
                <td>Empty</td>
                <td>{{number_format($mt->ctr_20_mty, 0 , ',', '.')}}</td>
                <td>{{$rbm->ctr_20_mty}}</td>
                <td>{{number_format($mt->ctr_20_mty * $rbm->ctr_20_mty, 0 , ',', '.')}}</td>
               </tr>
               <tr>
                <td>3</td>
                <td>21</td>
                <td>Full</td>
                <td>{{number_format($mt->ctr_21_fcl, 0 , ',', '.')}}</td>
                <td>{{$rbm->ctr_21_fcl}}</td>
                <td>{{number_format($mt->ctr_21_fcl * $rbm->ctr_21_fcl, 0 , ',', '.')}}</td>
               </tr>
               <tr>
                <td>4</td>
                <td>21</td>
                <td>Empty</td>
                <td>{{number_format($mt->ctr_21_mty, 0 , ',', '.')}}</td>
                <td>{{$rbm->ctr_21_mty}}</td>
                <td>{{number_format($mt->ctr_21_mty * $rbm->ctr_21_mty, 0 , ',', '.')}}</td>
               </tr>
               <tr>
                <td>5</td>
                <td>40</td>
                <td>Full</td>
                <td>{{number_format($mt->ctr_40_fcl, 0 , ',', '.')}}</td>
                <td>{{$rbm->ctr_40_fcl}}</td>
                <td>{{number_format($mt->ctr_40_fcl * $rbm->ctr_40_fcl, 0 , ',', '.')}}</td>
               </tr>
               <tr>
                <td>6</td>
                <td>40</td>
                <td>Empty</td>
                <td>{{number_format($mt->ctr_40_mty, 0 , ',', '.')}}</td>
                <td>{{$rbm->ctr_40_mty}}</td>
                <td>{{number_format($mt->ctr_40_mty * $rbm->ctr_40_mty, 0 , ',', '.')}}</td>
               </tr>
               <tr>
                <td>7</td>
                <td>42</td>
                <td>Full</td>
                <td>{{number_format($mt->ctr_42_fcl, 0 , ',', '.')}}</td>
                <td>{{$rbm->ctr_42_fcl}}</td>
                <td>{{number_format($mt->ctr_42_fcl * $rbm->ctr_42_fcl, 0 , ',', '.')}}</td>
               </tr>
               <tr>
                <td>8</td>
                <td>42</td>
                <td>Empty</td>
                <td>{{number_format($mt->ctr_42_mty, 0 , ',', '.')}}</td>
                <td>{{$rbm->ctr_42_mty}}</td>
                <td>{{number_format($mt->ctr_42_mty * $rbm->ctr_42_mty, 0 , ',', '.')}}</td>
               </tr>
              </tbody>
            </table>
            <strong>Total : Rp. {{number_format($stevadooring,0 ,',', '.')}}</strong>
            <input type="hidden" name="stevadooring_total" value="{{$stevadooring}}">
            <hr>
            @else
            <input type="hidden" name="stevadooring_total" value="0">
            @endif
            @if($inv->shifting == 'Y')
            <h4>Shifting</h4>
            <div class="row">
                <strong class="text-center"> Crane Dermaga </strong>
                <div class="col-6" style="border-right: 1px solid black;">
                    <strong class="text-center"> Dengan Landing </strong>
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Size</th>
                          <th>Status</th>
                          <th>Tarif Satuan</th>
                          <th>Jumlah</th>
                          <th>Total</th>
                        </tr>
                      </thead>
                      <tbody>
                       <tr>
                        <td>1</td>
                        <td>20</td>
                        <td>Full</td>
                        <td>{{number_format($mt->shift_20_fcl_d_l, 0 , ',', '.')}}</td>
                        <td>{{$rbm->shift_20_fcl_d_l}}</td>
                        <td>{{number_format($mt->shift_20_fcl_d_l * $rbm->shift_20_fcl_d_l, 0 , ',', '.')}}</td>
                       </tr>
                       <tr>
                        <td>2</td>
                        <td>20</td>
                        <td>Empty</td>
                        <td>{{number_format($mt->shift_20_mty_d_l, 0 , ',', '.')}}</td>
                        <td>{{$rbm->shift_20_mty_d_l}}</td>
                        <td>{{number_format($mt->shift_20_mty_d_l * $rbm->shift_20_mty_d_l, 0 , ',', '.')}}</td>
                       </tr>
                       <tr>
                        <td>3</td>
                        <td>21</td>
                        <td>Full</td>
                        <td>{{number_format($mt->shift_21_fcl_d_l, 0 , ',', '.')}}</td>
                        <td>{{$rbm->shift_21_fcl_d_l}}</td>
                        <td>{{number_format($mt->shift_21_fcl_d_l * $rbm->shift_21_fcl_d_l, 0 , ',', '.')}}</td>
                       </tr>
                       <tr>
                        <td>4</td>
                        <td>21</td>
                        <td>Empty</td>
                        <td>{{number_format($mt->shift_21_mty_d_l, 0 , ',', '.')}}</td>
                        <td>{{$rbm->shift_21_mty_d_l}}</td>
                        <td>{{number_format($mt->shift_21_mty_d_l * $rbm->shift_21_mty_d_l, 0 , ',', '.')}}</td>
                       </tr>
                       <tr>
                        <td>5</td>
                        <td>40</td>
                        <td>Full</td>
                        <td>{{number_format($mt->shift_40_fcl_d_l, 0 , ',', '.')}}</td>
                        <td>{{$rbm->shift_40_fcl_d_l}}</td>
                        <td>{{number_format($mt->shift_40_fcl_d_l * $rbm->shift_40_fcl_d_l, 0 , ',', '.')}}</td>
                       </tr>
                       <tr>
                        <td>6</td>
                        <td>40</td>
                        <td>Empty</td>
                        <td>{{number_format($mt->shift_40_mty_d_l, 0 , ',', '.')}}</td>
                        <td>{{$rbm->shift_40_mty_d_l}}</td>
                        <td>{{number_format($mt->shift_40_mty_d_l * $rbm->shift_40_mty_d_l, 0 , ',', '.')}}</td>
                       </tr>
                       <tr>
                        <td>7</td>
                        <td>42</td>
                        <td>Full</td>
                        <td>{{number_format($mt->shift_42_fcl_d_l, 0 , ',', '.')}}</td>
                        <td>{{$rbm->shift_42_fcl_d_l}}</td>
                        <td>{{number_format($mt->shift_42_fcl_d_l * $rbm->shift_42_fcl_d_l, 0 , ',', '.')}}</td>
                       </tr>
                       <tr>
                        <td>8</td>
                        <td>42</td>
                        <td>Empty</td>
                        <td>{{number_format($mt->shift_42_mty_d_l, 0 , ',', '.')}}</td>
                        <td>{{$rbm->shift_42_mty_d_l}}</td>
                        <td>{{number_format($mt->shift_42_mty_d_l * $rbm->shift_42_mty_d_l, 0 , ',', '.')}}</td>
                       </tr>
                      </tbody>
                    </table>
                </div>
                <div class="col-6">
                    <strong class="text-center"> Tanpa Landing </strong>
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Size</th>
                          <th>Status</th>
                          <th>Tarif Satuan</th>
                          <th>Jumlah</th>
                          <th>Total</th>
                        </tr>
                      </thead>
                      <tbody>
                       <tr>
                        <td>1</td>
                        <td>20</td>
                        <td>Full</td>
                        <td>{{number_format($mt->shift_20_fcl_d, 0 , ',', '.')}}</td>
                        <td>{{$rbm->shift_20_fcl_d}}</td>
                        <td>{{number_format($mt->shift_20_fcl_d * $rbm->shift_20_fcl_d, 0 , ',', '.')}}</td>
                       </tr>
                       <tr>
                        <td>2</td>
                        <td>20</td>
                        <td>Empty</td>
                        <td>{{number_format($mt->shift_20_mty_d, 0 , ',', '.')}}</td>
                        <td>{{$rbm->shift_20_mty_d}}</td>
                        <td>{{number_format($mt->shift_20_mty_d * $rbm->shift_20_mty_d, 0 , ',', '.')}}</td>
                       </tr>
                       <tr>
                        <td>3</td>
                        <td>21</td>
                        <td>Full</td>
                        <td>{{number_format($mt->shift_21_fcl_d, 0 , ',', '.')}}</td>
                        <td>{{$rbm->shift_21_fcl_d}}</td>
                        <td>{{number_format($mt->shift_21_fcl_d * $rbm->shift_21_fcl_d, 0 , ',', '.')}}</td>
                       </tr>
                       <tr>
                        <td>4</td>
                        <td>21</td>
                        <td>Empty</td>
                        <td>{{number_format($mt->shift_21_mty_d, 0 , ',', '.')}}</td>
                        <td>{{$rbm->shift_21_mty_d}}</td>
                        <td>{{number_format($mt->shift_21_mty_d * $rbm->shift_21_mty_d, 0 , ',', '.')}}</td>
                       </tr>
                       <tr>
                        <td>5</td>
                        <td>40</td>
                        <td>Full</td>
                        <td>{{number_format($mt->shift_40_fcl_d, 0 , ',', '.')}}</td>
                        <td>{{$rbm->shift_40_fcl_d}}</td>
                        <td>{{number_format($mt->shift_40_fcl_d * $rbm->shift_40_fcl_d, 0 , ',', '.')}}</td>
                       </tr>
                       <tr>
                        <td>6</td>
                        <td>40</td>
                        <td>Empty</td>
                        <td>{{number_format($mt->shift_40_mty_d, 0 , ',', '.')}}</td>
                        <td>{{$rbm->shift_40_mty_d}}</td>
                        <td>{{number_format($mt->shift_40_mty_d * $rbm->shift_40_mty_d, 0 , ',', '.')}}</td>
                       </tr>
                       <tr>
                        <td>7</td>
                        <td>42</td>
                        <td>Full</td>
                        <td>{{number_format($mt->shift_42_fcl_d, 0 , ',', '.')}}</td>
                        <td>{{$rbm->shift_42_fcl_d}}</td>
                        <td>{{number_format($mt->shift_42_fcl_d * $rbm->shift_42_fcl_d, 0 , ',', '.')}}</td>
                       </tr>
                       <tr>
                        <td>8</td>
                        <td>42</td>
                        <td>Empty</td>
                        <td>{{number_format($mt->shift_42_mty_d, 0 , ',', '.')}}</td>
                        <td>{{$rbm->shift_42_mty_d}}</td>
                        <td>{{number_format($mt->shift_42_mty_d * $rbm->shift_42_mty_d, 0 , ',', '.')}}</td>
                       </tr>
                      </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <strong class="text-center"> Crane Kapal </strong>
                <div class="col-6" style="border-right: 1px solid black;">
                    <strong class="text-center"> Dengan Landing </strong>
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Size</th>
                          <th>Status</th>
                          <th>Tarif Satuan</th>
                          <th>Jumlah</th>
                          <th>Total</th>
                        </tr>
                      </thead>
                      <tbody>
                       <tr>
                        <td>1</td>
                        <td>20</td>
                        <td>Full</td>
                        <td>{{number_format($mt->shift_20_fcl_k_l, 0 , ',', '.')}}</td>
                        <td>{{$rbm->shift_20_fcl_k_l}}</td>
                        <td>{{number_format($mt->shift_20_fcl_k_l * $rbm->shift_20_fcl_k_l, 0 , ',', '.')}}</td>
                       </tr>
                       <tr>
                        <td>2</td>
                        <td>20</td>
                        <td>Empty</td>
                        <td>{{number_format($mt->shift_20_mty_k_l, 0 , ',', '.')}}</td>
                        <td>{{$rbm->shift_20_mty_k_l}}</td>
                        <td>{{number_format($mt->shift_20_mty_k_l * $rbm->shift_20_mty_k_l, 0 , ',', '.')}}</td>
                       </tr>
                       <tr>
                        <td>3</td>
                        <td>21</td>
                        <td>Full</td>
                        <td>{{number_format($mt->shift_21_fcl_k_l, 0 , ',', '.')}}</td>
                        <td>{{$rbm->shift_21_fcl_k_l}}</td>
                        <td>{{number_format($mt->shift_21_fcl_k_l * $rbm->shift_21_fcl_k_l, 0 , ',', '.')}}</td>
                       </tr>
                       <tr>
                        <td>4</td>
                        <td>21</td>
                        <td>Empty</td>
                        <td>{{number_format($mt->shift_21_mty_k_l, 0 , ',', '.')}}</td>
                        <td>{{$rbm->shift_21_mty_k_l}}</td>
                        <td>{{number_format($mt->shift_21_mty_k_l * $rbm->shift_21_mty_k_l, 0 , ',', '.')}}</td>
                       </tr>
                       <tr>
                        <td>5</td>
                        <td>40</td>
                        <td>Full</td>
                        <td>{{number_format($mt->shift_40_fcl_k_l, 0 , ',', '.')}}</td>
                        <td>{{$rbm->shift_40_fcl_k_l}}</td>
                        <td>{{number_format($mt->shift_40_fcl_k_l * $rbm->shift_40_fcl_k_l, 0 , ',', '.')}}</td>
                       </tr>
                       <tr>
                        <td>6</td>
                        <td>40</td>
                        <td>Empty</td>
                        <td>{{number_format($mt->shift_40_mty_k_l, 0 , ',', '.')}}</td>
                        <td>{{$rbm->shift_40_mty_k_l}}</td>
                        <td>{{number_format($mt->shift_40_mty_k_l * $rbm->shift_40_mty_k_l, 0 , ',', '.')}}</td>
                       </tr>
                       <tr>
                        <td>7</td>
                        <td>42</td>
                        <td>Full</td>
                        <td>{{number_format($mt->shift_42_fcl_k_l, 0 , ',', '.')}}</td>
                        <td>{{$rbm->shift_42_fcl_k_l}}</td>
                        <td>{{number_format($mt->shift_42_fcl_k_l * $rbm->shift_42_fcl_k_l, 0 , ',', '.')}}</td>
                       </tr>
                       <tr>
                        <td>8</td>
                        <td>42</td>
                        <td>Empty</td>
                        <td>{{number_format($mt->shift_42_mty_k_l, 0 , ',', '.')}}</td>
                        <td>{{$rbm->shift_42_mty_k_l}}</td>
                        <td>{{number_format($mt->shift_42_mty_k_l * $rbm->shift_42_mty_k_l, 0 , ',', '.')}}</td>
                       </tr>
                      </tbody>
                    </table>
                </div>
                <div class="col-6">
                    <strong class="text-center"> Tanpa Landing </strong>
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Size</th>
                          <th>Status</th>
                          <th>Tarif Satuan</th>
                          <th>Jumlah</th>
                          <th>Total</th>
                        </tr>
                      </thead>
                      <tbody>
                       <tr>
                        <td>1</td>
                        <td>20</td>
                        <td>Full</td>
                        <td>{{number_format($mt->shift_20_fcl_k, 0 , ',', '.')}}</td>
                        <td>{{$rbm->shift_20_fcl_k}}</td>
                        <td>{{number_format($mt->shift_20_fcl_k * $rbm->shift_20_fcl_k, 0 , ',', '.')}}</td>
                       </tr>
                       <tr>
                        <td>2</td>
                        <td>20</td>
                        <td>Empty</td>
                        <td>{{number_format($mt->shift_20_mty_k, 0 , ',', '.')}}</td>
                        <td>{{$rbm->shift_20_mty_k}}</td>
                        <td>{{number_format($mt->shift_20_mty_k * $rbm->shift_20_mty_k, 0 , ',', '.')}}</td>
                       </tr>
                       <tr>
                        <td>3</td>
                        <td>21</td>
                        <td>Full</td>
                        <td>{{number_format($mt->shift_21_fcl_k, 0 , ',', '.')}}</td>
                        <td>{{$rbm->shift_21_fcl_k}}</td>
                        <td>{{number_format($mt->shift_21_fcl_k * $rbm->shift_21_fcl_k, 0 , ',', '.')}}</td>
                       </tr>
                       <tr>
                        <td>4</td>
                        <td>21</td>
                        <td>Empty</td>
                        <td>{{number_format($mt->shift_21_mty_k, 0 , ',', '.')}}</td>
                        <td>{{$rbm->shift_21_mty_k}}</td>
                        <td>{{number_format($mt->shift_21_mty_k * $rbm->shift_21_mty_k, 0 , ',', '.')}}</td>
                       </tr>
                       <tr>
                        <td>5</td>
                        <td>40</td>
                        <td>Full</td>
                        <td>{{number_format($mt->shift_40_fcl_k, 0 , ',', '.')}}</td>
                        <td>{{$rbm->shift_40_fcl_k}}</td>
                        <td>{{number_format($mt->shift_40_fcl_k * $rbm->shift_40_fcl_k, 0 , ',', '.')}}</td>
                       </tr>
                       <tr>
                        <td>6</td>
                        <td>40</td>
                        <td>Empty</td>
                        <td>{{number_format($mt->shift_40_mty_k, 0 , ',', '.')}}</td>
                        <td>{{$rbm->shift_40_mty_k}}</td>
                        <td>{{number_format($mt->shift_40_mty_k * $rbm->shift_40_mty_k, 0 , ',', '.')}}</td>
                       </tr>
                       <tr>
                        <td>7</td>
                        <td>42</td>
                        <td>Full</td>
                        <td>{{number_format($mt->shift_42_fcl_k, 0 , ',', '.')}}</td>
                        <td>{{$rbm->shift_42_fcl_k}}</td>
                        <td>{{number_format($mt->shift_42_fcl_k * $rbm->shift_42_fcl_k, 0 , ',', '.')}}</td>
                       </tr>
                       <tr>
                        <td>8</td>
                        <td>42</td>
                        <td>Empty</td>
                        <td>{{number_format($mt->shift_42_mty_k, 0 , ',', '.')}}</td>
                        <td>{{$rbm->shift_42_mty_k}}</td>
                        <td>{{number_format($mt->shift_42_mty_k * $rbm->shift_42_mty_k, 0 , ',', '.')}}</td>
                       </tr>
                      </tbody>
                    </table>
                </div>
            </div>
            <strong>Total : Rp. {{number_format($shifting,0 ,',', '.')}}</strong>
            <input type="hidden" name="shifting_total" value="{{$shifting}}">
            <hr>
            @else
            <input type="hidden" name="shifting_total" value="0">
            @endif
          </div>
        </div>

        <div class="col-12">
                <div class="card" style="border-radius:15px !important;background-color:#435ebe !important;">
                  <div class="card-body">
                    <div class="row text-white p-3">
                      <div class="col-6">
                          <h1 class="lead text-white">
                              Total Summary 
                          </h1>
                          <h4 class="text-white">Total Amount :</h4>
                          <h4 class="text-white">Admin :</h4>
                          <h4 class="text-white">Tax {{$mt->pajak}}%      :</h4>
                          <h4 class="text-white">Grand Total  :</h4>
                      </div>

                      <div class="col-6 mt-4" style="text-align:right;">
                        <h4 class="text-white">Rp. {{number_format($total, 0, ',', '.')}}</h4>
                        <input type="hidden" name="total" value="{{$total}}">
                        <input type="hidden" name="admin" value="{{$mt->admin}}">
                        <h4 class="text-white">Rp. {{number_format($mt->admin, 0, ',', '.')}} </h4>
                        <input type="hidden" name="pajak" value="{{$pajak}}">
                        <h4 class="text-white">Rp. {{number_format($pajak, 0, ',', '.')}}</h4>
                        <input type="hidden" name="grand_total" value="{{$gt}}">
                        <h4 class="color:#ff5265;">Rp. {{number_format($gt, 0, ',', '.')}} </h4>
                      </div>
                      
                    </div>
                  </div>
                </div>
              </div>

        <div class="row mt-3">
          <div class="col-12 text-right">
            <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> Submit</button>
            <a class="btn btn-primary text-white" href="/billing/stevadooring/edit-invoice/{{$inv->id}}"><i class="fa fa-pen"></i> Edit</a>
            <!-- <button class="btn btn-primary text-white opacity-50" data-toggle="tooltip" data-placement="top" title="Still on Development!">
              <a><i class="fa fa-pen"></i> Edit</a>
            </button> -->
            <!-- <a type="button" class="btn btn-primary" style="opacity: 50%;"><i class="fa fa-pen "></i> Edit</a> -->
            <a href="{{ route('index-stevadooring')}}" class="btn btn-warning"><i class="fa fa-refresh"></i> Back</a>
            <!-- <a onclick="cancelAddCustomer();" type="button" class="btn btn-warning"><i class="fa fa-close"></i> Batal</a> -->
          </div>
        </div>

      </div>

    </div>
  </form>
</div>

@endsection