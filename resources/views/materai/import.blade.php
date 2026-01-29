<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 6px;
        }

        th {
            background: #f2f2f2;
            text-align: center;
        }

        .no-border td {
            border: none;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .mt-10 {
            margin-top: 10px;
        }

        .mt-20 {
            margin-top: 20px;
        }

        hr {
            border: 0;
            border-top: 1px solid #000;
            margin: 10px 0;
        }
    </style>
</head>

<body>

{{-- ================= HEADER ================= --}}
<table class="no-border">
    <tr>
        <td width="60%">
            <h2 style="margin:0;">INVOICE</h2>
            <p style="margin:2px 0;">Proforma No : <strong>{{ $invoice->proforma_no }}</strong></p>
            <p style="margin:2px 0;">Invoice No : <strong>{{ $invoice->inv_no }}</strong></p>
        </td>
        <td width="40%" class="text-right">
            @if($invoice->inv_type === 'DSK')
            <img src="{{ public_path('logoInvoice/logoDSK.jpg') }}" width="120"><br><br>
            @else
            <img src="{{ public_path('logoInvoice/logoDS.jpg') }}" width="120"><br><br>
            @endif
            <img src="{{ public_path('images/paid.png') }}" width="80">
        </td>
    </tr>
</table>

<hr>

{{-- ================= CUSTOMER & ORDER ================= --}}
<table class="no-border mt-10">
    <tr>
        <td width="50%" valign="top">
            <strong>Billed To</strong><br>
            {{ $invoice->customer->name }}<br>
            {{ $invoice->customer->alamat }}<br>
            Fax : {{ $invoice->customer->fax }}
            <br><br>

            <strong>Tipe Invoice</strong><br>
            {{ $invoice->inv_type }}
            <br><br>

            <strong>Order Service</strong><br>
            {{ $invoice->os_name }}
            <br><br>

            <strong>DO Number</strong><br>
            {{ $invoice->Form->doOnline->do_no ?? '-' }}
        </td>

        <td width="50%" valign="top">
            <strong>Order Date</strong><br>
            {{ $invoice->invoice_date }}
            <br><br>

            @if($invoice->inv_type === 'DSK')
            <strong>Metode Pembayaran</strong><br>
            Bank : <strong>MANDIRI</strong><br>
            A/N : <strong>PT. INDO KONTAINER SARANA</strong><br>
            No Rek : <strong>1460002771975</strong>
            <br><br>
            @else
            <strong>Metode Pembayaran</strong><br>
            Bank : <strong>MANDIRI</strong><br>
            A/N : <strong>PT. DEPO INDO KONTAINER SARANA</strong><br>
            No Rek : <strong>1460021308742</strong>
            <br><br>
            @endif

            <strong>Vessel</strong><br>
            Ves Name : {{ $form->Kapal->ves_name ?? '-' }}<br>
            Voy No : {{ $form->Kapal->voy_in ?? '-' }}<br>
            Arrival : {{ $form->Kapal->arrival_date ?? '-' }}<br>
            Departure : {{ $form->Kapal->deparature_date ?? '-' }}
        </td>
    </tr>
</table>

{{-- ================= DETAIL ================= --}}
@foreach ($invGroup as $ukuran => $details)
    <p class="mt-20"><strong>Container {{ $ukuran }}</strong></p>

    <table>
        <thead>
            <tr>
                <th>Keterangan</th>
                <th>Jumlah Container</th>
                <th>Hari</th>
                <th>Tarif Satuan</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($details as $detail)
            <tr>
                <td>{{ $detail->master_item_name }}</td>
                <td class="text-right">{{ $detail->jumlah }}</td>
                <td class="text-right">{{ $detail->jumlah_hari }}</td>
                <td class="text-right">{{ number_format($detail->tarif, 2, ',', '.') }}</td>
                <td class="text-right">{{ number_format($detail->total, 2, ',', '.') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endforeach

{{-- ================= TOTAL ================= --}}
@php
    $tanggalBerlaku = \Carbon\Carbon::create(2025, 6, 1);
    $orderAt = \Carbon\Carbon::parse($invoice->order_at);
    $total = $orderAt->gt($tanggalBerlaku)
        ? $invoice->total
        : $invoice->total + $admin;
@endphp

<table class="no-border mt-20">
    <tr>
        <td width="60%" valign="top">
            <strong>Terbilang :</strong><br>
            "{{ $terbilang }} Rupiah"
        </td>
        <td width="40%">
            <table>
                <tr>
                    <td>Admin</td>
                    <td class="text-right">{{ number_format($admin,2,',','.') }}</td>
                </tr>
                <tr>
                    <td>Total Amount</td>
                    <td class="text-right">{{ number_format($total,2,',','.') }}</td>
                </tr>
                <tr>
                    <td>Discount</td>
                    <td class="text-right">{{ number_format($invoice->discount,2,',','.') }}</td>
                </tr>
                <tr>
                    <td>PPN</td>
                    <td class="text-right">{{ number_format($invoice->pajak,2,',','.') }}</td>
                </tr>
                <tr>
                    <td><strong>Grand Total</strong></td>
                    <td class="text-right">
                        <strong>{{ number_format($invoice->grand_total,2,',','.') }}</strong>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

{{-- ================= NOTE ================= --}}
<p class="mt-20"><strong>Note :</strong></p>
<p>
1. Pembayaran dilakukan secara penuh sesuai nilai invoice.<br>
2. Complain invoice maksimal 3 (tiga) hari setelah invoice diterima.<br>
3. Invoice dianggap lunas jika pembayaran masuk ke rekening tertera.<br>
4. Due date dihitung dari ATD.<br>
5. Tidak menerima pembayaran tunai.
</p>

</body>
</html>
