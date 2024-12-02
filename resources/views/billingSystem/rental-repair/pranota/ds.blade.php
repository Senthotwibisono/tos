<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=0.5">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{$title}} | Icon Sarana</title>
  <link rel="stylesheet" href="{{ asset('dist/assets/css/main/app.css') }}">
  <link rel="shortcut icon" href="{{ asset('logo/icon.png') }}" type="image/x-icon">
  <link rel="shortcut icon" href="{{ asset('logo/icon.png') }}" type="image/png">
</head>

<style>
    @page {
        size: 11in 9.5in;
        margin: 0;
    }

    body {
        margin: 0;
        padding: 0;
        background: #eee;
        font-family: Arial, sans-serif;
        font-size: 10px; /* Reduced from 12px */
    }

    .container {
        width: 100%;
        max-width: 950px;
        margin: 0 auto;
        padding: 20px; /* Reduced from 30px */
        background: #fff;
    }

    .invoice-title h2, .invoice-title .small {
        display: inline-block;
        font-size: 14px; /* Reduced from default size */
    }

    .invoice hr {
        margin-top: 10px;
        border-color: #ddd;
    }

    .invoice .table {
        width: 100%;
        margin-bottom: 15px; /* Reduced from 20px */
    }

    .invoice .table th, .invoice .table td {
        padding: 6px; /* Reduced from 8px */
        border-bottom: 1px solid #ddd;
        font-size: 10px; /* Reduced from default size */
    }

    .invoice .table th {
        background: #f5f5f5;
    }

    .invoice .identity {
        margin-top: 10px;
        font-size: 10px; /* Reduced from 1.1em */
        font-weight: 300;
    }

    .invoice .identity strong {
        font-weight: 600;
    }

    .grid {
        padding: 15px; /* Reduced from 20px */
        margin-bottom: 20px; /* Reduced from 25px */
        border-radius: 2px;
        box-shadow: 0px 1px 4px rgba(0, 0, 0, 0.1);
    }

    .text-right {
        text-align: right;
    }

    .mt-3 {
        margin-top: 0.5rem; /* Reduced from 1rem */
    }

    .p-3 {
        padding: 0.5rem; /* Reduced from 1rem */
    }
</style>

<body>
  <div class="container">
    <div class="row">
      <div class="col-xs-12">
        <div class="grid invoice">
          <div class="grid-body">
            <div class="invoice-title">
              <h2>Pranota<br><span class="small">Proforma No. # {{$invoice->proforma_no}}</span></h2>
            </div>
            <hr>
            <div class="row">
              <div class="col-6">
                <address>
                  <strong>Billed To:</strong><br>
                  Customer Name : {{$invoice->customer->name}}
                  <br>
                  Fax : {{$invoice->customer->fax}}
                  <br>
                  Address : {{$invoice->customer->alamat}}
                  <br>
                </address>
                <address>
                  <strong>Tipe Invoice:</strong><br> DS
                </address>
                <address>
                  <strong>Order Service:</strong><br> {{$invoice->os_name}}
                </address>
              </div>
              <div class="col-6">
                <address>
                  <strong>Order Date:</strong><br> {{$invoice->order_at}}
                </address>
                <address>
                  <strong>Metode Pembayaran</strong><br>
                  Nama Bank: <strong>MANDIRI</strong> <br>
                  Pemilik Rekening: <strong>PT. INDO KONTAINER SARANA</strong><br>
                  Kode Bank: <strong>008</strong><br>
                  Nomor Rekening: <strong>1460002771975</strong><br>
                </address>
              </div>
            </div>

            <div class="row mt-3">
              <div class="col-md-12">
                <h6>PRANOTA SUMMARY</h6>
                <table class="table table-striped">
                @if($form->service->order != 'P')
                  <thead>
                    <tr>
                      <td class="text-center"><strong>Keterangan</strong></td>
                      <td class="text-center"><strong>Jumlah Container</strong></td>
                    </tr>
                  </thead>
                  <tbody>
               
                    <tr>
                      <td class="text-center">{{$form->service->name}}</td>
                      <td class="text-center">{{$form->palka}}</td>
                    </tr>
             
                  </tbody>
                  @else
                    <thead>
                      <tr>
                        <th class="text-center">Kapal</th>
                        <th class="text-center">Voy In</th>
                        <th class="text-center">Voy Out</th>
                        <th class="text-center">Jumlah Palka</th>
                      </tr>
                    </thead>
                    <tbody>
                    <tr>
                      <td class="text-center">{{$form->Kapal->ves_name}}</td>
                      <td class="text-center">{{$form->Kapal->voy_in}}</td>
                      <td class="text-center">{{$form->Kapal->voy_out}}</td>
                      <td class="text-center">{{$form->palka}}</td>
                    </tr>
                    </tbody>
                  @endif
                </table>
              </div>
            </div>
            <div class="row p-3">
              <div class="col-6">
                <p>Admin :</p>
                <p>Total Amount: </p>
                <p>Discount {{ number_format($form->discount_ds, 2) }}% :</p>
                <p>Tax (11%): </p>
                <p>Grand Total: </p>
              </div>
              <div class="col-6 text-right">
                <!-- <p><strong>Rp. {{ number_format($invoice->total, 0, ',', '.') }} ,00 ~</strong></p>
                <p><strong>Rp. {{ number_format($admin, 0, ',', '.') }} ,00 ~</strong></p>
                <p><strong>Rp. {{ number_format($invoice->pajak, 0, ',', '.') }}, 00 ~</strong></p>
                <p><strong>Rp. {{ number_format($invoice->grand_total, 0, ',', '.') }},00 ~</strong></p> -->
                <p><strong>Rp. {{ number_format($admin, 2, ',', '.') }}</strong></p>
                <p><strong>Rp. {{ number_format($invoice->total + $admin, 2, ',', '.') }}</strong></p>
                <p><strong>Rp. {{ number_format($invoice->discount, 2, ',', '.') }}</strong></p>
                <p><strong>Rp. {{ number_format($invoice->pajak, 2, ',', '.') }}</strong></p>
                <p><strong>Rp. {{ number_format($invoice->grand_total, 2, ',', '.') }}</strong></p>

              </div>
              <div class="col-12">
                <p>Terbilang <strong>"{{$terbilang}} Rupiah"</strong></p>
              </div>
              <div class="col-12">
              <h6>Note : </h6>
              <p><strong>1. Pembayaran secara penuh sesuai nilai invoice. Biaya lainnya diluar tanggung jawab kami. 2. Complain Invoice Maksimal 3 (tiga) hari setelah invoice diterima. 3. Invoice dianggap lunas jika pembayaran masuk ke rekening yang telah diinfokan di invoice ini.
                4. Due date dihitung dari ATD. 5. Tidak Menerima pembayaran dalam bentuk tunai.</strong></p>
            </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>
