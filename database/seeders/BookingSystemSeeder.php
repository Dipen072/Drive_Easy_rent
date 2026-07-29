<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\ExtraService;
use App\Models\Location;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BookingSystemSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Locations — 10 Major City Branches
        $locationsList = [
            ['name' => 'Mumbai Airport', 'city' => 'Mumbai', 'address' => 'Chhatrapati Shivaji Maharaj International Airport, Santacruz East, Mumbai - 400099', 'phone' => '+91 22 6685 1000', 'status' => 'Active'],
            ['name' => 'Delhi IGI Airport', 'city' => 'Delhi', 'address' => 'Indira Gandhi International Airport, New Delhi - 110037', 'phone' => '+91 11 2567 5000', 'status' => 'Active'],
            ['name' => 'Bangalore Koramangala', 'city' => 'Bangalore', 'address' => '12th Cross, Koramangala 4th Block, Bengaluru - 560034', 'phone' => '+91 80 4123 7800', 'status' => 'Active'],
            ['name' => 'Hyderabad Hitech City', 'city' => 'Hyderabad', 'address' => 'HUDA Techno Enclave, Hitech City, Hyderabad - 500081', 'phone' => '+91 40 6770 1234', 'status' => 'Active'],
            ['name' => 'Pune Station', 'city' => 'Pune', 'address' => 'Near Pune Railway Station, Shivajinagar, Pune - 411005', 'phone' => '+91 20 2553 8800', 'status' => 'Active'],
            ['name' => 'Chennai Anna Salai', 'city' => 'Chennai', 'address' => '45 Anna Salai, Thousand Lights, Chennai - 600006', 'phone' => '+91 44 2811 2200', 'status' => 'Active'],
            ['name' => 'Kolkata Park Street', 'city' => 'Kolkata', 'address' => '18 Park Street, Kolkata - 700016', 'phone' => '+91 33 2229 4400', 'status' => 'Active'],
            ['name' => 'Goa Airport Branch', 'city' => 'Goa', 'address' => 'Dabolim International Airport, South Goa - 403801', 'phone' => '+91 832 254 0800', 'status' => 'Active'],
            ['name' => 'Jaipur City Center', 'city' => 'Jaipur', 'address' => 'C-Scheme, Ashok Marg, Jaipur - 302001', 'phone' => '+91 141 222 7700', 'status' => 'Active'],
            ['name' => 'Ahmedabad SG Highway', 'city' => 'Ahmedabad', 'address' => 'SG Highway, Satellite, Ahmedabad - 380015', 'phone' => '+91 79 2646 5500', 'status' => 'Active'],
        ];

        foreach ($locationsList as $locData) {
            Location::firstOrCreate(
                ['name' => $locData['name']],
                $locData
            );
        }

        // 2. Extra Services
        ExtraService::firstOrCreate(
            ['name' => 'Full Comprehensive Insurance'],
            ['description' => 'Zero excess coverage for accidental damages & theft protection', 'price_per_day' => 500.00, 'icon_class' => 'fas fa-shield-halved', 'status' => 'Active']
        );
        ExtraService::firstOrCreate(
            ['name' => 'Child Safety Seat'],
            ['description' => 'Suitable for infants and toddlers up to 4 years', 'price_per_day' => 300.00, 'icon_class' => 'fas fa-baby-carriage', 'status' => 'Active']
        );
        ExtraService::firstOrCreate(
            ['name' => 'Additional Driver Permission'],
            ['description' => 'Allow second driver to legally operate the vehicle', 'price_per_day' => 400.00, 'icon_class' => 'fas fa-user-plus', 'status' => 'Active']
        );
        ExtraService::firstOrCreate(
            ['name' => 'Portable Wi-Fi Hotspot'],
            ['description' => 'High-speed 5G data coverage for up to 5 devices', 'price_per_day' => 250.00, 'icon_class' => 'fas fa-wifi', 'status' => 'Active']
        );

        // 3. Coupons
        Coupon::firstOrCreate(
            ['code' => 'FIRSTRIDE'],
            [
                'discount_type'          => 'percentage',
                'discount_value'         => 10.00,
                'minimum_booking_amount' => 1000.00,
                'maximum_discount'       => 1500.00,
                'start_date'             => now()->subDays(10)->format('Y-m-d'),
                'end_date'               => now()->addYear()->format('Y-m-d'),
                'usage_limit'            => 500,
                'status'                 => 'Active',
            ]
        );
        Coupon::firstOrCreate(
            ['code' => 'SUMMER500'],
            [
                'discount_type'          => 'fixed',
                'discount_value'         => 500.00,
                'minimum_booking_amount' => 2000.00,
                'maximum_discount'       => 500.00,
                'start_date'             => now()->subDays(10)->format('Y-m-d'),
                'end_date'               => now()->addYear()->format('Y-m-d'),
                'usage_limit'            => 200,
                'status'                 => 'Active',
            ]
        );

        // 4. Sample Category & Car if none exist
        if (Category::count() === 0) {
            $cat = Category::create(['category_name' => 'SUV Premium', 'status' => 'Active']);
        } else {
            $cat = Category::first();
        }

        if (Car::count() === 0) {
            Car::create([
                'brand_name'   => 'Mahindra',
                'model_name'   => 'Thar Roxx 4x4',
                'year'         => 2025,
                'category_id'  => $cat->id,
                'rate_per_day' => 3500.00,
                'location'     => 'Mumbai Airport',
                'seats'        => 5,
                'fuel_type'    => 'Diesel',
                'transmission' => 'Automatic',
                'image'        => 'upload/cars/thar.webp',
                'status'       => 'Available',
            ]);
        }

        // 5. Sample Customer if none exist
        if (Customer::count() === 0) {
            Customer::create([
                'first_name' => 'Arjun',
                'last_name'  => 'Sharma',
                'email'      => 'arjun@example.com',
                'phone'      => '+91 98765 43210',
                'password'   => Hash::make('123456'),
                'address'    => '102 Sunshines Heights, Bandra',
                'city'       => 'Mumbai',
                'state'      => 'Maharashtra',
                'zip_code'   => '400050',
                'has_dl'     => true,
                'dl_number'  => 'MH-0120230012345',
                'status'     => 'Active',
            ]);
        }
    }
}
