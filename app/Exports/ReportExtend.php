<?php

namespace App\Exports;

use App\Models\Extend;
use App\Models\Item; 
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class ReportExtend implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize
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

        $no = $this->invoices->search($invoice) + 1;

        $containerKeys = json_decode($invoice->container_key, true);

        $containerNos = [];
        foreach ($containerKeys as $key) {
            $keysArray = explode(',', $key); // Pisahkan string menjadi array
            foreach ($keysArray as $key) {
                $containers = Item::where('container_key', $key)->pluck('container_no')->toArray();
                $containerNos = array_merge($containerNos, $containers);
            }
        }
        $containerNoStr = implode(', ', $containerNos);

        return [
            $no,
            $invoice->proforma_no,
            $invoice->os_name,
            $invoice->inv_type,
            $invoice->inv_no,
            $containerNoStr,
            $invoice->order_at,
            $invoice->cust_name,
            $invoice->ctr_20,
            $invoice->ctr_40,
            $invoice->ctr_21,
            $invoice->ctr_42,
            $invoice->m1_20,
            $invoice->m2_20,
            $invoice->m3_20,
            $invoice->m1_21,
            $invoice->m2_21,
            $invoice->m3_21,
            $invoice->m1_40,
            $invoice->m2_40,
            $invoice->m3_40,
            $invoice->m1_42,
            $invoice->m2_42,
            $invoice->m3_42,
            $invoice->total,
            $invoice->pajak,
            $invoice->grand_total,
            $status,
            
        ];
    }

    public function headings(): array
    {
        return [
            'No',
            'Proforma',
            'Order Service',
            'Invoice Type',
            'Invoice No',
            'Container No',
            'Order Date',
            'Customer Name',
            'ctr_20',
            'ctr_40',
            'ctr_21',
            'ctr_42',
            'm1_20',
            'm2_20',
            'm3_20',
            'm1_21',
            'm2_21',
            'm3_21',
            'm1_40',
            'm2_40',
            'm3_40',
            'lolo_full_40',
            'lolo_empty_40',
            'm1_42',
            'm2_42',
            'm3_42',
            'Total Amount',
            'PPN',
            'Grand Total',
            'Status',
        ];
    }
}
