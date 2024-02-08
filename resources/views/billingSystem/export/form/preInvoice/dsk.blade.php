<div class="col-12">
                <h4 class="card-title">
                  Invoice DSK 
                </h4>
              </div>
              @foreach($groupedContainers as $ukuran => $containers)
    <div class="col-12">
        <h4 class="card-title">
            Pranota Summary 
        </h4>
        <p>Dengan Data Container <b>Container Size {{$ukuran}}</b></p>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns display">
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
                      
                            @if($service->id == '6' || $service->id == '7' || $service->id == '14')
                                <tr>
                                    <td>Pass Truck Masuk</td>n 
                                    <td>{{$jumlahContainerPerUkuran[$ukuran]}}</td>
                                    <td>0</td>
                                    <td>{{ number_format($tarif[$ukuran]->pass_truck_masuk, 0, ',', '.') }}</td>
                                    <td>{{ number_format($jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->pass_truck_masuk, 0, ',', '.') }}</td>
                                </tr>
                                @if($service->id == '14')
                                    <tr>
                                        <td>Pass Truck Keluar</td>
                                        <td>{{$jumlahContainerPerUkuran[$ukuran]}}</td>
                                        <td>0</td>
                                        <td>{{ number_format($tarif[$ukuran]->pass_truck_keluar, 0, ',', '.') }}</td>
                                        <td>{{ number_format($jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->pass_truck_keluar, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td>Lift On/Off Full</td>
                                    <td>{{$jumlahContainerPerUkuran[$ukuran]}}</td>
                                    <td>0</td>
                                    <td>{{ number_format($tarif[$ukuran]->lolo_full, 0, ',', '.') }}</td>
                                    <td>{{ number_format($jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->lolo_full, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Penumpukan Massa 1</td>
                                    <td>{{$jumlahContainerPerUkuran[$ukuran]}}</td>
                                    <td>1 Hari</td>
                                    <td>{{ number_format($tarif[$ukuran]->m1, 0, ',', '.') }}</td>
                                    <td>{{ number_format($jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->m1, 0, ',', '.') }}</td>
                                </tr>
                            @elseif($service->id == '9' || $service->id == '15' || $service->id == '11' || $service->id == '13')
                                @if($service->id == '9' || $service->id == '15')
                                    <tr>
                                        <td>Pass Truck</td>
                                        <td>{{$jumlahContainerPerUkuran[$ukuran]}}</td>
                                        <td>0</td>
                                        <td>{{ number_format($tarif[$ukuran]->pass_truck_masuk, 0, ',', '.') }}</td>
                                        <td>{{ number_format($jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->pass_truck_masuk, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                                @if($service->id == '11' || $service->id == '13')
                                    <tr>
                                        <td>Cargo Dooring</td>
                                        <td>{{$jumlahContainerPerUkuran[$ukuran]}}</td>
                                        <td>0</td>
                                        <td>{{ number_format($tarif[$ukuran]->cargo_dooring, 0, ',', '.') }}</td>
                                        <td>{{ number_format($jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->cargo_dooring, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Sewa Crane</td>
                                        <td>{{$jumlahContainerPerUkuran[$ukuran]}}</td>
                                        <td>0</td>
                                        <td>{{ number_format($tarif[$ukuran]->sewa_crane, 0, ',', '.') }}</td>
                                        <td>{{ number_format($jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->sewa_crane, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                            @endif
                     
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
                        <h4 class="text-white">Tax 11%      :</h4>
                        <h4 class="text-white">Grand Total  :</h4>
                    </div>

                      <div class="col-6 mt-4" style="text-align:right;">
                        <h4 class="text-white"> Rp. {{number_format($AmountDSK, 0, ',', '.')}}</h4>
                        <input type="hidden" name="totalDSK" value="{{ $AmountDSK + $adminDSK }}">
                        <h4 class="text-white"> Rp. {{number_format($adminDSK, 0, ',', '.')}}</h4>
                        <input type="hidden" name="pajakDSK" value="{{$ppnDSK}}">
                        <h4 class="text-white">Rp. {{number_format($ppnDSK, 0, ',', '.')}}</h4>
                        <input type="hidden" name="grand_totalDSK" value="{{$grandDSK}}">
                        <h4 class="color:#ff5265;"> Rp. {{number_format($grandDSK, 0, ',', '.')}}</h4>
                       
                      </div>
                    </div>
                  </div>
                </div>
              </div>