<?php

namespace App\Exports;

// use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class laporanGate implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $items;

    public function __construct($items)
    {
        $this->items = $items;
    }

    public function collection()
    {
        return $this->items;
    }

    public function map($item): array
    {
        $no = $this->items->search($item) + 1;
        return [
            $no,
            $item->container_no,
            $item->ctr_i_e_t,
            $item->ves_name,
            $item->voy_no,
            $item->truck_in_date,
            $item->truck_out_date,
            $item->stid,
        ];
    }

    public function headings(): array
    {
        return [
            'No',
            'Container No',
            'Import/Export',
            'Ex Kapal',
            'Voy',
            'Truck In Date',
            'Truck Out Date',
            'STID',
        ];
    }
}
