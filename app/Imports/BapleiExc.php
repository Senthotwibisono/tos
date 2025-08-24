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
use App\Models\Ship;
use Auth;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BapleiExc implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */

    protected $ves_id;
    protected $ves_name;
    protected $voy_no;
    protected $ves_code;
    protected $user_id;

    public function __construct($ves_id, $user_id, $ves_name, $voy_no, $ves_code)
    {
        $this->ves_id = $ves_id;
        $this->user_id = $user_id;
        $this->ves_name = $ves_name;
        $this->voy_no = $voy_no;
        $this->ves_code = $ves_code;
    }

    public function collection(Collection $rows)
    {
        // dd($rows->first());
        $this->ves_id = $this->ves_id;
        $this->ves_name = $this->ves_name;
        $this->voy_no = $this->voy_no;
        $this->ves_code = $this->ves_code;
        $this->user_id = $this->user_id;

        foreach ($rows as $row) {
            $bay_slot = substr(trim($row['stowage']), 0, 2);
            $bay_row = substr(trim($row['stowage']), 2, 2);
            $bay_tier = substr(trim($row['stowage']), 4, 2);

            $ctr_status = trim($row['full_empty_fm']);
            if ($ctr_status == 'FULL') {
                $ctr_status = 'FCL';
            } elseif ($ctr_status == 'Empty') {
                $ctr_status = 'MTY';
            }

            $iso_code = trim($row['iso_code']);
            $isoCodeData = Isocode::where('iso_code', $iso_code)->first();
            if (!$isoCodeData) {
                return back()->with('error', 'ISO code not found: ' . $iso_code);
            }
            if ($isoCodeData) {
                $ctr_size = $isoCodeData->iso_size;
                $ctr_type = $isoCodeData->iso_type;
            } else {
                $ctr_size = NULL; // Nilai default jika tidak ditemukan
                $ctr_type = NULL; // Nilai default jika tidak ditemukan
            }

            $containerNo = trim($row['container_no']);
            $isoContainer = $iso_code;
            $item = Item::where('container_no', $containerNo)->where('iso_code', $isoContainer)->where('ves_id',  $this->ves_id)->first();

            if ($item) {
               if ($item->ctr_intern_status =='01') {
                $item->update([
                    'ves_id' => $this->ves_id,
                    'ves_code' => $this->ves_code,
                    'ves_name' => $this->ves_name,
                    'voy_no' => $this->voy_no,
                    'disch_port' => trim($row['pod']),
                    'load_port' => trim($row['pol']),
                    'bay_slot'  => $bay_slot,
                    'bay_row'   => $bay_row,
                    'bay_tier'  => $bay_tier,
                    'gross' => trim($row['weightton']),
                    'chilled_temp' => trim($row['temperature_celcius']),
                    'ctr_intern_status' => '01',
                    'ctr_i_e_t' => 'I',
                    'disc_load_trans_shift' => 'DISC',
                    'user_id' => $this->user_id,
                    'ctr_active_yn' => 'Y',
                    'selected_do'=>'N',
                ]);
               }
               $ship = Ship::where('ves_id', $this->ves_id)->where('bay_slot', $bay_slot)->where('bay_row', $bay_row)->where('bay_tier', $bay_tier)->first();
               if ($ship) {
                $ship->update([
                    'container_no'=>$item->container_no,
                    'container_key'=>$item->container_key,
                    'ctr_size'=>$item->ctr_size,
                    'ctr_type'=>$item->ctr_type,
                    'dangerous_yn'=>$item->dangerous_yn,
                    'ctr_i_e_t'=> "I",
                ]);
               }
            }else {
                $item = [
                    'ves_id' => $this->ves_id,
                    'ves_code' => $this->ves_code,
                    'ves_name' => $this->ves_name,
                    'voy_no' => $this->voy_no,
                    'disch_port' => trim($row['pod']),
                    'load_port' => trim($row['pol']),
                    'bay_slot'  => $bay_slot,
                    'bay_row'   => $bay_row,
                    'bay_tier'  => $bay_tier,
                    'container_no' => trim($row['container_no']),
                    'iso_code' => $iso_code,
                    'ctr_size' => $ctr_size,
                    'ctr_type' => $ctr_type,
                    'ctr_opr' => trim($row['operator']),
                    'ctr_status' => $ctr_status,
                    'gross' => trim($row['weightton']),
                    'chilled_temp' => trim($row['temperature_celcius']),
                    'ctr_intern_status' => '01',
                    'ctr_i_e_t' => 'I',
                    'disc_load_trans_shift' => 'DISC',
                    'user_id' => $this->user_id,
                    'ctr_active_yn' => 'Y',
                    'selected_do'=>'N',
                    'relokasi_flag' => trim($row['relokasi_flag']),

                ];
    
                $item = Item::create($item);

                $ship = Ship::where('ves_id', $this->ves_id)->where('bay_slot', $bay_slot)->where('bay_row', $bay_row)->where('bay_tier', $bay_tier)->first();
                if ($ship) {
                 $ship->update([
                     'container_no'=>$item->container_no,
                     'container_key'=>$item->container_key,
                     'ctr_size'=>$item->ctr_size,
                     'ctr_type'=>$item->ctr_type,
                     'dangerous_yn'=>$item->dangerous_yn,
                     'ctr_i_e_t'=> "I",
                 ]);
                }
            }
           
   
        }
    }

}
