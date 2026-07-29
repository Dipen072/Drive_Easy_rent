<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'city',
        'address',
        'phone',
        'status',
    ];

    public function pickupBookings()
    {
        return $this->hasMany(Booking::class, 'pickup_location_id');
    }

    public function dropoffBookings()
    {
        return $this->hasMany(Booking::class, 'dropoff_location_id');
    }
}
