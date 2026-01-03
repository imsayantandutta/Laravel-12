<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn([
                'product_id',
                'order_id',
                'vrio_api_response',
                'telegra_patient_api_response',
                'telegra_patient_order_api_response',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('order_id')->nullable();
            $table->longText('vrio_api_response')->nullable();
            $table->longText('telegra_patient_api_response')->nullable();
            $table->longText('telegra_patient_order_api_response')->nullable();
        });
    }
};
