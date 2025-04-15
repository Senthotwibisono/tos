<div class="col-12">
  <h4 class="card-title">
    Invoice OSK 
  </h4>
</div>
<div class="col-12">
  <h4 class="card-title">
    Pranota Summary 
  </h4>
</div>
@php

    $totalOSK = 0;
    $itemOSK = [];

@endphp
@foreach($groupSummary as $group)
    @php
        $tarifUsed = $tarifs->where('ctr_size', $group['size'])->where('ctr_status', $group['status'])->first();
        $detailHarga = $tarifDetails->where('master_tarif_id', $tarifUsed->id)->whereIn('master_item_id', $serviceOSK->pluck('master_item_id'));
    @endphp
    <div class="col-12">
        <p>Dengan Data Container <b> {{$group['size']}} -- {{$group['status']}} </b></p>
      <div class="card">
        <div class="card-body">
            <div class="table">
                <table class="table table-hover table-striped">
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
                     @foreach($detailHarga as $harga)
                     @if($harga->count_by != 'O')
                        <tr>
                            <td>{{$harga->master_item_name}}</td>
                            <td>{{$group['count']}}</td>
                            <td>{{($harga->count_by == 'T') ? 1 : 0}}</td>
                            <td>{{ number_format($harga->tarif, 2)}}</td>
                            @php
                              $totalItem = $harga->tarif * $group['count'];
                              $totalOSK += $totalItem;

                              $itemOSK[] = [
                                    'inv_type' => 'OSK',
                                    'keterangan' => $form->service->name,
                                    'ukuran' => $group['size'],
                                    'jumlah' => $group['count'],
                                    'satuan' => 'unit',
                                    'expired_date' => $form->expired_date,
                                    'lunas' => 'N',
                                    'cust_id' => $form->cust_id,
                                    'cust_name' => $form->customer->name,
                                    'os_id' => $form->os_id,
                                    'jumlah_hari' => ($harga->count_by == 'T') ? 1 : 0,
                                    'master_item_id' => $harga->master_item_id,
                                    'master_item_name' => $harga->master_item_name,
                                    'kode' => $harga->MItem->kode.$group['size'],
                                    'tarif' => $harga->tarif,
                                    'total' => $totalItem,
                                    'form_id' => $form->id,
                                    'count_by' => $harga->count_by,
                                ];
                            @endphp
                            <td>{{number_format($totalItem, 2)}}</td>
                        </tr>
                     @endif
                     @endforeach
                  </tbody>
                </table>
            </div>
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
              <h4 class="text-white">Discount :</h4>
              <h4 class="text-white">Tax 11%      :</h4>
              <h4 class="text-white">Grand Total  :</h4>
          </div>
            @php
                $adminOSK = $detailHarga->where('count_by', 'O')->first()->tarif ?? 0;
                if ($adminOSK > 0) {
                        $itemOSK[] = [
                            'inv_type' => 'OSK',
                            'keterangan' => $form->service->name,
                            'ukuran' => 0,
                            'jumlah' => 1,
                            'satuan' => 'unit',
                            'expired_date' => $form->expired_date,
                            'lunas' => 'N',
                            'cust_id' => $form->cust_id,
                            'cust_name' => $form->customer->name,
                            'os_id' => $form->os_id,
                            'jumlah_hari' => 0,
                            'master_item_id' => $harga->master_item_id,
                            'master_item_name' => $harga->master_item_name,
                            'kode' => $harga->MItem->kode,
                            'tarif' => $adminOSK,
                            'total' => $adminOSK,
                            'form_id' => $form->id,
                            'count_by' => $harga->count_by,
                        ];
                }
                $totalAmountOSK = ($totalOSK + $adminOSK) - $form->discount_dsk;
                $ppnOSK = $totalAmountOSK * 0.11; 
                $grandTotalOSK = $totalAmountOSK + $ppnOSK;
            @endphp
          <div class="col-6 mt-4" style="text-align:right;">
            <h4 class="text-white"> Rp. {{number_format($totalOSK, 2, ',', '.')}}</h4>
            <h4 class="text-white"> Rp. {{number_format($adminOSK, 2, ',', '.')}}</h4>
            <h4 class="text-white"> Rp. {{number_format($form->discount_dsk, 2, ',', '.')}}</h4>
            <h4 class="text-white"> Rp. {{number_format($ppnOSK, 2, ',', '.')}}</h4>
            <h4 class="text-white"> Rp. {{number_format($grandTotalOSK, 2, ',', '.')}}</h4>
            <input type="hidden" name="adminOSK" value="{{$adminOSK}}">
            <input type="hidden" name="totalAmountOSK" value="{{$totalAmountOSK}}">
            <input type="hidden" name="ppnOSK" value="{{$ppnOSK}}">
            <input type="hidden" name="grandTotalOSK" value="{{$grandTotalOSK}}"> 
            <input type="hidden" name="itemOSK" value='@json($itemOSK)'> 
          </div>
      </div>
    </div>
  </div>
</div>