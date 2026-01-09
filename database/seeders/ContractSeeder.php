<?php

namespace Database\Seeders;

use App\Models\Contract;
use App\Models\ContractItem;
use App\Models\Item;
use App\Models\Sudin;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ContractSeeder extends Seeder
{
    public function run(): void
    {
        $sudins = Sudin::all();
        $users = User::all();
        $items = Item::all();

        if ($sudins->isEmpty() || $items->isEmpty()) {
            // $this->command->warn('Sudin / Vendor / Item kosong. Seeder dilewati.');
            return;
        }

        // bikin 5 contract
        foreach (range(1, 5) as $i) {

            $contract = Contract::create([
                'sudin_id' => $sudins->random()->id,
                'user_id' => $users->random()->id,
                'nomor' => 'CTR-' . now()->format('Y') . '-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'tanggal_mulai' => now(),
                'tanggal_selesai' => now()->addMonths(6),
                'status' => collect(['draft', 'approved'])->random(),
            ]);

            $total = 0;

            // ambil 3–5 item
            $items->random(rand(3, 5))->each(function ($item) use ($contract, &$total) {

                $qty = rand(1, 10);
                $price = rand(100_000, 1_000_000);
                $subtotal = $qty * $price;

                ContractItem::create([
                    'contract_id' => $contract->id,
                    'item_id' => $item->id,
                    'qty' => $qty,
                    'price' => $price,
                    'subtotal' => $subtotal,
                ]);

                $total += $subtotal;
            });


        }
    }
}
