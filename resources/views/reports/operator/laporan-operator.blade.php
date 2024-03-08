<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title><?= $title ?></title>


</head>


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
      width: 50%;
      max-width: 50%;
      height: auto;
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
    
                  <h2>Laporan Produktivitas Operator</h2>

                </div>

              </div>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="card">
                <div class="card-header">
                    <h4>Jumlah Kerja</h4>
                </div>
                <div class="card-body">
                        <table class="table table-striped">
                          <thead>
                            <tr>
                              <th>No</th>
                              <th>Name</th>
                              <th>Role</th>
                              <th>Jumlah Kerja</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($alat as $alt)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$alt->name}}</td>
                                    <td>{{$alt->role}}</td>
                                    <td>
                                    @if(isset($jumlah[$alt->id]) && $jumlah[$alt->id] != 0)
                                        {{$jumlah[$alt->id]}}
                                    @else
                                        -
                                    @endif
                                    </td>
                                </tr>
                            @endforeach
                          </tbody>
                        </table>
                </div>
                <div class="card-footer">

                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="card">
                <div class="card-header">
                    <h4>Realisasi</h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Alat</th>
                                <th>Container</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activity as $act)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$act->operator_name}}</td>
                                <td>{{$act->alat_name}}</td>
                                <td>{{$act->container_no}}</td>
                                <td>{{$act->created_at}}</td>
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