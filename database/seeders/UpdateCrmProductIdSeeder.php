<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateCrmProductIdSeeder extends Seeder
{
    public function run(): void
    {
        // Update CRM Product IDs for existing products
        DB::table('products')->where('id', 1)->update(['crm_product_id' => 156]);
        DB::table('products')->where('id', 2)->update(['crm_product_id' => 157]);
        DB::table('products')->where('id', 3)->update(['crm_product_id' => 158]);
        DB::table('products')->where('id', 4)->update(['crm_product_id' => 159]);
    }
}

