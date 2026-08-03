<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_number',
        'customer_id',
        'car_id',
        'pickup_location_id',
        'dropoff_location_id',
        'pickup_address',
        'pickup_lat',
        'pickup_lng',
        'dropoff_address',
        'dropoff_lat',
        'dropoff_lng',
        'pickup_date',
        'return_date',
        'pickup_time',
        'return_time',
        'rental_days',
        'base_price',
        'extras_amount',
        'discount_amount',
        'tax_amount',
        'total_amount',
        'coupon_id',
        'payment_status',
        'booking_status',
        'cancellation_reason',
        'cancelled_at',
        'confirmed_at',
        'completed_at',
    ];

    public function getPickupDisplayAddressAttribute(): string
    {
        if (!empty($this->pickup_address)) {
            return $this->pickup_address;
        }
        return $this->pickupLocation ? ($this->pickupLocation->name . ' (' . $this->pickupLocation->city . ')') : 'N/A';
    }

    public function getDropoffDisplayAddressAttribute(): string
    {
        if (!empty($this->dropoff_address)) {
            return $this->dropoff_address;
        }
        return $this->dropoffLocation ? ($this->dropoffLocation->name . ' (' . $this->dropoffLocation->city . ')') : 'N/A';
    }

    protected $casts = [
        'pickup_date'  => 'date',
        'return_date'  => 'date',
        'cancelled_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id');
    }

    public function pickupLocation()
    {
        return $this->belongsTo(Location::class, 'pickup_location_id');
    }

    public function dropoffLocation()
    {
        return $this->belongsTo(Location::class, 'dropoff_location_id');
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }

    public function bookingExtras()
    {
        return $this->hasMany(BookingExtra::class, 'booking_id');
    }

    public function extraServices()
    {
        return $this->belongsToMany(ExtraService::class, 'booking_extras', 'booking_id', 'extra_service_id')
                    ->withPivot('quantity', 'price', 'total')
                    ->withTimestamps();
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'booking_id');
    }
}
