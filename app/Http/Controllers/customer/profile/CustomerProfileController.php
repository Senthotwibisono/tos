<?php

namespace App\Http\Controllers\customer\profile;

use App\Http\Controllers\Controller;
use App\Http\Controllers\customer\CustomerMainController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class CustomerProfileController extends CustomerMainController
{
    public function index()
    {
        $user = User::find($this->userId);
        $data['title'] = 'Profile Account, ' . $user->name;
        $data['user'] = $user;

        return view('customer.profile', $data);
    }

    public function profile(Request $request)
{
    // Validation for the incoming request
   

    $path = 'profil';
    $user = User::find($this->userId); // Get the authenticated user

    // Check if password field is filled or keep the old one
    $password = $request->password ? Hash::make($request->password) : $user->password;

    // Check if there is a file for profile picture
    if ($request->hasFile('profil')) {
        $file = $request->file('profil');
        $new_name = 'UIMG_' . date('Ymd') . uniqid() . '.' . $file->getClientOriginalExtension();

        // Move the new profile picture to the public path
        $upload = $file->move(public_path($path), $new_name);

        if (!$upload) {
            return response()->json(['status' => 0, 'msg' => 'Something went wrong, upload new picture failed.']);
        }

        // Delete the old profile picture if it exists
        if ($user->profil && \File::exists(public_path($path . '/' . $user->profil))) {
            \File::delete(public_path($path . '/' . $user->profil));
        }

        // Update user with new profile picture
        $user->update([
            'profil' => $new_name,
            'name' => $request->name,
            'email' => $request->email,
            'password' => $password,
        ]);

        return response()->json(['status' => 1, 'msg' => 'Your profile picture has been updated successfully']);
    }

    // If no new profile picture, update other data
    $user->update([
        'name' => $request->name,
        'email' => $request->email,
        'password' => $password,
    ]);

    return response()->json(['status' => 1, 'msg' => 'User data has been updated successfully']);
}

}
