<?php

namespace App\Exports;

use App\Models\ImportDetail;
use App\Models\Item; 
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class ImportZahir implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize
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

        if ($data->count_by == 'T' || $data->count_by == 'H') {
            if ($data->jumlah_hari == 0 || $data->jumlah_hari == null) {
                $item = '0';
            }else {
                $item = $data->jumlah_hari * $data->jumlah;
            }
        }else {
            $item = $data->jumlah;
        }

        

        $keterangan = "By. ". $data->master_item_name. ' ' . $data->jumlah . 'x' . $data->ukuran . '('. $data->customer->name .', PT)';
        $note = $data->Form->Kapal->ves_code . ' V.' . $data->Form->Kapal->voy_in . '/' . $data->Form->Kapal->voy_out . '/' . $data->Form->Kapal->no_ppk;

        $expiredDate = Carbon::parse($data->order_date);
        // Add 30 days to the expired date
        $expiredDate->addDays(30);
        // Format the new date in the desired format (d/m/Y)
        $formattedExpiredDate = $expiredDate->format('d/m/Y');

        return [
            date("d/m/Y", strtotime($data->order_date)),
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
           $data->kode,
           $item,
           $data->satuan,
           $data->tarif,
           '',
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
