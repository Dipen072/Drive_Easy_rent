<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'icon', 'description'];

    public function cars()
    {
        return $this->hasMany(Car::class, 'category_id');
    }
}
