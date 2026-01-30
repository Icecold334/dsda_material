<?php

namespace Database\Seeders;

use App\Models\Sudin;
use App\Models\Division;
use App\Models\Position;
use App\Models\ApprovalFlow;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ApprovalFlowPengirimanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (Sudin::all() as $sudin) {
            $sudinId = $sudin->id;
            $module = 'pengiriman';

            // ambil posisi

            $pengurusBarang = Position::where('slug', 'pengurus-barang')->firstOrFail();
            $ppk = Position::where('slug', 'ppk')->firstOrFail();
            $pptk = Position::where('slug', 'pptk')->firstOrFail();


            $flows = [
                [
                    'level' => 1,
                    'position_id' => $pengurusBarang->id,
                    'division_id' => null,
                ],
                [
                    'level' => 2,
                    'position_id' => $ppk->id,
                    'division_id' => null,
                ],
                [
                    'level' => 3,
                    'position_id' => $pptk->id,
                    'division_id' => null,
                ],
            ];

            foreach ($flows as $flow) {
                ApprovalFlow::updateOrCreate(
                    [
                        'sudin_id' => $sudinId,
                        'module' => $module,
                        'level' => $flow['level'],
                    ],
                    [
                        'position_id' => $flow['position_id'],
                        'division_id' => $flow['division_id'],
                    ]
                );
            }
        }
    }
}
