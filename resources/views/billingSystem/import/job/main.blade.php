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
                    <h5 class="lead">: {{$jb->Invoice->inv_no}}</h5>
                    <h5 class="lead">: {{$jb->Invoice->proforma_no}} </h5>
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
                    @if($jb->Invoice->Form->service->order == "SP2")
                      SURAT PENYERAHAN PETIKEMAS (SP2)</h3>
                    @elseif($jb->Invoice->Form->service->order == "SPPS")
                      SURAT PENARIKAN PETIKEMAS STRIPPING (SPPS)
                    @else
                      Perpanjangan
                    @endif
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
                      <h5 class="lead">Customer </h5>
                      <h5 class="lead">Lokasi dilapangan</h5>
                      <h5 class="lead">Tujuan </h5>
                      <h5 class="lead">No. Kendaraan </h5>
                    </div>
                    <div class="col-6">
                      <h5 class="lead">: {{$jb->Item->container_no}}</h5>
                      <h5 class="lead">: {{$jb->Item->ctr_size}}</h5>
                      <h5 class="lead">: {{$jb->Item->ves_name}}--{{$jb->Item->voy_no}}</h5>
                      <h5 class="lead">: {{$jb->Invoice->Form->customer->name}}</h5>
                      <h5 class="lead">: {{$jb->Item->yard_block}} / {{$jb->Item->yard_slot}} / {{$jb->Item->yard_row}} / {{$jb->Item->yard_tier}}</h5>
                      <h5 class="lead">: {{$jb->Item->disch_port}}</h5>
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
                      <h5 class="lead">Tgl. Tiba</h5>
                      <h5 class="lead">No. B/L</h5>
                      <h5 class="lead">No. D.O</h5>
                      <h5 class="lead">Pembayaran dari Tgl.</h5>
                      <h5 class="lead">s/d Tgl.</h5>
                    </div>
                    <div class="col-6">
                      <h5 class="lead"><span class="rectangle"></span></h5>
                      <h5 class="lead">:  {{$jb->Item->over_height}} // {{$jb->Item->over_weight}}</h5>
                      <h5 class="lead">:  {{$jb->Item->disc_date}}</h5>
                      <h5 class="lead">:  {{$jb->Invoice->Form->doOnline->bl_no ?? ''}}</h5>
                      <h5 class="lead">:  {{$jb->Invoice->Form->doOnline->do_no ?? ''}}</h5>
                      <h5 class="lead">:  {{$jb->Invoice->order_at}}</h5>
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
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
            <div class="row mt-4">
            <div class="col-4">
              
              <p class="bold">PENERIMA : </p>
              <br>
              <br>
              <br>
              <hr>
              <p><i>(Nama Jelas)</i></p>

            </div>
            <div class="col-4 text-center pt-5">
              <img src="/images/GATE.png" class="img" style="width: 100% !important; max-width:100% !important;" alt="">
            </div>
            <div class="col-4">

              <p class="bold">PETUGAS PINTU : </p>
              <br>
              <br>
              <br>
              <hr>
              <p><i>(Nama Jelas)</i></p>

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
  <div class="page-break"></div>
</body>
@endforeach
<div class="">
{{ $job->links('pagination::bootstrap-5') }}
</div>
</html>