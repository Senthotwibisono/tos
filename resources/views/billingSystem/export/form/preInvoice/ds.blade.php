<div class="col-12">
                <h4 class="card-title">
                  Invoice OS 
                </h4>
              </div>
              @foreach($groupedContainers as $ukuran => $containers)
              <input type="hidden" name="ctr_{{$ukuran}}" value="{{$jumlahContainerPerUkuran[$ukuran]}}">
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
                        @if($service->id == '6' || $service->id == '8' || $service->id == '14' )
                          @if($service->id == '6' || $service->id == '8' )
                          <tr>
                              <td>Pass Truck Keluar</td>
                              <td>{{$jumlahContainerPerUkuran[$ukuran]}}</td>
                              <td>0</td>
                              <td>{{ number_format($tarif[$ukuran]->pass_truck_keluar, 0, ',', '.') }}</td>
                              <td>{{ number_format($jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->pass_truck_keluar, 0, ',', '.') }}
                                <input type="hidden" name="pass_truck_keluar_{{$ukuran}}" value="{{$jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->pass_truck_keluar}}">
                              </td>
                          </tr>
                          @endif
                        <tr>
                            <td>Lift On/Off Empty</td>
                            <td>{{$jumlahContainerPerUkuran[$ukuran]}}</td>
                            <td>0</td>
                            <td>{{ number_format($tarif[$ukuran]->lolo_empty, 0, ',', '.') }}</td>
                            <td>{{ number_format($jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->lolo_empty, 0, ',', '.') }}
                            <input type="hidden" name="lolo_empty_{{$ukuran}}" value="{{$jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->lolo_empty}}">
                            </td>
                        </tr>
                        @elseif($service->id == '9' || $service->id == '10')
                        <tr>
                              <td>Pass Truck Keluar</td>
                              <td>{{$jumlahContainerPerUkuran[$ukuran]}}</td>
                              <td>0</td>
                              <td>{{ number_format($tarif[$ukuran]->pass_truck_keluar, 0, ',', '.') }}</td>
                              <td>{{ number_format($jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->pass_truck_keluar, 0, ',', '.') }}
                              <input type="hidden" name="pass_truck_keluar_{{$ukuran}}" value="{{$jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->pass_truck_keluar}}">
                              </td>
                          </tr>
                          <tr>
                              <td>JPB Extruck</td>
                              <td>{{$jumlahContainerPerUkuran[$ukuran]}}</td>
                              <td>0</td>
                              <td>{{ number_format($tarif[$ukuran]->jpb_extruck, 0, ',', '.') }}</td>
                              <td>{{ number_format($jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->jpb_extruck, 0, ',', '.') }}
                              <input type="hidden" name="jpb_extruck_{{$ukuran}}" value="{{$jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->jpb_extruck}}">
                              </td>
                          </tr>
                            @if($service->id == '10')
                            <tr>
                                <td>Lift On/Off Empty</td>
                                <td>{{$jumlahContainerPerUkuran[$ukuran]}}</td>
                                <td>0</td>
                                <td>{{ number_format($tarif[$ukuran]->lolo_empty, 0, ',', '.') }}</td>
                                <td>{{ number_format($jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->lolo_empty, 0, ',', '.') }}
                                <input type="hidden" name="lolo_empty_{{$ukuran}}" value="{{$jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->lolo_empty}}">
                                </td>
                            </tr>
                            @endif
                        <tr>
                            <td>Penumpukan Massa 1</td>
                            <td>{{$jumlahContainerPerUkuran[$ukuran]}}</td>
                            <td>1 Hari</td>
                            <td>{{ number_format($tarif[$ukuran]->m1, 0, ',', '.') }}</td>
                            <td>{{ number_format($jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->m1, 0, ',', '.') }}
                            <input type="hidden" name="m1_{{$ukuran}}" value="{{$jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->m1}}">
                            </td>
                        </tr>
                        @elseif($service->id == '11' || $os == '12' || $service->id == '13' || $service->id == '15')
                            @if($service->id == '13')
                            <tr>
                              <td>Pass Truck Masuk</td>
                              <td>{{$jumlahContainerPerUkuran[$ukuran]}}</td>
                              <td>0</td>
                              <td>{{ number_format($tarif[$ukuran]->pass_truck_masuk, 0, ',', '.') }}</td>
                              <td>{{ number_format($jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->pass_truck_masuk, 0, ',', '.') }}
                              <input type="hidden" name="pass_truck_masuk_{{$ukuran}}" value="{{$jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->pass_truck_masuk}}">
                              </td>
                          </tr>
                            <tr>
                              <td>Pass Truck Keluar</td>
                              <td>{{$jumlahContainerPerUkuran[$ukuran]}}</td>
                              <td>0</td>
                              <td>{{ number_format($tarif[$ukuran]->pass_truck_keluar, 0, ',', '.') }}</td>
                              <td>{{ number_format($jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->pass_truck_keluar, 0, ',', '.') }}
                              <input type="hidden" name="pass_truck_keluar_{{$ukuran}}" value="{{$jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->pass_truck_keluar}}">
                              </td>
                          </tr>
                          <tr>
                                <td>Lift On/Off Full</td>
                                <td>{{$jumlahContainerPerUkuran[$ukuran]}}</td>
                                <td>0</td>
                                <td>{{ number_format($tarif[$ukuran]->lolo_full, 0, ',', '.') }}</td>
                                <td>{{ number_format($jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->lolo_full, 0, ',', '.') }}
                                <input type="hidden" name="lolo_full_{{$ukuran}}" value="{{$jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->lolo_full}}">
                                </td>
                            </tr>
                            <tr>
                                <td>Lift On/Off Empty</td>
                                <td>{{$jumlahContainerPerUkuran[$ukuran]}}</td>
                                <td>0</td>
                                <td>{{ number_format($tarif[$ukuran]->lolo_empty, 0, ',', '.') }}</td>
                                <td>{{ number_format($jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->lolo_empty, 0, ',', '.') }}
                                <input type="hidden" name="lolo_empty_{{$ukuran}}" value="{{$jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->lolo_empty}}">
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td>Paket Stuffing</td>
                                <td>{{$jumlahContainerPerUkuran[$ukuran]}}</td>
                                <td>0</td>
                                <td>{{ number_format($tarif[$ukuran]->paket_stuffing, 0, ',', '.') }}</td>
                                <td>{{ number_format($jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->paket_stuffing, 0, ',', '.') }}
                                <input type="hidden" name="paket_stuffing_{{$ukuran}}" value="{{$jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->paket_stuffing}}">
                                </td>
                            </tr>
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
                        <h4 class="text-white"> Rp. {{number_format($AmountDS, 0, ',', '.')}}</h4>
                        <input type="hidden" name="total" value="{{ $AmountDS + $adminDS }}">
                        <h4 class="text-white"> Rp. {{number_format($adminDS, 0, ',', '.')}}</h4>
                        <input type="hidden" name="pajak" value="{{$ppnDS}}">
                        <h4 class="text-white">Rp. {{number_format($ppnDS, 0, ',', '.')}}</h4>
                        <input type="hidden" name="grand_total" value="{{$grandDS}}">
                        <h4 class="color:#ff5265;"> Rp. {{number_format($grandDS, 0, ',', '.')}}</h4>
                       
                      </div>
                    </div>
                  </div>
                </div>
              </div>