<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class AddNewProductSeeder extends Seeder
{
    public function run(): void
    {
        // Check if product already exists
        if (!Product::where('name', 'Vitamin C 500mg')->exists()) {
            Product::create([
                'name' => 'Vitamin C 500mg',
                'image' => 'vitamin_c.jpg',
                'price' => 60.00,
            ]);
        }
    }
}
