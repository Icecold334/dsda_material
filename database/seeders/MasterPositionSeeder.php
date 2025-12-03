<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Position;

class MasterPositionSeeder extends Seeder
{
    public function run(): void
    {
        $list = [
            'Super Admin',
            'Admin Provinsi',
            'Admin Sudin',

            'Kepala Suku Dinas',
            'Kepala Sub Bagian Umum',
            'Kepala Seksi',
            'PPK',
            'PPTK',
            'Pengurus Barang',
            'Staff',
        ];

        foreach ($list as $name) {
            Position::create(['name' => $name]);
        }
    }
}
