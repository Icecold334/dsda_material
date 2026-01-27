<?php

namespace Database\Seeders;

use App\Models\Sudin;
use Illuminate\Database\Seeder;
use App\Models\ApprovalFlow;
use App\Models\Position;
use App\Models\Division;

class ApprovalFlowPermintaanNonRabSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Sudin::all() as $sudin) {
            $sudinId = $sudin->id;
            $module = 'permintaanNonRab';

            // ambil posisi
            $kasie = Position::where('slug', 'kepala-seksi')->firstOrFail();
            $kasudin = Position::where('slug', 'kepala-suku-dinas')->firstOrFail();
            $kasubagTu = Position::where('slug', 'kepala-sub-bagian')->firstOrFail();
            $pengurusBarang = Position::where('slug', 'pengurus-barang')->firstOrFail();

            // ambil division (khusus level kasie/subbag)
            $seksiPemeliharaan = Division::where('name', 'Pemeliharaan')
                ->where('type', 'seksi')
                ->firstOrFail();
            $subbagTu = Division::where('name', 'Tata Usaha')
                ->where('type', 'subbag')
                ->firstOrFail();

            $flows = [
                [
                    'level' => 1,
                    'position_id' => $kasie->id,
                    'division_id' => $seksiPemeliharaan->id,
                ],
                [
                    'level' => 2,
                    'position_id' => $kasudin->id,
                    'division_id' => null,
                ],
                [
                    'level' => 3,
                    'position_id' => $kasubagTu->id,
                    'division_id' => $subbagTu->id,
                ],
                [
                    'level' => 4,
                    'position_id' => $pengurusBarang->id,
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
