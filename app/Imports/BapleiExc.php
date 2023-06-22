<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Models\Item;
use App\Models\Isocode;
use App\Models\VVoyage;
use Auth;

class BapleiExc implements ToModel, WithStartRow, WithMapping
{
    /**
    * @param Collection $collection
    */
   
    public function startRow(): int
    {
        return 2; // Nomor baris mulai membaca (lewatkan baris judul)
    }

    public function __construct($ves_id, $user_id,
    $ves_name,
    $voy_no,
    $ves_code,
    )
    {
        $this->ves_id = $ves_id;
        $this->ves_name = $ves_name;
        $this->voy_no = $voy_no;
        $this->ves_code = $ves_code;
        $this->user_id = $user_id;
    }


    public function map($row): array
{
    $bay_slot = substr($row[5], 0, 2);
    $bay_row = substr($row[5], 2, 2);
    $bay_tier = substr($row[5], 4, 2);

    $ctr_status = $row[9];
    if ($ctr_status == 'FULL') {
        $ctr_status = 'FCL';
    }
    elseif ($ctr_status == 'Empty') {
        $ctr_status = 'MTY';
    }
   
    $iso_code = $row[7];
   $isoCodeData = Isocode::where('iso_code', $iso_code)->first();
   if ($isoCodeData) {
       $ctr_size = $isoCodeData->iso_size;
       $ctr_type = $isoCodeData->iso_type;
   } else {
       $ctr_size = NULL; // Nilai default jika tidak ditemukan
       $ctr_type = NULL; // Nilai default jika tidak ditemukan
   }
    return [
        'ves_id' => $this->ves_id,
            'ves_code' => $this->ves_code,
            'ves_name' => $this->ves_name,
            'voy_no' => $this->voy_no,
             'disch_port' => $row[1],
             'load_port' => $row[2],
             'bay_slot'  => $bay_slot,
             'bay_row'   => $bay_row,
             'bay_tier'  => $bay_tier,
             'container_no' => $row[6],
             'iso_code' => $iso_code,
             'ctr_size' => $ctr_size,
             'ctr_type' => $ctr_type,
             'ctr_opr' => $row[8],
             'ctr_status' => $ctr_status,
             'gross' => $row[10],
             'ctr_intern_status'=>'01',
             'ctr_i_e_t'=> 'I',                          
             'disc_load_trans_shift'=>'DISC',
             'user_id' => $this->user_id,
    ];
}

    public function model(array $row)
    {     
         return new Item($row);
    }

}
