<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $products = [
            ['name' => 'Laptop', 'description' => 'High performance laptop', 'price' => 1200, 'stock' => 50],
            ['name' => 'Smartphone', 'description' => 'Latest smartphone', 'price' => 800, 'stock' => 100],
            ['name' => 'Headphones', 'description' => 'Noise cancelling headphones', 'price' => 150, 'stock' => 200],
            ['name' => 'Mouse', 'description' => 'Wireless mouse', 'price' => 30, 'stock' => 150],
            ['name' => 'Keyboard', 'description' => 'Mechanical keyboard', 'price' => 100, 'stock' => 80],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

    }
}
