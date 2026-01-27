<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sudin;

class MasterSudinSeeder extends Seeder
{
    public function run(): void
    {
        $list = [
            ['name' => 'Jakarta Barat', 'short' => 'JAKBAR', 'postal_code' => '11220', 'phone' => '021-5835221'],
            ['name' => 'Jakarta Timur', 'short' => 'JAKTIM', 'postal_code' => '13330', 'phone' => '021-8197234'],
            ['name' => 'Jakarta Selatan', 'short' => 'JAKSEL', 'postal_code' => '12240', 'phone' => '021-7262045'],
            ['name' => 'Jakarta Pusat', 'short' => 'JAKPUS', 'postal_code' => '10710', 'phone' => '021-3456789'],
            ['name' => 'Jakarta Utara', 'short' => 'JAKUT', 'postal_code' => '14410', 'phone' => '021-4387654'],
            ['name' => 'Kepulauan Seribu', 'short' => 'KEPSER', 'postal_code' => '14550', 'phone' => '021-6872345'],
        ];

        foreach ($list as $s) {
            Sudin::create($s);
        }
    }
}
