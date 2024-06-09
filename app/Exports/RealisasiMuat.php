<?php

namespace App\Exports;

use App\Models\Item;
use App\Models\VVoyage;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class RealisasiMuat implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function view(): View
    {
        $query = Item::where('ves_id', $this->id)->where('ctr_i_e_t', '=', 'E');

        $kapal = VVoyage::where('ves_id', $this->id)->first();
        $containers = $query->get();
        return view('realisasiMuat.table', ['containers' => $containers], ['kapal' => $kapal]);
    }
}
