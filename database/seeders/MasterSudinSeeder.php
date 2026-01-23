<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sudin;

class MasterSudinSeeder extends Seeder
{
    public function run(): void
    {
        $list = [
            ['name' => 'Jakarta Barat', 'short' => 'JAKBAR'],
            ['name' => 'Jakarta Timur', 'short' => 'JAKTIM'],
            ['name' => 'Jakarta Selatan', 'short' => 'JAKSEL'],
            ['name' => 'Jakarta Pusat', 'short' => 'JAKPUS'],
            ['name' => 'Jakarta Utara', 'short' => 'JAKUT'],
            ['name' => 'Kepulauan Seribu', 'short' => 'KEPSER'],
        ];

        foreach ($list as $s) {
            Sudin::create($s);
        }
    }
}
