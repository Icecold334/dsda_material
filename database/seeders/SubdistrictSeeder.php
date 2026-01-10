<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Subdistrict;
use Illuminate\Database\Seeder;

class SubdistrictSeeder extends Seeder
{
    public function run(): void
    {
        if (District::count() === 0) {
            $this->command->warn('District kosong, skip SubdistrictSeeder');
            return;
        }

        $kecamatanNames = [
            'Cempaka Putih',
            'Menteng',
            'Tanah Abang',
            'Senen',
            'Johar Baru',
            'Kemayoran',
            'Sawah Besar',
            'Gambir',
        ];

        District::all()->each(function ($district) use ($kecamatanNames) {
            // Buat 2-4 kecamatan per Kelurahan
            $count = rand(2, 4);
            $selected = collect($kecamatanNames)->random(min($count, count($kecamatanNames)));

            foreach ($selected as $index => $name) {
                Subdistrict::create([
                    'sudin_id' => $district->sudin_id,
                    'district_id' => $district->id,
                    'name' => $name . ' ' . ($index + 1),
                ]);
            }
        });

        $this->command->info('Subdistricts (Kecamatan) seeded successfully!');
    }
}
