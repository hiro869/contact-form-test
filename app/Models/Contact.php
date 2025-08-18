<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id','first_name','last_name','gender',
        'email','tel','address','detail',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

