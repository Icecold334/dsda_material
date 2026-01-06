<?php

namespace Database\Seeders;

use App\Models\Personnel;
use App\Models\Sudin;
use Illuminate\Database\Seeder;

class PersonnelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sudins = Sudin::all();

        foreach ($sudins as $sudin) {
            // Create 2-3 driver per sudin
            Personnel::create([
                'name' => 'Driver ' . $sudin->short . ' 1',
                'sudin_id' => $sudin->id,
                'type' => 'driver',
            ]);

            Personnel::create([
                'name' => 'Driver ' . $sudin->short . ' 2',
                'sudin_id' => $sudin->id,
                'type' => 'driver',
            ]);
        }
    }
}
