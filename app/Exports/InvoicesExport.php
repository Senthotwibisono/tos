<?php

namespace App\Exports;

use App\Models\InvoiceImport;
use App\Models\Item; 
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class InvoicesExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $invoices;

    public function __construct($invoices)
    {
        $this->invoices = $invoices;
    }

    public function collection()
    {
        return $this->invoices;
    }

    public function map($invoice): array
    {
        $status = '';
        switch ($invoice->lunas) {
            case 'Y':
                $status = 'Lunas';
                break;
            case 'N':
                $status = 'Belum Bayar';
                break;
            case 'P':
                $status = 'Piutang';
                break;
            default:
                $status = 'Unknown';
                break;
        }

        $namaKapal = $invoice->Form->Kapal->ves_name ?? 'N/A';
        $voyKapal = $invoice->Form->Kapal->voy_out ?? 'N/A';
        $kapal = $namaKapal . '/' . $voyKapal;
        $no = $this->invoices->search($invoice) + 1;
        if ($invoice->jumlah_hari != 0) {
            $item = $invoice->jumlah_hari;
        }else {
            $item = $invoice->jumlah;
        }

        $pajak = ($invoice->total * 11) /100;
        $grand = $pajak + $invoice->total;
        return [
            $no,
            $invoice->order_date,
            $invoice->inv_no,
            $invoice->cust_name,
            $kapal,
            $invoice->master_item_name,
            $invoice->service->order,
            $invoice->kode,
            $invoice->tarif,
            $item,
            $invoice->total,
            '0',
            $pajak,
            $grand,         
        ];
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Invoice',
            'Customer',
            'Vessel',
            'Keterangan',
            'Kode',
            'Item',
            'Harga',
            'QTY',
            'Item Total',
            'Discount',
            'PPN',
            'Total',
        ];
    }
}
