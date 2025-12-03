<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Sudin;
use App\Models\Position;
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
        }
    }
}
