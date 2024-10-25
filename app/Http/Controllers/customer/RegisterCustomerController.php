<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Auth;
use App\Models\User;
use App\Models\Role;
use App\Models\MasterUserInvoice as MUI;

use Carbon;
use App\Models\Customer;

class RegisterCustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('role:android, lapangan');
    }

    public function index()
    {
        $data['title'] = "Customer Account";
        $data['custsAccount'] = User::role('customer')->get();

        return view('billingSystem.customer.register', $data);
    }

    public function createIndex()
    {
        $data['title'] = "Customer Account";
        $data['custs'] = Customer::all();
        return view('billingSystem.customer.registerCreate', $data);
    }

    public function createPost(Request $request)
    {
        try {
            // dd($request->customer_id);
            $custs = Customer::whereIn('id', $request->customer_id)->get();
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role'  => $request->role,
            ]);

            foreach ($custs as $cust) {
                $userInvoice = MUI::create([
                    'user_id' => $user->id,
                    'customer_id' => $cust->id
                ]);
            }

            $role = Role::findByName($request->role);
            $user->assignRole($role);
    
            return redirect('/invoice/customer/register')->with('success', 'Account berhasil di buat!');
        } catch (\Throwable $th) {
            return redirect('/invoice/customer/register')->with('error', 'Opps, Something Wrong'. $th->getMessage());
        }
    }

    public function editIndex($id)
    {
        $user = User::find($id);
        $userInvoice = MUI::where('user_id', $id)->get();
        $data['title'] = "Edit Account " . $user->name;

        $data['user'] = $user; 
        $data['custs'] = Customer::all();
        $data['userInvoiceCustomerIds'] = $userInvoice->pluck('customer_id')->toArray();
        // dd($data['userInvoices']);
        
        return view('billingSystem.customer.registerEdit', $data);
    }

    public function editPost(Request $request)
    {
        try {
            // dd($request->password);
            $user = User::find($request->id);
            $oldMUI = MUI::where('user_id', $user->id)->delete();
            
            if ($request->password == null) {
                $password = $user->password;
            }else {
                $password = Hash::make($request->password);
            }
            $custs = Customer::whereIn('id', $request->customer_id)->get();
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $password,
            ]);

            foreach ($custs as $cust) {
                $userInvoice = MUI::create([
                    'user_id' => $user->id,
                    'customer_id' => $cust->id
                ]);
            }
            return redirect('/invoice/customer/register')->with('success', 'Account berhasil di update!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Opps, Something Wrong'. $th->getMessage());
        }
    }

    // public function editPost(Request $request)
    // {
    //     try {
    //         $user = User::find($request->id);
    //         $user->update
    //     } catch (\Throwable $th) {
    //         throw $th;
    //     }
    // }
}
