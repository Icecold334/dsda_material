<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\Subdistrict;
use Illuminate\Database\Seeder;

class SubdistrictSeeder extends Seeder
{
    public function run(): void
    {
        // Cari divisions dengan type 'district' (kecamatan)
        $districts = Division::where('type', 'district')->get();

        if ($districts->count() === 0) {
            $this->command->warn('Division (district) kosong, skip SubdistrictSeeder');
            return;
        }

        $kelurahanNames = [
            'Cempaka Putih Barat',
            'Cempaka Putih Timur',
            'Rawasari',
            'Menteng Dalam',
            'Gondangdia',
            'Kebon Sirih',
            'Pegangsaan',
            'Bendungan Hilir',
            'Karet Tengsin',
            'Petamburan',
            'Gelora',
            'Kebon Melati',
            'Kebon Kacang',
            'Kampung Bali',
            'Kramat',
            'Kenari',
            'Paseban',
            'Bungur',
        ];

        $districts->each(function ($district) use ($kelurahanNames) {
            // Buat 3-6 kelurahan per Kecamatan
            $count = rand(3, 6);
            $selected = collect($kelurahanNames)->random(min($count, count($kelurahanNames)));

            foreach ($selected as $index => $name) {
                Subdistrict::create([
                    'sudin_id' => $district->sudin_id,
                    'division_id' => $district->id,  // kecamatan ada di divisions
                    'name' => $name,
                ]);
            }
        });

        $this->command->info('Subdistricts (Kelurahan) seeded successfully!');
    }
}
