<?php

namespace App\Exports;

use App\Models\InvoiceHeaderStevadooring as Header;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class InvoiceStevadoring implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize
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

        $type = '';
        switch ($invoice->rbm->tipe) {
            case 'I':
                $type = 'Import';
                break;
            case 'E':
                $type = 'Export';
                break;
            
            default:
                $type = ' ';
                break;
        }

        $no = $this->invoices->search($invoice) + 1;
        return [
            $no,
            $invoice->proforma_no,
            $invoice->invoice_no,
            $invoice->cust_id,
            $invoice->cust_name ?? '-',
            $invoice->fax,
            $invoice->npwp,
            $invoice->alamat,
            $invoice->rbm_id,
            $invoice->ves_id,
            $invoice->ves_code,
            $invoice->voy_in,
            $invoice->voy_out,
            $invoice->ves_name,
            $invoice->arrival_date,
            $invoice->deparature_date,
            $invoice->open_stack_date,
            $invoice->clossing_date,
            $type,
            $invoice->tambat_tongkak,
            $invoice->tambat_kapal,
            $invoice->stevadooring,
            $invoice->shifting,
            $invoice->tambat_tongkak_total,
            $invoice->tambat_kapal_total,
            $invoice->stevadooring_total,
            $invoice->shifting_total,
            $invoice->total,
            $invoice->pajak,
            $invoice->admin,
            $invoice->grand_total,
            $invoice->created_by,
            $invoice->created_at,
            $invoice->last_update_at,
            $invoice->last_update_by,
            $invoice->lunas_at,
            $invoice->piutang_at,
            $invoice->lunas,
            $status,
            
        ];
    }

    public function headings(): array
    {
        return [
            'no',
            'proforma_no',
            'invoice_no',
            'cust_id',
            'cust_name',
            'fax',
            'npwp',
            'alamat',
            'rbm_id',
            'ves_id',
            'ves_code',
            'voy_in',
            'voy_out',
            'ves_name',
            'arrival_date',
            'deparature_date',
            'open_stack_date',
            'clossing_date',
            'Tipe Invoice',
            'tambat_tongkak',
            'tambat_kapal',
            'stevadooring',
            'shifting',
            'tambat_tongkak_total',
            'tambat_kapal_total',
            'stevadooring_total',
            'shifting_total',
            'total',
            'pajak',
            'admin',
            'grand_total',
            'created_by',
            'created_at',
            'last_update_at',
            'last_update_by',
            'lunas_at',
            'piutang_at',
            'lunas',
            'status',
        ];
    }
}
