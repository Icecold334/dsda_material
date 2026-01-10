<?php

namespace Database\Seeders;

use App\Models\Rab;
use App\Models\Sudin;
use App\Models\District;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RabSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan sudah ada Sudin dan User
        if (Sudin::count() === 0) {
            $this->command->warn('Sudin kosong, skip RabSeeder');
            return;
        }

        if (User::count() === 0) {
            $this->command->warn('User kosong, skip RabSeeder');
            return;
        }

        $statuses = ['draft', 'approved', 'rejected'];

        Sudin::all()->each(function ($sudin) use ($statuses) {

            // Ambil user dari sudin
            $users = User::where('sudin_id', $sudin->id)->get();
            if ($users->isEmpty()) {
                $users = User::all();
            }

            // Ambil district dari sudin
            $districts = District::where('sudin_id', $sudin->id)->get();

            // 10 RAB per Sudin
            for ($i = 1; $i <= 10; $i++) {
                $district = $districts->random();
                $subdistrict = $district->subdistricts->count() > 0
                    ? $district->subdistricts->random()
                    : null;

                $startDate = now()->subDays(rand(0, 365));
                $endDate = (clone $startDate)->addDays(rand(30, 180));

                Rab::create([
                    'sudin_id' => $sudin->id,
                    'nomor' => 'RAB-' . date('Y') . '-' . strtoupper(Str::random(5)),
                    'tahun' => now()->year,
                    'total' => rand(50_000_000, 500_000_000),
                    'status' => $statuses[array_rand($statuses)],
                    'name' => 'Proyek ' . fake()->words(3, true) . ' ' . $i,
                    'tanggal_mulai' => $startDate,
                    'tanggal_selesai' => $endDate,
                    'district_id' => $district->id,
                    'subdistrict_id' => $subdistrict?->id,
                    'address' => fake()->address(),
                    'user_id' => $users->random()->id,
                    'panjang' => rand(10, 100) . ' m',
                    'lebar' => rand(5, 50) . ' m',
                    'tinggi' => rand(3, 20) . ' m',
                ]);
            }
        });
    }
}
