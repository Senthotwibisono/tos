<div class="col-12">
  <h4 class="card-title">
    Invoice OS
  </h4>
</div>

<div class="col-12">
  <h4 class="card-title">
    Pranota Summary 
  </h4>
  <p>Dengan Data Container <b>Container Size</b></p>
</div>
<div class="col-12">
  <div class="card">
    <div class="card-body">
      <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns display ">
        @if($form->service->order != 'P')
        <thead>
          <tr>
            <th>Keterangan</th>
            <th>Jumlah</th>
          </tr>
        </thead>
        <tbody>
        <tr>
          <td>{{$form->service->name}}</td>
          <td>{{$form->palka}}</td>
        </tr>
        </tbody>
        @else
        <thead>
          <tr>
            <th>Kapal</th>
            <th>Voy In</th>
            <th>Voy Out</th>
            <th>Jumlah Palka</th>
          </tr>
        </thead>
        <tbody>
        <tr>
          <td>{{$form->Kapal->ves_name}}</td>
          <td>{{$form->Kapal->voy_in}}</td>
          <td>{{$form->Kapal->voy_out}}</td>
          <td>{{$form->palka}}</td>
        </tr>
        </tbody>
        @endif
      </table>
    </div>
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
          <input type="hidden" name="adminDS" value="{{$adminDS}}">
          <input type="hidden" name="discountDS" value="{{$discountDS}}">
          <input type="hidden" name="totalDS" value="{{$totalDS}}">
          <input type="hidden" name="pajakDS" value="{{$pajakDS}}">
          <input type="hidden" name="grandTotalDS" value="{{$grandTotalDS}}">
         
        </div>
      </div>
    </div>
  </div>
</div>