<?php

namespace Database\Seeders;

use App\Models\ItemType;
use App\Models\ItemUnit;
use Illuminate\Support\Str;
use App\Models\ItemCategory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ItemCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 50; $i++) {
            $unit = ItemUnit::all()->random();
            $type = ItemType::where('active', true)->inRandomOrder()->first();
            $name = fake()->sentence(3);

            if (!$unit || !$type) {
                // skip kalau satuan atau tipe belum ada
                continue;
            }

            ItemCategory::firstOrCreate(
                [
                    'slug' => Str::slug($name),
                ],
                [
                    'name' => $name,
                    'item_unit_id' => $unit->id,
                    'item_type_id' => $type->id,
                ]
            );
        }
    }
}
