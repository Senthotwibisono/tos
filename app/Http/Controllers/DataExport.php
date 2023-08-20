<?php

// File: app/Exports/DataExport.php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DataExport implements FromCollection, WithHeadings
{
  protected $data;
  protected $headings;

  public function __construct(Collection $data, array $headings)
  {
    $this->data = $data;
    $this->headings = $headings;
  }

  public function collection()
  {
    // Return the data collection to the Excel export
    return $this->data;
  }

  public function headings(): array
  {
    // Return the column headings for the Excel sheet
    return $this->headings;
  }
}
