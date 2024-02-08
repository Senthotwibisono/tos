<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title><?= $title ?></title>
  <link rel="stylesheet" href="{{asset('dist/assets/css/main/app.css')}}">
  <link rel="shortcut icon" href="{{asset('logo/icon.png')}}" type="image/x-icon">
  <link rel="shortcut icon" href="{{asset('logo/icon.png')}}" type="image/png">
  <style>
    .section {
      padding-top: 5%;
    }

    .card {
      margin-bottom: 20px;
    }

    .card-body {
      padding: 15px;
    }

    .row {
      display: flex;
      flex-wrap: wrap;
      margin-right: -15px;
      margin-left: -15px;
    }

    .col-6 {
      flex: 0 0 50%;
      max-width: 50%;
      padding-right: 15px;
      padding-left: 15px;
    }

    .text-center {
      text-align: center;
    }

    .img {
      width: 100%;
      max-width: 50%;
      height: auto;
    }

    h5 {
      margin-top: 10px;
      margin-bottom: 10px;
      font-size: 14px;
      font-weight: bold;
    }

    h6 {
      margin-top: 10px;
      margin-bottom: 10px;
      font-size: 12px;
      font-weight: bold;
    }
  </style>

</head>

@foreach($job as $jb)
<body>
  @foreach($cont as $ct)
  @if($ct->container_key == $jb->container_key)
  <div class="section">

    <div class="row">
      <!-- LEFT SIDE  -->
      <div class="card">
        <div class="card-body">
          <div class="row">

            <div class="col-6">
              <div class="text-center">
                <img src="/logo/ICON2.png" class="img" alt="">
                <br>
          <!-- Taruh Qr Qode Disini -->
                {{$qrcodes[$jb->id]}}
                <br>
                <br>
                <p><strong>Kartu Export Container</strong></p>
                <p>{{$ct->container_no}}</p>
                <p>{{$ct->ctr_size}} / {{$ct->ctr_type}} / {{$ct->ctr_status}}</p>
                <P><strong>Location in Yard</strong></P>
                <p>{{$ct->yard_block}} / {{$ct->yard_slot}} / {{$ct->yard_row}} / {{$ct->yard_tier}}</p>
                <p><strong>Vessel {{$kapal->ves_name}}--{{$kapal->voy_out}}</strong></p>
                <p><strong>Clossing Date :</strong>{{$kapal->clossing_date}} -- <strong>Departure Date :</strong>{{$kapal->etd_date}}</p>

                <p>Active to {{$jb->active_to}} </p>
                
                <p>{{$inv->cust_name}}</p>

              </div>

            </div>
            <!-- RIGHT SIDE -->
            <div class="col-6">
              <div class="text-center">
                <h6>EQUIPMENT INTERCHANGE RECEIPT (EIR)</h6>
                <img src="/images/EIR.png" class="img" alt="">
                <h6>WAJIB MEMAKAI APD <br> SAAT TURUN TRUK</h6>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

  </div>
  @endif
@endforeach
</body>
@endforeach

</html>