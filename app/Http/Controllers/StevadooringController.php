<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use App\Models\VVoyage;
use App\Models\Item;
use App\Models\Shifting;
use App\Models\RBM;
use App\Models\InvoiceExport;
use App\Models\TarifStevadooring as Tarif;
use App\Models\OrderService as OS;
use App\Models\MasterTarif as MT;
use App\Models\Customer;
use App\Models\DOonline;
use App\Models\KodeDok;
use App\Models\InvoiceHeaderStevadooring as Header;
use App\Models\StevadooringDetail as STV;
use App\Models\ShiftingDetail as SFT;
use App\Models\TKapalDetail as TK;
use App\Models\TTongkakDetail as TT;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InvoiceStevadoring;

class StevadooringController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function main()
    {
        $data['title'] = "Billing System Stevadooring";
        $data['inv'] = Header::where('status', '=', '2')->get();

        return view('billingSystem.stevadooring.billing.main', $data);
    }

    public function TarifIndex()
    {
        $data['title'] = "Master Tariv and Activity";
        $data['tarif'] = Tarif::first();

        return view('billingSystem.stevadooring.masterTarif.main', $data);
    }

    public function RBM_Index()
    {
        $data['title'] = "Realisasi Bonglar Muat";

        $now = Carbon::now();
        $data['ves'] = VVoyage::orderBy('arrival_date', 'desc')->get();
        $data['realisasiBongkarMuat'] = RBM::orderBy('created_at', 'desc')->get();

        return view('billingSystem.stevadooring.RBM.main', $data);
    }

    public function RBM_Create(Request $request)
    {
        $data['title'] = "Realisasi Bonglar Muat || Form";

        $ves = VVoyage::where('ves_id', $request->ves_id)->first();
        $rbm = RBM::where('ves_id', $request->ves_id)->first();
        $data['rbm'] = $rbm;

        // if ($rbm) {
        //     return redirect()->back()->with('error', 'Data Sudah Tersedia');
        // }
        $data['kapal'] = $ves;
        
        $cont = Item::where('ves_id', $request->ves_id)->whereNot('ctr_intern_status', '01')->get();
        // Import
        $data['ctr_20_fcl_import'] = $cont->where('ctr_size', '20')->where('ctr_i_e_t', 'I')->where('ctr_status', 'FCL')->count();
        $data['ctr_20_mty_import'] = $cont->where('ctr_size', '20')->where('ctr_i_e_t', 'I')->where('ctr_status', 'MTY')->count();
        
        $data['ctr_21_fcl_import'] = $cont->where('ctr_size', '21')->where('ctr_i_e_t', 'I')->where('ctr_status', 'FCL')->count();
        $data['ctr_21_mty_import'] = $cont->where('ctr_size', '21')->where('ctr_i_e_t', 'I')->where('ctr_status', 'MTY')->count();

        $data['ctr_40_fcl_import'] = $cont->where('ctr_size', '40')->where('ctr_i_e_t', 'I')->where('ctr_status', 'FCL')->count();
        $data['ctr_40_mty_import'] = $cont->where('ctr_size', '40')->where('ctr_i_e_t', 'I')->where('ctr_status', 'MTY')->count();

        $data['ctr_42_fcl_import'] = $cont->where('ctr_size', '42')->where('ctr_i_e_t', 'I')->where('ctr_status', 'FCL')->count();
        $data['ctr_42_mty_import'] = $cont->where('ctr_size', '42')->where('ctr_i_e_t', 'I')->where('ctr_status', 'MTY')->count();

        // Export
        $data['ctr_20_fcl_export'] = $cont->where('ctr_size', '20')->where('ctr_i_e_t', 'E')->where('ctr_status', 'FCL')->count();
        $data['ctr_20_mty_export'] = $cont->where('ctr_size', '20')->where('ctr_i_e_t', 'E')->where('ctr_status', 'MTY')->count();

        $data['ctr_21_fcl_export'] = $cont->where('ctr_size', '21')->where('ctr_i_e_t', 'E')->where('ctr_status', 'FCL')->count();
        $data['ctr_21_mty_export'] = $cont->where('ctr_size', '21')->where('ctr_i_e_t', 'E')->where('ctr_status', 'MTY')->count();

        $data['ctr_40_fcl_export'] = $cont->where('ctr_size', '40')->where('ctr_i_e_t', 'E')->where('ctr_status', 'FCL')->count();
        $data['ctr_40_mty_export'] = $cont->where('ctr_size', '40')->where('ctr_i_e_t', 'E')->where('ctr_status', 'MTY')->count();

        $data['ctr_42_fcl_export'] = $cont->where('ctr_size', '42')->where('ctr_i_e_t', 'E')->where('ctr_status', 'FCL')->count();
        $data['ctr_42_mty_export'] = $cont->where('ctr_size', '42')->where('ctr_i_e_t', 'E')->where('ctr_status', 'MTY')->count();
        // Total
        $data['ctr_20_fcl'] = $cont->where('ctr_size', '20')->where('ctr_status', 'FCL')->count();
        $data['ctr_20_mty'] = $cont->where('ctr_size', '20')->where('ctr_status', 'MTY')->count();

        $data['ctr_21_fcl'] = $cont->where('ctr_size', '21')->where('ctr_status', 'FCL')->count();
        $data['ctr_21_mty'] = $cont->where('ctr_size', '21')->where('ctr_status', 'MTY')->count();

        $data['ctr_40_fcl'] = $cont->where('ctr_size', '40')->where('ctr_status', 'FCL')->count();
        $data['ctr_40_mty'] = $cont->where('ctr_size', '40')->where('ctr_status', 'MTY')->count();

        $data['ctr_42_fcl'] = $cont->where('ctr_size', '42')->where('ctr_status', 'FCL')->count();
        $data['ctr_42_mty'] = $cont->where('ctr_size', '42')->where('ctr_status', 'MTY')->count();

        // Shifting
        $shift = Shifting::where('ves_id', $request->ves_id)->get();
        $sk = Shifting::where('ves_id', $request->ves_id)->where('crane_d_k', 'K')->get();
        $sd = Shifting::where('ves_id', $request->ves_id)->where('crane_d_k', 'D')->get();

        $skl = Shifting::where('ves_id', $request->ves_id)->where('crane_d_k', 'K')->where('landing', 'Y')->get();
        $skn = Shifting::where('ves_id', $request->ves_id)->where('crane_d_k', 'K')->where('landing', 'N')->get();

        $sdl = Shifting::where('ves_id', $request->ves_id)->where('crane_d_k', 'D')->where('landing', 'Y')->get();
        $sdn = Shifting::where('ves_id', $request->ves_id)->where('crane_d_k', 'D')->where('landing', 'N')->get();

        // crane Dermaga
        $data['shift_20_fcl_d_l'] = $sdl->where('ctr_size', '20')->where('ctr_status', 'FCL')->count();
        $data['shift_21_fcl_d_l'] = $sdl->where('ctr_size', '21')->where('ctr_status', 'FCL')->count();
        $data['shift_40_fcl_d_l'] = $sdl->where('ctr_size', '40')->where('ctr_status', 'FCL')->count();
        $data['shift_42_fcl_d_l'] = $sdl->where('ctr_size', '42')->where('ctr_status', 'FCL')->count();

        $data['shift_20_mty_d_l'] = $sdl->where('ctr_size', '20')->where('ctr_status', 'MTY')->count();
        $data['shift_21_mty_d_l'] = $sdl->where('ctr_size', '21')->where('ctr_status', 'MTY')->count();
        $data['shift_40_mty_d_l'] = $sdl->where('ctr_size', '40')->where('ctr_status', 'MTY')->count();
        $data['shift_42_mty_d_l'] = $sdl->where('ctr_size', '42')->where('ctr_status', 'MTY')->count();

        $data['shift_20_fcl_d'] = $sdn->where('ctr_size', '20')->where('ctr_status', 'FCL')->count();
        $data['shift_21_fcl_d'] = $sdn->where('ctr_size', '21')->where('ctr_status', 'FCL')->count();
        $data['shift_40_fcl_d'] = $sdn->where('ctr_size', '40')->where('ctr_status', 'FCL')->count();
        $data['shift_42_fcl_d'] = $sdn->where('ctr_size', '42')->where('ctr_status', 'FCL')->count();

        $data['shift_20_mty_d'] = $sdn->where('ctr_size', '20')->where('ctr_status', 'MTY')->count();
        $data['shift_21_mty_d'] = $sdn->where('ctr_size', '21')->where('ctr_status', 'MTY')->count();
        $data['shift_40_mty_d'] = $sdn->where('ctr_size', '40')->where('ctr_status', 'MTY')->count();
        $data['shift_42_mty_d'] = $sdn->where('ctr_size', '42')->where('ctr_status', 'MTY')->count();

        // crane Kapal
        $data['shift_20_fcl_k_l'] = $skl->where('ctr_size', '20')->where('ctr_status', 'FCL')->count();
        $data['shift_21_fcl_k_l'] = $skl->where('ctr_size', '21')->where('ctr_status', 'FCL')->count();
        $data['shift_40_fcl_k_l'] = $skl->where('ctr_size', '40')->where('ctr_status', 'FCL')->count();
        $data['shift_42_fcl_k_l'] = $skl->where('ctr_size', '42')->where('ctr_status', 'FCL')->count();

        $data['shift_20_mty_k_l'] = $skl->where('ctr_size', '20')->where('ctr_status', 'MTY')->count();
        $data['shift_21_mty_k_l'] = $skl->where('ctr_size', '21')->where('ctr_status', 'MTY')->count();
        $data['shift_40_mty_k_l'] = $skl->where('ctr_size', '40')->where('ctr_status', 'MTY')->count();
        $data['shift_42_mty_k_l'] = $skl->where('ctr_size', '42')->where('ctr_status', 'MTY')->count();

        $data['shift_20_fcl_k'] = $skn->where('ctr_size', '20')->where('ctr_status', 'FCL')->count();
        $data['shift_21_fcl_k'] = $skn->where('ctr_size', '21')->where('ctr_status', 'FCL')->count();
        $data['shift_40_fcl_k'] = $skn->where('ctr_size', '40')->where('ctr_status', 'FCL')->count();
        $data['shift_42_fcl_k'] = $skn->where('ctr_size', '42')->where('ctr_status', 'FCL')->count();

        $data['shift_20_mty_k'] = $skn->where('ctr_size', '20')->where('ctr_status', 'MTY')->count();
        $data['shift_21_mty_k'] = $skn->where('ctr_size', '21')->where('ctr_status', 'MTY')->count();
        $data['shift_40_mty_k'] = $skn->where('ctr_size', '40')->where('ctr_status', 'MTY')->count();
        $data['shift_42_mty_k'] = $skn->where('ctr_size', '42')->where('ctr_status', 'MTY')->count();

        return view('billingSystem.stevadooring.RBM.create', $data);
    }

    public function RBM_Post(Request $request)
    {
        $ves = VVoyage::where('ves_id', $request->ves_id)->first();
        $now = Carbon::now();
        $user = Auth::user()->id;
        $rbm = RBM::create([
            'tipe' =>$request->tipe,
            'ves_id' => $request->ves_id,
            'ves_code' => $ves->ves_code,
            'voy_in' => $ves->voy_in,
            'voy_out' => $ves->voy_out,
            'ves_name' => $ves->ves_name,
            'arrival_date' => $ves->arrival_date,
            'deparature_date' => $ves->deparature_date,
            'open_stack_date' => $ves->open_stack_date,
            'clossing_date' => $ves->clossing_date,
            'ctr_20_fcl' => $request->ctr_20_fcl,
            'ctr_21_fcl' => $request->ctr_21_fcl,
            'ctr_40_fcl' => $request->ctr_40_fcl,
            'ctr_42_fcl' => $request->ctr_42_fcl,
            'ctr_20_mty' => $request->ctr_20_mty,
            'ctr_21_mty' => $request->ctr_21_mty,
            'ctr_40_mty' => $request->ctr_40_mty,
            'ctr_42_mty' => $request->ctr_42_mty,
            'shift_20_fcl_d_l' => $request->shift_20_fcl_d_l,
            'shift_20_mty_d_l' => $request->shift_20_mty_d_l,
            'shift_21_fcl_d_l' => $request->shift_21_fcl_d_l,
            'shift_21_mty_d_l' => $request->shift_21_mty_d_l,
            'shift_40_fcl_d_l' => $request->shift_40_fcl_d_l,
            'shift_40_mty_d_l' => $request->shift_40_mty_d_l,
            'shift_42_fcl_d_l' => $request->shift_42_fcl_d_l,
            'shift_42_mty_d_l' => $request->shift_42_mty_d_l,
            'shift_20_fcl_d' => $request->shift_20_fcl_d,
            'shift_20_mty_d' => $request->shift_20_mty_d,
            'shift_21_fcl_d' => $request->shift_21_fcl_d,
            'shift_21_mty_d' => $request->shift_21_mty_d,
            'shift_40_fcl_d' => $request->shift_40_fcl_d,
            'shift_40_mty_d' => $request->shift_40_mty_d,
            'shift_42_fcl_d' => $request->shift_42_fcl_d,
            'shift_42_mty_d' => $request->shift_42_mty_d,
            'shift_20_fcl_k_l' => $request->shift_20_fcl_k_l,
            'shift_20_mty_k_l' => $request->shift_20_mty_k_l,
            'shift_21_fcl_k_l' => $request->shift_21_fcl_k_l,
            'shift_21_mty_k_l' => $request->shift_21_mty_k_l,
            'shift_40_fcl_k_l' => $request->shift_40_fcl_k_l,
            'shift_40_mty_k_l' => $request->shift_40_mty_k_l,
            'shift_42_fcl_k_l' => $request->shift_42_fcl_k_l,
            'shift_42_mty_k_l' => $request->shift_42_mty_k_l,
            'shift_20_fcl_k' => $request->shift_20_fcl_k,
            'shift_20_mty_k' => $request->shift_20_mty_k,
            'shift_21_fcl_k' => $request->shift_21_fcl_k,
            'shift_21_mty_k' => $request->shift_21_mty_k,
            'shift_40_fcl_k' => $request->shift_40_fcl_k,
            'shift_40_mty_k' => $request->shift_40_mty_k,
            'shift_42_fcl_k' => $request->shift_42_fcl_k,
            'shift_42_mty_k' => $request->shift_42_mty_k,
            'loose_cargo' => $request->loose_cargo,
            'ctr_tt' => $request->ctr_tt,
            'gt_kapal' => $request->gt_kapal,
            'etmal' => $request->etmal,
            'created_at' => $now,
            'created_by' => $user,
        ]);

        return redirect()->route('index-stevadooring-RBM')->with('success', 'Data Berhasil Di Muat');
    }

    public function RBM_Detail($id)
    {
        $rbm = RBM::where('id', $id)->first();
        $data['rbm'] = $rbm;
        $ves = VVoyage::where('ves_id', $rbm->ves_id)->first();
        
        $data['title'] = "Realisasi Bonglar Muat " . $ves->ves_name . " " . $ves->voy_out;
        
        $data['kapal'] = $ves;
        
        $cont = Item::where('ves_id', $rbm->ves_id)->whereNot('ctr_intern_status', '01')->get();
        // Import
        $data['ctr_20_fcl_import'] = $cont->where('ctr_size', '20')->where('ctr_i_e_t', 'I')->where('ctr_status', 'FCL')->count();
        $data['ctr_20_mty_import'] = $cont->where('ctr_size', '20')->where('ctr_i_e_t', 'I')->where('ctr_status', 'MTY')->count();
        
        $data['ctr_21_fcl_import'] = $cont->where('ctr_size', '21')->where('ctr_i_e_t', 'I')->where('ctr_status', 'FCL')->count();
        $data['ctr_21_mty_import'] = $cont->where('ctr_size', '21')->where('ctr_i_e_t', 'I')->where('ctr_status', 'MTY')->count();

        $data['ctr_40_fcl_import'] = $cont->where('ctr_size', '40')->where('ctr_i_e_t', 'I')->where('ctr_status', 'FCL')->count();
        $data['ctr_40_mty_import'] = $cont->where('ctr_size', '40')->where('ctr_i_e_t', 'I')->where('ctr_status', 'MTY')->count();

        $data['ctr_42_fcl_import'] = $cont->where('ctr_size', '42')->where('ctr_i_e_t', 'I')->where('ctr_status', 'FCL')->count();
        $data['ctr_42_mty_import'] = $cont->where('ctr_size', '42')->where('ctr_i_e_t', 'I')->where('ctr_status', 'MTY')->count();

        // Export
        $data['ctr_20_fcl_export'] = $cont->where('ctr_size', '20')->where('ctr_i_e_t', 'E')->where('ctr_status', 'FCL')->count();
        $data['ctr_20_mty_export'] = $cont->where('ctr_size', '20')->where('ctr_i_e_t', 'E')->where('ctr_status', 'MTY')->count();

        $data['ctr_21_fcl_export'] = $cont->where('ctr_size', '21')->where('ctr_i_e_t', 'E')->where('ctr_status', 'FCL')->count();
        $data['ctr_21_mty_export'] = $cont->where('ctr_size', '21')->where('ctr_i_e_t', 'E')->where('ctr_status', 'MTY')->count();

        $data['ctr_40_fcl_export'] = $cont->where('ctr_size', '40')->where('ctr_i_e_t', 'E')->where('ctr_status', 'FCL')->count();
        $data['ctr_40_mty_export'] = $cont->where('ctr_size', '40')->where('ctr_i_e_t', 'E')->where('ctr_status', 'MTY')->count();

        $data['ctr_42_fcl_export'] = $cont->where('ctr_size', '42')->where('ctr_i_e_t', 'E')->where('ctr_status', 'FCL')->count();
        $data['ctr_42_mty_export'] = $cont->where('ctr_size', '42')->where('ctr_i_e_t', 'E')->where('ctr_status', 'MTY')->count();
        // Total
        $data['ctr_20_fcl'] = $cont->where('ctr_size', '20')->where('ctr_status', 'FCL')->count();
        $data['ctr_20_mty'] = $cont->where('ctr_size', '20')->where('ctr_status', 'MTY')->count();

        $data['ctr_21_fcl'] = $cont->where('ctr_size', '21')->where('ctr_status', 'FCL')->count();
        $data['ctr_21_mty'] = $cont->where('ctr_size', '21')->where('ctr_status', 'MTY')->count();

        $data['ctr_40_fcl'] = $cont->where('ctr_size', '40')->where('ctr_status', 'FCL')->count();
        $data['ctr_40_mty'] = $cont->where('ctr_size', '40')->where('ctr_status', 'MTY')->count();

        $data['ctr_42_fcl'] = $cont->where('ctr_size', '42')->where('ctr_status', 'FCL')->count();
        $data['ctr_42_mty'] = $cont->where('ctr_size', '42')->where('ctr_status', 'MTY')->count();

        // Shifting
        $shift = Shifting::where('ves_id', $rbm->ves_id)->get();
        $sk = Shifting::where('ves_id', $rbm->ves_id)->where('crane_d_k', 'K')->get();
        $sd = Shifting::where('ves_id', $rbm->ves_id)->where('crane_d_k', 'D')->get();

        $skl = Shifting::where('ves_id', $rbm->ves_id)->where('crane_d_k', 'K')->where('landing', 'Y')->get();
        $skn = Shifting::where('ves_id', $rbm->ves_id)->where('crane_d_k', 'K')->where('landing', 'N')->get();

        $sdl = Shifting::where('ves_id', $rbm->ves_id)->where('crane_d_k', 'D')->where('landing', 'Y')->get();
        $sdn = Shifting::where('ves_id', $rbm->ves_id)->where('crane_d_k', 'D')->where('landing', 'N')->get();

        // crane Dermaga
        $data['shift_20_fcl_d_l'] = $sdl->where('ctr_size', '20')->where('ctr_status', 'FCL')->count();
        $data['shift_21_fcl_d_l'] = $sdl->where('ctr_size', '21')->where('ctr_status', 'FCL')->count();
        $data['shift_40_fcl_d_l'] = $sdl->where('ctr_size', '40')->where('ctr_status', 'FCL')->count();
        $data['shift_42_fcl_d_l'] = $sdl->where('ctr_size', '42')->where('ctr_status', 'FCL')->count();

        $data['shift_20_mty_d_l'] = $sdl->where('ctr_size', '20')->where('ctr_status', 'MTY')->count();
        $data['shift_21_mty_d_l'] = $sdl->where('ctr_size', '21')->where('ctr_status', 'MTY')->count();
        $data['shift_40_mty_d_l'] = $sdl->where('ctr_size', '40')->where('ctr_status', 'MTY')->count();
        $data['shift_42_mty_d_l'] = $sdl->where('ctr_size', '42')->where('ctr_status', 'MTY')->count();

        $data['shift_20_fcl_d'] = $sdn->where('ctr_size', '20')->where('ctr_status', 'FCL')->count();
        $data['shift_21_fcl_d'] = $sdn->where('ctr_size', '21')->where('ctr_status', 'FCL')->count();
        $data['shift_40_fcl_d'] = $sdn->where('ctr_size', '40')->where('ctr_status', 'FCL')->count();
        $data['shift_42_fcl_d'] = $sdn->where('ctr_size', '42')->where('ctr_status', 'FCL')->count();

        $data['shift_20_mty_d'] = $sdn->where('ctr_size', '20')->where('ctr_status', 'MTY')->count();
        $data['shift_21_mty_d'] = $sdn->where('ctr_size', '21')->where('ctr_status', 'MTY')->count();
        $data['shift_40_mty_d'] = $sdn->where('ctr_size', '40')->where('ctr_status', 'MTY')->count();
        $data['shift_42_mty_d'] = $sdn->where('ctr_size', '42')->where('ctr_status', 'MTY')->count();

        // crane Kapal
        $data['shift_20_fcl_k_l'] = $skl->where('ctr_size', '20')->where('ctr_status', 'FCL')->count();
        $data['shift_21_fcl_k_l'] = $skl->where('ctr_size', '21')->where('ctr_status', 'FCL')->count();
        $data['shift_40_fcl_k_l'] = $skl->where('ctr_size', '40')->where('ctr_status', 'FCL')->count();
        $data['shift_42_fcl_k_l'] = $skl->where('ctr_size', '42')->where('ctr_status', 'FCL')->count();

        $data['shift_20_mty_k_l'] = $skl->where('ctr_size', '20')->where('ctr_status', 'MTY')->count();
        $data['shift_21_mty_k_l'] = $skl->where('ctr_size', '21')->where('ctr_status', 'MTY')->count();
        $data['shift_40_mty_k_l'] = $skl->where('ctr_size', '40')->where('ctr_status', 'MTY')->count();
        $data['shift_42_mty_k_l'] = $skl->where('ctr_size', '42')->where('ctr_status', 'MTY')->count();

        $data['shift_20_fcl_k'] = $skn->where('ctr_size', '20')->where('ctr_status', 'FCL')->count();
        $data['shift_21_fcl_k'] = $skn->where('ctr_size', '21')->where('ctr_status', 'FCL')->count();
        $data['shift_40_fcl_k'] = $skn->where('ctr_size', '40')->where('ctr_status', 'FCL')->count();
        $data['shift_42_fcl_k'] = $skn->where('ctr_size', '42')->where('ctr_status', 'FCL')->count();

        $data['shift_20_mty_k'] = $skn->where('ctr_size', '20')->where('ctr_status', 'MTY')->count();
        $data['shift_21_mty_k'] = $skn->where('ctr_size', '21')->where('ctr_status', 'MTY')->count();
        $data['shift_40_mty_k'] = $skn->where('ctr_size', '40')->where('ctr_status', 'MTY')->count();
        $data['shift_42_mty_k'] = $skn->where('ctr_size', '42')->where('ctr_status', 'MTY')->count();

        return view('billingSystem.stevadooring.RBM.detail', $data, compact('rbm'));
    }

    public function RBM_Update(Request $request)
    {
        $rbm = RBM::where('id', $request->id)->first();
        // dd($request->id);
        $ves = VVoyage::where('ves_id', $rbm->ves_id)->first();
        $now = Carbon::now();
        $user = Auth::user()->id;
        $rbm->update([
            'tipe' =>$request->tipe,
            'ves_id' => $request->ves_id,
            'ves_code' => $ves->ves_code,
            'voy_in' => $ves->voy_in,
            'voy_out' => $ves->voy_out,
            'ves_name' => $ves->ves_name,
            'arrival_date' => $ves->arrival_date,
            'deparature_date' => $ves->deparature_date,
            'open_stack_date' => $ves->open_stack_date,
            'clossing_date' => $ves->clossing_date,
            'ctr_20_fcl' => $request->ctr_20_fcl,
            'ctr_21_fcl' => $request->ctr_21_fcl,
            'ctr_40_fcl' => $request->ctr_40_fcl,
            'ctr_42_fcl' => $request->ctr_42_fcl,
            'ctr_20_mty' => $request->ctr_20_mty,
            'ctr_21_mty' => $request->ctr_21_mty,
            'ctr_40_mty' => $request->ctr_40_mty,
            'ctr_42_mty' => $request->ctr_42_mty,
            'shift_20_fcl_d_l' => $request->shift_20_fcl_d_l,
            'shift_20_mty_d_l' => $request->shift_20_mty_d_l,
            'shift_21_fcl_d_l' => $request->shift_21_fcl_d_l,
            'shift_21_mty_d_l' => $request->shift_21_mty_d_l,
            'shift_40_fcl_d_l' => $request->shift_40_fcl_d_l,
            'shift_40_mty_d_l' => $request->shift_40_mty_d_l,
            'shift_42_fcl_d_l' => $request->shift_42_fcl_d_l,
            'shift_42_mty_d_l' => $request->shift_42_mty_d_l,
            'shift_20_fcl_d' => $request->shift_20_fcl_d,
            'shift_20_mty_d' => $request->shift_20_mty_d,
            'shift_21_fcl_d' => $request->shift_21_fcl_d,
            'shift_21_mty_d' => $request->shift_21_mty_d,
            'shift_40_fcl_d' => $request->shift_40_fcl_d,
            'shift_40_mty_d' => $request->shift_40_mty_d,
            'shift_42_fcl_d' => $request->shift_42_fcl_d,
            'shift_42_mty_d' => $request->shift_42_mty_d,
            'shift_20_fcl_k_l' => $request->shift_20_fcl_k_l,
            'shift_20_mty_k_l' => $request->shift_20_mty_k_l,
            'shift_21_fcl_k_l' => $request->shift_21_fcl_k_l,
            'shift_21_mty_k_l' => $request->shift_21_mty_k_l,
            'shift_40_fcl_k_l' => $request->shift_40_fcl_k_l,
            'shift_40_mty_k_l' => $request->shift_40_mty_k_l,
            'shift_42_fcl_k_l' => $request->shift_42_fcl_k_l,
            'shift_42_mty_k_l' => $request->shift_42_mty_k_l,
            'shift_20_fcl_k' => $request->shift_20_fcl_k,
            'shift_20_mty_k' => $request->shift_20_mty_k,
            'shift_21_fcl_k' => $request->shift_21_fcl_k,
            'shift_21_mty_k' => $request->shift_21_mty_k,
            'shift_40_fcl_k' => $request->shift_40_fcl_k,
            'shift_40_mty_k' => $request->shift_40_mty_k,
            'shift_42_fcl_k' => $request->shift_42_fcl_k,
            'shift_42_mty_k' => $request->shift_42_mty_k,
            'loose_cargo' => $request->loose_cargo,
            'ctr_tt' => $request->ctr_tt,
            'gt_kapal' => $request->gt_kapal,
            'etmal' => $request->etmal,
            'admin' => $request->admin,
            'pajak' => $request->pajak,
            'update_by' => $user,
        ]);

        return redirect()->route('index-stevadooring-RBM')->with('success', 'Data Berhasil Di Muat');

    }

    public function TarifUpdate(Request $request)
    {
        $tarif = Tarif::first();
        $user = Auth::user()->id;
        
        $tarif->update([
            'ctr_20_fcl'=>$request->ctr_20_fcl,
            'ctr_21_fcl'=>$request->ctr_21_fcl,
            'ctr_40_fcl'=>$request->ctr_40_fcl,
            'ctr_42_fcl'=>$request->ctr_42_fcl,
            'ctr_20_mty'=>$request->ctr_20_mty,
            'ctr_21_mty'=>$request->ctr_21_mty,
            'ctr_40_mty'=>$request->ctr_40_mty,
            'ctr_42_mty'=>$request->ctr_42_mty,
            'shift_20_fcl_d_l'=>$request->shift_20_fcl_d_l,
            'shift_20_mty_d_l'=>$request->shift_20_mty_d_l,
            'shift_21_fcl_d_l'=>$request->shift_21_fcl_d_l,
            'shift_21_mty_d_l'=>$request->shift_21_mty_d_l,
            'shift_40_fcl_d_l'=>$request->shift_40_fcl_d_l,
            'shift_40_mty_d_l'=>$request->shift_40_mty_d_l,
            'shift_42_fcl_d_l'=>$request->shift_42_fcl_d_l,
            'shift_42_mty_d_l'=>$request->shift_42_mty_d_l,
            'shift_20_fcl_d'=>$request->shift_20_fcl_d,
            'shift_20_mty_d'=>$request->shift_20_mty_d,
            'shift_21_fcl_d'=>$request->shift_21_fcl_d,
            'shift_21_mty_d'=>$request->shift_21_mty_d,
            'shift_40_fcl_d'=>$request->shift_40_fcl_d,
            'shift_40_mty_d'=>$request->shift_40_mty_d,
            'shift_42_fcl_d'=>$request->shift_42_fcl_d,
            'shift_42_mty_d'=>$request->shift_42_mty_d,
            'shift_20_fcl_k_l'=>$request->shift_20_fcl_k_l,
            'shift_20_mty_k_l'=>$request->shift_20_mty_k_l,
            'shift_21_fcl_k_l'=>$request->shift_21_fcl_k_l,
            'shift_21_mty_k_l'=>$request->shift_21_mty_k_l,
            'shift_40_fcl_k_l'=>$request->shift_40_fcl_k_l,
            'shift_40_mty_k_l'=>$request->shift_40_mty_k_l,
            'shift_42_fcl_k_l'=>$request->shift_42_fcl_k_l,
            'shift_42_mty_k_l'=>$request->shift_42_mty_k_l,
            'shift_20_fcl_k'=>$request->shift_20_fcl_k,
            'shift_20_mty_k'=>$request->shift_20_mty_k,
            'shift_21_fcl_k'=>$request->shift_21_fcl_k,
            'shift_21_mty_k'=>$request->shift_21_mty_k,
            'shift_40_fcl_k'=>$request->shift_40_fcl_k,
            'shift_40_mty_k'=>$request->shift_40_mty_k,
            'shift_42_fcl_k'=>$request->shift_42_fcl_k,
            'shift_42_mty_k'=>$request->shift_42_mty_k,
            'loose_cargo'=>$request->loose_cargo,
            'ctr_tt'=>$request->ctr_tt,
            'tambat_kapal'=>$request->tambat_kapal,
            'created_at'=>$request->created_at,
            'created_by'=>$request->created_by,
            'last_update_by'=>$request->last_update_by,
            'admin' => $request->admin,
            'pajak' => $request->pajak,
        ]);

        return redirect()->back()->with('success', 'Data Berhasil Dimuat');
    }

    public function listForm()
    {
        $data['title'] = 'Stevadooring Form-List';
        $data['inv'] = Header::where('status', '=', '1')->get();

        return view('billingSystem.stevadooring.form.list', $data);
    }

    public function Form()
    {

        $data['title'] = 'Stevadooring Form';
        $user = Auth::user();
        $data["user"] = $user->id;

        $data['customer'] = Customer::get();
        $data['orderService'] = OS::where('ie', '=', 'I')->get();
        $data['do_online'] = DOonline::where('active', 'Y')->get();
        $data['ves'] = VVoyage::where('arrival_date', '<=', Carbon::now())->get();

        $data['rbm'] = RBM::orderBy('deparature_date', 'desc')->get();

        return view('billingSystem.stevadooring.form.create', $data);
    }

    public function FormPost(Request $request)
    {
        $tt = $request->tambat_tongkak;
        if ($tt) {
            $tongkak = 'Y';
        }else {
            $tongkak = 'N';
        }
        $tk = $request->tambat_kapal;
        if ($tk) {
            $tkapal = 'Y';
        }else {
            $tkapal = 'N';
        }
        $st  =$request->stevadooring;
        if ($st) {
            $stevadooring = 'Y';
        }else {
            $stevadooring = 'N';
        }
        $sh = $request->shifting;
        if ($sh) {
            $shifting = 'Y';
        }else {
            $shifting = 'N';
        }

        // dd($tt, $tk, $st, $sh, $tongkak, $shifting, $tkapal, $stevadooring);

        $rbm = RBM::where('id', $request->rbm_id)->first();
        $cust = Customer::where('id', $request->customer)->first();
        $user = Auth::user()->id;
        $nextProformaNumber = $this->getNextProformaNumber();
        
        $header = Header::create([
            'proforma_no'=>$nextProformaNumber,

            'cust_id'=>$request->customer,
            'cust_name'=>$cust->name,
            'fax'=>$cust->fax,
            'npwp'=>$cust->npwp,
            'alamat'=>$cust->alamat,
            'rbm_id'=>$request->rbm_id,
            'ves_id'=>$rbm->ves_id,
            'ves_code'=>$rbm->ves_code,
            'voy_in'=>$rbm->voy_in,
            'voy_out'=>$rbm->voy_out,
            'ves_name'=>$rbm->ves_name,
            'arrival_date'=>$rbm->arrival_date,
            'deparature_date'=>$rbm->deparature_date,
            'open_stack_date'=>$rbm->open_stack_date,
            'clossing_date'=>$rbm->clossing_date,
            'tambat_tongkak'=>$tongkak,
            'tambat_kapal'=>$tkapal,
            'stevadooring'=>$stevadooring,
            'shifting'=>$shifting,
            'created_by'=>$user,
            'created_at'=>Carbon::now(),
            'lunas'=> 'N',
            'status'=>'1',
        ]);

        $data['inv'] = $header;
        $data['title'] = 'Pre-Invoice Stevadooring';

        return redirect()->route('stevadooringPreInvoice', ['id' => $header->id]);

    }

    public function showInvoice($id)
    {
       
        $data['title'] = 'Pre-Invoice Stevadooring';

        $header = Header::where('id', $id)->first();
        $tarif = Tarif::first();
        $data['mt'] = $tarif;
        $data['inv'] = $header;
        switch ($header->rbm->tipe) {
            case 'I':
                $data['type'] = 'Import';
                break;
            case 'E':
                $data['type'] = 'Export';
                break;
            
            default:
                $data['type'] = ' ';
                break;
        }

        $rbm = RBM::where('id', $header->rbm_id)->first();
        $data['rbm'] = $rbm;

        if ($header->tambat_tongkak == 'Y') {
            $loose_cargo = $tarif->loose_cargo * $rbm->loose_cargo;
            $ctr_tt = $tarif->ctr_tt * $rbm->ctr_tt;
            $t_tongkak = $loose_cargo + $ctr_tt;
        }else {
            $t_tongkak = 0;
        }

        if ($header->tambat_kapal == "Y") {
            $t_kapal = $tarif->tambat_kapal * $rbm->etmal * $rbm->gt_kapal;
        }else {
            $t_kapal = 0;
        }

        if ($header->stevadooring == "Y") {
            $ctr_20_fcl = $tarif->ctr_20_fcl * $rbm->ctr_20_fcl;
            $ctr_21_fcl = $tarif->ctr_21_fcl * $rbm->ctr_21_fcl;
            $ctr_40_fcl = $tarif->ctr_40_fcl * $rbm->ctr_40_fcl;
            $ctr_42_fcl = $tarif->ctr_42_fcl * $rbm->ctr_42_fcl;
            $ctr_20_mty = $tarif->ctr_20_mty * $rbm->ctr_20_mty;
            $ctr_21_mty = $tarif->ctr_21_mty * $rbm->ctr_21_mty;
            $ctr_40_mty = $tarif->ctr_40_mty * $rbm->ctr_40_mty;
            $ctr_42_mty = $tarif->ctr_42_mty * $rbm->ctr_42_mty;

            $stevadooring = $ctr_20_fcl + $ctr_21_fcl + $ctr_40_fcl + $ctr_42_fcl + $ctr_20_mty + $ctr_21_mty + $ctr_40_mty + $ctr_42_mty;
        }else {
            $stevadooring = 0;
        }

        if ($header->shifting == "Y") {
            $shift_20_fcl_d_l = $tarif->shift_20_fcl_d_l * $rbm->shift_20_fcl_d_l;
            $shift_20_mty_d_l = $tarif->shift_20_mty_d_l * $rbm->shift_20_mty_d_l;
            $shift_21_fcl_d_l = $tarif->shift_21_fcl_d_l * $rbm->shift_21_fcl_d_l;
            $shift_21_mty_d_l = $tarif->shift_21_mty_d_l * $rbm->shift_21_mty_d_l;
            $shift_40_fcl_d_l = $tarif->shift_40_fcl_d_l * $rbm->shift_40_fcl_d_l;
            $shift_40_mty_d_l = $tarif->shift_40_mty_d_l * $rbm->shift_40_mty_d_l;
            $shift_42_fcl_d_l = $tarif->shift_42_fcl_d_l * $rbm->shift_42_fcl_d_l;
            $shift_42_mty_d_l = $tarif->shift_42_mty_d_l * $rbm->shift_42_mty_d_l;
            $shift_20_fcl_d = $tarif->shift_20_fcl_d * $rbm->shift_20_fcl_d;
            $shift_20_mty_d = $tarif->shift_20_mty_d * $rbm->shift_20_mty_d;
            $shift_21_fcl_d = $tarif->shift_21_fcl_d * $rbm->shift_21_fcl_d;
            $shift_21_mty_d = $tarif->shift_21_mty_d * $rbm->shift_21_mty_d;
            $shift_40_fcl_d = $tarif->shift_40_fcl_d * $rbm->shift_40_fcl_d;
            $shift_40_mty_d = $tarif->shift_40_mty_d * $rbm->shift_40_mty_d;
            $shift_42_fcl_d = $tarif->shift_42_fcl_d * $rbm->shift_42_fcl_d;
            $shift_42_mty_d = $tarif->shift_42_mty_d * $rbm->shift_42_mty_d;
            $shift_20_fcl_k_l = $tarif->shift_20_fcl_k_l * $rbm->shift_20_fcl_k_l;
            $shift_20_mty_k_l = $tarif->shift_20_mty_k_l * $rbm->shift_20_mty_k_l;
            $shift_21_fcl_k_l = $tarif->shift_21_fcl_k_l * $rbm->shift_21_fcl_k_l;
            $shift_21_mty_k_l = $tarif->shift_21_mty_k_l * $rbm->shift_21_mty_k_l;
            $shift_40_fcl_k_l = $tarif->shift_40_fcl_k_l * $rbm->shift_40_fcl_k_l;
            $shift_40_mty_k_l = $tarif->shift_40_mty_k_l * $rbm->shift_40_mty_k_l;
            $shift_42_fcl_k_l = $tarif->shift_42_fcl_k_l * $rbm->shift_42_fcl_k_l;
            $shift_42_mty_k_l = $tarif->shift_42_mty_k_l * $rbm->shift_42_mty_k_l;
            $shift_20_fcl_k = $tarif->shift_20_fcl_k * $rbm->shift_20_fcl_k;
            $shift_20_mty_k = $tarif->shift_20_mty_k * $rbm->shift_20_mty_k;
            $shift_21_fcl_k = $tarif->shift_21_fcl_k * $rbm->shift_21_fcl_k;
            $shift_21_mty_k = $tarif->shift_21_mty_k * $rbm->shift_21_mty_k;
            $shift_40_fcl_k = $tarif->shift_40_fcl_k * $rbm->shift_40_fcl_k;
            $shift_40_mty_k = $tarif->shift_40_mty_k * $rbm->shift_40_mty_k;
            $shift_42_fcl_k = $tarif->shift_42_fcl_k * $rbm->shift_42_fcl_k;
            $shift_42_mty_k = $tarif->shift_42_mty_k * $rbm->shift_42_mty_k;

            $shifting = $shift_20_fcl_d_l + $shift_20_mty_d_l + $shift_21_fcl_d_l + $shift_21_mty_d_l + $shift_40_fcl_d_l +
                        $shift_40_mty_d_l +
                        $shift_42_fcl_d_l +
                        $shift_42_mty_d_l +
                        $shift_20_fcl_d +
                        $shift_20_mty_d +
                        $shift_21_fcl_d +
                        $shift_21_mty_d +
                        $shift_40_fcl_d +
                        $shift_40_mty_d +
                        $shift_42_fcl_d +
                        $shift_42_mty_d +
                        $shift_20_fcl_k_l +
                        $shift_20_mty_k_l +
                        $shift_21_fcl_k_l +
                        $shift_21_mty_k_l +
                        $shift_40_fcl_k_l +
                        $shift_40_mty_k_l +
                        $shift_42_fcl_k_l +
                        $shift_42_mty_k_l +
                        $shift_20_fcl_k +
                        $shift_20_mty_k +
                        $shift_21_fcl_k +
                        $shift_21_mty_k +
                        $shift_40_fcl_k +
                        $shift_40_mty_k +
                        $shift_42_fcl_k +
                        $shift_42_mty_k;
        }else {
            $shifting = 0;
        }

        $total = $t_kapal + $t_tongkak + $stevadooring + $shifting;
        $data['total'] = $total;

        $ppn = ($total * $tarif->pajak) / 100;        
        $data['pajak'] = $ppn;
        $grandTotal = $total + $ppn + $tarif->admin;
        $data['gt'] = $grandTotal;
        return view('billingSystem.stevadooring.form.preinvoice', $data, compact('t_kapal', 't_tongkak', 'stevadooring', 'shifting'));
    }

    public function editInvoice($id)
    {
        $header = Header::where('id', $id)->first();
        $data['title'] = 'Invoice';
        $data['rbm'] = RBM::orderBy('deparature_date', 'desc')->get();
        $data['title'] = 'Stevadooring Form';
        $user = Auth::user();
        $data["user"] = $user->id;

        $data['customer'] = Customer::get();
        $data['inv'] = $header;
        return view('billingSystem.stevadooring.form.edit', $data);
    }

    public function FormUpdate(Request $request)
    {
        $tt = $request->tambat_tongkak;
        if ($tt) {
            $tongkak = 'Y';
        }else {
            $tongkak = 'N';
        }
        $tk = $request->tambat_kapal;
        if ($tk) {
            $tkapal = 'Y';
        }else {
            $tkapal = 'N';
        }
        $st  =$request->stevadooring;
        if ($st) {
            $stevadooring = 'Y';
        }else {
            $stevadooring = 'N';
        }
        $sh = $request->shifting;
        if ($sh) {
            $shifting = 'Y';
        }else {
            $shifting = 'N';
        }

        // dd($tt, $tk, $st, $sh, $tongkak, $shifting, $tkapal, $stevadooring);
        $header = Header::where('id', $request->id)->first();
        $rbm = RBM::where('id', $request->rbm_id)->first();
        $cust = Customer::where('id', $request->customer)->first();
        $user = Auth::user()->id;
        $header->update([
            'cust_id'=>$request->customer,
            'cust_name'=>$cust->name,
            'fax'=>$cust->fax,
            'npwp'=>$cust->npwp,
            'alamat'=>$cust->alamat,
            'rbm_id'=>$request->rbm_id,
            'ves_id'=>$rbm->ves_id,
            'ves_code'=>$rbm->ves_code,
            'voy_in'=>$rbm->voy_in,
            'voy_out'=>$rbm->voy_out,
            'ves_name'=>$rbm->ves_name,
            'arrival_date'=>$rbm->arrival_date,
            'deparature_date'=>$rbm->deparature_date,
            'open_stack_date'=>$rbm->open_stack_date,
            'clossing_date'=>$rbm->clossing_date,
            'tambat_tongkak'=>$tongkak,
            'tambat_kapal'=>$tkapal,
            'stevadooring'=>$stevadooring,
            'shifting'=>$shifting,
            'update_by'=>$user,
            'status'=>'1',
        ]);

        $data['inv'] = $header;
        $data['title'] = 'Pre-Invoice Stevadooring';

        return redirect()->route('stevadooringPreInvoice', ['id' => $header->id]);

    }

    public function stevadooringDetailPost(Request $request)
    {
        $header = Header::where('id', $request->id)->first();
        $tarif = Tarif::first();
        $rbm = RBM::where('id', $header->rbm_id)->first();
        if ($header) {
            if ($header->tambat_tongkak == 'Y') {
                $loose_cargo = TT::create([
                    'inv_id'=>$header->id,
                    'name'=>'loose_cargo',
                    'tarif'=>$tarif->loose_cargo,
                    'jumlah'=>$rbm->loose_cargo,
                    'total'=> $tarif->loose_cargo * $rbm->loose_cargo,
                ]);
                $ctr_tt = TT::create([
                    'inv_id'=>$header->id,
                    'name'=>'ctr_tt',
                    'tarif'=>$tarif->ctr_tt,
                    'jumlah'=>$rbm->ctr_tt,
                    'total'=> $tarif->ctr_tt * $rbm->ctr_tt,
                ]);
               
            }
    
            if ($header->tambat_kapal == "Y") {
                $t_kapal = TK::create([
                    'inv_id'=> $header->id,
                    'gt_kapal'=> $rbm->gt_kapal,
                    'etmal'=> $rbm->etmal,
                    'tarif'=> $tarif->tambat_kapal,
                    'total'=> $tarif->tambat_kapal * $rbm->etmal * $rbm->gt_kapal,
                ]);
            }

            if ($header->stevadooring == "Y") {
                $ctr_20_fcl = STV::create([
                    'inv_id'=> $header->id,
                    'ctr_size' => '20',
                    'ctr_status'=> 'FCL',
                    'tarif'=> $tarif->ctr_20_fcl,
                    'jumlah'=>$rbm->ctr_20_fcl,
                    'total'=>$rbm->ctr_20_fcl * $tarif->ctr_20_fcl,
                ]);
                $ctr_21_fcl = STV::create([
                    'inv_id'=> $header->id,
                    'ctr_size' => '21',
                    'ctr_status'=> 'FCL',
                    'tarif'=> $tarif->ctr_21_fcl,
                    'jumlah'=>$rbm->ctr_21_fcl,
                    'total'=>$rbm->ctr_21_fcl * $tarif->ctr_21_fcl,
                ]);
                $ctr_40_fcl = STV::create([
                    'inv_id'=> $header->id,
                    'ctr_size' => '40',
                    'ctr_status'=> 'FCL',
                    'tarif'=> $tarif->ctr_40_fcl,
                    'jumlah'=>$rbm->ctr_40_fcl,
                    'total'=>$rbm->ctr_40_fcl * $tarif->ctr_40_fcl,
                ]);
                $ctr_42_fcl = STV::create([
                    'inv_id'=> $header->id,
                    'ctr_size' => '42',
                    'ctr_status'=> 'FCL',
                    'tarif'=> $tarif->ctr_42_fcl,
                    'jumlah'=>$rbm->ctr_42_fcl,
                    'total'=>$rbm->ctr_42_fcl * $tarif->ctr_42_fcl,
                ]);
                $ctr_20_mty = STV::create([
                    'inv_id'=> $header->id,
                    'ctr_size' => '20',
                    'ctr_status'=> 'MTY',
                    'tarif'=> $tarif->ctr_20_mty,
                    'jumlah'=>$rbm->ctr_20_mty,
                    'total'=>$rbm->ctr_20_mty * $tarif->ctr_20_mty,
                ]);
                $ctr_21_mty = STV::create([
                    'inv_id'=> $header->id,
                    'ctr_size' => '21',
                    'ctr_status'=> 'MTY',
                    'tarif'=> $tarif->ctr_21_mty,
                    'jumlah'=>$rbm->ctr_21_mty,
                    'total'=>$rbm->ctr_21_mty * $tarif->ctr_21_mty,
                ]);
                $ctr_40_mty = STV::create([
                    'inv_id'=> $header->id,
                    'ctr_size' => '40',
                    'ctr_status'=> 'MTY',
                    'tarif'=> $tarif->ctr_40_mty,
                    'jumlah'=>$rbm->ctr_40_mty,
                    'total'=>$rbm->ctr_40_mty * $tarif->ctr_40_mty,
                ]);
                $ctr_42_mty = STV::create([
                    'inv_id'=> $header->id,
                    'ctr_size' => '42',
                    'ctr_status'=> 'MTY',
                    'tarif'=> $tarif->ctr_42_mty,
                    'jumlah'=>$rbm->ctr_42_mty,
                    'total'=>$rbm->ctr_42_mty * $tarif->ctr_42_mty,
                ]);
            }

            if ($header->shifting == "Y") {
                $shift_20_fcl_d_l = SFT::create([
                    'inv_id'=> $header->id,
                    'ctr_size' => '20',
                    'ctr_status'=> 'FCL',
                    'crane'=>'D',
                    'landing'=>'Y',
                    'tarif'=> $tarif->shift_20_fcl_d_l,
                    'jumlah'=>$rbm->shift_20_fcl_d_l,
                    'total'=>$rbm->shift_20_fcl_d_l * $tarif->shift_20_fcl_d_l,
                ]);
                $shift_20_mty_d_l =  SFT::create([
                    'inv_id'=> $header->id,
                    'ctr_size' => '20',
                    'ctr_status'=> 'MTY',
                    'crane'=>'D',
                    'landing'=>'Y',
                    'tarif'=> $tarif->shift_20_mty_d_l,
                    'jumlah'=>$rbm->shift_20_mty_d_l,
                    'total'=>$rbm->shift_20_mty_d_l * $tarif->shift_20_mty_d_l,
                ]);
                $shift_21_fcl_d_l = SFT::create([
                    'inv_id'=> $header->id,
                    'ctr_size' => '21',
                    'ctr_status'=> 'FCL',
                    'crane'=>'D',
                    'landing'=>'Y',
                    'tarif'=> $tarif->shift_21_fcl_d_l,
                    'jumlah'=>$rbm->shift_21_fcl_d_l,
                    'total'=>$rbm->shift_21_fcl_d_l * $tarif->shift_21_fcl_d_l,
                ]);
                $shift_21_mty_d_l =  SFT::create([
                    'inv_id'=> $header->id,
                    'ctr_size' => '21',
                    'ctr_status'=> 'MTY',
                    'crane'=>'D',
                    'landing'=>'Y',
                    'tarif'=> $tarif->shift_21_mty_d_l,
                    'jumlah'=>$rbm->shift_21_mty_d_l,
                    'total'=>$rbm->shift_21_mty_d_l * $tarif->shift_21_mty_d_l,
                ]);
                $shift_40_fcl_d_l = SFT::create([
                    'inv_id'=> $header->id,
                    'ctr_size' => '40',
                    'ctr_status'=> 'FCL',
                    'crane'=>'D',
                    'landing'=>'Y',
                    'tarif'=> $tarif->shift_40_fcl_d_l,
                    'jumlah'=>$rbm->shift_40_fcl_d_l,
                    'total'=>$rbm->shift_40_fcl_d_l * $tarif->shift_40_fcl_d_l,
                ]);
                $shift_40_mty_d_l =  SFT::create([
                    'inv_id'=> $header->id,
                    'ctr_size' => '40',
                    'ctr_status'=> 'MTY',
                    'crane'=>'D',
                    'landing'=>'Y',
                    'tarif'=> $tarif->shift_40_mty_d_l,
                    'jumlah'=>$rbm->shift_40_mty_d_l,
                    'total'=>$rbm->shift_40_mty_d_l * $tarif->shift_40_mty_d_l,
                ]);
                $shift_42_fcl_d_l = SFT::create([
                    'inv_id'=> $header->id,
                    'ctr_size' => '42',
                    'ctr_status'=> 'FCL',
                    'crane'=>'D',
                    'landing'=>'Y',
                    'tarif'=> $tarif->shift_42_fcl_d_l,
                    'jumlah'=>$rbm->shift_42_fcl_d_l,
                    'total'=>$rbm->shift_42_fcl_d_l * $tarif->shift_42_fcl_d_l,
                ]);
                $shift_42_mty_d_l =  SFT::create([
                    'inv_id'=> $header->id,
                    'ctr_size' => '42',
                    'ctr_status'=> 'MTY',
                    'crane'=>'D',
                    'landing'=>'Y',
                    'tarif'=> $tarif->shift_42_mty_d_l,
                    'jumlah'=>$rbm->shift_42_mty_d_l,
                    'total'=>$rbm->shift_42_mty_d_l * $tarif->shift_42_mty_d_l,
                ]);
                $shift_20_fcl_d = SFT::create([
                    'inv_id'=> $header->id,
                    'ctr_size' => '20',
                    'ctr_status'=> 'FCL',
                    'crane'=>'D',
                    'landing'=>'N',
                    'tarif'=> $tarif->shift_20_fcl_d,
                    'jumlah'=>$rbm->shift_20_fcl_d,
                    'total'=>$rbm->shift_20_fcl_d * $tarif->shift_20_fcl_d,
                ]);
                $shift_20_mty_d =  SFT::create([
                    'inv_id'=> $header->id,
                    'ctr_size' => '20',
                    'ctr_status'=> 'MTY',
                    'crane'=>'D',
                    'landing'=>'Y',
                    'tarif'=> $tarif->shift_20_mty_d,
                    'jumlah'=>$rbm->shift_20_mty_d,
                    'total'=>$rbm->shift_20_mty_d * $tarif->shift_20_mty_d,
                ]);
                $shift_21_fcl_d = SFT::create([
                    'inv_id'=> $header->id,
                    'ctr_size' => '21',
                    'ctr_status'=> 'FCL',
                    'crane'=>'D',
                    'landing'=>'N',
                    'tarif'=> $tarif->shift_21_fcl_d,
                    'jumlah'=>$rbm->shift_21_fcl_d,
                    'total'=>$rbm->shift_21_fcl_d * $tarif->shift_21_fcl_d,
                ]);
                $shift_21_mty_d =  SFT::create([
                    'inv_id'=> $header->id,
                    'ctr_size' => '21',
                    'ctr_status'=> 'MTY',
                    'crane'=>'D',
                    'landing'=>'Y',
                    'tarif'=> $tarif->shift_21_mty_d,
                    'jumlah'=>$rbm->shift_21_mty_d,
                    'total'=>$rbm->shift_21_mty_d * $tarif->shift_21_mty_d,
                ]);
                $shift_40_fcl_d = SFT::create([
                    'inv_id'=> $header->id,
                    'ctr_size' => '40',
                    'ctr_status'=> 'FCL',
                    'crane'=>'D',
                    'landing'=>'N',
                    'tarif'=> $tarif->shift_40_fcl_d,
                    'jumlah'=>$rbm->shift_40_fcl_d,
                    'total'=>$rbm->shift_40_fcl_d * $tarif->shift_40_fcl_d,
                ]);
                $shift_40_mty_d =  SFT::create([
                    'inv_id'=> $header->id,
                    'ctr_size' => '40',
                    'ctr_status'=> 'MTY',
                    'crane'=>'D',
                    'landing'=>'Y',
                    'tarif'=> $tarif->shift_40_mty_d,
                    'jumlah'=>$rbm->shift_40_mty_d,
                    'total'=>$rbm->shift_40_mty_d * $tarif->shift_40_mty_d,
                ]);
                $shift_42_fcl_d = SFT::create([
                    'inv_id'=> $header->id,
                    'ctr_size' => '42',
                    'ctr_status'=> 'FCL',
                    'crane'=>'D',
                    'landing'=>'N',
                    'tarif'=> $tarif->shift_42_fcl_d,
                    'jumlah'=>$rbm->shift_42_fcl_d,
                    'total'=>$rbm->shift_42_fcl_d * $tarif->shift_42_fcl_d,
                ]);
                $shift_42_mty_d =  SFT::create([
                    'inv_id'=> $header->id,
                    'ctr_size' => '42',
                    'ctr_status'=> 'MTY',
                    'crane'=>'D',
                    'landing'=>'Y',
                    'tarif'=> $tarif->shift_42_mty_d,
                    'jumlah'=>$rbm->shift_42_mty_d,
                    'total'=>$rbm->shift_42_mty_d * $tarif->shift_42_mty_d,
                ]);

                // Crane Kapal
                $shift_20_fcl_k_l = SFT::create([
                    'inv_id'=> $header->id,
                    'ctr_size' => '20',
                    'ctr_status'=> 'FCL',
                    'crane'=>'K',
                    'landing'=>'Y',
                    'tarif'=> $tarif->shift_20_fcl_k_l,
                    'jumlah'=>$rbm->shift_20_fcl_k_l,
                    'total'=>$rbm->shift_20_fcl_k_l * $tarif->shift_20_fcl_k_l,
                ]);
                $shift_20_mty_k_l =  SFT::create([
                    'inv_id'=> $header->id,
                    'ctr_size' => '20',
                    'ctr_status'=> 'MTY',
                    'crane'=>'K',
                    'landing'=>'Y',
                    'tarif'=> $tarif->shift_20_mty_k_l,
                    'jumlah'=>$rbm->shift_20_mty_k_l,
                    'total'=>$rbm->shift_20_mty_k_l * $tarif->shift_20_mty_k_l,
                ]);
                $shift_21_fcl_k_l = SFT::create([
                    'inv_id'=> $header->id,
                    'ctr_size' => '21',
                    'ctr_status'=> 'FCL',
                    'crane'=>'K',
                    'landing'=>'Y',
                    'tarif'=> $tarif->shift_21_fcl_k_l,
                    'jumlah'=>$rbm->shift_21_fcl_k_l,
                    'total'=>$rbm->shift_21_fcl_k_l * $tarif->shift_21_fcl_k_l,
                ]);
                $shift_21_mty_k_l =  SFT::create([
                    'inv_id'=> $header->id,
                    'ctr_size' => '21',
                    'ctr_status'=> 'MTY',
                    'crane'=>'K',
                    'landing'=>'Y',
                    'tarif'=> $tarif->shift_21_mty_k_l,
                    'jumlah'=>$rbm->shift_21_mty_k_l,
                    'total'=>$rbm->shift_21_mty_k_l * $tarif->shift_21_mty_k_l,
                ]);
                $shift_40_fcl_k_l = SFT::create([
                    'inv_id'=> $header->id,
                    'ctr_size' => '40',
                    'ctr_status'=> 'FCL',
                    'crane'=>'K',
                    'landing'=>'Y',
                    'tarif'=> $tarif->shift_40_fcl_k_l,
                    'jumlah'=>$rbm->shift_40_fcl_k_l,
                    'total'=>$rbm->shift_40_fcl_k_l * $tarif->shift_40_fcl_k_l,
                ]);
                $shift_40_mty_k_l =  SFT::create([
                    'inv_id'=> $header->id,
                    'ctr_size' => '40',
                    'ctr_status'=> 'MTY',
                    'crane'=>'K',
                    'landing'=>'Y',
                    'tarif'=> $tarif->shift_40_mty_k_l,
                    'jumlah'=>$rbm->shift_40_mty_k_l,
                    'total'=>$rbm->shift_40_mty_k_l * $tarif->shift_40_mty_k_l,
                ]);
                $shift_42_fcl_k_l = SFT::create([
                    'inv_id'=> $header->id,
                    'ctr_size' => '42',
                    'ctr_status'=> 'FCL',
                    'crane'=>'K',
                    'landing'=>'Y',
                    'tarif'=> $tarif->shift_42_fcl_k_l,
                    'jumlah'=>$rbm->shift_42_fcl_k_l,
                    'total'=>$rbm->shift_42_fcl_k_l * $tarif->shift_42_fcl_k_l,
                ]);
                $shift_42_mty_k_l =  SFT::create([
                    'inv_id'=> $header->id,
                    'ctr_size' => '42',
                    'ctr_status'=> 'MTY',
                    'crane'=>'K',
                    'landing'=>'Y',
                    'tarif'=> $tarif->shift_42_mty_k_l,
                    'jumlah'=>$rbm->shift_42_mty_k_l,
                    'total'=>$rbm->shift_42_mty_k_l * $tarif->shift_42_mty_k_l,
                ]);
                $shift_20_fcl_k = SFT::create([
                    'inv_id'=> $header->id,
                    'ctr_size' => '20',
                    'ctr_status'=> 'FCL',
                    'crane'=>'K',
                    'landing'=>'N',
                    'tarif'=> $tarif->shift_20_fcl_k,
                    'jumlah'=>$rbm->shift_20_fcl_k,
                    'total'=>$rbm->shift_20_fcl_k * $tarif->shift_20_fcl_k,
                ]);
                $shift_20_mty_k =  SFT::create([
                    'inv_id'=> $header->id,
                    'ctr_size' => '20',
                    'ctr_status'=> 'MTY',
                    'crane'=>'K',
                    'landing'=>'Y',
                    'tarif'=> $tarif->shift_20_mty_k,
                    'jumlah'=>$rbm->shift_20_mty_k,
                    'total'=>$rbm->shift_20_mty_k * $tarif->shift_20_mty_k,
                ]);
                $shift_21_fcl_k = SFT::create([
                    'inv_id'=> $header->id,
                    'ctr_size' => '21',
                    'ctr_status'=> 'FCL',
                    'crane'=>'K',
                    'landing'=>'N',
                    'tarif'=> $tarif->shift_21_fcl_k,
                    'jumlah'=>$rbm->shift_21_fcl_k,
                    'total'=>$rbm->shift_21_fcl_k * $tarif->shift_21_fcl_k,
                ]);
                $shift_21_mty_k =  SFT::create([
                    'inv_id'=> $header->id,
                    'ctr_size' => '21',
                    'ctr_status'=> 'MTY',
                    'crane'=>'K',
                    'landing'=>'Y',
                    'tarif'=> $tarif->shift_21_mty_k,
                    'jumlah'=>$rbm->shift_21_mty_k,
                    'total'=>$rbm->shift_21_mty_k * $tarif->shift_21_mty_k,
                ]);
                $shift_40_fcl_k = SFT::create([
                    'inv_id'=> $header->id,
                    'ctr_size' => '40',
                    'ctr_status'=> 'FCL',
                    'crane'=>'K',
                    'landing'=>'N',
                    'tarif'=> $tarif->shift_40_fcl_k,
                    'jumlah'=>$rbm->shift_40_fcl_k,
                    'total'=>$rbm->shift_40_fcl_k * $tarif->shift_40_fcl_k,
                ]);
                $shift_40_mty_k =  SFT::create([
                    'inv_id'=> $header->id,
                    'ctr_size' => '40',
                    'ctr_status'=> 'MTY',
                    'crane'=>'K',
                    'landing'=>'Y',
                    'tarif'=> $tarif->shift_40_mty_k,
                    'jumlah'=>$rbm->shift_40_mty_k,
                    'total'=>$rbm->shift_40_mty_k * $tarif->shift_40_mty_k,
                ]);
                $shift_42_fcl_k = SFT::create([
                    'inv_id'=> $header->id,
                    'ctr_size' => '42',
                    'ctr_status'=> 'FCL',
                    'crane'=>'K',
                    'landing'=>'N',
                    'tarif'=> $tarif->shift_42_fcl_k,
                    'jumlah'=>$rbm->shift_42_fcl_k,
                    'total'=>$rbm->shift_42_fcl_k * $tarif->shift_42_fcl_k,
                ]);
                $shift_42_mty_k =  SFT::create([
                    'inv_id'=> $header->id,
                    'ctr_size' => '42',
                    'ctr_status'=> 'MTY',
                    'crane'=>'K',
                    'landing'=>'Y',
                    'tarif'=> $tarif->shift_42_mty_k,
                    'jumlah'=>$rbm->shift_42_mty_k,
                    'total'=>$rbm->shift_42_mty_k * $tarif->shift_42_mty_k,
                ]);
            }

            $header->update([
                'tambat_tongkak_total'=>$request->tambat_tongkak_total,
                'tambat_kapal_total'=>$request->tambat_kapal_total,
                'stevadooring_total'=>$request->stevadooring_total,
                'shifting_total'=>$request->shifting_total,
                'total'=>$request->total,
                'pajak'=>$request->pajak,
                'admin'=>$request->admin,
                'grand_total'=>$request->grand_total,
                'status'=>'2',
            ]);

            return redirect()->route('index-stevadooring')->with('success', 'Data Berhasil Di Muat');

        }else {
            return redirect()->back()->with('error', 'Terjadi Kesalahan, Hubungi Admin!');
        }
    }

    public function Pranota($id)
    {
        $header = Header::where('id', $id)->first();
        switch ($header->rbm->tipe) {
            case 'I':
                $data['type'] = 'Import';
                break;
            case 'E':
                $data['type'] = 'Export';
                break;
            
            default:
                $data['type'] = ' ';
                break;
        }
        $data['title'] = 'Pranota Stevadooring ' . $header->ves_name . ' ' . $header->voy_out;
        $data['invoice'] = $header;
        if ($header->tambat_tongkak == 'Y') {
           $data['tongkak'] = TT::where('inv_id', $header->id)->get();
        //    dd($data['tongkak']);
        }
        if ($header->tambat_kapal == 'Y') {
            $data['tkapal'] = TK::where('inv_id', $header->id)->first();
        }
        if ($header->stevadooring == 'Y') {
            $data['stevadooring'] = STV::where('inv_id', $header->id)->whereNot('total', 0)->orderBy('ctr_size', 'asc')->orderBy('ctr_status', 'asc')->get();
        }
        if ($header->shifting == 'Y') {
            $data['crane_dermaga'] = SFT::where('inv_id', $header->id)->where('crane', '=', 'D')->whereNot('total', 0)->orderBy('landing', 'asc')->orderBy('ctr_size', 'asc')->orderBy('ctr_status', 'asc')->get();
            $data['crane_kapal'] = SFT::where('inv_id', $header->id)->where('crane', '=', 'K')->whereNot('total', 0)->orderBy('landing', 'asc')->orderBy('ctr_size', 'asc')->orderBy('ctr_status', 'asc')->get();
        }
        
        $data['terbilang'] = $this->terbilang($header->grand_total);
        return view('billingSystem.stevadooring.pranota.main', $data);
    }

    public function Pay($id)
    {
        $invoice = Header::where('id', $id)->first();
        if ($invoice) {
            return response()->json([
                'success' => true,
                'message' => 'updated successfully!',
                'data'    => $invoice,
            ]);
        }
    }

    public function payFullStevadooring(Request $request)
    {
        $id = $request->inv_id;
        $invoice = Header::where('id', $id)->first();
        if ($invoice->invoice_no == null) {
                $invoiceNo = $this->getNextInvoiceDSK();
                $invoiceDate = Carbon::now();
       }else {
         $invoiceNo = $invoice->invoice_no;
         $invoiceDate = $invoice->invoice_date;
       }
        if ($invoice) {
            $invoice->update([
                'invoice_no' => $invoiceNo,
                'lunas' => 'Y',
                'lunas_at'=> Carbon::now(),
                'invoice_date' => $invoiceDate,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'updated successfully!',
                'data'    => $invoice,
            ]);
        }else {
            return response()->json([
                'success' => false,
                'message' => 'Something Wrong!',
            ]);
        }
    }

    public function piutangStevadooring(Request $request)
    {
        $id = $request->inv_id;
        $invoice = Header::where('id', $id)->first();
        if ($invoice->invoice_no == null) {
                $invoiceNo = $this->getNextInvoiceDSK();
       }else {
         $invoiceNo = $invoice->invoice_no;
       }
        if ($invoice) {
            $invoice->update([
                'invoice_no' => $invoiceNo,
                'lunas' => 'P',
                'piutang_at'=> Carbon::now(),
                'invoice_date'=> Carbon::now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'updated successfully!',
                'data'    => $invoice,
            ]);
        }else {
            return response()->json([
                'success' => false,
                'message' => 'Something Wrong!',
            ]);
        }
    }

    public function Invoice($id)
    {
        $header = Header::where('id', $id)->first();
        switch ($header->rbm->tipe) {
            case 'I':
                $data['type'] = 'Import';
                break;
            case 'E':
                $data['type'] = 'Export';
                break;
            
            default:
                $data['type'] = ' ';
                break;
        }
        $data['title'] = 'Pranota Stevadooring ' . $header->ves_name . ' ' . $header->voy_out;
        $data['invoice'] = $header;
        if ($header->tambat_tongkak == 'Y') {
           $data['tongkak'] = TT::where('inv_id', $header->id)->get();
        //    dd($data['tongkak']);
        }
        if ($header->tambat_kapal == 'Y') {
            $data['tkapal'] = TK::where('inv_id', $header->id)->first();
        }
        if ($header->stevadooring == 'Y') {
            $data['stevadooring'] = STV::where('inv_id', $header->id)->whereNot('total', 0)->orderBy('ctr_size', 'asc')->orderBy('ctr_status', 'asc')->get();
        }
        if ($header->shifting == 'Y') {
            $data['crane_dermaga'] = SFT::where('inv_id', $header->id)->where('crane', '=', 'D')->whereNot('total', 0)->orderBy('landing', 'asc')->orderBy('ctr_size', 'asc')->orderBy('ctr_status', 'asc')->get();
            $data['crane_kapal'] = SFT::where('inv_id', $header->id)->where('crane', '=', 'K')->whereNot('total', 0)->orderBy('landing', 'asc')->orderBy('ctr_size', 'asc')->orderBy('ctr_status', 'asc')->get();
        }
        
        $data['terbilang'] = $this->terbilang($header->grand_total);
        return view('billingSystem.stevadooring.invoice.main', $data);
    }

    private function getNextProformaNumber()
    {
        // Mendapatkan nomor proforma terakhir
        $latestProforma = Header::orderBy('created_at', 'desc')->first();
    
        // Jika tidak ada proforma sebelumnya, kembalikan nomor proforma awal
        if (!$latestProforma) {
            return 'P0000001';
        }
    
        // Mendapatkan nomor urut proforma terakhir
        $lastProformaNumber = $latestProforma->proforma_no;
    
        // Mengekstrak angka dari nomor proforma terakhir
        $lastNumber = (int)substr($lastProformaNumber, 1);
    
        // Menambahkan 1 ke nomor proforma terakhir
        $nextNumber = $lastNumber + 1;
    
        // Menghasilkan nomor proforma berikutnya dengan format yang benar
        return 'P' . str_pad($nextNumber, 7, '0', STR_PAD_LEFT);
    }
    
    private function getNextInvoiceDSK()
    {
        // Mendapatkan nomor invoice terakhir dari kedua tabel
        $latestExport = InvoiceExport::where('inv_type', 'OSK')->orderBy('inv_no', 'desc')->first();
        $latestStev = Header::orderBy('invoice_no', 'desc')->first();

        // Jika tidak ada invoice sebelumnya di kedua tabel, kembalikan nomor invoice awal
        if (!$latestExport && !$latestStev) {
            return 'OSK0000001';
        }

        // Mendapatkan nomor invoice terakhir dari kedua tabel
        $lastExportInvoice = $latestExport ? $latestExport->inv_no : null;
        $lastStevInvoice = $latestStev ? $latestStev->invoice_no : null;

        // Mengekstrak angka dari nomor invoice terakhir dari kedua tabel
        $lastExportNumber = $lastExportInvoice ? (int)substr($lastExportInvoice, 3) : 0;
        $lastStevNumber = $lastStevInvoice ? (int)substr($lastStevInvoice, 3) : 0;

        // Menentukan nomor invoice terbesar dari kedua tabel
        $lastNumber = max($lastExportNumber, $lastStevNumber);

        // Menambahkan 1 ke nomor invoice terakhir
        $nextNumber = $lastNumber + 1;

        // Menghasilkan nomor invoice berikutnya dengan format yang benar
        return 'OSK' . str_pad($nextNumber, 7, '0', STR_PAD_LEFT);
    }


    private function terbilang($number)
    {
        $x = abs($number);
        $angka = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");

        $result = "";
        if ($x < 12) {
            $result = " " . $angka[$x];
        } elseif ($x < 20) {
            $result = $this->terbilang($x - 10) . " Belas";
        } elseif ($x < 100) {
            $result = $this->terbilang($x / 10) . " Puluh" . $this->terbilang($x % 10);
        } elseif ($x < 200) {
            $result = " Seratus" . $this->terbilang($x - 100);
        } elseif ($x < 1000) {
            $result = $this->terbilang($x / 100) . " Ratus" . $this->terbilang($x % 100);
        } elseif ($x < 2000) {
            $result = " Seribu" . $this->terbilang($x - 1000);
        } elseif ($x < 1000000) {
            $result = $this->terbilang($x / 1000) . " Ribu" . $this->terbilang($x % 1000);
        } elseif ($x < 1000000000) {
            $result = $this->terbilang($x / 1000000) . " Juta" . $this->terbilang($x % 1000000);
        } elseif ($x < 1000000000000) {
            $result = $this->terbilang($x / 1000000000) . " Milyar" . $this->terbilang(fmod($x, 1000000000));
        } elseif ($x < 1000000000000000) {
            $result = $this->terbilang($x / 1000000000000) . " Trilyun" . $this->terbilang(fmod($x, 1000000000000));
        }

        return $result;
    }

    public function ReportExcel(Request $request)
    {
      $startDate = $request->start;
      $endDate = $request->end;
      $invoice = Header::whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate)->orderBy('created_at', 'asc')->get();
        $fileName = 'ReportInvoiceStevadooring-'. $startDate . $endDate .'.xlsx';
      return Excel::download(new InvoiceStevadoring($invoice), $fileName);
    }

    public function invoiceDelete($id)
    {
        // var_dump($id);
        // die;
        $invoice = Header::find($id);

        // Check if the invoice exists
        if (!$invoice) {
            return response()->json(['message' => 'Invoice not found.'], 404);
        }
    
        // Delete related records in one query per model
        STV::where('inv_id', $invoice->id)->delete();
        SFT::where('inv_id', $invoice->id)->delete();
        TT::where('inv_id', $invoice->id)->delete();
        TK::where('inv_id', $invoice->id)->delete();
    
        // Delete the invoice
        $invoice->delete();

        return response()->json(['message' => 'Data berhasil dihapus.']);
    }

    public function cancelStevadooring(Request $request)
    {
        $id = $request->inv_id;
        $header = Header::find($id);

        // var_dump($header);
        // die();
        if ($header) {
            if ($header->tambat_tongkak == 'Y') {
                $tongkak = TT::where('inv_id', $header->id)->get();
                foreach ($tongkak as $tk) {
                    $tk->update([
                        'total' => 0,
                    ]);
                }
             }
             if ($header->tambat_kapal == 'Y') {
                 $tkapal = TK::where('inv_id', $header->id)->get();
                 foreach ($tkapal as $tt) {
                    $tt->update([
                        'total' => 0,
                    ]);
                }
             }
             if ($header->stevadooring == 'Y') {
                 $stevadooring = STV::where('inv_id', $header->id)->get();
                 foreach ($stevadooring as $sv) {
                    $sv->update([
                        'total' => 0,
                    ]);
                }
             }
             if ($header->shifting == 'Y') {
                 $shift = SFT::where('inv_id', $header->id)->get();
                 foreach ($shift as $sft) {
                    $sft->update([
                        'total' => 0,
                    ]);
                 }
             }

             $header->update([
                'total'=>0,
                'pajak'=>0,
                'admin'=>0,
                'grand_total'=>0,
                'lunas'=>'C',
             ]);
             return response()->json([
                'success' => true,
                'message' => 'Invoice Berhasil di Cancel!',
            ]);
        }

       
    }

}
