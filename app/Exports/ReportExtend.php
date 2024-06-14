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
            $status
            
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
            'Status'
        ];
    }
}
