<?php

namespace App\Exports;

use App\Models\Stock;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StockOpnameTemplateExport implements FromArray, WithHeadings, WithColumnWidths, WithStyles
{
    protected $warehouseId;

    public function __construct($warehouseId)
    {
        $this->warehouseId = $warehouseId;
    }

    public function array(): array
    {
        $stocks = Stock::where('warehouse_id', $this->warehouseId)
            ->with(['item.itemCategory'])
            ->get();

        $data = [];

        // Tambahkan 1 baris contoh
        $data[] = [
            'ITEM-CONTOH01',
            'Contoh Nama Barang',
            'Contoh Spesifikasi',
            '15.50',
            date('Y-m-d')
        ];

        // Tambahkan data asli
        foreach ($stocks as $stock) {
            $item = $stock->item;
            $kodeItem = $item->code ?? '';

            $data[] = [
                $kodeItem,
                $item->itemCategory ? $item->itemCategory->name : '-',
                $item->spec,
                '', // Kolom kosong untuk diisi user
                '' // Tanggal diisi user
            ];
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Kode Spek',
            'Nama Barang',
            'Spesifikasi',
            'Stok Aktual',
            'Tanggal Opname'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,  // Kode Spek (kode item)
            'B' => 30,  // Nama Barang
            'C' => 35,  // Spesifikasi
            'D' => 15,  // Stok Aktual
            'E' => 18,  // Tanggal Opname
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2E8F0']
                ]
            ],
        ];
    }
}
