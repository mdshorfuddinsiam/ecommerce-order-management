<?php

namespace Database\Seeders;

use App\Models\Outlet;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        // Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => bcrypt('password'),
            'role' => 'super_admin',
        ]);

        // Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Outlet In Charges
        $outlets = Outlet::all();
        foreach ($outlets as $key => $outlet) {
            User::create([
                'name' => 'Outlet In Charge ' . ($key + 1),
                'email' => 'outlet'.($key+1).'@example.com',
                'password' => bcrypt('password'),
                'role' => 'outlet',
                'outlet_id' => $outlet->id,
            ]);
        }

    }
}
