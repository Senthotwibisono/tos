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
     body{
        font-family: 'Roboto Condensed', sans-serif;
    }
    .img {
      width: 100%;
      max-width: 100%;
      height: auto;
    }

    .page-break {
                page-break-before: always;
            }
    .m-0{
        margin: 0px;
    }
    .p-0{
        padding: 0px;
    }
    .pt-5{
        padding-top:5px;
    }
    .mt-10{
        margin-top:10px;
    }
    .text-center{
        text-align:center !important;
    }
    .w-100{
        width: 100%;
    }
    .w-50{
        width:50%;   
    }
    .w-85{
        width:85%;   
    }
    .w-15{
        width:15%;   
    }
    .logo img{
        width:45px;
        height:45px;
        padding-top:30px;
    }
    .logo span{
        margin-left:8px;
        top:19px;
        position: absolute;
        font-weight: bold;
        font-size:25px;
    }
    .gray-color{
        color:#5D5D5D;
    }
    .text-bold{
        font-weight: bold;
    }
    .border{
        border:1px solid black;
    }
    table tr,th,td{
        border: 1px solid #d2d2d2;
        border-collapse:collapse;
        padding:7px 8px;
    }
    table tr th{
        background: #F4F4F4;
        font-size:15px;
    }
    table tr td{
        font-size:13px;
    }
    table{
        border-collapse:collapse;
    }
    .box-text p{
        line-height:10px;
    }
    .float-left{
        float:left;
    }
    .total-part{
        font-size:16px;
        line-height:12px;
    }
    .total-right p{
        padding-right:20px;
    }
  </style>

</head>

@foreach($job as $jb)
<body>
  @foreach($cont as $ct)
  @if($ct->container_key == $jb->container_key)
  <!-- <div class="section">

    <div class="row">
      <div class="card">
        <div class="card-body">
          <div class="row">

            <div class="col-6">
              <div class="text-center">
                
                <br>
                {{$qrcodes[$jb->id]}}
                <br>
                <br>
                <h4><strong>{{$ct->order_service}} Card Container</strong></h4>
                <p>{{$ct->container_no}}</p>
                <p>{{$ct->ctr_size}} / {{$ct->ctr_type}} / {{$ct->ctr_status}}</p>
                <P><strong>Location in Yard</strong></P>
                <p>{{$ct->yard_block}} / {{$ct->yard_slot}} / {{$ct->yard_row}} / {{$ct->yard_tier}}</p>
                <p><strong>Vessel</strong></p>
                <p>{{$ct->ves_name}}--{{$ct->voy_no}}</p>

                <p>Active to {{$jb->active_to}} </p>
                
                <p>{{$inv->cust_name}}</p>

              </div>

            </div>
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

  </div> -->
  <section class="row">
    <div class="col-12">

      <div class="card">
        <div class="card-body">
          <div class="row">

            <div class="col-6 text-center">
              <h4><strong>DEPO INDO KONTAINER SARANA PONTIANAK</strong></h4>
            </div>
            <div class="col-2 text-left">
            <img src="/logo/ICON2.png" class="img" alt="">
            </div>
            <div class="col-4">
                <div class="row">
                  <div class="col-5">
                    <h5 class="lead">No. Invoice </h5>
                    <h5 class="lead">No. Nota  </h5>
                    <h5 class="lead">No. Job </h5>
                  </div>
                  <div class="col-6">
                    <h5 class="lead">: {{$inv->inv_no}}</h5>
                    <h5 class="lead">: {{$inv->proforma_no}} </h5>
                    <h5 class="lead">: {{$jb->job_no}}</h5>
                  </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12  py-5">
              <div class="row">
                <div class="col-8 text-center">
                  <h3 class="bold">
                    @if($form->service->order == "SP2")
                      Kartu Export (Reciving Card)
                    @elseif($form->service->order == "SPPS")
                      SURAT PENARIKAN PETIKEMAS STRIPPING (STUFFING)
                    @elseif($form->service->order == "MTK")
                      Reciving MT
                    @else
                      Reciving Full
                    @endif
                    </h3>
                </div>
                <div class="col-3 text-center">
                   {{$qrcodes[$jb->id]}}
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-4" style="margin-left: 100px;">
                  <div class="row">
                    <div class="col-6">
                      <h5 class="lead">No. Petikemas</h5>
                      <h5 class="lead">Ukuran / Status </h5>
                      <h5 class="lead">Ex. Kapal / Voyage </h5>
                      <h5 class="lead">Agen </h5>
                      <h5 class="lead">Tujuan </h5>
                      <h5 class="lead">No. Kendaraan </h5>
                    </div>
                    <div class="col-6">
                      <h5 class="lead">: {{$ct->container_no}}</h5>
                      <h5 class="lead">: {{$ct->ctr_size}}</h5>
                      <h5 class="lead">: {{$ct->ves_name}}--{{$ct->voy_no}}</h5>
                      <h5 class="lead">: {{$ct->Kapal->agent ?? ''}}</h5>
                      <h5 class="lead">: {{$ct->disch_port}}</h5>
                      <h5 class="lead">: </h5>
                    </div>
                  </div>
                </div>
                <!-- Commit Update -->
                <div class="col-5" style="margin-left: 100px;">
                  <style>
                    .rectangle {
                      display: inline-block;
                      width: 30px;
                      height: 30px;
                      border: 2px solid #000;
                      /* Set the border style with a black color */
                      /* margin-left: 10px; */
                      background: transparent;
                      /* Make the background transparent */
                    }
                  </style>
                  <div class="row">
                    <div class="col-6">
                      <h5 class="lead">Cek </h5>
                      <h5 class="lead">O/H. O/W. Temp</h5>
                      <h5 class="lead">Tgl. Input</h5>
                      <h5 class="lead">
                      @if($form->service->order == "SP2")
                        Booking Number
                      @else
                        RO Number
                      @endif
                      </h5>
                      <h5 class="lead">Pembayaran dari Tgl.</h5>
                      <h5 class="lead">s/d Tgl.</h5>
                    </div>
                    <div class="col-6">
                      <h5 class="lead"><span class="rectangle"></span></h5>
                      <h5 class="lead">:  {{$ct->over_height}} // {{$ct->over_weight}}</h5>
                      <h5 class="lead">:  {{$ct->created_at}}</h5>
                      <h5 class="lead">:
                      @if($form->service->order == "SP2")
                        {{$ct->booking_no}}
                      @else
                        {{$ct->ro_no}}
                      @endif  
                       </h5>
                      <h5 class="lead">:  {{$inv->order_at}}</h5>
                      <h5 class="lead">:  {{$jb->active_to}}</h5>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-6">
                <div class="row">
                  <div class="col-6 text-center">
                    <p>Petugas Lapangan</p>
                  </div>
                </div>
              </div>
              <div class="col-6 text-right">
                <div class="row">
                  <div class="col-6 text-center">
                    <div class="col-12 text-left">
                    <p><strong>Pontianak, {{$formattedDate}}</strong></p>
                    <p>PT. INDO KONTAINER SARANA</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <br>
            <br>
            <br>
            <br>
            <div class="row">
              <div class="col-6">
                <div class="row">
                  <div class="col-6 text-center">
                   <hr>
                   (Nama Jelas)
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="row">
                  <div class="col-6 text-center">
                  <hr>
                  (Nama Jelas)
                  </div>
                </div>
              </div>
            </div>
            
          <!-- <div class="row pt-5">
            <div class="col-5" style="margin-left: 50px;">
              <h5 class="bold">PETUGAS LAPANGAN</h5>
              <br>
              <br>
              <br>
              <p><i>(Nama Jelas)</i></p>
            </div>
            <div class="col-5" style="margin-left: 100px;">
              <p>PONTIANAK,</p>
              <h5 class="bold">PT.INDO KONTAINER SARANA</h5>
              <br>
              <br>
              <br>
              <p><i>(Nama Jelas)</i></p>
            </div>
          </div>
          <div class="row pt-5">
            <div class="col-12 text-center">
              <h3 class="bold">BERILAH TANDA YANG JELAS BILA TERDAPAT KERUSAKAN</h3>
            </div>
            <div class="col-12 mt-5">
              <img src="/images/container.png" class="img" style="width: 100% !important; max-width:100% !important;" alt="">
            </div>
          </div>
          
        </div> -->

      </div>
    </div>
    </div>
  </section>
  @endif
  @endforeach
  <div class="page-break"></div>
</body>
@endforeach

</html>