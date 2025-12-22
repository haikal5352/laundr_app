<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // User Admin
        User::updateOrCreate(
            ['email' => 'admin@chingu.com'],
            [
                'name' => 'Admin Chingu',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '08123456789',
                'address' => 'Chingu HQ'
            ]
        );

        // Clear existing services to avoid duplicates during dev
        // Service::truncate(); // Optional, but good for exact match. careful with foreign keys.

        $services = [
            ['name' => 'Daily Kiloan', 'price' => 7000, 'unit' => 'kg'],
            ['name' => 'Cuci & Setrika', 'price' => 10000, 'unit' => 'kg'],
            ['name' => 'Laundry Sepatu', 'price' => 35000, 'unit' => 'lsg'],
            ['name' => 'Laundry Tas', 'price' => 40000, 'unit' => 'lsg'],
            ['name' => 'Laundry Karpet', 'price' => 15000, 'unit' => 'm2'],
            ['name' => 'Laundry Gorden', 'price' => 12000, 'unit' => 'm2'],
            ['name' => 'Laundry Stroller', 'price' => 150000, 'unit' => 'unit'],
            ['name' => 'Laundry Boneka', 'price' => 20000, 'unit' => 'pcs'],
            ['name' => 'Laundry Bed Cover', 'price' => 35000, 'unit' => 'pcs'],
            ['name' => 'Laundry Sprei', 'price' => 15000, 'unit' => 'pcs'],
            ['name' => 'Laundry Sarung Bantal', 'price' => 5000, 'unit' => 'pcs'],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(['name' => $service['name']], $service);
        }
    }
}
