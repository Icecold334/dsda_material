<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Sudin;
use App\Models\Division;
use App\Models\Position;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // SUPERADMIN
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@dsda.test',
            'password' => Hash::make('password'),
            'sudin_id' => null,
            'division_id' => null,
            'position_id' => Position::where('name', 'Super Admin')->first()->id,
        ])->assignRole('superadmin');



        // ADMIN SUDIN (untuk semua sudin)
        $adminSudinPosition = Position::where('name', 'Admin Sudin')->first()->id;

        foreach (Sudin::all() as $sudin) {
            User::create([
                'name' => 'Admin ' . $sudin->short,
                'email' => strtolower('admin_' . $sudin->short) . '@dsda.test',
                'password' => Hash::make('password'),
                'sudin_id' => $sudin->id,
                'division_id' => null,
                'position_id' => $adminSudinPosition,
            ])->assignRole('admin_sudin');


            $sudinId = $sudin->id;

            $users = [
                // KASATPEL (KECAMATAN)
                [
                    'name' => 'Kasatpel Tebet',
                    'email' => 'satpeltebet',
                    'position' => 'Kepala Satuan Pelaksana',
                    'division' => 'Tebet',
                ],
                [
                    'name' => 'Kasatpel Gambir',
                    'email' => 'satpelagambir',
                    'position' => 'Kepala Satuan Pelaksana',
                    'division' => 'Gambir',
                ],
                [
                    'name' => 'Kasatpel Senen',
                    'email' => 'satpelasenen',
                    'position' => 'Kepala Satuan Pelaksana',
                    'division' => 'Senen',
                ],

                // KASIE SEKSI
                [
                    'name' => 'Kasie Pemeliharaan',
                    'email' => 'kasie@test.id',
                    'position' => 'Kepala Seksi',
                    'division' => 'Pemeliharaan',
                ],

                // KASUBAG TU
                [
                    'name' => 'Kasubag Tata Usaha',
                    'email' => 'kasubag.tu@test.id',
                    'position' => 'Kepala Sub Bagian',
                    'division' => 'Tata Usaha',
                ],

                // KASUDIN
                [
                    'name' => 'Kasudin',
                    'email' => 'kasudin@test.id',
                    'position' => 'Kepala Suku Dinas',
                    'division' => null,
                ],

                // PENGURUS BARANG
                [
                    'name' => 'Pengurus Barang',
                    'email' => 'pb@test.id',
                    'position' => 'Pengurus Barang',
                    'division' => null,
                ],
            ];

            foreach ($users as $u) {

                $position = Position::where('slug', Str::slug($u['position']))->first();
                $division = $u['division']
                    ? Division::where('name', $u['division'])->first()
                    : null;

                $name = '';

                if ($position && $division) {
                    $name = $position->name . ' ' . $division->name . ' ' . explode(' ', $sudin->name)[1];
                } else {
                    $name = $u['name'] . ' ' . explode(' ', $sudin->name)[1];
                }
                User::updateOrCreate(
                    ['email' => Str::slug($u['position']) . Str::slug($u['division']) . Str::slug($sudin->name) . '@test.com'],
                    [
                        'name' => $name,
                        'password' => Hash::make('password'),
                        'position_id' => $position->id,
                        'division_id' => $division?->id,
                        'sudin_id' => $sudinId,
                    ]
                );
            }

        }
    }
}
