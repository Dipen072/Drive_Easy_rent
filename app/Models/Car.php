<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = [
        'brand_name',
        'model_name',
        'year',
        'category_id',
        'rate_per_day',
        'location',
        'seats',
        'fuel_type',
        'transmission',
        'image',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
