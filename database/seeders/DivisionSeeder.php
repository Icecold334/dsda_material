<?php

namespace Database\Seeders;

use App\Models\Sudin;
use Illuminate\Database\Seeder;
use App\Models\Division;

class DivisionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Sudin::all() as $sudin) {
            $sudinId = $sudin->id; // sementara satu sudin dulu

            $divisions = [
                // SEKSI
                ['name' => 'Pemeliharaan', 'type' => 'seksi'],
                ['name' => 'Pembangunan', 'type' => 'seksi'],

                // SUB BAGIAN
                ['name' => 'Tata Usaha', 'type' => 'subbag'],
                ['name' => 'Kepegawaian', 'type' => 'subbag'],

            ];

            foreach ($divisions as $d) {
                Division::firstOrCreate(
                    [
                        'name' => $d['name'],
                        'sudin_id' => $sudinId,
                    ],
                    [
                        'type' => $d['type'],
                    ]
                );
            }
        }
    }
}
