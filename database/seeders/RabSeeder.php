<?php

namespace Database\Seeders;

use App\Models\Rab;
use App\Models\Sudin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RabSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan sudah ada Sudin
        if (Sudin::count() === 0) {
            $this->command->warn('Sudin kosong, skip RabSeeder');
            return;
        }

        $statuses = ['draft', 'approved', 'rejected'];

        Sudin::all()->each(function ($sudin) use ($statuses) {

            // 3 RAB per Sudin
            for ($i = 1; $i <= 10; $i++) {

                Rab::create([
                    'sudin_id' => $sudin->id,
                    'nomor' => 'RAB-' . date('Y') . '-' . strtoupper(Str::random(5)),
                    'tahun' => now()->year,
                    'total' => rand(50_000_000, 500_000_000),
                    'status' => $statuses[array_rand($statuses)],
                ]);
            }
        });
    }
}
