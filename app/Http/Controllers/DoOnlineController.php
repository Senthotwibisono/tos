<?php

namespace App\Http\Controllers;

use Auth;
use Config\Services;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

use App\Exports\DataTableExport;


class DoOnlineController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index()
  {
    $client = new Client();
    $data = [];


    // GET ALL DO ONLINE
    // $url_invoice = getenv('API_URL') . '/customer-service/customerAll';
    $url_do = getenv('API_URL') . '/delivery-service/do/all';
    $req_do = $client->get($url_do);
    $response_do = $req_do->getBody()->getContents();
    $result_do = json_decode($response_do);
    // dd($result_do);

    $data["do"] = $result_do->data;
    $data["title"] = "DO Online Check";
    return view('do.dashboard', $data);
  }

  public function create()
  {
    $client = new Client();
    $data = [];

    $data["title"] = "DO Online Check";
    return view('do.create', $data);
  }

  public function store(Request $request)
  {
    // dd("inputted");
    // $data = [];
    // dd($request->all());
    $client = new Client();

    $path1 = $request->file('storedo')->store('temp');
    $path = storage_path('app') . '/' . $path1;
    $data = Excel::toArray([], $path)[0];

    if (count($data) > 1) {
      $columns = $data[0];
      $rows = array_slice($data, 1);

      $formattedData = [];

      foreach ($rows as $row) {
        $formattedRow = [];
        foreach ($columns as $index => $column) {
          $formattedRow['column' . ($index + 1)] = $row[$index];
        }
        $formattedData[] = $formattedRow;
      }

      // Wrap the data under a "data" key
      $jsonData = ['data' => $formattedData];
    } else {
      // If there is no data or only column names in the file
      $jsonData = ['data' => []];
    }

    // dd($jsonData);

    // $fields = [];

    $url = getenv('API_URL') . '/delivery-service/do/create/bulk';
    $req = $client->post(
      $url,
      [
        "json" => $jsonData
      ]
    );
    $response = $req->getBody()->getContents();
    $result = json_decode($response);
    // dd($result);
    if ($req->getStatusCode() == 200 || $req->getStatusCode() == 201) {
      return redirect('/do')->with('success', 'Form berhasil disimpan!');
    } else {
      return redirect('/do')->with('success', 'Data gagal disimpan!');
    }
  }
}
