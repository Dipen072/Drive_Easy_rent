<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LocalDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = __DIR__ . '/local_data.json';

        if (!file_exists($jsonPath)) {
            $this->command->error("local_data.json not found in seeders directory.");
            return;
        }

        $data = json_decode(file_get_contents($jsonPath), true);

        // Order of table insertion to prevent Foreign Key constraint issues
        $tableOrder = [
            'categories',
            'locations',
            'extra_services',
            'coupons',
            'customers',
            'admins',
            'cars',
            'contacts',
            'bookings',
            'booking_extras',
            'payments'
        ];

        foreach ($tableOrder as $table) {
            if (isset($data[$table]) && count($data[$table]) > 0) {
                $this->command->info("Seeding $table (" . count($data[$table]) . " records)...");
                foreach ($data[$table] as $row) {
                    DB::table($table)->updateOrInsert(['id' => $row['id']], $row);
                }
            }
        }

        // Reset primary key sequences for PostgreSQL so future auto-increments work properly
        if (DB::getDriverName() === 'pgsql') {
            foreach ($tableOrder as $table) {
                if (Schema::hasTable($table)) {
                    $maxId = DB::table($table)->max('id') ?? 0;
                    if ($maxId > 0) {
                        try {
                            DB::statement("SELECT setval(pg_get_serial_sequence('$table', 'id'), $maxId);");
                        } catch (\Exception $e) {
                            // ignore sequence error if not auto-incrementing
                        }
                    }
                }
            }
        }

        $this->command->info("All local XAMPP data successfully imported!");
    }
}
