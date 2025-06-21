<?php

namespace Database\Seeders;

use App\Models\Outlet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OutletsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $outlets = [
            ['name' => 'Main Branch', 'location' => 'Dhaka'],
            ['name' => 'Second Branch', 'location' => 'Chittagong'],
            ['name' => 'Third Branch', 'location' => 'Sylhet'],
        ];

        foreach ($outlets as $outlet) {
            Outlet::create($outlet);
        }

    }
}
