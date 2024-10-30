<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\DOonline;
use Carbon\Carbon;
use Auth;

class uploadDO implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        // Inisialisasi array di luar loop
        // dd($rows);
        $containerNumbers = [];
    
        foreach ($rows as $row) {
            // Ambil nilai dari setiap kolom
            $containerNo = $row['container_no'];
            $doNo = $row['do_no'];
            $blNo = $row['bl_no'];
            $customerCode = $row['customer_code'];
            $active = 'Y';
            $doExpired = Carbon::createFromDate(1900, 1, 1)->addDays($row['do_expired'] - 2);
        
            // Cek apakah nomor DO sudah ada dalam array $containerNumbers
            if (!isset($containerNumbers[$doNo])) {
                // Jika belum, inisialisasi array kosong untuk nomor DO tersebut
                $containerNumbers[$doNo] = [];
            }
        
            // Cek apakah nomor DO sudah ada dalam database
            $doOnline = DoOnline::where('do_no', $doNo)->first();
        
            // Jika nomor DO belum ada, buat entri baru
            if (!$doOnline) {
                $doOnline = new DoOnline();
                $doOnline->do_no = $doNo;
                $doOnline->bl_no = $blNo;
                $doOnline->customer_code = $customerCode;
                $doOnline->expired = $doExpired;
                $doOnline->active = $active;
                $doOnline->created_at = Carbon::now();
                $doOnline->created_by = Auth::user()->name;
                $doOnline->save();
            }
        
            // Tambahkan nomor kontainer ke dalam array JSON pada entri DoOnline yang sesuai dengan nomor DO
            $containerNumbers[$doNo][] = $containerNo;
            $doOnline->container_no = $containerNumbers[$doNo];
        
            // Simpan data DoOnline
            $doOnline->save();
        }
    }

}
