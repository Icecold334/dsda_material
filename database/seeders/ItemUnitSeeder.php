<?php

namespace Database\Seeders;

use App\Models\ItemUnit;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ItemUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 3; $i++) {
            $name = fake()->word;
            ItemUnit::firstOrCreate(
                [
                    'slug' => Str::slug($name)
                ],
                [
                    'name' => $name,
                ]
            );
        }
    }
}
