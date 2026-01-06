<?php

namespace Database\Seeders;

use App\Models\Sudin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Driver;
class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sudins = Sudin::all();

        foreach ($sudins as $sudin) {
            // Create 2-3 driver entries per sudin
            Driver::create([
                'name' => 'Driver ' . $sudin->short . ' 1',
                'sudin_id' => $sudin->id,
            ]);

            Driver::create([
                'name' => 'Driver ' . $sudin->short . ' 2',
                'sudin_id' => $sudin->id,
            ]);
        }
    }
}
