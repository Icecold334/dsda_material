<?php

namespace App\Services;

use App\Models\Stock;
use App\Models\StockTransaction;
use App\Models\RequestItem;
use Illuminate\Support\Facades\DB;
use Exception;

class StockLedgerService
{
    /**
     * Hitung stok fisik (saldo nyata)
     */
    public function physicalStock(
        $itemId,
        $warehouseId,
        $sudinId
    ): int {
        return (int) Stock::where([
            'item_id' => $itemId,
            'warehouse_id' => $warehouseId,
            'sudin_id' => $sudinId,
        ])->value('qty') ?? 0;
    }

    /**
     * Hitung stok yang sedang di-booking (permintaan belum final)
     */
    public function reservedStock(
        $itemId,
        $warehouseId,
        // $sudinId
    ): int {
        return (int) RequestItem::where('item_id', $itemId)
            ->whereHas('request', function ($q) use ($warehouseId) {
                $q->where('warehouse_id', $warehouseId)
                    // ->where('sudin_id', $sudinId)
                    ->whereIn('status', [
                        'pending',
                        'approved',
                    ]);
            })
            ->sum('qty_request');
    }

    /**
     * Stok tersedia (boleh dipakai)
     */
    public function availableStock(
        $itemId,
        $warehouseId,
        $sudinId
    ): int {
        $physical = $this->physicalStock($itemId, $warehouseId, $sudinId);
        $reserved = $this->reservedStock($itemId, $warehouseId, $sudinId);

        return max(0, $physical - $reserved);
    }

    /**
     * Validasi booking saat submit permintaan
     */
    public function validateAvailability(
        $itemId,
        $warehouseId,
        $sudinId,
        $qty
    ): void {
        $available = $this->availableStock($itemId, $warehouseId, $sudinId);

        if ($qty > $available) {
            throw new Exception(
                "Stok tidak mencukupi. Tersedia: {$available}"
            );
        }
    }

    /**
     * Ledger BARANG MASUK (REAL EVENT)
     */
    public function in(array $data): void
    {
        DB::transaction(function () use ($data) {

            $stock = Stock::firstOrCreate(
                [
                    'item_id' => $data['item_id'],
                    'warehouse_id' => $data['warehouse_id'],
                    'sudin_id' => $data['sudin_id'],
                ],
                ['qty' => 0]
            );

            $before = $stock->qty;
            $after = $before + $data['qty'];

            StockTransaction::create([
                'type' => 'IN',
                'qty' => $data['qty'],
                'before_qty' => $before,
                'after_qty' => $after,
                'ref_type' => $data['ref_type'],
                'ref_id' => $data['ref_id'],
                'item_id' => $data['item_id'],
                'warehouse_id' => $data['warehouse_id'],
                'sudin_id' => $data['sudin_id'],
                'user_id' => $data['user_id'],
            ]);

            $stock->update(['qty' => $after]);
        });
    }

    /**
     * Ledger BARANG KELUAR (FINAL APPROVAL)
     */
    public function out(array $data): void
    {
        DB::transaction(function () use ($data) {

            $stock = Stock::where([
                'item_id' => $data['item_id'],
                'warehouse_id' => $data['warehouse_id'],
                'sudin_id' => $data['sudin_id'],
            ])->lockForUpdate()->first();

            if (!$stock) {
                throw new Exception('Stok tidak ditemukan');
            }

            $before = $stock->qty;
            $after = $before - $data['qty'];

            if ($after < 0) {
                throw new Exception('Stok fisik tidak mencukupi');
            }

            StockTransaction::create([
                'type' => 'OUT',
                'qty' => -$data['qty'],
                'before_qty' => $before,
                'after_qty' => $after,
                'ref_type' => $data['ref_type'],
                'ref_id' => $data['ref_id'],
                'item_id' => $data['item_id'],
                'warehouse_id' => $data['warehouse_id'],
                'sudin_id' => $data['sudin_id'],
                'user_id' => $data['user_id'],
            ]);

            $stock->update(['qty' => $after]);
        });
    }

    /**
     * Penyesuaian stok (opname / koreksi)
     */
    public function adjust(array $data): void
    {
        DB::transaction(function () use ($data) {

            $stock = Stock::firstOrCreate(
                [
                    'item_id' => $data['item_id'],
                    'warehouse_id' => $data['warehouse_id'],
                    'sudin_id' => $data['sudin_id'],
                ],
                ['qty' => 0]
            );

            $before = $stock->qty;
            $after = $data['new_qty'];
            $diff = $after - $before;

            StockTransaction::create([
                'type' => 'ADJUST',
                'qty' => $diff,
                'before_qty' => $before,
                'after_qty' => $after,
                'ref_type' => $data['ref_type'],
                'ref_id' => $data['ref_id'],
                'item_id' => $data['item_id'],
                'warehouse_id' => $data['warehouse_id'],
                'sudin_id' => $data['sudin_id'],
                'user_id' => $data['user_id'],
            ]);

            $stock->update(['qty' => $after]);
        });
    }
}
