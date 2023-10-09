<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Yard;
use App\Models\RO_Gate;
use App\Models\RO_Realisasi;
use App\Models\RO;
use App\Models\VVoyage;
use App\Models\MasterAlat;
use App\Models\ActAlat;
use Auth;
use Illuminate\Http\Request;
use GuzzleHttp\Client;


class Stuffing extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $title = 'Stuffing';
        $confirmed = Item::where('ctr_intern_status', '=', 53,)->orderBy('update_time', 'desc')->get();
        $formattedData = [];
        $data = [];

        foreach ($confirmed as $tem) {
            $now = Carbon::now();
            $updatedAt = Carbon::parse($tem->update_time);

            // Perhitungan selisih waktu
            $diff = $updatedAt->diffForHumans($now);

            // Jika selisih waktu kurang dari 1 hari, maka tampilkan format jam
            if ($updatedAt->diffInDays($now) < 1) {
                $diff = $updatedAt->diffForHumans($now, true);
                $diff = str_replace(['hours', 'hour', 'minutes', 'minutes', 'seconds', 'seconds'], ['jam', 'jam', 'menit', 'menit', 'detik', 'detik'], $diff);
            } else {
                // Jika selisih waktu lebih dari 1 hari, maka tampilkan format hari dan jam
                $diff = $updatedAt->diffForHumans($now, true);
                $diff = str_replace(['days', 'day', 'hours', 'hour', 'minutes', 'minutes', 'seconds', 'seconds'], ['hari', 'hari', 'jam', 'jam', 'menit', 'menit', 'detik', 'detik'], $diff);
            }

            $formattedData[] = [
                'container_no' => $tem->container_no,
                'ctr_type' => $tem->ctr_type,
                'yard_block' => $tem->yard_block,
                'yard_slot' => $tem->yard_slot,
                'yard_row' => $tem->yard_row,
                'yard_tier' => $tem->yard_tier,
                'update_time' => $diff . ' yang lalu',
                'container_key' => $tem->container_key,
                'ro_no' => $tem->ro_no
            ];
        }


        $containerKeys = Item::where('ctr_intern_status', '=', '04')
            ->pluck('container_no', 'container_key');
        $items = Item::where('ctr_intern_status', 04)->get();

        $users = User::all();
        $yard_block = Yard::distinct('yard_block')->pluck('yard_block');
        $yard_slot = Yard::distinct('yard_slot')->pluck('yard_slot');
        $yard_row = Yard::distinct('yard_row')->pluck('yard_row');
        $yard_tier = Yard::distinct('yard_tier')->pluck('yard_tier');
        $currentDateTime = Carbon::now();
        $currentDateTimeString = $currentDateTime->format('Y-m-d H:i:s');
        $now = Carbon::now();

        $ro_stuffing_dalam = RO::where('stuffing_service', '=', 'in')->pluck('ro_no')->toArray();
        $ro_gate_dalam = RO_Gate::whereIn('ro_no', $ro_stuffing_dalam)->whereNotIn('status', ['3', '9'])->orderBy('status', 'asc')->get();
        $container_truck = RO_Realisasi::whereIn('truck_id', $ro_gate_dalam->pluck('ro_id_gati')->toArray())
            ->groupBy('truck_id')
            ->selectRaw('truck_id, count(distinct container_no) as container_count')
            ->pluck('container_count', 'truck_id');

        $ro_stuffing_luar = RO::where('stuffing_service', '=', 'out')->pluck('ro_no')->toArray();
        $ro_gate_luar = RO_Gate::whereIn('ro_no', $ro_stuffing_luar)->whereNotIn('status', ['3', '9'])->orderBy('status', 'asc')->get();
        $container_truck_luar = RO_Realisasi::whereIn('truck_id', $ro_gate_luar->pluck('ro_id_gati')->toArray())
            ->groupBy('truck_id')
            ->selectRaw('truck_id, count(distinct container_no) as container_count')
            ->pluck('container_count', 'truck_id');
        $vessel = VVoyage::where('deparature_date', '>=', $now)->get();
        $alat = MasterAlat::where('category', '=', 'Yard')->get();
        return view('stuffing.main', $data, compact('confirmed', 'formattedData', 'title', 'items', 'users', 'currentDateTimeString', 'yard_block', 'yard_slot', 'yard_row', 'yard_tier', 'containerKeys', 'ro_gate_dalam', 'ro_stuffing_dalam', 'vessel', 'container_truck', 'ro_gate_luar', 'ro_stuffing_luar', 'container_truck_luar', 'alat'), $data);
    }

    public function android()
    {
        $title = 'Stuffing';
        $confirmed = Item::where('ctr_intern_status', '=', 53,)->orderBy('update_time', 'desc')->get();
        $formattedData = [];
        $data = [];

        foreach ($confirmed as $tem) {
            $now = Carbon::now();
            $updatedAt = Carbon::parse($tem->update_time);

            // Perhitungan selisih waktu
            $diff = $updatedAt->diffForHumans($now);

            // Jika selisih waktu kurang dari 1 hari, maka tampilkan format jam
            if ($updatedAt->diffInDays($now) < 1) {
                $diff = $updatedAt->diffForHumans($now, true);
                $diff = str_replace(['hours', 'hour', 'minutes', 'minutes', 'seconds', 'seconds'], ['jam', 'jam', 'menit', 'menit', 'detik', 'detik'], $diff);
            } else {
                // Jika selisih waktu lebih dari 1 hari, maka tampilkan format hari dan jam
                $diff = $updatedAt->diffForHumans($now, true);
                $diff = str_replace(['days', 'day', 'hours', 'hour', 'minutes', 'minutes', 'seconds', 'seconds'], ['hari', 'hari', 'jam', 'jam', 'menit', 'menit', 'detik', 'detik'], $diff);
            }

            $formattedData[] = [
                'container_no' => $tem->container_no,
                'ctr_type' => $tem->ctr_type,
                'yard_block' => $tem->yard_block,
                'yard_slot' => $tem->yard_slot,
                'yard_row' => $tem->yard_row,
                'yard_tier' => $tem->yard_tier,
                'update_time' => $diff . ' yang lalu',
                'container_key' => $tem->container_key,
                'ro_no' => $tem->ro_no
            ];
        }


        $containerKeys = Item::where('ctr_intern_status', '=', '04')
            ->pluck('container_no', 'container_key');
        $items = Item::where('ctr_intern_status', 04)->get();

        $users = User::all();
        $yard_block = Yard::distinct('yard_block')->pluck('yard_block');
        $yard_slot = Yard::distinct('yard_slot')->pluck('yard_slot');
        $yard_row = Yard::distinct('yard_row')->pluck('yard_row');
        $yard_tier = Yard::distinct('yard_tier')->pluck('yard_tier');
        $currentDateTime = Carbon::now();
        $currentDateTimeString = $currentDateTime->format('Y-m-d H:i:s');
        $now = Carbon::now();

        $ro_stuffing_dalam = RO::where('stuffing_service', '=', 'in')->pluck('ro_no')->toArray();
        $ro_gate_dalam = RO_Gate::whereIn('ro_no', $ro_stuffing_dalam)->whereNotIn('status', ['3', '9'])->orderBy('status', 'asc')->get();
        $container_truck = RO_Realisasi::whereIn('truck_id', $ro_gate_dalam->pluck('ro_id_gati')->toArray())
            ->groupBy('truck_id')
            ->selectRaw('truck_id, count(distinct container_no) as container_count')
            ->pluck('container_count', 'truck_id');

        $ro_stuffing_luar = RO::where('stuffing_service', '=', 'out')->pluck('ro_no')->toArray();
        $ro_gate_luar = RO_Gate::whereIn('ro_no', $ro_stuffing_luar)->whereNotIn('status', ['3', '9'])->orderBy('status', 'asc')->get();
        $container_truck_luar = RO_Realisasi::whereIn('truck_id', $ro_gate_luar->pluck('ro_id_gati')->toArray())
            ->groupBy('truck_id')
            ->selectRaw('truck_id, count(distinct container_no) as container_count')
            ->pluck('container_count', 'truck_id');
        $vessel = VVoyage::where('deparature_date', '>=', $now)->get();
        $alat = MasterAlat::where('category', '=', 'Yard')->get();
        return view('stuffing.android', $data, compact('confirmed', 'formattedData', 'title', 'items', 'users', 'currentDateTimeString', 'yard_block', 'yard_slot', 'yard_row', 'yard_tier', 'containerKeys', 'ro_gate_dalam', 'ro_stuffing_dalam', 'vessel', 'container_truck', 'ro_gate_luar', 'ro_stuffing_luar', 'container_truck_luar', 'alat'), $data);
    }

    public function get_stuffing(Request $request)
    {
        // $client = new Client();
        $container_key = $request->container_key;
        $name = Item::where('container_key', $container_key)->first();
        if ($name) {
            return response()->json(['container_key' => $name->container_key, 'container_no' => $name->container_no, 'tipe' => $name->ctr_type, 'oldblock' => $name->yard_block, 'oldslot' => $name->yard_slot, 'oldrow' => $name->yard_row, 'oldtier' => $name->yard_tier]);
        }
        return response()->json(['container_no' => 'data tidak ditemukan', 'tipe' => 'data tidak ditemukan', 'coname' => 'data tidak ditemukan', 'oldblock' => 'data tidak ditemukan', 'oldslot' => 'data tidak ditemukan', 'oldrow' => 'data tidak ditemukan', 'oldtier' => 'data tidak ditemukan']);
    }

    public function get_vessel(Request $request)
    {
        // $client = new Client();
        $vessel = $request->ves_id;
        $kapal = VVoyage::where('ves_id', $vessel)->first();
        if ($kapal) {
            return response()->json(['ves_name' => $kapal->ves_name, 'ves_code' => $kapal->ves_code, 'voy_no' => $kapal->voy_out]);
        }
        return response()->json(['ves_name' => 'data tidak ditemukan', 'ves_code' => 'data tidak ditemukan', 'voy_no' => 'data tidak ditemukan']);
    }

    // public function get_truck(Request $request)
    // {
    //     $truck = $request->truck_no;
    //     $ro_gate = RO_Gate::where('truck_no', $ro_gate)->first();

    //     if ($ro_gate) {
    //         return response()->json(['truck_in_date' => $kapal->truck_in_date,]);
    //     }

    // }
    public function stuffing_place(Request $request)
    {

        $ro_no = $request->ro_no;
        $ro = RO::where('ro_no', $ro_no)->first();
        if ($ro->stuffing_service == "in") {
            $container_key = $request->container_key;
            $item = Item::where('container_key', $container_key)->first();
            $request->validate([
                'container_no' => 'required',
                'yard_block'  => 'required',
                'yard_slot'  => 'required',
                'yard_row'  => 'required',
                'yard_tier' => 'required',
                'truck_no' => 'required',
                'id_alat' => 'required',

            ], [
                'container_no.required' => 'Container Number is required.',
                'yard_block.required' => 'Block is required.',
                'yard_slot.required' => 'Slot Alat Number is required.',
                'yard_row.required' => 'Row Alat Number is required.',
                'yard_tier.required' => 'Tier Alat Number is required.',
                'trucK_no.required' => 'Nomor Truck is required.',
            ]);


            $yard_rowtier = Yard::where('yard_block', $request->yard_block)
                ->where('yard_slot', $request->yard_slot)
                ->where('yard_row', $request->yard_row)
                ->where('yard_tier', $request->yard_tier)
                ->first();

            if (is_null($yard_rowtier->container_key)) {
                $now = Carbon::now();


                $cont_check = $ro->jmlh_cont;
                $cont =  RO_Realisasi::where('ro_no', $ro_no)->count();


                if ($cont_check > $cont) {
                    $id_alat = $request->alat;
                    $alat = MasterAlat::where('id', $id_alat)->first();

                    $truck = $request->ro_id_gati;
                    $ro_gate = RO_Gate::where('ro_id_gati', $truck)->first();

                    $ro_gate->update([
                        'status' => '2',
                    ]);


                    $realisasi = RO_Realisasi::create([
                        'ro_no' => $request->ro_no,
                        'container_key' => $container_key,
                        'container_no' => $request->container_no,
                        'truck_no' => $request->truck_no,
                        'truck_id' => $truck,
                    ]);

                    $item->update([
                        'ro_no' => $ro_no,
                        'ves_id' => $request->ves_id,
                        'ves_name' => $request->ves_name,
                        'ves_code' => $request->ves_code,
                        'voy_no' => $request->voy_no,
                        'yard_block' => $request->yard_block,
                        'yard_slot' => $request->yard_slot,
                        'yard_row' => $request->yard_row,
                        'yard_tier' => $request->yard_tier,
                        'ctr_intern_status' => '53',
                        'wharf_yard_oa' => $request->wharf_yard_oa,
                        'stuffing_date' => $now,
                        'truck_no' => $request->truck_no,
                        'truck_in_date' => $ro_gate->truck_in_date,
                        'stuffing_procces' => $ro->stuffing_service,
                    ]);
                    $act_alat = ActAlat::create([
                        'id_alat' =>  $request->alat,
                        'category' => $alat->category,
                        'nama_alat' => $alat->name,
                        'container_key' => $request->container_key,
                        'container_no' => $request->container_no,
                        'activity' => 'STFG-IN',
                    ]);

                    $client = new Client();

                    $fields = [
                        "container_key" => $request->container_key,
                        "ctr_intern_status" => "53",
                        'yard_block' => $request->yard_block,
                        'yard_slot' => $request->yard_slot,
                        'yard_row' => $request->yard_row,
                        'yard_tier' => $request->yard_tier,
                        'roNumber' => $request->ro_no,
                        'ves_id' => $request->ves_id,
                        'ves_name' => $request->ves_name,
                        'ves_code' => $request->ves_code,
                        'voy_no' => $request->voy_no,
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

                    if ($req->getStatusCode() == 200 || $req->getStatusCode() == 201) {

                        return response()->json([
                            'success' => true,
                            'message' => 'Updated successfully!',
                            'item' => $item,
                        ]);
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'Something wrong happened while updating with api',
                        ]);
                    }
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Jumlah Container Melebihi Dokumen R.O !!',
                    ]);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Yard sudah terisi oleh container -' . $yard_rowtier->container_no . '!!',
                ]);
            }
        } elseif ($ro->stuffing_service == "out") {
            $container_key = $request->container_key;
            $item = Item::where('container_key', $container_key)->first();
            $now = Carbon::now();
            $cont_check = $ro->jmlh_cont;
            $cont =  RO_Realisasi::where('ro_no', $ro_no)->count();


            if ($cont_check > $cont) {
                $id_alat = $request->alat;
                $alat = MasterAlat::where('id', $id_alat)->first();

                $truck = $request->ro_id_gati;
                $ro_gate = RO_Gate::where('ro_id_gati', $truck)->first();




                $realisasi = RO_Realisasi::create([
                    'ro_no' => $request->ro_no,
                    'container_key' => $container_key,
                    'container_no' => $item->container_no,
                    'truck_no' => $request->truck_no,
                    'truck_id' => $truck,
                ]);

                $item->update([
                    'ro_no' => $ro_no,
                    'ctr_intern_status' => '06',
                    'wharf_yard_oa' => $request->wharf_yard_oa,
                    'stuffing_date' => $now,
                    'truck_no' => $request->truck_no,
                    'truck_in_date' => $ro_gate->truck_in_date,
                    'stuffing_procces' => $ro->stuffing_service,
                ]);

                $act_alat = ActAlat::create([
                    'id_alat' =>  $request->alat,
                    'category' => $alat->category,
                    'nama_alat' => $alat->name,
                    'container_key' => $request->container_key,
                    'container_no' => $item->container_no,
                    'activity' => 'STFG-OUT',
                ]);

                $ro_gate->update([
                    'status' => '4',
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Updated successfully!',
                    'item' => $item,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah Container Melebihi Dokumen R.O !!',
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Service Stuffing tidak diketahui !!',
            ]);
        }





        // $client = new Client();

        // $fields = [
        //     "container_key" => $request->container_key,
        //     "ctr_intern_status" => "53",
        //     'yard_block' => $request->yard_block,
        //     'yard_slot' => $request->yard_slot,
        //     'yard_row' => $request->yard_row,
        //     'yard_tier' => $request->yard_tier,
        //     'ro_no' => $request->ro_no,
        //     'ves_id'=> $request->ves_id,
        //     'ves_name' => $request->ves_name,
        //     'ves_code' => $request->ves_code,
        //     'voy_no' => $request->voy_no,
        // ];
        // // dd($fields, $item->getAttributes());

        // $url = getenv('API_URL') . '/delivery-service/container/confirmGateIn';
        // $req = $client->post(
        //     $url,
        //     [
        //         "json" => $fields
        //     ]
        // );
        // $response = $req->getBody()->getContents();
        // $result = json_decode($response);

        // if ($req->getStatusCode() == 200 || $req->getStatusCode() == 201) {




        // } else {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Something wrong happened while updating with api',
        //     ]);
        // }

    }

    public function choose_container(Request $request)
    {
        $id = $request->ro_id_gati;
        $truck = RO_Gate::where('ro_id_gati', '=', $id)->first();

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
            'data' => $truck,
        ]);
    }

    public function choose_container_luar(Request $request)
    {
        $id = $request->ro_id_gati;
        $truck = RO_Gate::where('ro_id_gati', '=', $id)->first();

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
            'data' => $truck,
        ]);
    }

    public function detail_cont(Request $request)
    {
        $id = $request->ro_id_gati;
        $detail_cont = RO_Realisasi::where('truck_id', $id)->get();

        if ($detail_cont) {
            return response()->json([
                'success' => true,
                'message' => 'Updated successfully!',
                'data' => $detail_cont,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Something Wrong!!',
            ]);
        }
    }

    public function view_cont(Request $request)
    {
        $id = $request->container_key;
        $view_cont = Item::where('container_key', $id)->get();

        if ($view_cont) {
            return response()->json([
                'success' => true,
                'message' => 'Updated successfully!',
                'data' => $view_cont,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Something Wrong!!',
            ]);
        }
    }

    public function confirm_out(Request $request)
    {
        $id = $request->ro_id_gati;
        $ro_gate = RO_Gate::where('ro_id_gati', '=', $id)->first();

        if ($ro_gate->status === '7') {
            $ro_gate->update([
                'status' => '8',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Updated successfully!',
                'data' => $ro_gate,
            ]);
        } else {
            $ro_gate->update([
                'status' => '5',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Updated successfully!',
                'data' => $ro_gate,
            ]);
        }
    }

    public function place_cont_luar($ro_id_gati)
    {
        $users = User::all();
        $yard_block = Yard::distinct('yard_block')->pluck('yard_block');
        $yard_slot = Yard::distinct('yard_slot')->pluck('yard_slot');
        $yard_row = Yard::distinct('yard_row')->pluck('yard_row');
        $yard_tier = Yard::distinct('yard_tier')->pluck('yard_tier');
        $now = Carbon::now();

        $truck_no = RO_Gate::where('ro_id_gati', '=', $ro_id_gati)->pluck('truck_no');
        $truck_id_modal = RO_Gate::where('ro_id_gati', '=', $ro_id_gati)->pluck('ro_id_gati');
        $truck = str_replace(['[', '"', '"', ']'], '', $truck_no);
        $truck_id = str_replace(['[', '"', '"', ']'], '', $truck_id_modal);

        $ro = RO_Gate::where('ro_id_gati', '=', $ro_id_gati)->distinct('ro_no')->pluck('ro_no');
        //    $ro = str_replace(['[', '"', '"', ']'], '', $ro_no);

        $container = RO_Realisasi::where('truck_id', '=', $ro_id_gati)->where('status', '=', null)->get();

        $truck_table = RO_Realisasi::where('truck_id', '=', $ro_id_gati)->where('status', '=', '1')->get();
        $vessel = VVoyage::where('deparature_date', '>=', $now)->get();
        $alat = MasterAlat::where('category', '=', 'Yard')->get();
        return view('stuffing.luar.place_cont', compact('truck', 'truck_table', 'vessel', 'now', 'yard_block', 'yard_slot', 'yard_row', 'yard_tier', 'ro', 'container', 'truck_id', 'alat'));
    }

    public function update_place_cont_luar(Request $request)
    {
        $container_key = $request->container_key;
        $item = Item::where('container_key', $container_key)->first();
        $request->validate([
            'container_key' => 'required',
            'yard_block'  => 'required',
            'yard_slot'  => 'required',
            'yard_row'  => 'required',
            'yard_tier' => 'required',
            'truck_no' => 'required',
            'id_alat' => 'required',

        ], [
            'container_key.required' => 'Container Number is required.',
            'yard_block.required' => 'Block is required.',
            'yard_slot.required' => 'Slot Alat Number is required.',
            'yard_row.required' => 'Row Alat Number is required.',
            'yard_tier.required' => 'Tier Alat Number is required.',
            'trucK_no.required' => 'Nomor Truck is required.',
        ]);


        $yard_rowtier = Yard::where('yard_block', $request->yard_block)
            ->where('yard_slot', $request->yard_slot)
            ->where('yard_row', $request->yard_row)
            ->where('yard_tier', $request->yard_tier)
            ->first();

        if (is_null($yard_rowtier->container_key)) {
            $now = Carbon::now();
            $id_alat = $request->alat;
            $alat = MasterAlat::where('id', $id_alat)->first();



            $update_realisasi =  RO_Realisasi::where('container_key', $container_key)->first();

            $update_realisasi->update([
                'status' => '1',
            ]);

            $item->update([
                'ves_id' => $request->ves_id,
                'ves_name' => $request->ves_name,
                'ves_code' => $request->ves_code,
                'voy_no' => $request->voy_no,
                'yard_block' => $request->yard_block,
                'yard_slot' => $request->yard_slot,
                'yard_row' => $request->yard_row,
                'yard_tier' => $request->yard_tier,
                'ctr_intern_status' => '53',
                'wharf_yard_oa' => $request->wharf_yard_oa,
                'stuffing_date' => $now,
                'ctr_status' => 'FCL',
            ]);

            $act_alat = ActAlat::create([
                'id_alat' =>  $request->alat,
                'category' => $alat->category,
                'nama_alat' => $alat->name,
                'container_key' => $request->container_key,
                'container_no' => $item->container_no,
                'activity' => 'STFG-OUT-FULL',
            ]);

            $client = new Client();

            $fields = [
                "container_key" => $request->container_key,
                "ctr_intern_status" => "53",
                'yard_block' => $request->yard_block,
                'yard_slot' => $request->yard_slot,
                'yard_row' => $request->yard_row,
                'yard_tier' => $request->yard_tier,
                'roNumber' => $request->ro_no,
                'ves_id' => $request->ves_id,
                'ves_name' => $request->ves_name,
                'ves_code' => $request->ves_code,
                'voy_no' => $request->voy_no,
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

            if ($req->getStatusCode() == 200 || $req->getStatusCode() == 201) {

                return response()->json([
                    'success' => true,
                    'message' => 'Updated successfully!',
                    'item' => $item,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Something wrong happened while updating with api',
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Yard sudah terisi oleh container -' . $yard_rowtier->container_no . '!!',
            ]);
        }
    }
}
