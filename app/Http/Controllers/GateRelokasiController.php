<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Item;
use Carbon\Carbon;
use App\Models\User;
use GuzzleHttp\Client;
use Auth;

class GateRelokasiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $title = 'Gate Rlokasi';
        $item = Item::whereIn('order_service', ['sp2iks', 'sp2icon', 'sppsrelokasipelindo'])->whereIn('ctr_intern_status',  ['11', '15'])->get();
        $item_confirmed = Item::whereiN('ctr_intern_status',  ['12', '13'])->get();

        $client = new Client();
        // GET ALL JOB_CONTAINER
        $url_jobContainer = getenv('API_URL') . '/delivery-service/job/osds';
        $req_jobContainer = $client->get($url_jobContainer);
        $response_jobContainer = $req_jobContainer->getBody()->getContents();
        $result_jobContainer = json_decode($response_jobContainer);
        // dd($result_jobContainer);
        // dd($containerKeys);
        $data["jobContainers"] = $result_jobContainer->data;
        return view('gate.relokasi.main', compact('item', 'title', 'item_confirmed'), $data);
    }
    public function android()
    {
        $title = 'Gate Rlokasi';
        $item = Item::whereIn('order_service', ['sp2iks', 'sp2icon', 'sppsrelokasipelindo'])->whereIn('ctr_intern_status',  ['11', '15'])->get();
        $item_confirmed = Item::whereiN('ctr_intern_status',  ['12', '13'])->get();

        $client = new Client();
        // GET ALL JOB_CONTAINER
        $url_jobContainer = getenv('API_URL') . '/delivery-service/job/osds';
        $req_jobContainer = $client->get($url_jobContainer);
        $response_jobContainer = $req_jobContainer->getBody()->getContents();
        $result_jobContainer = json_decode($response_jobContainer);
        // dd($result_jobContainer);
        // dd($containerKeys);
        $data["jobContainers"] = $result_jobContainer->data;
        return view('gate.relokasi.android', compact('item', 'title', 'item_confirmed'), $data);
    }

    public function data_container(Request $request)
    {
        // $container_key = $request->container_key;
        // $name = Item::where('container_key', $container_key)->first();

        // if ($name) {
        //     return response()->json(['container_no' => $name->container_no, 'job' => $name->job_no, 'invoice' => $name->invoice_no]);
        // }
        // return response()->json(['container_no' => 'data tidak ditemukan', 'job' => 'data tidak ditemukan', 'invoice' => 'data tidak ditemukan']);
        $client = new Client();

        $fields = [
            "container_key" => $request->container_key,
        ];
        // dd($fields, $item->getAttributes());

        $url = getenv('API_URL') . '/delivery-service/job/containerbykey';
        $req = $client->post(
            $url,
            [
                "json" => $fields
            ]
        );
        $response = $req->getBody()->getContents();
        $result = json_decode($response);
        // var_dump($response);
        // die();
        // dd($result);
        if ($req->getStatusCode() == 200 || $req->getStatusCode() == 201) {
            // $item->save();

            echo $response;
        } else {
            return response()->json(['service' => 'data tidak ditemukan']);
        }
    }

    public function permit(Request $request)
    {
        $container_key = $request->container_key;
        $item = Item::where('container_key', $container_key)->first();

        if ($item) {
            $service = $request->order_service;
            // SP2 BALIK IKS
            if ($service === 'sp2iks') {
                $item->update([
                    'ctr_intern_status' => 12,
                    'ctr_status' => 'MTY',
                    'ctr_active_yn' => 'Y',
                    'truck_no' => $request->truck_no,
                ]);
                $client = new Client();

                $fields = [
                    "container_key" => $request->container_key,
                    "ctr_intern_status" => "12",
                ];
                // var_dump($fields);
                // die();
                $url = getenv('API_URL') . '/delivery-service/container/confirmGateIn';
                $req = $client->post(
                    $url,
                    [
                        "json" => $fields
                    ]
                );
                $response = $req->getBody()->getContents();
                $result = json_decode($response);
                // var_dump($result);
                // die();
                if ($req->getStatusCode() == 200 || $req->getStatusCode() == 201) {
                    // $item->save();

                    return response()->json([
                        'success' => true,
                        'message' => 'Silahkan Menuju Bagian Stripping',
                        'data'    => $item,
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'updated successfully!',
                    ]);
                }

                // SPPS RELOKASI
            } elseif ($service === 'sppsrelokasipelindo') {
                $item->update([
                    'ctr_intern_status' => 12,
                    'order_service' => $request->order_service,
                    'no_dok' => $request->no_dok,
                    'jenis_dok' => $request->jenis_dok,
                    'truck_no' => $request->truck_no,
                ]);
                $client = new Client();

                $fields = [
                    "container_key" => $request->container_key,
                    "ctr_intern_status" => "12",
                ];
                // dd($fields, $item->getAttributes());

                $url = getenv('API_URL') . '/delivery-service/container/confirmGateIn';
                $req = $client->post(
                    $url,
                    [
                        "json" => $fields
                    ]
                );
                $response = $req->getBody()->getContents();
                $result = json_decode($response);
                // var_dump($result);
                // die();
                if ($req->getStatusCode() == 200 || $req->getStatusCode() == 201) {
                    // $item->save();

                    return response()->json([
                        'success' => true,
                        'message' => 'Silahkan Menuju Bagian Stripping',
                        'data'    => $item,
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'updated successfully!',
                    ]);
                }

                // SP2 RELOKASI
            } elseif ($service === 'sp2icon') {
                $item->update([
                    'ctr_intern_status' => 13,
                    'order_service' => $request->order_service,
                    'no_dok' => $request->no_dok,
                    'jenis_dok' => $request->jenis_dok,
                    'truck_no' => $request->truck_no,
                ]);
                $client = new Client();

                $fields = [
                    "container_key" => $request->container_key,
                    "ctr_intern_status" => "13",
                ];
                // dd($fields, $item->getAttributes());
                // var_dump($fields);die();

                $url = getenv('API_URL') . '/delivery-service/container/confirmGateIn';
                $req = $client->post(
                    $url,
                    [
                        "json" => $fields
                    ]
                );
                $response = $req->getBody()->getContents();
                $result = json_decode($response);
                // var_dump($result);
                // die();
                if ($req->getStatusCode() == 200 || $req->getStatusCode() == 201) {
                    // $item->save();

                    return response()->json([
                        'success' => true,
                        'message' => 'Silahkan Menuju Bagian Placement',
                        'data'    => $item,
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'updated successfully!',
                    ]);
                }
            }
        }
    }
}
