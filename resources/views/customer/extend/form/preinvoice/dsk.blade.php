<div class="col-12">
  <h4 class="card-title">
    Invoice DSK 
  </h4>
</div>
<div class="col-12">
  <h4 class="card-title">
    Pranota Summary 
  </h4>
</div>
@php
    $totalDSK = 0;
    $adminDSK = 0;
@endphp
@foreach($sizes as $size)
  @foreach($statuses as $status)
    @php
        $jumlahCont = $listContainers->where('ctr_size', $size)->where('ctr_status', $status)->count();
    @endphp
    @if($jumlahCont > 0)
    <div class="col-12">
        <p>Dengan Data Container <b> {{$status}} -- {{$size}} </b></p>
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
               
                @foreach($serviceDSK as $dsk)
                  @php
                      $selectedTarif = $tarif->where('ctr_size', $size)->where('ctr_status', $status)->first();
                      $tarifDSK = $tarifDetail->where('master_tarif_id', $selectedTarif->id)->where('master_item_id', $dsk->master_item_id)->first();
                  @endphp
                  @if($dsk->MItem->count_by != 'O')
                  <tr>
                      <td>{{$dsk->master_item_name}}</td>
                      <td>{{$jumlahCont}}</td>
                      @php $hari = 0; @endphp
  
                      @if ($tarifDSK->MItem->count_by == 'T')
                          @if ($tarifDSK->MItem->massa == 3)
                              @php $hari = $form->massa3 ?? 0; @endphp
                          @elseif ($tarifDSK->MItem->massa == 2)
                              @php $hari = $form->massa2 ?? 0; @endphp
                          @else
                              @php $hari = 1; @endphp
                          @endif
                      @endif
                      <td>{{$hari}}</td>
                      <td>{{number_format($tarifDSK->tarif, 2)}}</td>
                      @php
                          $hariHitung = ($hari === 0) ? 1 : $hari;
  
                          $totalAmount = $jumlahCont * $hariHitung * $tarifDSK->tarif;
                          $totalDSK += $totalAmount;
                      @endphp
                      <td>{{number_format($totalAmount,2)}}</td>
                  </tr>
                  @else
                    @php
                      $adminDSK = $tarifDSK->tarif ?? 0;
                    @endphp
                  @endif
                @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
    @endif
  @endforeach
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
            $totalAmountDSK = $totalDSK + $adminDSK;
            $ppnDSK = ($totalAmountDSK * 11)/100;
            $grandTotalDSK = $totalAmountDSK + $ppnDSK; 
          @endphp
          <div class="col-6 mt-4" style="text-align:right;">
            <h4 class="text-white"> Rp. {{number_format($totalDSK, 2, ',', '.')}}</h4>
            <h4 class="text-white"> Rp. {{number_format($adminDSK, 2, ',', '.')}}</h4>
            <h4 class="text-white">Rp. {{number_format($ppnDSK, 2, ',', '.')}}</h4>
            <h4 class="color:#ff5265;">Rp. {{number_format($grandTotalDSK, 2, ',', '.')}} </h4>
            <input type="hidden" name="adminDSK" value="{{$totalAmountDSK}}">
            <input type="hidden" name="totalDSK" value="{{$totalDSK}}">
            <input type="hidden" name="ppnDSK" value="{{$ppnDSK}}">
            <input type="hidden" name="grandTotalDSK" value="{{$grandTotalDSK}}"> 
          </div>
      </div>
    </div>
  </div>
</div>