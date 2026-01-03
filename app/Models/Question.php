<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['product_id', 'question_text'];

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}

