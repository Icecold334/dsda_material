<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Sudin;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    public function run(): void
    {
        if (Sudin::count() === 0) {
            $this->command->warn('Sudin kosong, skip DistrictSeeder');
            return;
        }

        $kelurahanNames = [
            'Cempaka Putih',
            'Cempaka Putih Barat',
            'Cempaka Putih Timur',
            'Menteng',
            'Menteng Dalam',
            'Gondangdia',
            'Kebon Sirih',
            'Pegangsaan',
            'Tanah Abang',
            'Bendungan Hilir',
            'Karet Tengsin',
            'Petamburan',
            'Gelora',
            'Kebon Melati',
            'Kebon Kacang',
            'Kampung Bali',
            'Senen',
            'Kramat',
            'Kenari',
            'Paseban',
            'Bungur',
        ];

        Sudin::all()->each(function ($sudin) use ($kelurahanNames) {
            // Buat 5-8 kelurahan per Sudin
            $count = rand(5, 8);
            $selected = collect($kelurahanNames)->random($count);

            foreach ($selected as $index => $name) {
                District::create([
                    'sudin_id' => $sudin->id,
                    'name' => $name,
                ]);
            }
        });

        $this->command->info('Districts (Kelurahan) seeded successfully!');
    }
}
