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
    
                  <h2>Laporan Batal Muat</h2>

                </div>

              </div>
            </div>
          </div>
          <hr>
        <div class="row">
            <div class="card">
                <div class="card-header">
                    <h4>Container Batal Muat</h4>
                </div>
                <div class="card-body">
                <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>NO</th>
                            <th>Container No</th>
                            <th>Batal dari Kapal</th>
                            <th>Alasan Batal</th>
                            <th>Tanggal Batal</th>
                            <th>Posisi Container</th>
                            <th>Action</th>
                            <th>Tanggal Action</th>
                            <th>Kapal Baru</th>
                        </thead>
                        <tbody>
                        @foreach($batalMuat as $cont)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$cont->container_no}}</td>
                            <td>{{$cont->old_ves_name}} || {{$cont->old_voy_no}}</td>
                            <td>{{$cont->alasan_batal_muat}}</td>
                            <td>{{$cont->tanggal_batal_muat}}</td>
                            @if($cont->cont->ctr_intern_status == '49')
                                <td>Belum Masuk IKS</td>
                            @elseif($cont->cont->ctr_intern_status == '51')
                                <td>Gate In Reciving</td>
                            @elseif($cont->cont->ctr_intern_status == '53' || $cont->cont->ctr_intern_status == '03')
                                <td>Yard at Blok <strong>{{$cont->cont->yard_block}}</strong> Slot <strong>{{$cont->cont->yard_slot}}</strong> Row <strong>{{$cont->cont->yard_row}}</strong> Tier <strong>{{$cont->cont->yard_tier}}</strong></td>
                            @elseif($cont->cont->ctr_intern_status == '10')
                                <td>Gate In Delivery (Dalam Proses Keluar)</td>
                            @elseif($cont->cont->ctr_intern_status == '09')
                                <td>Gate Out Delivery (Sudah Meninggalkan Lapangan)</td>
                            @elseif($cont->cont->ctr_intern_status == '56')
                                <td>Sudah di Kapal Baru</td>
                              @endif
    
                            @if($cont->ctr_action == "OUT")
                            <td><strong>Keluar Dari IKS</strong></td>
                            @elseif($cont->cont == null)
                            <td><strong>Belum Di Proses</strong></td>
                            @else
                            <td><strong>Pindah Kapal</strong></td>
                              @endif
                            <td>{{$cont->tanggal_action}}</td>
                            @if($cont->ctr_action == "OUT")
                            <td><strong>Keluar Dari IKS</strong></td>
                            @elseif($cont->cont == null)
                            <td><strong>Belum Di Proses</strong></td>
                            @else
                            <td><strong>{{$cont->new_ves_name}} || {{$cont->new_voy_no}}</strong></td>
                            @endif
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