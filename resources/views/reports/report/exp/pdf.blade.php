<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title><?= $title ?></title>
  <link rel="stylesheet" href="{{asset('dist/assets/css/main/app.css')}}">

</head>


<style>
  body {
    margin-top: 20px;
    background: #eee;
  }

  .invoice {
    padding: 30px;
  }

  .invoice h2 {
    margin-top: 0px;
    line-height: 0.8em;
  }

  .invoice .small {
    font-weight: 300;
  }

  .invoice hr {
    margin-top: 10px;
    border-color: blue;
  }

  .invoice .table tr.line {
    border-bottom: 1px solid #ccc;
  }

  .invoice .table td {
    border: none;
  }

  .invoice .identity {
    margin-top: 10px;
    font-size: 1.1em;
    font-weight: 300;
  }

  .invoice .identity strong {
    font-weight: 600;
  }


  .grid {
    position: relative;
    width: 100%;
    background: #fff;
    color: #666666;
    border-radius: 2px;
    margin-bottom: 25px;
    box-shadow: 0px 1px 4px rgba(0, 0, 0, 0.1);
  }

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

<div class="container">
  <div class="row">
    <!-- BEGIN INVOICE -->
    <div class="col-xs-12">
      <div class="grid invoice">
        <div class="grid-body">
          <div class="invoice-title">
            <div class="row">
              <div class="col-xs-12">
                <!-- <img src="http://vergo-kertas.herokuapp.com/assets/img/logo.png" alt="" height="35"> -->
              </div>
            </div>
            <br>
            <div class="row">
            <div class="col-6">
                <div class="text-center">
                  <img src="/logo/ICON2.png" class="img" alt="">
                  <!-- <br> -->
    
                  <h2>Realisasi Export</h2>

                </div>

              </div>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="card">
                <div class="card-header">
                    <div class="row">

                        <div class="col-8">
                            <h4>{{$name}} - {{$voy}}</h4>
                        </div>
                        <div class="col-3">
                            <h4>Container : {{$total}}</h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <h6>{{$flag}} / <span>{{$port}}</span></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="card">
                <div class="card-header">
                    <h4></h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Container</th>
                                <th>ISO</th>
                                <th>Type</th>
                                <th>Size</th>
                                <th>Status</th>
                                <th>Service</th>
                                <th>Location</th>
                            </tr>
                        </thead>
                        <tbody>
                           @foreach($cont as $item)
                           <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$item->container_no}}</td>
                            <td>{{$item->iso_code}}</td>
                            <td>{{$item->ctr_type}}</td>
                            <td>{{$item->ctr_size}}</td>
                            <td>{{$item->ctr_status}}</td>
                            <td>{{$item->order_service}}</td>
                            <td>{{$item->bay_slot}}|{{$item->bay_row}}|{{$item->bay_tier}}</td>
                           </tr>
                           @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
      </div>
    </div>
    <!-- END INVOICE -->
  </div>
</div>

</html>