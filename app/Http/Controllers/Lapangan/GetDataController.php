<?php

namespace App\Http\Controllers\Lapangan;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;

use App\Models\VVoyage;
use App\Models\Item;
use App\Models\VMaster;
use App\Models\VSeq;
use App\Models\VService;
use App\Models\Berth;
use App\Models\Isocode;

use App\Models\JobImport;
use App\Models\JobExtend;
use App\Models\JobExport;

class GetDataController extends Controller
{
    

    public function getVessel(Request $request)
    {
        // var_dump($request->all());
        try {
            $vessel = VMaster::where('ves_code', $request->vesCode)->first();
            if ($vessel) {
                return response()->json([
                    'success' => true,
                    'data' => $vessel
                ]);
            }else {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage() 
            ]);
        }
    }

    public function getBerth(Request $request)
    {
        // var_dump($request->all());
        try {
            $berth = Berth::where('berth_no', $request->berthNo)->first();
            if ($berth) {
                return response()->json([
                    'success' => true,
                    'data' => $berth
                ]);
            }else {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage() 
            ]);
        }
    }

    public function getVoyage(Request $request)
    {
        $vessel = VVoyage::where('ves_id', $request->id)->first();
       
        $counterImport  = Item::where('ves_id', $vessel->ves_id)->where('ctr_i_e_t', 'I')->count();
        $counterExport  = Item::where('ves_id', $vessel->ves_id)->where('ctr_i_e_t', 'E')->count();

        return response()->json([
            'success' => true,
            'data' => [
                'import' => $counterImport,
                'export' => $counterExport,
                'vessel' => $vessel
            ]
            ]);
    }

    public function getContainer(Request $request)
    {
        $item = Item::where('container_key', $request->container_key)->first();
        if ($item) {
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                'data' => $item
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    public function getIso(Request $request)
    {
        $iso = Isocode::where('iso_code', $request->iso_code)->first();
        if ($iso) {
            return response()->json([
                'success' => true,
                'data' => $iso 
            ]);
        }else{
            return response()->jsom([
                'success'  => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    public function getJobImport(Request $request)
    {
        // var_dump($request->all());
        // die();
        $search = $request->search;
        $page = $request->page ?? 1;
        $perPage = 5;   

        if ($request->type == 'I') {
            $importQuery = JobImport::select('job_no', 'container_key')->distinct();    
    
            // Query untuk extend (distinct)
            $extendQuery = JobExtend::select('job_no', 'container_key')->distinct();    
    
            // Tambahkan pencarian jika ada
            if ($search) {
                $importQuery->where('job_no', 'like', "%{$search}%");
                $extendQuery->where('job_no', 'like', "%{$search}%");
            }   
    
            // Gabungkan query dengan unionAll
            $unionQuery = $importQuery->unionAll($extendQuery); 
        } else {
            $exportQuery = JobExport::select('job_no', 'container_key')->distinct();
            if ($search) {
                $exportQuery->where('job_no', 'like', "%{$search}%");
            }   
            $unionQuery = $exportQuery; 
        }
        // Query untuk import (distinct)

        // Bungkus union + distinct di luar
        $wrappedQuery = DB::query()
            ->fromSub($unionQuery, 'old_invoices')
            ->select('job_no', 'container_key')
            ->distinct();   

        // Ambil data + 1 untuk cek next page
            $totalCount = $wrappedQuery->count();
            $oldInvoice = $wrappedQuery->skip(($page - 1) * $perPage)
                ->take($perPage)
                ->get();        

            return response()->json([
                'data' => $oldInvoice,
                'more' => false,
            ]);
    }

    public function getContainerSelect(Request $request)
    {
        $search = $request->search;
        $page = $request->page ?? 1;
        $perPage = 5;

        if ($request->type == 'balikMt') {
            $subQuery = Item::selectRaw('MAX(container_key) as container_key, container_no')
                ->where('ctr_intern_status', '09')
                ->groupBy('container_no');
        }

        if ($request->type == 'pelindoImport') {
            $subQuery = Item::selectRaw('MAX(container_key) as container_key, container_no')
                ->whereIn('ctr_intern_status', ['01', '02', '03', '10'])->where('relokasi_flag', 'Y')
                ->groupBy('container_no');
        }

        if ($request->type == 'ambilMt') {
            $subQuery = Item::selectRaw('MAX(container_key) as container_key, container_no')
                ->whereIn('ctr_intern_status', ['49', '03', '04', '50', '51', '53', '54'])
                ->groupBy('container_no');
        }
        

        if ($search) {
            $subQuery->having('container_no', 'like', "%{$search}%");
        }

        $wrappedQuery = DB::table(DB::raw("({$subQuery->toSql()}) as latest"))
            ->mergeBindings($subQuery->getQuery()) // penting biar binding dari $subQuery ikut
            ->select('container_no', 'container_key');

        $totalCount = $wrappedQuery->count();

        $oldInvoice = $wrappedQuery
            ->orderBy('container_key', 'desc')
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        return response()->json([
            'data' => $oldInvoice,
            'more' => false // untuk select2 pagination
        ]);
    }



    public function getJobImportDetil(Request $request)
    {
        if ($request->type == 'I') {
            $job = JobImport::where('job_no', $request->jobNo)->first();    
    
            if (!$job) {
                $job = JobExtend::where('job_no', $request->jobNo)->first();
            }   
    
            // Jika job tidak ditemukan
            if (!$job) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }   
    
            // Cari item berdasarkan container_key
            $item = Item::where('container_key', $job->container_key)->first(); 
    
            // Jika status tidak sesuai
            if (!in_array($item->ctr_intern_status, ['04', '03', '09'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat dipilih, status container tidak untuk keluar'
                ]);
            }   
        } else {
            $job = JobExport::where('job_no', $request->jobNo)->first();
            if (!$job) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }   
    
            // Cari item berdasarkan container_key
            $item = Item::where('container_key', $job->container_key)->first(); 
    
            // Jika status tidak sesuai
            if (!in_array($item->ctr_intern_status, ['49'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat dipilih, status container tidak untuk masuk'
                ]);
            }   
        }
        // Cari job di JobImport atau JobExtend

        // Tentukan keterangan
        $keterangan = match ($item->ctr_intern_status) {
            '03' => 'Masuk ambil container',
            '04' => 'Stripping',
            '49' => 'Masuk antar container',
            default => '',
        };  

        return response()->json([
            'success' => true,
            'data' => $job,
            'item' => $item,
            'keterangan' => $keterangan
        ]);
    }

    public function getJobImportDetilOut(Request $request)
    {
        if ($request->type == 'I') {
            $job = JobImport::where('job_no', $request->jobNo)->first();    
    
            if (!$job) {
                $job = JobExtend::where('job_no', $request->jobNo)->first();
            }   
    
            // Jika job tidak ditemukan
            if (!$job) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }   
    
            // Cari item berdasarkan container_key
            $item = Item::where('container_key', $job->container_key)->first(); 
    
            // Jika status tidak sesuai
            if (!in_array($item->ctr_intern_status, ['04', '10'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat dipilih, status container tidak untuk keluar'
                ]);
            }   
        } else {
            $job = JobExport::where('job_no', $request->jobNo)->first();
            if (!$job) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }   
    
            // Cari item berdasarkan container_key
            $item = Item::where('container_key', $job->container_key)->first(); 
    
            // Jika status tidak sesuai
            if (!in_array($item->ctr_intern_status, ['50', '51', '53', '56', '49'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat dipilih, status container tidak untuk masuk'
                ]);
            }   
        }
        // Cari job di JobImport atau JobExtend

        // Tentukan keterangan
        $keterangan = match ($item->ctr_intern_status) {
            '10' => 'Container Keluar',
            '04' => 'Stripping',
            default => '',
        };  

        return response()->json([
            'success' => true,
            'data' => $job,
            'item' => $item,
            'keterangan' => $keterangan
        ]);
    }

}
