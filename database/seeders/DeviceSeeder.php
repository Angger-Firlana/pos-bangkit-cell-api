<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Device;

class DeviceSeeder extends Seeder
{
    public function run(): void
    {
        $devices = [
            [
                'merek' => 'Apple',
                'model' => 'iPhone 15 Pro',
                'tipe' => 'Pro',
            ],
            [
                'merek' => 'Apple',
                'model' => 'iPhone 14',
                'tipe' => 'Standard',
            ],
            [
                'merek' => 'Samsung',
                'model' => 'Galaxy S23',
                'tipe' => 'Ultra',
            ],
            [
                'merek' => 'Xiaomi',
                'model' => 'Redmi Note 13',
                'tipe' => 'Standard',
            ],
            [
                'merek' => 'Oppo',
                'model' => 'Reno 11 Pro',
                'tipe' => 'Pro',
            ],
        ];

        foreach ($devices as $device) {
            Device::create($device);
        }
    }
}
