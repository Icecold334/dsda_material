<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Item;
use App\Models\Sudin;
use App\Models\ItemCategory;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $sudins = Sudin::all();
        $categories = ItemCategory::all();

        if ($sudins->isEmpty()) {
            $this->command->warn('Sudin kosong. ItemSeeder dilewati.');
            return;
        }

        foreach ($sudins as $sudin) {

            $totalItem = rand(20, 40);

            // Prefix code per sudin (AMAN & HUMAN READABLE)
            // $sudinCode = strtoupper(Str::slug($sudin->name ?? 'SD', ''));
            $sudinCode = strtoupper(fake()->lexify('SD??'));

            for ($i = 1; $i <= $totalItem; $i++) {

                Item::create([
                    'sudin_id' => $sudin->id,

                    'item_category_id' => $categories->isNotEmpty()
                        ? $categories->random()->id
                        : null,

                    // 🔑 CODE UNIQUE
                    'code' => sprintf(
                        'ITEM-%s-%04d',
                        $sudinCode,
                        $i
                    ),

                    'name' => ucfirst(fake()->words(rand(2, 4), true)),
                    'spec' => fake()->optional()->sentence,
                    'unit' => fake()->randomElement(['pcs', 'unit', 'liter', 'meter', 'paket']),
                    'active' => fake()->boolean(90),
                ]);
            }
        }
    }
}
