<?php

namespace App\Exports;

use App\Models\InvoiceImport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class ReportInvoice implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize
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
            case 'C':
                $status = 'Canceled';
                break;
            default:
                $status = 'Unknown';
                break;
        }

        $namaKapal = $invoice->Form->Kapal->ves_name ?? 'N/A';
        $voyKapal = $invoice->Form->Kapal->voy_out ?? 'N/A';
        $kapal = $namaKapal . '/' . $voyKapal;
        $no = $this->invoices->search($invoice) + 1;
        
        return [
            $no,
            $invoice->invoice_date,
            $invoice->proforma_no,
            $invoice->inv_no,
            $invoice->inv_type,
            $invoice->customer->name,
            $kapal,
            $invoice->os_name,
            $invoice->total,
            $invoice->admin,
            $invoice->discount,
            $invoice->pajak,
            $invoice->grand_total,
            $status      
        ];
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Proforma No',
            'Invoice',
            'Tipe',
            'Customer',
            'Vessel',
            'Keterangan',
            'Total',
            'Admin',
            'Discount',
            'PPN',
            'Grand Total',
            'Status'
        ];
    }
}
