<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileControllers extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $user = Auth::user();
        
        return view('user.main', compact('user'));
    }

    function profil(Request $request)
    {
        $path = 'profil';
        $file = $request->file('profil');
    
        // Periksa apakah ada file yang diunggah
        if ($file) {
            $new_name = 'UIMG_' . date('Ymd') . uniqid() . $file->getClientOriginalName();
    
            // Unggah gambar profil yang baru
            $upload = $file->move(public_path($path), $new_name);
    
            if (!$upload) {
                return response()->json(['status' => 0, 'msg' => 'Something went wrong, upload new picture failed.']);
            }
    
            // Dapatkan gambar profil lama
            $oldPicture = Auth::user()->profil;
    
            if ($oldPicture) {
                // Hapus gambar profil lama
                if (\File::exists(public_path($path . '/' . $oldPicture))) {
                    \File::delete(public_path($path . '/' . $oldPicture));
                }
            }
    
            // Perbarui profil pengguna di database
            $update = Auth::user()->update([
                'profil' => $new_name,
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password'])
            ]);
    
            if (!$update) {
                return response()->json(['status' => 0, 'msg' => 'Something went wrong, updating picture in db failed.']);
            }
    
            return response()->json(['status' => 1, 'msg' => 'Your profile picture has been updated successfully']);
        }
    
        // Jika tidak ada file yang diunggah, perbarui data pengguna kecuali gambar profil
        $update = Auth::user()->update([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password'])
        ]);
    
        if (!$update) {
            return response()->json(['status' => 0, 'msg' => 'Something went wrong, updating user data failed.']);
        }
    
        return response()->json(['status' => 1, 'msg' => 'User data has been updated successfully']);
    }
    
    
    

}
