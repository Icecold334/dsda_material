<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sudin;

class MasterSudinSeeder extends Seeder
{
    public function run(): void
    {
        $list = [
            ['name' => 'Sudin Jakarta Barat', 'short' => 'JAKBAR'],
            ['name' => 'Sudin Jakarta Timur', 'short' => 'JAKTIM'],
            ['name' => 'Sudin Jakarta Selatan', 'short' => 'JAKSEL'],
            ['name' => 'Sudin Jakarta Pusat', 'short' => 'JAKPUS'],
            ['name' => 'Sudin Jakarta Utara', 'short' => 'JAKUT'],
            ['name' => 'Sudin Kepulauan Seribu', 'short' => 'KEPSER'],
        ];

        foreach ($list as $s) {
            Sudin::create($s);
        }
    }
}
