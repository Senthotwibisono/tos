<?php

namespace App\Exports;

use App\Models\InvoiceExport;
use App\Models\Item; 
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class ZahirOthers implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }

    public function map($data): array
    {
        $status = '';
        switch ($data->lunas) {
            case 'Y':
                $status = 'T';
                break;
            case 'N':
                $status = 'F';
                break;
            case 'P':
                $status = 'F';
                break;
            default:
                $status = 'Unknown';
                break;
        }


        

        $keterangan = "By. ". $data->master_item_name. ' ' . $data->Form->palka . 'x' . $data->ukuran . '('. $data->customer->name .', PT)';
        $note = $data->Form->Kapal->ves_code . ' V.' . $data->Form->Kapal->voy_in . '/' . $data->Form->Kapal->voy_out . '/' . $data->Form->Kapal->no_ppk;

        $expiredDate = Carbon::parse($data->order_date);
        // Add 30 days to the expired date
        $expiredDate->addDays(30);
        // Format the new date in the desired format (d/m/Y)
        $formattedExpiredDate = $expiredDate->format('d/m/Y');

        switch ($data->os_id) {
            case 32:
                $kode = 'TAGICON20';
                break;
            case  36:
                $kode = 'PALKA';
                break;
            case 38:
                $kode = 'REPAIR';
                break;
            case 42:
                $kode = 'Biaya Penggunaan Peralatan';
                break;
            case 43:
                $kode = 'RELOKASI';
                break;
            
            default:
                $kode = null;
                break;
        }

        return [
            date("d/m/Y", strtotime($data->invoice_date)),
           $data->inv_no,
           $data->customer->mapping_zahir,
           'Head Quarter',
           'Head Quarter',
           '',
           $keterangan,
           '',
           '',
           '',
           '',
           '',
           $kode,
           $data->Form->palka,
           'unit',
           $data->total,
           ceil($data->Form->discount_ds ?? $data->Form->discount_dsk ?? 0),
           'VAT',
           $formattedExpiredDate,
           '',
           'Head Quarter',
           '0',
           $note,
           '',
           'IDR',
           '1',
           '',
           '',

            
        ];
    }

    public function headings(): array
    {
        return [
            'TGL TRANS', 'NO. REFERENSI/INV', 'NAMA PELANGGAN', 'NAMA GUDANG', 'NAMA DEPT', 'ID JOB', 'KETERANGAN',
                'NAMA SALESMAN', 'ISTUNAI', 'BIAYALAIN', 'DISKONFINAL', 'UANGMUKA', 'PLU/KODE BARANG', 'QTY', 'SATUAN', 
                'HARGA', 'DISKON (%)', 'KODE PAJAK', 'TGL JATUH TEMPO', 'AKUN BANK', 'NAMA DEPT DETAIL', 'IDJ JOB DETAIL',
                'NOTE DETA', 'NO DOKUMEN', 'MATA UANG', 'NILAI TUKAR', 'NOMOR SERI', 'NOMOR SO',
        ];
    }
}
