<?php

namespace Database\Seeders;

use App\Models\Rab;
use App\Models\Item;
use App\Models\RabItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RabItemSeeder extends Seeder
{
    public function run(): void
    {
        // Guard: pastikan master data ada
        if (Rab::count() === 0 || Item::count() === 0) {
            $this->command->warn('Rab atau Item kosong, skip RabItemSeeder');
            return;
        }

        Rab::all()->each(function ($rab) {

            // Random 3–7 item per RAB
            $items = Item::inRandomOrder()->take(rand(3, 7))->get();

            foreach ($items as $item) {

                $qty = rand(1, 20);
                $price = rand(10_000, 5_000_000);
                $subtotal = $qty * $price;

                RabItem::create([
                    'rab_id' => $rab->id,
                    'item_id' => $item->id,
                    'qty' => $qty,
                    'price' => $price,
                    'subtotal' => $subtotal,
                ]);
            }

            // Optional: update total RAB dari subtotal item
            $rab->update([
                'total' => $rab->items()->sum('subtotal'),
            ]);
        });
    }
}
