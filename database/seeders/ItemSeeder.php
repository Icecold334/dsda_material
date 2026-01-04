<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Sudin;
use App\Models\ItemCategory;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

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
            foreach (range(1, rand(20, 40)) as $i) {
                Item::create([
                    'sudin_id'         => $sudin->id,
                    'item_category_id' => $categories->isNotEmpty()
                        ? $categories->random()->id
                        : null,
                    'name'   => ucfirst(fake()->words(rand(2, 4), true)),
                    'spec'   => fake()->optional()->sentence,
                    'unit'   => fake()->randomElement(['pcs', 'unit', 'liter', 'meter', 'paket']),
                    'active' => fake()->boolean(90),
                ]);
            }
        }
    }
}
