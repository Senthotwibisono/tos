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
        $data['permission'] = Permission::whereNot('id', 21)->whereNotBetween('id', [36,51])->get();

        return view('system.user.assignedPermission', $data);
    }

    public function assignPermissionPost($id, Request $request)
    {
        $user = User::findOrFail($id);
        $requestedPermissions = collect($request->permissions ?? [])
        ->map(fn($id) => (int) $id)
        ->filter(fn($id) => $id < 36 || $id > 51);

        // Ambil permission ID saat ini, hanya yang <36 atau >51
        $currentPermissionIds = $user->permissions
            ->pluck('id')
            ->filter(fn($id) => $id < 36 || $id > 51);

        // Hitung selisih: yang perlu ditambah dan dicabut
        $toAdd = $requestedPermissions->diff($currentPermissionIds);
        $toRemove = $currentPermissionIds->diff($requestedPermissions);

        // Tambah permission baru
        if ($toAdd->isNotEmpty()) {
            $permissionsToAdd = Permission::whereIn('id', $toAdd)->get();
            $user->givePermissionTo($permissionsToAdd);
        }

        // Cabut permission yang tidak lagi dipilih
        if ($toRemove->isNotEmpty()) {
            $permissionsToRemove = Permission::whereIn('id', $toRemove)->get();
            $user->revokePermissionTo($permissionsToRemove);
        }
        return redirect()->back()->with('success', 'Permissions updated successfully!');
    }


    public function invoiceUserIndex()
    {
        $data['title'] = 'User Control & Management';
        $data['roles'] = Role::all();

        return view('billingSystem.system.user-index', $data);
    }

    public function invoiceUserData(Request $request)
    {
        $users = User::get();

        return DataTables::of($users)
        ->addColumn('profile', function($users){
            $imgSrc = $users->profile 
                ? asset('profil/' . $users->profile) 
            : asset('dist/assets/images/faces/1.jpg');
            
            $html = '
                <div class="round-image" id="uploaded_view" data-bs-toggle="modal" data-bs-target="#galleryModal-' . $users->id . '">
                    <img class="w-100 active" src="' . $imgSrc . '" data-bs-target="#Gallerycarousel" data-bs-slide-to="0">
                </div>
            ';

            return $html;
        })
        ->addColumn('roles', function($users){
            return $users->roles->implode('name', ', ');
        })
        ->addColumn('edit', function($users) {
            return '<button type="button" data-id="'.$users->id.'" class="btn btn-warning" onClick="editUser(this)"><i class="fas fa-pencil"></i></button>';
        })
        ->addColumn('permission', function($users){
            $url = route('invoiceService.system.assignIndex', ['id' => $users->id]);
            return '<a href="' . $url . '" class="btn btn-info">Assigned User</a>';
        })
        ->rawColumns(['profile', 'edit', 'permission'])
        ->make(true);
    }

    public function invoiceUserEdit(Request $request)
    {
        $user = User::with('roles')->findOrFail($request->id);
        // var_dump($user);
        if ($user) {
            return response()->json([
                'success' => true,
                'data' => $user,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ]);
        }
    }

    public function invoiceUserPost(Request $request)
    {
        $this->validate($request, [
            'data.name' => 'required|string|max:255',
            'data.role' => 'required|exists:roles,name'
        ]);

        try {
           
            if ($request->data['id'] != null) {
                $user = User::findOrFail($request->data['id']);
                $this->validate($request, [
                    'data.email' => 'required|string|email|unique:users,email,' . $user->id . '|max:255',
                ]);
                $password = ($request->data['password'] != null) ? Hash::make($request->data['password']) : $user->password;
                $user->update([
                    'name' => $request->data['name'],
                    'email' => $request->data['email'],
                    'password' => $password,
                ]);
                $role = Role::where('name', $request->data['role'])->first();
                $user->syncRoles([$role->id]);
                return response()->json([
                    'success' => true,
                    'message' => 'Data user ' . $user->name. ' berhasil diperbarui',
                ]);
            } else {
                $this->validate($request, [
                    'data.password' => 'required',
                    'data.email' => 'required|string|email|unique:users,email|max:255',
                ]);
                $user = User::create([
                    'name' => $request->data['name'],
                    'email' => $request->data['email'],
                    'password' => Hash::make($request->data['password']),
                ]);
                $role = Role::where('name', $request->data['role'])->first();
                $user->syncRoles([$role->id]);
                return response()->json([
                    'success' => true,
                    'message' => 'Data user ' . $user->name. ' berhasil disimpan',
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Opss something wrong in: ' . $th->getMessage(),
            ]);
        }
    }

    public function invoiceUserassignIndex($id)
    {
        $user = User::find($id);
        $data['users'] = $user;
        $data['title'] = 'Assigned Permission for ' . $user->name;
        $data['permission'] = Permission::where('id', '>=', 36)->get();

        return view('billingSystem.system.user-permission', $data);
    }

    public function invoiceUserassignPost($id, Request $request)
    {
        $user = User::findOrFail($id);
        $requestedPermissions = collect($request->permissions ?? [])
            ->filter(fn($id) => $id >= 36)
            ->map(fn($id) => (int) $id); // pastikan integer
        $currentPermissionIds = $user->permissions
            ->pluck('id')
            ->filter(fn($id) => $id >= 36);

        $toAdd = $requestedPermissions->diff($currentPermissionIds);
        $toRemove = $currentPermissionIds->diff($requestedPermissions);

        if ($toAdd->isNotEmpty()) {
            $permissionsToAdd = Permission::whereIn('id', $toAdd)->get();
            $user->givePermissionTo($permissionsToAdd);
        }

        if ($toRemove->isNotEmpty()) {
            $permissionsToRemove = Permission::whereIn('id', $toRemove)->get();
            $user->revokePermissionTo($permissionsToRemove);
        }

        return redirect()->back()->with('success', 'Permissions updated successfully!');
    }
}
