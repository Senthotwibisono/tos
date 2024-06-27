<div class="col-12">
                <h4 class="card-title">
                  Invoice DS
                </h4>
              </div>
              @foreach($ctrGroup as $ukuran => $containers)
              <div class="col-12">
                <h4 class="card-title">
                  Pranota Summary 
                </h4>
                <p>Dengan Data Container <b>Container Size {{$ukuran}}</b></p>
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
                      @foreach($resultsDS as $result)
                        @if($result['ctr_size'] == $ukuran && $result['count_by'] != 'O')
                            <tr>
                                <td>{{ $result['keterangan'] }}</td>
                                <td>{{ $result['containerCount'] }}</td>
                                <td>{{ $result['jumlahHari'] }}</td>
                                <td>{{ $result['tarif'] }}</td>
                                <td>{{ $result['harga'] }}</td>
                            </tr>
                        @endif
                      @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              @endforeach
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
                        <h4 class="text-white">Discount {{ number_format($form->discount_ds, 2) }}%  :</h4>
                        <h4 class="text-white">Tax 11%      :</h4>
                        <h4 class="text-white">Grand Total  :</h4>
                    </div>

                      <div class="col-6 mt-4" style="text-align:right;">
                        <h4 class="text-white"> Rp. {{number_format($totalDS, 0, ',', '.')}}</h4>
                        <h4 class="text-white"> Rp. {{number_format($adminDS, 0, ',', '.')}}</h4>
                        <h4 class="text-white"> Rp. {{number_format($discountDS, 0, ',', '.')}}</h4>
                        <h4 class="text-white">Rp. {{number_format($pajakDS, 0, ',', '.')}}</h4>
                        <h4 class="color:#ff5265;">Rp. {{number_format($grandTotalDS, 0, ',', '.')}} </h4>
                        <input type="hidden" name="discountDS" value="{{$discountDS}}">
                        <input type="hidden" name="totalDS" value="{{$totalDS}}">
                        <input type="hidden" name="pajakDS" value="{{$pajakDS}}">
                        <input type="hidden" name="grandTotalDS" value="{{$grandTotalDS}}">
                       
                      </div>
                    </div>
                  </div>
                </div>
              </div>