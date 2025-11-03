<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DeviceServiceVariant;

class DeviceServiceVariantSeeder extends Seeder
{
    public function run(): void
    {
        $variants = [
            [
                'device_id' => 1, // iPhone 15 Pro
                'service_id' => 1, // Ganti LCD
                'tipe_part' => 'Original',
                'harga_min' => 2500000,
                'harga_max' => 3500000,
            ],
            [
                'device_id' => 1,
                'service_id' => 1,
                'tipe_part' => 'OEM',
                'harga_min' => 1800000,
                'harga_max' => 2500000,
            ],
            [
                'device_id' => 2, // iPhone 14
                'service_id' => 2, // Ganti Baterai
                'tipe_part' => 'Original',
                'harga_min' => 800000,
                'harga_max' => 1000000,
            ],
            [
                'device_id' => 3, // Samsung S23
                'service_id' => 1, // Ganti LCD
                'tipe_part' => 'Original',
                'harga_min' => 2200000,
                'harga_max' => 3000000,
            ],
            [
                'device_id' => 4, // Redmi Note 13
                'service_id' => 4, // Ganti Speaker
                'tipe_part' => 'OEM',
                'harga_min' => 300000,
                'harga_max' => 500000,
            ],
        ];

        foreach ($variants as $v) {
            DeviceServiceVariant::create($v);
        }
    }
}
