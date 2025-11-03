<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'nama' => 'Ganti LCD',
                'deskripsi' => 'Penggantian layar LCD rusak atau pecah.',
            ],
            [
                'nama' => 'Ganti Baterai',
                'deskripsi' => 'Mengganti baterai yang sudah drop atau rusak.',
            ],
            [
                'nama' => 'Ganti Kamera Belakang',
                'deskripsi' => 'Perbaikan modul kamera belakang.',
            ],
            [
                'nama' => 'Ganti Speaker',
                'deskripsi' => 'Perbaikan atau penggantian speaker rusak.',
            ],
            [
                'nama' => 'Ganti Port Charger',
                'deskripsi' => 'Perbaikan konektor charger yang longgar atau tidak berfungsi.',
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
