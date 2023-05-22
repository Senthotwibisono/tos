<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Auth;
use App\Models\User;
use App\Models\Role;

use Illuminate\Http\Request;

class SystemController extends Controller
{
    public function user()
    {
        $users = User::all();
        return view('system.user.main', compact('users'));
    }

    public function role()
    {
        $roles = Role::all();
        return view('system.role.main', compact('roles'));
    }

    public function createrole()
    {
        return view('system.role.create');
    }

    public function rolestore(Request $request)
    {
        // dd("masuk");
        // validasi form input
        $validatedData = $request->validate([
            'name' => 'required|min:2',
        ]);

        // simpan data ke database
        Role::create([
            'name' => $request->name,
            'guard_name' => $request->web,
        ]);
        // redirect ke halaman sukses
        return redirect('/system/role')->with('success', 'Data berhasil disimpan!');
    }

    public function edit_role($id)
    {
        $roles = Role::where('id', $id)->first();
        // dd($role);
        return view('system.role.edit', compact('roles'));
    }

    public function update_role(Request $request, $id)
    {
        Role::where('id', $id)->update([
            'name' => $request->name,
            'guard_name' => $request->guard_name,

        ]);
        return redirect('/system/role');
    }

    public function delete_role(string $id)
    {
        Role::destroy($id);
        return back();
    }

    public function create_user()
    {
        $roles = Role::all();
        return view('system.user.create', compact('roles'));
    }

    public function user_store(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role'  => $request->role,
        ]);

        $role = Role::findByName($request->role);
        $user->assignRole($role);

        return redirect('/system/user');
    }

    public function edit_user($id)
    {
        $users = User::where('id', $id)->first();
        $roles = Role::all();
        return view('system.user.edit', compact('users', 'roles'));
    }

    public function update_user(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email,' . $user->id . '|max:255',
            'role' => 'required|exists:roles,name'
        ]);

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->password);
        $user->save();

        $role = Role::where('name', $request->input('role'))->first();
        $user->syncRoles([$role->id]);

        return redirect('/system/user');
    }

    public function delete_user($id)
    {
        User::destroy($id);
        return back();
    }
}
