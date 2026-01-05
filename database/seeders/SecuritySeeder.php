<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Security;
use App\Models\Sudin;

class SecuritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sudins = Sudin::all();

        foreach ($sudins as $sudin) {
            // Create 2-3 security entries per sudin
            Security::create([
                'name' => 'Satpam ' . $sudin->short . ' 1',
                'sudin_id' => $sudin->id,
            ]);

            Security::create([
                'name' => 'Satpam ' . $sudin->short . ' 2',
                'sudin_id' => $sudin->id,
            ]);
        }
    }
}
