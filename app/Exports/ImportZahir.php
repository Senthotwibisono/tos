<?php

namespace App\Exports;

use App\Models\ImportDetail;
use App\Models\Item; 
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


        return [
           $data->order_date,
           $data->inv_no,
           $data->cust_id,
           'Head Quarter',
           'Head Quarter',
           '0',
           $data->keterangan,
           '0',
           $status,
           '0',
           '0',
           '0',
           $data->detail,
           $data->jumlah,
           $data->satuan,
           $data->harga,
           '0',
           'VAT',
           $data->expired_date,
           '0',
           'Head Quarter',
           '0',
           '0',
           '0',
           'IDR',
           '1',
           '0',
           '0'

            
        ];
    }

    public function headings(): array
    {
        return [
            'TGL TRANS', ' NO. REFERENSI/INV', 'NAMA PELANGGAN', 'NAMA GUDANG', 'NAMA DEPT', 'ID JOB', 'KETERANGAN',
                'NAMA SALESMAN', 'ISTUNAI', 'BIAYALAIN', 'DISKONFINAL', 'UANGMUKA', 'PLU/KODE BARANG', 'QTY', 'SATUAN', 
                'HARGA', 'DISKON (%)', 'KODE PAJAK', 'TGL JATUH TEMPO', 'AKUN BANK', 'NAMA DEPT DETAIL', 'IDJ JOB DETAIL',
                'NOTE DETA', 'NO DOKUMEN', 'MATA UANG', 'NILAI TUKAR', 'NOMOR SERI', 'NOMOR SO'
        ];
    }


}
