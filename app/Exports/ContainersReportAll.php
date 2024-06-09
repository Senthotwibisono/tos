<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ContainersReportAll implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $filters;
 

    public function __construct(array $filters)
    {
        $this->filters = $filters;
        
    }

    public function view(): View
    {
        $query = Item::query();

        if (!empty($this->filters['ves_id'])) {
            $query->where('ves_id', $this->filters['ves_id']);
        }
        if (!empty($this->filters['yard_block'])) {
            $query->where('yard_block', $this->filters['yard_block']);
        }
        if (!empty($this->filters['ctr_i_e_t'])) {
            $query->where('ctr_i_e_t', $this->filters['ctr_i_e_t']);
        }
        if (!empty($this->filters['ctr_active_yn'])) {
            $query->where('ctr_active_yn', $this->filters['ctr_active_yn']);
        }
        if (!empty($this->filters['is_damage'])) {
            $query->where('is_damage', $this->filters['is_damage']);
        }
        if (!empty($this->filters['ctr_size'])) {
            $query->where('ctr_size', $this->filters['ctr_size']);
        }
        if (!empty($this->filters['ctr_status'])) {
            $query->where('ctr_status', $this->filters['ctr_status']);
        }
        if (!empty($this->filters['ctr_intern_status'])) {
            $query->where('ctr_intern_status', $this->filters['ctr_intern_status']);
        }
        if (!empty($this->filters['ctr_opr'])) {
            $query->where('ctr_opr', 'like', '%' . $this->filters['ctr_opr']);
        }
        if (!empty($this->filters['ctr_type'])) {
            $query->where('ctr_type', 'like', '%' . $this->filters['ctr_type']);
        }

        $containers = $query->get();
        return view('container.detail.table', ['containers' => $containers]);
    }
}
