<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Auth;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

use DataTables;

use Illuminate\Http\Request;

class SystemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function user()
    {
        $title = 'User View';
        $users = User::all();
        return view('system.user.main', compact('users', 'title'));
    }

    public function role()
    {
        $title = 'Role View';
        $roles = Role::all();
        return view('system.role.main', compact('roles', 'title'));
    }

    public function createrole()
    {
        $title = 'Add Role';
        return view('system.role.create', compact('title'));
    }

    public function rolestore(Request $request)
    {
        // Validate form input
        $validatedData = $request->validate([
            'name' => 'required|min:2',
        ]);

        // Check if the role already exists
        $existingRole = Role::where('name', $request->name)->first();

        if ($existingRole) {
            return redirect('/system/role')->with('error', 'Role already exists!');
        }

        // If the role doesn't exist, create and save it
        Role::create([
            'name' => $request->name,
            'guard_name' => $request->web,
        ]);

        // Redirect to the role view page with a success message
        return redirect('/system/role')->with('success', 'Data berhasil disimpan!');
    }

    public function edit_role($id)
    {
        $title = 'Edit Role';
        $roles = Role::where('id', $id)->first();
        // dd($role);
        return view('system.role.edit', compact('roles', 'title'));
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
        $title = 'Add User';
        $roles = Role::all();
        return view('system.user.create', compact('roles', 'title'));
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
        $title = 'Edit User';
        $users = User::where('id', $id)->first();
        $roles = Role::all();
        return view('system.user.edit', compact('users', 'roles', 'title'));
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

    public function indexPermisson()
    {
        $data['title'] = 'Permission Index';

        return view('system.permission.index', $data);
    }

    public function dataPermission(Request $request)
    {
        $perm = Permission::get();
        
        return DataTables::of($perm)
        ->make(true);
    }

    public function createPermisson(Request $request)
    {
        // var_dump($request->all());
        try {
            $permission = Permission::create($request->all());
            return response()->json([
                'success' => true,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function assignedIndex($id)
    {  
        $user = User::find($id);
        $data['users'] = $user;
        $data['title'] = 'Assigned Permission for ' . $user->name;
        $data['permission'] = Permission::get();

        return view('system.user.assignedPermission', $data);
    }
}
