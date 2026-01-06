<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Warehouse;
use App\Models\Sudin;

class WarehouseSeeder extends Seeder
{
    public function run(): void
    {
        $sudins = Sudin::pluck('id');

        if ($sudins->isEmpty()) {
            $this->command->warn('Seeder Warehouse dilewati karena tabel sudins kosong.');
            return;
        }

        foreach ($sudins as $index => $sudinId) {
            // Setiap sudin punya 1–3 gudang
            $warehouseCount = rand(1, 3);

            for ($i = 1; $i <= $warehouseCount; $i++) {
                Warehouse::create([
                    'id' => (string) Str::uuid(),
                    'sudin_id' => $sudinId,
                    'name' => 'Gudang ' . fake()->city,
                    'location' => 'Lokasi Gudang ' . ($i),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
