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

        // Track used codes to prevent duplicates
        $usedCodes = [];

        foreach ($sudins as $sudin) {
            $totalItem = rand(20, 40);

            // Generate unique sudin code
            do {
                $sudinCode = strtoupper(fake()->lexify('SD??'));
            } while (in_array($sudinCode, $usedCodes));

            $usedCodes[] = $sudinCode;

            for ($i = 1; $i <= $totalItem; $i++) {
                $spec = fake()->sentence;

                Item::create([
                    'sudin_id' => $sudin->id,
                    'item_category_id' => $categories->isNotEmpty()
                        ? $categories->random()->id
                        : null,
                    'code' => sprintf('ITEM-%s-%04d', $sudinCode, $i),
                    'spec' => $spec,
                    'slug' => Str::slug($spec),
                    'active' => fake()->boolean(90),
                ]);
            }
        }
    }
}