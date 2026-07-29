<?php

namespace Database\Seeders;

use App\Models\Admin;
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
        // 0. Ensure Admin Account Exists
        Admin::firstOrCreate(
            ['email' => 'admin@driveease.com'],
            [
                'name'     => 'System Admin',
                'password' => Hash::make('admin123'),
                'role'     => 'superadmin',
            ]
        );

        // 1. Locations — Major City Branches
        $locationsList = [
            ['name' => 'Mumbai Airport', 'city' => 'Mumbai', 'address' => 'Chhatrapati Shivaji Maharaj International Airport, Santacruz East, Mumbai - 400099', 'phone' => '+91 22 6685 1000', 'status' => 'Active'],
            ['name' => 'Delhi IGI Airport', 'city' => 'Delhi', 'address' => 'Indira Gandhi International Airport, New Delhi - 110037', 'phone' => '+91 11 2567 5000', 'status' => 'Active'],
            ['name' => 'Bangalore Koramangala', 'city' => 'Bangalore', 'address' => '12th Cross, Koramangala 4th Block, Bengaluru - 560034', 'phone' => '+91 80 4123 7800', 'status' => 'Active'],
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

        // 4. Categories & Cars
        $catSUV     = Category::firstOrCreate(['category_name' => 'SUV Premium'], ['status' => 'Active']);
        $catSedan   = Category::firstOrCreate(['category_name' => 'Sedan Luxury'], ['status' => 'Active']);
        $catHatch   = Category::firstOrCreate(['category_name' => 'Hatchback Compact'], ['status' => 'Active']);
        $catEV      = Category::firstOrCreate(['category_name' => 'EV Electric'], ['status' => 'Active']);

        $carsList = [
            [
                'brand_name'   => 'Mahindra',
                'model_name'   => 'Thar Roxx 4x4',
                'year'         => 2025,
                'category_id'  => $catSUV->id,
                'rate_per_day' => 3500.00,
                'location'     => 'Mumbai Airport',
                'seats'        => 5,
                'fuel_type'    => 'Diesel',
                'transmission' => 'Automatic',
                'image'        => 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?w=800&q=80',
                'status'       => 'Available',
            ],
            [
                'brand_name'   => 'Toyota',
                'model_name'   => 'Fortuner Legender',
                'year'         => 2024,
                'category_id'  => $catSUV->id,
                'rate_per_day' => 4500.00,
                'location'     => 'Delhi IGI Airport',
                'seats'        => 7,
                'fuel_type'    => 'Diesel',
                'transmission' => 'Automatic',
                'image'        => 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?w=800&q=80',
                'status'       => 'Available',
            ],
            [
                'brand_name'   => 'Honda',
                'model_name'   => 'City V-Tec',
                'year'         => 2024,
                'category_id'  => $catSedan->id,
                'rate_per_day' => 2200.00,
                'location'     => 'Ahmedabad SG Highway',
                'seats'        => 5,
                'fuel_type'    => 'Petrol',
                'transmission' => 'Manual',
                'image'        => 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=800&q=80',
                'status'       => 'Available',
            ],
            [
                'brand_name'   => 'Tata',
                'model_name'   => 'Nexon EV Max',
                'year'         => 2025,
                'category_id'  => $catEV->id,
                'rate_per_day' => 2800.00,
                'location'     => 'Bangalore Koramangala',
                'seats'        => 5,
                'fuel_type'    => 'Electric',
                'transmission' => 'Automatic',
                'image'        => 'https://images.unsplash.com/photo-1563720223185-11003d516935?w=800&q=80',
                'status'       => 'Available',
            ],
            [
                'brand_name'   => 'Hyundai',
                'model_name'   => 'i20 N-Line',
                'year'         => 2024,
                'category_id'  => $catHatch->id,
                'rate_per_day' => 1800.00,
                'location'     => 'Mumbai Airport',
                'seats'        => 5,
                'fuel_type'    => 'Petrol',
                'transmission' => 'Automatic',
                'image'        => 'https://images.unsplash.com/photo-1541899481282-d53bffe3c35d?w=800&q=80',
                'status'       => 'Available',
            ],
        ];

        foreach ($carsList as $carData) {
            Car::firstOrCreate(
                ['brand_name' => $carData['brand_name'], 'model_name' => $carData['model_name']],
                $carData
            );
        }
    }
}
