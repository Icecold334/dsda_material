<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Delivery;
use App\Models\DeliveryItem;
use App\Models\Sudin;
use App\Models\Unit;
use App\Models\User;
use App\Models\Personnel;
use App\Models\Item;
use App\Models\Warehouse;

class DeliverySeeder extends Seeder
{
    public function run(): void
    {
        $sudins = Sudin::pluck('id');
        $units = Unit::pluck('id');
        $users = User::pluck('id');
        $drivers = Personnel::pluck('id');
        $securities = Personnel::pluck('id');
        $items = Item::pluck('id');
        $warehouses = Warehouse::pluck('id');

        if ($sudins->isEmpty() || $users->isEmpty() || $items->isEmpty()) {
            $this->command->warn('Seeder Delivery dilewati karena data master belum lengkap.');
            return;
        }
        // Jumlah delivery
        for ($i = 1; $i <= 10; $i++) {

            /** =========================
             *  CREATE DELIVERY
             *  ========================= */
            $delivery = Delivery::create([
                'warehouse_id' => $warehouses->random() ?? null,
                'nomor' => 'DEL-' . now()->format('Ymd') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'sudin_id' => $sudins->random(),
                'user_id' => $users->random(),
                'tanggal_pengiriman' => now()->addDays(rand(1, 7)),
                'status' => collect(['draft', 'pending', 'approved'])->random(),
                'notes' => 'Seeder delivery ke-' . $i,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            /** =========================
             *  CREATE DELIVERY ITEMS
             *  ========================= */
            $itemCount = rand(1, 5); // 1–5 item per delivery
            for ($j = 1; $j <= $itemCount; $j++) {
                DeliveryItem::create([
                    'id' => (string) Str::uuid(),
                    'delivery_id' => $delivery->id,
                    'item_id' => $items->random(),
                    'qty' => rand(1, 100),
                    'notes' => 'Item ke-' . $j . ' untuk delivery ' . $delivery->nomor,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
