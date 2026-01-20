<?php

namespace Database\Seeders;

use App\Models\Division;
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

        Sudin::all()->each(function ($sudin) use ($kecamatanNames) {
            // Buat 3-5 kecamatan per Sudin
            $count = rand(3, 5);
            $selected = collect($kecamatanNames)->random($count);

            foreach ($selected as $index => $name) {
                Division::create([
                    'sudin_id' => $sudin->id,
                    'name' => $name,
                    'type' => 'district',  // kecamatan
                ]);
            }
        });

        $this->command->info('Districts (Kecamatan) seeded to divisions table successfully!');
    }
}
