<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportController extends Controller implements FromCollection, WithHeadings
{
  public function export(Request $request)
  {
    // Fetch JSON data from the API
    $response = Http::get(getenv('API_URL') . '/delivery-service/invoice/activeto');
    $jsonData = $response->json()['data'];

    // Manually extract the desired data fields for export
    $exportData = collect($jsonData)->map(function ($item) {
      return [
        'Performa ID' => $item['performaId'],
        'Invoice Number' => $item['invoiceNumber'],
        'Order Service' => $item['orderService'],
        'Job Number' => $item['jobNumber'],
        'Is Paid' => $item['isPaid'],
        'Active To' => $item['active_to'],
        // 'Is Active' => $item['isActive'],
      ];
    });

    // Return the filtered data as a collection to the Excel export
    return Excel::download($exportData, 'data.xlsx');
  }

  // Implement the FromCollection method
  public function collection()
  {
    // Fetch JSON data from the API
    $response = Http::get(getenv('API_URL') . '/delivery-service/invoice/activeto');
    $jsonData = $response->json()['data'];

    // Manually extract the desired data fields for export
    $exportData = collect($jsonData)->map(function ($item) {
      return [
        'Performa ID' => $item['performaId'],
        'Invoice Number' => $item['invoiceNumber'],
        'Order Service' => $item['orderService'],
        'Job Number' => $item['jobNumber'],
        'Is Paid' => $item['isPaid'],
        'Active To' => $item['active_to'],
        // 'Is Active' => $item['isActive'],
      ];
    });

    // Return the filtered data as a collection to the Excel export
    return $exportData;
  }

  // Implement the WithHeadings method
  public function headings(): array
  {
    // Define the column headings for the Excel sheet
    return [
      'Performa ID',
      'Invoice Number',
      'Order Service',
      'Job Number',
      'Is Paid',
      'Active To',
      // 'Is Active',
    ];
  }
}
