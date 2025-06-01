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
    $totalDS = 0;
    $adminDS= 0;
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
                @foreach($serviceDS as $ds)
                @php
                    $selectedTarif = $tarif->where('ctr_size', $size)->where('ctr_status', $status)->first();
                    $tarifDS = $tarifDetail->where('master_tarif_id', $selectedTarif->id)->where('master_item_id', $ds->master_item_id)->first();
                @endphp
                  @if($ds->MItem->count_by != 'O')
                  <tr>
                      <td>{{$ds->master_item_name}}</td>
                      <td>{{$jumlahCont}}</td>
                      @php
                        $hari = 0; 
                        $hariHitung = 1;
                      @endphp
  
                      @if ($tarifDS->MItem->count_by == 'T')
                          @if ($tarifDS->MItem->massa == 3)
                              @php 
                                $hari = $form->massa3 ?? 0; 
                                $hariHitung = $form->massa3 ?? 0; 
                              @endphp
                          @elseif ($tarifDS->MItem->massa ?? 0 == 2)
                              @php
                                $hari = $form->massa2; 
                                $hariHitung = $form->massa2; 
                              @endphp
                          @else
                              @php
                                $hari = 1; 
                                $hariHitung = 1; 
                              @endphp
                          @endif
                      @endif
                      <td>{{$hari}}</td>
                      <td>{{number_format($tarifDS->tarif, 2)}}</td>
                      @php
                          $totalAmount = $jumlahCont * $hariHitung * $tarifDS->tarif;
                          $totalDS += $totalAmount;
                      @endphp
                      <td>{{number_format($totalAmount,2)}}</td>
                  </tr>
                  @else
                    @php
                      $adminDS = $tarifDS->tarif ?? 0;
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
              <h4 class="text-white">Tax 11%      :</h4>
              <h4 class="text-white">Discount :</h4>
              <h4 class="text-white">Grand Total  :</h4>
          </div>
          @php
            $totalAmountDS = $totalDS + $adminDS;
            $ppnDS = ($totalAmountDS * 11)/100;
            $grandTotalDS = $totalAmountDS + $ppnDS - $form->discount_ds; 
          @endphp
          <div class="col-6 mt-4" style="text-align:right;">
            <h4 class="text-white"> Rp. {{number_format($totalDS, 2, ',', '.')}}</h4>
            <h4 class="text-white"> Rp. {{number_format($adminDS, 2, ',', '.')}}</h4>
            <h4 class="text-white">Rp. {{number_format($ppnDS, 2, ',', '.')}}</h4>
            <h4 class="text-white">Rp. {{number_format($form->discount_ds, 2, ',', '.')}}</h4>
            <h4 class="color:#ff5265;">Rp. {{number_format($grandTotalDS, 2, ',', '.')}} </h4>
            <input type="hidden" name="adminDS" value="{{$adminDS}}">
            <input type="hidden" name="totalDS" value="{{$totalDS}}">
            <input type="hidden" name="ppnDS" value="{{$ppnDS}}">
            <input type="hidden" name="grandTotalDS" value="{{$grandTotalDS}}"> 
            <input type="hidden" name="discountDS" value="{{$form->discount_ds}}"> 
          </div>
      </div>
    </div>
  </div>
</div>