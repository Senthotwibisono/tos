<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title><?= $title ?></title>
 
  <style>

    .invoice {
      background: #fff;
      padding: 20px;
      box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    }
    .invoice-title {
      text-align: center;
    }
    .invoice-title img {
      max-width: 100%;
      height: auto;
    }
    .invoice-title h2 {
      margin: 20px 0;
    }
    .invoice hr {
      border: 1px solid #ddd;
    }
    .table {
      width: 100%;
      border-collapse: collapse;
    }
    .table th, .table td {
      border: 1px solid #ddd;
      padding: 10px;
      text-align: center;
    }

    .img {
      width: 100%;
      max-width: 50%;
      height: auto;
  }
  </style>
</head>
<body>
  <div class="container">
    <div class="invoice">
      <div class="invoice-title">
        <img src="/logo/ICON2.png" alt="">
        <h2>Realisasi Export</h2>
      </div>
      <hr>
      <div class="card">
        <div class="card-header">
          <h4>{{$name}} - {{$voy}}</h4>
        </div>
        <div class="card-body">
          <p>{{$flag}} / <span>{{$port}}</span></p>
          <p>Arrival: {{$arrival}}</p>
          <p>Departure: {{$departure}}</p>
        </div>
      </div>
      <hr>
      <div class="card">
        <div class="card-header">
          <h4></h4>
        </div>
        <div class="card-body">
          <table class="table">
            <thead>
              <tr>
                <th>No</th>
                <th>Container</th>
                <th>ISO</th>
                <th>Type</th>
                <th>Size</th>
                <th>Status</th>
                <th>Gross</th>
                <th>G Class</th>
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
                <td>{{$item->gross}}</td>
                <td>{{$item->gross_class}}</td>
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
</body>
</html>
