<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Unit;
use App\Models\User;
use App\Models\Sudin;
use App\Models\Contract;
use App\Models\Delivery;
use App\Models\Personnel;
use App\Models\Warehouse;
use Illuminate\Support\Str;
use App\Models\DeliveryItem;
use Illuminate\Database\Seeder;
use App\Services\ApprovalService;

class DeliverySeeder extends Seeder
{
    public function run(): void
    {
        $contracts = Contract::pluck('id');
        $sudin = Sudin::where('name', 'Jakarta Barat')->first();
        $units = Unit::pluck('id');
        $users = User::whereSudinId($sudin->id)->pluck('id');
        $drivers = Personnel::pluck('id');
        $securities = Personnel::pluck('id');
        $items = Item::pluck('id');
        $warehouses = Warehouse::pluck('id');

        if ($contracts->isEmpty() || $users->isEmpty() || $items->isEmpty() || $warehouses->isEmpty()) {
            $this->command->warn('Seeder Delivery dilewati karena data master belum lengkap.');
            return;
        }
        // Jumlah delivery
        for ($i = 1; $i <= 100; $i++) {

            /** =========================
             *  CREATE DELIVERY
             *  ========================= */
            $delivery = Delivery::create([
                'contract_id' => $contracts->random(),
                'warehouse_id' => $warehouses->random(),
                'nomor' => 'DEL-' . now()->format('Ymd') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'sudin_id' => $sudin->id,
                'user_id' => $users->random(),
                'tanggal_delivery' => now()->addDays(rand(1, 7)),
                'status' => 'pending',
                'notes' => 'Seeder delivery ke-' . $i,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            app(ApprovalService::class)->init($delivery, 'pengiriman');

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
