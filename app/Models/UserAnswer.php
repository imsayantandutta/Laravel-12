<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAnswer extends Model
{
    protected $fillable = ['customer_id', 'product_id', 'question_id', 'answer_id'];
}
