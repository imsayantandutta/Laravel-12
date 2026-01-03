<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'product_id',
        'order_id',
        'vrio_api_response',
        'telegra_patient_api_response',
        'telegra_patient_order_api_response',
    ];

    // Relationship to Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
