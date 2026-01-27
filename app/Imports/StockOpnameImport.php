<?php

namespace App\Imports;

use App\Models\Stock;
use App\Models\StockOpname;
use App\Models\StockTransaction;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StockOpnameImport implements ToCollection, WithHeadingRow
{
    protected $warehouseId;
    protected $userId;
    protected $results = [];

    public function __construct($warehouseId, $userId)
    {
        $this->warehouseId = $warehouseId;
        $this->userId = $userId;
    }

    public function collection(Collection $rows)
    {
        DB::beginTransaction();

        try {
            foreach ($rows as $row) {
                // Skip jika stok aktual kosong atau baris contoh
                if (empty($row['stok_aktual']) || str_starts_with(strtoupper($row['kode_spek'] ?? ''), 'ITEM-CONTOH')) {
                    continue;
                }

                $kodeItem = trim($row['kode_spek']); // Kolom 'kode_spek' sekarang berisi kode item
                $qtyReal = (float) $row['stok_aktual'];
                $tanggalOpname = $row['tanggal_opname'] ?? date('Y-m-d');

                // Cari stock berdasarkan kode item (LIKE search)
                $stock = Stock::where('warehouse_id', $this->warehouseId)
                    ->whereHas('item', function ($query) use ($kodeItem) {
                        $query->where('code', 'LIKE', "%{$kodeItem}%");
                    })
                    ->with(['item.itemCategory', 'sudin'])
                    ->first();

                if (!$stock) {
                    $this->results[] = [
                        'status' => 'error',
                        'kode_spek' => $kodeItem,
                        'message' => 'Barang tidak ditemukan'
                    ];
                    continue;
                }

                $qtySystem = $stock->qty;
                $selisih = $qtyReal - $qtySystem;

                // Simpan ke tabel stock_opnames
                $stockOpname = StockOpname::create([
                    'sudin_id' => $stock->sudin_id,
                    'warehouse_id' => $this->warehouseId,
                    'item_id' => $stock->item_id,
                    'user_id' => $this->userId,
                    'qty_system' => $qtySystem,
                    'qty_real' => $qtyReal,
                    'selisih' => $selisih,
                    'tanggal_opname' => $tanggalOpname,
                ]);

                // Catat transaksi di stock_transactions
                StockTransaction::create([
                    'sudin_id' => $stock->sudin_id,
                    'warehouse_id' => $this->warehouseId,
                    'item_id' => $stock->item_id,
                    'type' => 'OPNAME',
                    'qty' => $qtyReal,
                    'before_qty' => $qtySystem,
                    'after_qty' => $qtyReal,
                    'ref_type' => null,
                    'ref_id' => null,
                    'user_id' => $this->userId,
                ]);

                // Update stok dengan qty hasil opname
                $stock->update(['qty' => $qtyReal]);

                $this->results[] = [
                    'status' => 'success',
                    'kode_spek' => $kodeItem,
                    'item_name' => $stock->item->itemCategory->name ?? '-',
                    'qty_system' => $qtySystem,
                    'qty_real' => $qtyReal,
                    'selisih' => $selisih,
                ];
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getResults()
    {
        return $this->results;
    }
}
