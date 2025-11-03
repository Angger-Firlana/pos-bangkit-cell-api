<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Device;

class DeviceSeeder extends Seeder
{
    public function run(): void
    {
        $devices = [
            ['brand_id' => 1, 'model' => 'iPhone 15 Pro', 'tipe' => 'Pro'],
            ['brand_id' => 1, 'model' => 'iPhone 14', 'tipe' => 'Standard'],
            ['brand_id' => 2, 'model' => 'Galaxy S23', 'tipe' => 'Ultra'],
            ['brand_id' => 3, 'model' => 'Redmi Note 13', 'tipe' => 'Standard'],
            ['brand_id' => 4, 'model' => 'Reno 11 Pro', 'tipe' => 'Pro'],
        ];

        foreach ($devices as $device) {
            Device::create($device);
        }
    }
}
