<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\RequestModel;
use App\Models\RequestItem;
use App\Models\Sudin;
use App\Models\Unit;
use App\Models\User;
use App\Models\Personnel;
use App\Models\Item;
use App\Models\Rab;

class RequestSeeder extends Seeder
{
    public function run(): void
    {
        $sudin = Sudin::first();
        $unit = Unit::first();
        $user = User::first();
        $rab = Rab::all()->random();
        $driver = Personnel::where('type', 'driver')->first();
        $security = Personnel::where('type', 'security')->first();
        $items = Item::all();

        if (!$sudin || !$user || $items->isEmpty()) {
            $this->command->warn('Seeder Request dilewati, data master belum lengkap.');
            return;
        }

        // Buat 5 request
        for ($i = 1; $i <= 10; $i++) {

            $request = RequestModel::create([
                'nomor' => 'REQ-' . now()->format('Ymd') . '-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'rab_id' => fake()->boolean ? $rab->id : null,
                'sudin_id' => $sudin->id,
                'unit_id' => $unit?->id,
                'user_id' => $user->id,
                'driver_id' => $driver?->id,
                'security_id' => $security?->id,
                'tanggal_permintaan' => now()->subDays(rand(0, 10)),
                'status' => collect(['draft', 'pending', 'approved'])->random(),
                'notes' => 'Seeder request ke-' . $i,
            ]);

            // Request Items
            foreach ($items->random(rand(10, 20)) as $item) {
                RequestItem::create([
                    'request_id' => $request->id,
                    'item_id' => $item->id,
                    'qty_request' => rand(50, 100),
                    'qty_approved' => rand(45, 95),
                    'notes' => 'Item ' . $item->name,
                ]);
            }
        }
    }
}
