<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Item;
use App\Models\Isocode;
use App\Models\VVoyage;
use Auth;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
class Coparn implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */

    protected $ves_id;
    protected $ves_name;
    protected $voy_no;
    protected $ves_code;

    public function __construct($ves_id, $ves_name, $voy_no, $ves_code)
    {
        $this->ves_id = $ves_id;
        $this->ves_code = $ves_code;
        $this->ves_name = $ves_name;
        $this->voy_no = $voy_no;
    }

    public function collection(Collection $rows)
    {
        // $headers = $rows->first()->keys()->all();
        // dd($headers); 
        $this->ves_id = $this->ves_id;
        $this->ves_code = $this->ves_code;
        $this->ves_name = $this->ves_name;
        $this->voy_no = $this->voy_no;

        foreach ($rows as $row) {
            $ctr_status = trim($row['status_fe']);
            if ($ctr_status == 'F') {
                $ctr_status = 'FCL';
            } elseif ($ctr_status == 'E') {
                $ctr_status = 'MTY';
            }

            $iso_code = trim($row['iso_code']);
            $isoCodeData = Isocode::where('iso_code', $iso_code)->first();
            if ($isoCodeData) {
                $ctr_size = $isoCodeData->iso_size;
                $ctr_type = $isoCodeData->iso_type;
            } else {
                $ctr_size = NULL; // Nilai default jika tidak ditemukan
                $ctr_type = NULL; // Nilai default jika tidak ditemukan
            }

            $containerNo = trim($row['container_no']);
            $isoContainer = $iso_code;
            
                $item = [
                    'ves_id' => $this->ves_code,
                    'ves_code' => $this->voy_no,
                    'ves_name' => $this->ves_id,
                    'voy_no' => $this->ves_name,
                    'disch_port' => trim($row['pod']),
                    'load_port' => trim($row['spod']),
                    'container_no' => trim($row['container_no']),
                    'iso_code' => $iso_code,
                    'ctr_size' => $ctr_size,
                    'ctr_type' => $ctr_type,
                    'ctr_opr' => trim($row['container_operator']),
                    'ctr_status' => $ctr_status,
                    'gross' => trim($row['gross']),
                    'ctr_intern_status' => '49',
                    'ctr_i_e_t' => 'E',
                    'disc_load_trans_shift' => 'LOAD',
                    'user_id' => Auth::user()->name,
                    'ctr_active_yn' => 'Y',
                    'selected_do'=>'N',
                    'booking_no'=>trim($row['booking_no']),
                    'chilled_temp'=>trim($row['temperature']),
                    'customer_code'=>trim($row['customer_code']),


                ];
                dd($item);        
                Item::create($item);
            
        }
    }
}
