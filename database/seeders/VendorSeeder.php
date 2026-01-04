<?php

namespace Database\Seeders;

use App\Models\Vendor;
use App\Models\Sudin;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class VendorSeeder extends Seeder
{
    public function run(): void
    {

        $sudins = Sudin::all();

        if ($sudins->isEmpty()) {
            $this->command->warn('Sudin kosong. VendorSeeder dilewati.');
            return;
        }

        // tiap sudin punya 5–10 vendor
        foreach ($sudins as $sudin) {
            foreach (range(1, rand(5, 10)) as $i) {
                Vendor::create([
                    'sudin_id' => $sudin->id,
                    'name'     => fake()->company,
                    'address'  => fake()->address,
                    'phone'    => fake()->phoneNumber,
                    'email'    => fake()->companyEmail,
                    'npwp'     => fake()->numerify('##.###.###.#-###.###'),
                ]);
            }
        }
    }
}
