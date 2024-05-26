@extends ('partial.invoice.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Review Data Pranota Form & Kalkulasi</p>
</div>
<div class="page content mb-5">
  <form action="{{ route('extendCreate')}}" method="POST" enctype="multipart/form-data">
    @CSRF
    <input type="hidden" name="deliveryFormId" >
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-12">
            <h4 class="card-title">
              Delivery Form Detail
            </h4>
            <p>Informasi Detil Formulir Delivery</p>
          </div>
          <div class="col-4">
            <div class="form-group">
              <label for="">Customer</label>
              <input type="text" name="cust_name" class="form-control" readonly value="{{$customer->name}}">
              <input type="hidden" name="cust_id" class="form-control" readonly value="{{$customer->id}}">
              <input type="hidden" name="inv_id" class="form-control" readonly value="{{$oldInv->id}}">
            </div>
          </div>
          <div class="col-4">
            <div class="form-group">
              <label for="">NPWP</label>
              <input type="text" name="npwp" class="form-control" readonly value="{{$customer->npwp}}">
            </div>
          </div>
          <div class="col-4">
            <div class="form-group">
              <label for="">Expired Date</label>
              <input type="date" name="expired_date"class="form-control" readonly value="{{$expired}}">
            </div>
          </div>
          <div class="col-12">
            <div class="form-group">
              <label for="">Address</label>
              <input type="text" name="alamat" class="form-control" readonly value="{{$customer->alamat}}">
              <input type="hidden" name="fax" class="form-control" readonly value="{{$customer->fax}}">
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <label for="">Old Invoice Number</label>
              <input type="text" name="old_inv_no" class="form-control" readonly value="{{$oldInv->inv_no}}">
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <label for="">Old Invoice Expired</label>
              <input type="date" name="old_inv_no" class="form-control" readonly value="{{$oldInv->expired_date}}">
            </div>
          </div>
        <input type="hidden" name="m1" value="{{$newM1}}">
        <input type="hidden" name="m2" value="{{$newM2}}">
        <input type="hidden" name="m3" value="{{$newM3}}">
        </div>

        <div class="row mt-3">
          <div class="col-12">
            <h4 class="card-title">
              Selected Container Detail
            </h4>
            <p>Informasi Detil Container</p>
          </div>
          <div class="col-12">
            <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
              <thead>
                <tr>
                  <th>Container No</th>
                  <th>Vessel Name</th>
                  <th>Size</th>
                  <th>Type</th>
                  <th>CTR Status</th>
                  <th>CTR Intern Status</th>
                  <th>Gross</th>
                </tr>
              </thead>
              <tbody>
               @foreach($item as $cont)
               <tr>
                <td>{{$cont->container_no}}</td>
                <td>{{$cont->ves_name}}</td>
                <td>{{$cont->ctr_size}}</td>
                <td>{{$cont->ctr_type}}</td>
                <td>{{$cont->ctr_status}}</td>
                <td>{{$cont->ctr_intern_status}}</td>
                <td>{{$cont->gross}}</td>
               </tr>
               @endforeach
              </tbody>
            </table>
          </div>
        </div>
        <div class="row mt-3">
        <input type="hidden" name="contKey_Selected" value="{{ implode(',', $selectedCont) }}">
        @if($ctr_20 != null)
        <input type="hidden" name="ctr_20" value="{{$ctr_20}}">
        <div class="col-12">
                <h4 class="card-title">
                  Pranota Summary 
                </h4>
                <p>Dengan Data Container <b>Container Size 20</b></p>
              </div>
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns display ">
                      <thead>
                        <tr>
                          <th>Keterangan</th>
                          <th>Jumlah</th>
                          <th>Hari</th>
                          <th>Tarif Satuan</th>
                          <th>Amount</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($newM1 != '0')
                       <tr>
                        <td>Massa 1</td>
                        <td>{{$ctr_20}}</td>
                        <td>{{$newM1}}</td>
                        <td>0</td>
                        <td>{{$m1_20}} <input type="hidden" name="m1_20" value="{{$m1_20}}"> </td>
                       </tr>
                       @endif
                       @if($newM2 != '0')
                       <tr>
                        <td>Massa 2</td>
                        <td>{{$ctr_20}}</td>
                        <td>{{$newM2}}</td>
                        <td>54.000</td>
                        <td>{{$m2_20}} <input type="hidden" name="m2_20" value="{{$m2_20}}"> </td>
                       </tr>
                       @endif
                       @if($newM3 != '0')
                       <tr>
                        <td>Massa 3</td>
                        <td>{{$ctr_20}}</td>
                        <td>{{$newM3}}</td>
                        <td>81.000</td>
                        <td>{{$m3_20}} <input type="hidden" name="m3_20" value="{{$m3_20}}"></td>
                       </tr>
                       @endif
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
        @endif
        @if($ctr_21 != null)
        <div class="col-12">
        <input type="hidden" name="ctr_21" value="{{$ctr_21}}">
                <h4 class="card-title">
                  Pranota Summary 
                </h4>
                <p>Dengan Data Container <b>Container Size 21</b></p>
              </div>
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns display ">
                      <thead>
                        <tr>
                          <th>Keterangan</th>
                          <th>Jumlah</th>
                          <th>Hari</th>
                          <th>Tarif Satuan</th>
                          <th>Amount</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($newM1 != '0')
                       <tr>
                        <td>Massa 1</td>
                        <td>{{$ctr_21}}</td>
                        <td>{{$newM1}}</td>
                        <td>{{$tarif21->m1}}</td>
                        <td>{{$m1_21}} <input type="hidden" name="m1_21" value="{{$m1_21}}"></td>
                       </tr>
                       @endif
                       @if($newM2 != '0')
                       <tr>
                        <td>Massa 2</td>
                        <td>{{$ctr_21}}</td>
                        <td>{{$newM2}}</td>
                        <td>{{$tarif21->m2}}</td>
                        <td>{{$m2_21}} <input type="hidden" name="m2_21" value="{{$m2_21}}"> </td>
                       </tr>
                       @endif
                       @if($newM3 != '0')
                       <tr>
                        <td>Massa 3</td>
                        <td>{{$ctr_21}}</td>
                        <td>{{$newM3}}</td>
                        <td>{{$tarif21->m3}}</td>
                        <td>{{$m3_21}}  <input type="hidden" name="m3_21" value="{{$m3_21}}"></td>
                       </tr>
                       @endif
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
        @endif

        @if($ctr_40 != null)
        <div class="col-12">
        <input type="hidden" name="ctr_40" value="{{$ctr_40}}">

                <h4 class="card-title">
                  Pranota Summary 
                </h4>
                <p>Dengan Data Container <b>Container Size 40</b></p>
              </div>
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns display ">
                      <thead>
                        <tr>
                          <th>Keterangan</th>
                          <th>Jumlah</th>
                          <th>Hari</th>
                          <th>Tarif Satuan</th>
                          <th>Amount</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($newM1 != '0')
                       <tr>
                        <td>Massa 1</td>
                        <td>{{$ctr_40}}</td>
                        <td>{{$newM1}}</td>
                        <td>{{$tarif40->m1}}</td>
                        <td>{{$m1_40}}  <input type="hidden" name="m1_40" value="{{$m1_40}}"> </td>
                       </tr>
                       @endif
                       @if($newM2 != '0')
                       <tr>
                        <td>Massa 2</td>
                        <td>{{$ctr_40}}</td>
                        <td>{{$newM2}}</td>
                        <td>{{$tarif40->m2}}</td>
                        <td>{{$m2_40}}  <input type="hidden" name="m2_40" value="{{$m2_40}}"> </td>
                       </tr>
                       @endif
                       @if($newM3 != '0')
                       <tr>
                        <td>Massa 3</td>
                        <td>{{$ctr_40}}</td>
                        <td>{{$newM3}}</td>
                        <td>{{$tarif40->m3}}</td>
                        <td>{{$m3_40}}  <input type="hidden" name="m3_40" value="{{$m3_40}}"> </td>
                       </tr>
                       @endif
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
        @endif
         

        @if($ctr_42 != null)
        <div class="col-12">
        <input type="hidden" name="ctr_42" value="{{$ctr_42}}">

                <h4 class="card-title">
                  Pranota Summary 
                </h4>
                <p>Dengan Data Container <b>Container Size 42</b></p>
              </div>
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns display ">
                      <thead>
                        <tr>
                          <th>Keterangan</th>
                          <th>Jumlah</th>
                          <th>Hari</th>
                          <th>Tarif Satuan</th>
                          <th>Amount</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($newM1 != '0')
                       <tr>
                        <td>Massa 1</td>
                        <td>{{$ctr_42}}</td>
                        <td>{{$newM1}}</td>
                        <td>{{$tarif42->m1}}</td>
                        <td>{{$m1_42}} <input type="hidden" name="m1_42" value="{{$m1_42}}"> </td>
                       </tr>
                       @endif
                       @if($newM2 != '0')
                       <tr>
                        <td>Massa 2</td>
                        <td>{{$ctr_42}}</td>
                        <td>{{$newM2}}</td>
                        <td>{{$tarif42->m2}}</td>
                        <td>{{$m2_42}} <input type="hidden" name="m2_42" value="{{$m2_42}}"> </td>
                       </tr>
                       @endif
                       @if($newM3 != '0')
                       <tr>
                        <td>Massa 3</td>
                        <td>{{$ctr_42}}</td>
                        <td>{{$newM3}}</td>
                        <td>{{$tarif42->m3}}</td>
                        <td>{{$m3_42}} <input type="hidden" name="m3_42" value="{{$m3_42}}"> </td>
                       </tr>
                       @endif
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
        @endif
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
                        <h4 class="text-white">Tax 11%      :</h4>
                        <h4 class="text-white">Grand Total  :</h4>
                    </div>

                      <div class="col-6 mt-4" style="text-align:right;">
                        <h4 class="text-white"> Rp. {{number_format($total, 0, ',', '.')}}</h4>
                        <input type="hidden" name="total" value="{{$total}}">
                        <input type="hidden" name="admin" value="{{$admin}}">
                        <h4 class="text-white"> Rp. {{number_format($admin, 0, ',', '.')}} </h4>
                        <input type="hidden" name="pajak" value="{{$pajakTot}}">
                        <h4 class="text-white">Rp. {{number_format($pajakTot, 0, ',', '.')}}</h4>
                        <input type="hidden" name="grand_total" value="{{$grandTotal}}">
                        <h4 class="color:#ff5265;"> Rp. {{number_format($grandTotal, 0, ',', '.')}} </h4>
                       
                      </div>
                    </div>
                  </div>
                </div>
              </div>
        </div>
        <div class="row mt-3">
          <div class="col-12 text-right">
            <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> Submit</button>
            <button class="btn btn-primary text-white opacity-50" data-toggle="tooltip" data-placement="top" title="Still on Development!">
              <a><i class="fa fa-pen"></i> Edit</a>
            </button>
            <!-- <a type="button" class="btn btn-primary" style="opacity: 50%;"><i class="fa fa-pen "></i> Edit</a> -->
            <a onclick="cancelAddCustomer();" type="button" class="btn btn-warning"><i class="fa fa-close"></i> Batal</a>
          </div>
        </div>

      </div>

    </div>
  </form>
</div>

@endsection