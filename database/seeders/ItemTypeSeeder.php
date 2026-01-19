<?php

namespace Database\Seeders;

use App\Models\ItemType;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ItemTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $itemTypes = [
            'Alat Tulis Kantor',
            'Elektronik',
            'Furniture',
            'Peralatan Kebersihan',
            'Peralatan Medis',
            'Bahan Bangunan',
            'Kendaraan',
            'Komputer dan Aksesoris',
            'Peralatan Komunikasi',
            'Pakaian Dinas',
        ];

        foreach ($itemTypes as $type) {
            ItemType::firstOrCreate(
                [
                    'slug' => Str::slug($type),
                ],
                [
                    'name' => $type,
                    'active' => true,
                ]
            );
        }
    }
}
