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
use App\Models\ItemType;
use App\Models\Rab;
use App\Models\Warehouse;
use App\Models\Division;
use App\Models\Subdistrict;

class RequestSeeder extends Seeder
{
    public function run(): void
    {


        // Buat 5 request
        for ($i = 1; $i <= 50; $i++) {
            $sudin = Sudin::all()->random();
            $unit = Unit::first();
            $user = User::whereHas('position', function ($pos) {
                return $pos->where('name', 'Kepala Satuan Pelaksana');
            })->whereSudinId($sudin->id)->inRandomOrder()->first();
            $rab = Rab::all()->random();
            $driver = Personnel::where('type', 'driver')->first();
            $security = Personnel::where('type', 'security')->first();
            $warehouse = Warehouse::all()->random();
            $district = Division::districts()->first();  // Kecamatan dari divisions
            $subdistrict = Subdistrict::first();
            $items = Item::all();

            if (!$sudin || !$user || $items->isEmpty()) {
                $this->command->warn('Seeder Request dilewati, data master belum lengkap.');
                return;
            }
            $request = RequestModel::create([
                'nomor' => 'REQ-' . now()->format('Ymd') . '-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'name' => 'Permintaan Barang ' . $i,
                'rab_id' => fake()->boolean ? $rab->id : null,
                'sudin_id' => $sudin->id,
                'warehouse_id' => $warehouse?->id,
                'item_type_id' => ItemType::where('active', true)->inRandomOrder()->first()?->id,
                'district_id' => $district?->id,
                'subdistrict_id' => $subdistrict?->id,
                'address' => fake()->address,
                'panjang' => rand(100, 500),
                'lebar' => rand(50, 200),
                'tinggi' => rand(50, 200),
                'nopol' => 'B ' . rand(1000, 9999) . ' ' . fake()->randomLetter() . fake()->randomLetter(),
                'unit_id' => $unit?->id,
                'user_id' => $user->id,
                'driver_id' => $driver?->id,
                'security_id' => $security?->id,
                'tanggal_permintaan' => now()->subDays(rand(0, 10)),
                // 'status' => collect(['draft', 'pending', 'approved'])->random(),
                'status' => 'draft',
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
