<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       
        $customers = Customer::all();      

        foreach ($customers as $customer) {
            // Cek jika user sudah ada berdasarkan email
            $user = User::where('email', $customer->email)->first();       

            if (!$user) {
                // Buat user baru
                $user = User::create([
                    'name' => $customer->name ?? $customer->code, // sesuaikan kolom name
                    'email' => $customer->email,
                    'password' => Hash::make('12345678'),
                ]);    

                // Tambahkan role dari Spatie (role id 13 = 'customer')
                $user->assignRole('customer'); // bisa juga pakai assignRole('customer')
            }      

            // Cek apakah sudah ada di master_user_invoice
            $exists = DB::table('master_user_invoice')->where([
                ['user_id', '=', $user->id],
                ['customer_id', '=', $customer->id],
            ])->exists();      

            if (!$exists) {
                DB::table('master_user_invoice')->insert([
                    'user_id' => $user->id,
                    'customer_id' => $customer->id,
                ]);
            }
        }      

        $this->info("User creation and assignment done.");

    }   
}
