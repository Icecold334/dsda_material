<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Stock;
use App\Models\Warehouse;
use App\Models\Item;
use App\Models\Sudin;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sudins = Sudin::all();

        if ($sudins->isEmpty()) {
            $this->command->warn('Sudin kosong. StockSeeder dilewati.');
            return;
        }

        foreach ($sudins as $sudin) {
            $warehouses = Warehouse::where('sudin_id', $sudin->id)->get();
            $items = Item::where('sudin_id', $sudin->id)->where('active', true)->get();

            if ($warehouses->isEmpty() || $items->isEmpty()) {
                continue;
            }

            foreach ($warehouses as $warehouse) {
                // Setiap gudang akan memiliki 40-70% dari total item yang tersedia
                $itemsInWarehouse = $items->random(rand((int) ($items->count() * 0.4), (int) ($items->count() * 0.7)));

                foreach ($itemsInWarehouse as $item) {
                    Stock::create([
                        'sudin_id' => $sudin->id,
                        'warehouse_id' => $warehouse->id,
                        'item_id' => $item->id,
                        'qty' => fake()->randomFloat(2, 10, 1000), // Stok antara 10 - 1000
                    ]);
                }
            }
        }

        $this->command->info('Stock seeder completed successfully.');
    }
}

