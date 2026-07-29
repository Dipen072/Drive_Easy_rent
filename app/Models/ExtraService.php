<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraService extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price_per_day',
        'icon_class',
        'status',
    ];

    public function bookingExtras()
    {
        return $this->hasMany(BookingExtra::class, 'extra_service_id');
    }
}
