<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            vertical-align: top;
        }

        th {
            background: #f2f2f2;
            text-align: center;
        }

        .no-border td {
            border: none;
            padding: 3px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        h2, h3, h4 {
            margin: 5px 0;
        }

        hr {
            border: 0;
            border-top: 1px solid #000;
            margin: 10px 0;
        }

        .mt-10 { margin-top: 10px; }
        .mt-20 { margin-top: 20px; }
    </style>
</head>

<body>

<!-- ================= HEADER ================= -->
<table class="no-border">
    <tr>
        <td width="60%">
            <h2>INVOICE</h2>
            <strong>Proforma No :</strong> {{ $invoice->proforma_no }}<br>
            <strong>Invoice No :</strong> {{ $invoice->invoice_no }}
        </td>
        <td width="40%" class="text-center">
            <img src="{{ public_path('logoInvoice/logoDSK.jpg') }}" width="160"><br><br>
            <img src="{{ public_path('images/paid.png') }}" width="120">
        </td>
    </tr>
</table>

<hr>

<!-- ================= CUSTOMER & PAYMENT ================= -->
<table class="no-border">
    <tr>
        <td width="50%">
            <strong>Billed To:</strong><br>
            Customer Name : {{ $invoice->cust_name }}<br>
            Fax : {{ $invoice->fax }}<br>
            Address : {{ $invoice->alamat }}<br><br>

            <strong>Tipe Invoice:</strong><br>
            Stevadooring<br><br>

            <strong>Activity Include:</strong><br>
            @if($invoice->tambat_tongkak == 'Y') Tambat Tongkang<br>@endif
            @if($invoice->tambat_kapal == 'Y') Tambat Kapal<br>@endif
            @if($invoice->stevadooring == 'Y') Stevadooring<br>@endif
            @if($invoice->shifting == 'Y') Shifting<br>@endif
        </td>

        <td width="50%">
            <strong>Invoice Date:</strong><br>
            {{ $invoice->invoice_date }}<br><br>

            <strong>Metode Pembayaran:</strong><br>
            Bank : <strong>MANDIRI</strong><br>
            Pemilik : <strong>PT. INDO KONTAINER SARANA</strong><br>
            Kode Bank : <strong>008</strong><br>
            No Rek : <strong>1460002771975</strong>
        </td>
    </tr>
</table>

<!-- ================= VESSEL INFO ================= -->
<h4 class="mt-20">Vessel Information</h4>
<table>
    <tr>
        <th>Vessel</th>
        <th>Ves Code</th>
        <th>Voy Out</th>
        <th>Arrival Date</th>
        <th>Departure Date</th>
    </tr>
    <tr>
        <td>{{ $invoice->ves_name }}</td>
        <td>{{ $invoice->ves_code }}</td>
        <td>{{ $invoice->voy_out }}</td>
        <td>{{ $invoice->arrival_date }}</td>
        <td>{{ $invoice->deparature_date }}</td>
    </tr>
</table>

<h4 class="mt-10">Type RBM</h4>
<table>
    <tr>
        <td class="text-center"><strong>{{ $type }}</strong></td>
    </tr>
</table>

<!-- ================= PRANOTA ================= -->
<h3 class="mt-20">INVOICE SUMMARY</h3>

@if($invoice->tambat_tongkak == 'Y')
<h4>Jasa Tambat Tongkang</h4>
<table>
    <tr>
        <th>No</th>
        <th>Item Name</th>
        <th>Tarif Satuan</th>
        <th>Jumlah</th>
        <th>Total</th>
    </tr>
    @foreach($tongkak as $tt)
    <tr>
        <td class="text-center">{{ $loop->iteration }}</td>
        <td>{{ $tt->name }}</td>
        <td class="text-right">{{ number_format($tt->tarif,2,',','.') }}</td>
        <td class="text-center">{{ $tt->jumlah }}</td>
        <td class="text-right">{{ number_format($tt->total,2,',','.') }}</td>
    </tr>
    @endforeach
</table>
@endif

@if($invoice->tambat_kapal == 'Y')
<h4 class="mt-10">Jasa Tambat Kapal</h4>
<table>
    <tr>
        <th>No</th>
        <th>Item</th>
        <th>Tarif</th>
        <th>GT Kapal</th>
        <th>Etmal</th>
        <th>Total</th>
    </tr>
    <tr>
        <td class="text-center">1</td>
        <td>Jasa Tambat Kapal</td>
        <td class="text-right">{{ number_format($tkapal->tarif,2,',','.') }}</td>
        <td class="text-center">{{ $tkapal->gt_kapal }}</td>
        <td class="text-center">{{ $tkapal->etmal }}</td>
        <td class="text-right">{{ number_format($tkapal->total,2,',','.') }}</td>
    </tr>
</table>
@endif

@if($invoice->stevadooring == 'Y')
<h4 class="mt-10">Stevadooring</h4>
<table>
    <tr>
        <th>No</th>
        <th>Container Size</th>
        <th>Status</th>
        <th>Tarif</th>
        <th>Jumlah</th>
        <th>Total</th>
    </tr>
    @foreach($stevadooring as $stv)
    <tr>
        <td class="text-center">{{ $loop->iteration }}</td>
        <td class="text-center">{{ $stv->ctr_size }}</td>
        <td class="text-center">{{ $stv->ctr_status }}</td>
        <td class="text-right">{{ number_format($stv->tarif,2,',','.') }}</td>
        <td class="text-center">{{ $stv->jumlah }}</td>
        <td class="text-right">{{ number_format($stv->total,2,',','.') }}</td>
    </tr>
    @endforeach
</table>
@endif

@if($invoice->shifting == 'Y')
<h4 class="mt-10">Shifting</h4>

<strong>Crane Dermaga</strong>
<table>
    <tr>
        <th>No</th>
        <th>Size</th>
        <th>Status</th>
        <th>Landing</th>
        <th>Tarif</th>
        <th>Jumlah</th>
        <th>Total</th>
    </tr>
    @foreach($crane_dermaga as $cd)
    <tr>
        <td class="text-center">{{ $loop->iteration }}</td>
        <td>{{ $cd->ctr_size }}</td>
        <td>{{ $cd->ctr_status }}</td>
        <td>{{ $cd->landing }}</td>
        <td class="text-right">{{ number_format($cd->tarif,2,',','.') }}</td>
        <td class="text-center">{{ $cd->jumlah }}</td>
        <td class="text-right">{{ number_format($cd->total,2,',','.') }}</td>
    </tr>
    @endforeach
</table>

<strong class="mt-10">Crane Kapal</strong>
<table>
    <tr>
        <th>No</th>
        <th>Size</th>
        <th>Status</th>
        <th>Landing</th>
        <th>Tarif</th>
        <th>Jumlah</th>
        <th>Total</th>
    </tr>
    @foreach($crane_kapal as $ck)
    <tr>
        <td class="text-center">{{ $loop->iteration }}</td>
        <td>{{ $ck->ctr_size }}</td>
        <td>{{ $ck->ctr_status }}</td>
        <td>{{ $ck->landing }}</td>
        <td class="text-right">{{ number_format($ck->tarif,2,',','.') }}</td>
        <td class="text-center">{{ $ck->jumlah }}</td>
        <td class="text-right">{{ number_format($ck->total,2,',','.') }}</td>
    </tr>
    @endforeach
</table>
@endif

<!-- ================= TOTAL ================= -->
<table class="no-border mt-20">
    <tr>
        <td width="70%">
            Admin (NK)<br>
            Discount<br>
            Total Amount<br>
            PPN<br>
            <strong>Grand Total</strong>
        </td>
        <td width="30%" class="text-right">
            Rp {{ number_format($invoice->admin,2,',','.') }}<br>
            Rp {{ number_format($invoice->discount,2,',','.') }}<br>
            Rp {{ number_format($invoice->total + $invoice->admin,2,',','.') }}<br>
            Rp {{ number_format($invoice->pajak,2,',','.') }}<br>
            <strong>Rp {{ number_format($invoice->grand_total,2,',','.') }}</strong>
        </td>
    </tr>
</table>

<p class="mt-10">
    Terbilang: <strong>"{{ $terbilang }} Rupiah"</strong>
</p>

</body>
</html>
