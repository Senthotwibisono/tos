<?php

namespace App\Http\Controllers;

use Auth;
use Config\Services;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\VMaster;
use App\Models\VVoyage;
use App\Models\Isocode;



class CoparnsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data = [];
        $client = new Client();


        $data["title"] = "Dashboard | Upload Coparn";
        return view('coparn/dashboard', $data);
    }

    public function create()
    {
        $client = new Client();
        $data = [];

        // $data["vessel"] = $result_vessel->data;
        $vessel_voyage = VVoyage::whereDate('deparature_date', '>=', now())->orderBy('deparature_date', 'desc')->get();
        // dd($vessel_voyage);
        $data["vessel"] = $vessel_voyage;

        $data["title"] = "Upload Coparn Document";
        return view('coparn.create', $data);
    }

    public function singleCreate()
    {
        $client = new Client();
        $data = [];

        // // GET ALL VESSEL
        // $url_vessel = getenv('API_URL') . '/delivery-service/vessel/all';
        // $req_vessel = $client->get($url_vessel);
        // $response_vessel = $req_vessel->getBody()->getContents();
        // $result_vessel = json_decode($response_vessel);
        // // dd($result_vessel);

        // $data["vessel"] = $result_vessel->data;

        $vessel_voyage = VVoyage::whereDate('deparature_date', '>=', now())->orderBy('deparature_date', 'desc')->get();
        // dd($vessel_voyage);
        $data["vessel"] = $vessel_voyage;

        $data["title"] = "Create Single Coparn Document";
        return view('coparn.create_single', $data);
    }

    public function store(Request $request)
    {
        // dd("inputted");
        // $data = [];
        // dd($request->all());
        $client = new Client();

        $vessel = $request->vessel;
        $voyage = $request->voyage;
        $ves_code = $request->vesselcode;
        $closing = $request->closingtime;
        $arrival = $request->arrival;
        $departure = $request->departure;

        $path1 = $request->file('storecoparn')->store('temp');
        $path = storage_path('app') . '/' . $path1;
        $data = Excel::toArray([], $path)[0];

        if (count($data) > 1) {
            $columns = $data[0];
            $rows = array_slice($data, 1);

            $formattedData = [];
            //  column3 = isocode 
            foreach ($rows as $row) {
                $formattedRow = [];
                foreach ($columns as $index => $column) {
                    $formattedRow['column' . ($index + 1)] = $row[$index];
                }
                $formattedRow['column20'] = $voyage;
                $formattedRow['column21'] = $ves_code;
                $formattedRow['column22'] = $arrival;
                $formattedRow['column23'] = $departure;
                $formattedRow['column24'] = $closing;
                $formattedRow['column25'] = $vessel;
                // $formattedRow['column26'] = "";
                $iso_code = Isocode::where('iso_code', '=', $formattedRow['column3'])->get();
                // dd($iso_code[0]);
                $formattedRow['column26'] = $iso_code[0]->iso_size;
                $formattedRow['column27'] = $iso_code[0]->iso_type;
                $formattedRow['column28'] = $iso_code[0]->iso_weight;
                if ($formattedRow['column4'] == "F") {
                    $formattedRow['column29'] = "FCL";
                } else {
                    $formattedRow['column29'] = "MTY";
                }
                $formattedRow['column30'] = "48";
                $formattedData[] = $formattedRow;
            }

            // Wrap the data under a "data" key
            $jsonData = [

                'data' => $formattedData
                // 'vessel_name'
            ];
        } else {
            // If there is no data or only column names in the file
            $jsonData = ['data' => []];
        }

        // dd($jsonData);

        // $fields = [];

        $url = getenv('API_URL') . '/delivery-service/coparn/create/bulk';
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
            return redirect('/coparn')->with('success', 'Form berhasil disimpan!');
        } else {
            return redirect('/coparn')->with('success', 'Data gagal disimpan!');
        }
    }

    public function singlestore(Request $request)
    {
        $client = new Client();
        $data = [];

        $input = $request->post();
        // dd($input);

        $fields = [
            "booking_no" => $input["booking"],
            "container_no" => $input["connumber"],
            "iso_code" => $input["iso"],
            "status_fe" => "",
            "ctr_size" => $input["size"],
            "ctr_type" => $input["type"],
            "status" => $input["status"],
            "spod" => $input["fpod"],
            "pod" => $input["pod"],
            "container_operator" => $input["contoperator"],
            "owner" => $input["owner"],
            "shipper" => "",
            "commoditor" => "",
            "gross" => $input["gross"],
            "imo_code" => $input["imo"],
            "un_number" => $input["unnumber"],
            "over_width" => $input["overw"],
            "over_height" => $input["overh"],
            "over_length" => $input["overl"],
            "seal_no" => "",
            "temperature" => $input["temperature"],
            "temperature_code" => "",
            "voy_no" => $input["voyage"],
            "ves_code" => $input["vesselcode"],
            "closing_date" => $input["closingtime"],
            "arrival_date" => $input["arrival"],
            "departure_date" => $input["departure"],
            "vessel_name" => $input["vessel"],
        ];

        $url = getenv('API_URL') . '/delivery-service/container/create';
        $req = $client->post(
            $url,
            [
                "json" => $fields
            ]
        );
        $response = $req->getBody()->getContents();
        $result = json_decode($response);
        // dd($result);
        if ($req->getStatusCode() == 200 || $req->getStatusCode() == 201) {
            return redirect('/coparn')->with('success', 'Form berhasil disimpan!');
        } else {
            return redirect('/coparn')->with('success', 'Data gagal disimpan!');
        }
    }

    public function findSingleVessel(Request $request)
    {
        $client = new Client();
        $data = [];
        $id = $request->ves_id;
        // var_dump($id);
        // die();
        // GET ALL VESSEL
        // $url_vessel = getenv('API_URL') . '/delivery-service/vessel/single/' . $id;
        // $req_vessel = $client->get($url_vessel);
        // $response_vessel = $req_vessel->getBody()->getContents();
        // $result_vessel = json_decode($response_vessel);
        // dd($result_vessel);
        $response_vessel = VVoyage::where('ves_id', '=', $id)->get();
        // var_dump($confirmed);
        // die();

        echo $response_vessel;
    }
}
