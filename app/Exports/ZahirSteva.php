<?php

namespace App\Exports;

use App\Models\ImportDetail;
use App\Models\Item; 
use App\Models\TKapalDetail; 
use App\Models\TTongkakDetail; 
use App\Models\StevadooringDetail; 
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class ZahirSteva implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize
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

        $result = [];

        if ($data->tambat_tongkak == 'Y') {
            $tt = TTongkakDetail::where('inv_id', $data->id)->get();
            $jumlahTT = $tt->sum('jumlah');
            $result[] = [
                date("d/m/Y", strtotime($data->created_at)),
                $data->invoice_no,
                $data->cust_name,
                'Head Quarter',
                'Head Quarter',
                '0',
                'Tambat Tongkak ' .$data->ves_name . '-' . $data->voy_out,
                '0',
                $status,
                '0',
                '0',
                '0',
                'TAMBAT',
                1,
                'LITER',
                $data->tambat_tongkak_total,
                ceil($data->discount),
                'VAT',
                '0',
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
    
        if ($data->tambat_kapal == 'Y') {
            $tk = TKapalDetail::where('inv_id', $data->id)->get();
            $jumlahTK = $tk->sum('gt_kapal');
            $result[] = [
                date("d/m/Y", strtotime($data->created_at)),
                $data->invoice_no,
                $data->cust_name,
                'Head Quarter',
                'Head Quarter',
                '0',
                'Tambat Kapal ' .$data->ves_name . '-' . $data->voy_out,
                '0',
                $status,
                '0',
                '0',
                '0',
                'TAMBAT',
                1,
                'LITER',
                $data->tambat_kapal_total,
                ceil($data->discount),
                'VAT',
                '0',
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

        if ($data->stevadooring == 'Y') {
            $stev = StevadooringDetail::where('inv_id', $data->id)->get();
            $jumlahST = $stev->sum('jumlah');  // Sum all 'jumlah' values in the collection
            $result[] = [
                date("d/m/Y", strtotime($data->created_at)),
                $data->invoice_no,
                $data->cust_name,
                'Head Quarter',
                'Head Quarter',
                '0',
                'STEVADOORING ' . $data->ves_name . '-' . $data->voy_out,
                '0',
                $status,
                '0',
                '0',
                '0',
                'STEVADOORING',
                1,
                'LITER',
                $data->stevadooring_total,
                ceil($data->discount),
                'VAT',
                '0',
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
    
        return $result;


       
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
