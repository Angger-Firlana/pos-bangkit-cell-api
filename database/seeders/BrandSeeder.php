<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            ['nama' => 'Apple', 'negara_asal' => 'USA'],
            ['nama' => 'Samsung', 'negara_asal' => 'Korea Selatan'],
            ['nama' => 'Xiaomi', 'negara_asal' => 'China'],
            ['nama' => 'Oppo', 'negara_asal' => 'China'],
            ['nama' => 'Vivo', 'negara_asal' => 'China'],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }
    }
}
