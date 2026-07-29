<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingExtra extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'extra_service_id',
        'quantity',
        'price',
        'total',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function extraService()
    {
        return $this->belongsTo(ExtraService::class, 'extra_service_id');
    }
}
