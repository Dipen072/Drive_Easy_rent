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

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'car_id');
    }

    /**
     * Check if car is available for given dates
     */
    public function isAvailableForDates($pickupDate, $returnDate, $excludeBookingId = null): bool
    {
        if ($this->status !== 'Available') {
            return false;
        }

        $query = $this->bookings()
            ->where('booking_status', '!=', 'Cancelled')
            ->where(function ($q) use ($pickupDate, $returnDate) {
                $q->where(function ($sub) use ($pickupDate, $returnDate) {
                    $sub->where('pickup_date', '<=', $returnDate)
                        ->where('return_date', '>=', $pickupDate);
                });
            });

        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }

        return $query->count() === 0;
    }
}
