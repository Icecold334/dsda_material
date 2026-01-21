<?php

namespace App\Livewire\Stock;

use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\Stock;
use App\Models\StockTransaction;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class AdjustmentModal extends Component
{
    public $warehouseId;
    public $warehouse;

    #[Validate('required')]
    public $itemCategoryId;

    #[Validate('required')]
    public $itemId;

    #[Validate('required|numeric|min:0.01')]
    public $qty;

    #[Validate('required|in:IN,KURANGI')]
    public $type = 'IN';

    public $categories = [];
    public $items = [];
    public $selectedCategoryName = '';

    protected $listeners = ['openAdjustmentModal'];

    public function openAdjustmentModal($warehouseId)
    {
        $this->reset(['itemCategoryId', 'itemId', 'qty', 'type', 'items', 'selectedCategoryName']);
        $this->warehouseId = $warehouseId;
        $this->warehouse = Warehouse::with('sudin')->find($warehouseId);

        // Load categories that have items with stock in this warehouse
        $this->categories = ItemCategory::whereHas('items.stocks', function ($q) use ($warehouseId) {
            $q->where('warehouse_id', $warehouseId);
        })->with('unit')->get();

        $this->dispatch('open-modal', 'adjustment-modal');
    }

    public function updatedItemCategoryId($value)
    {
        $this->itemId = null;
        $this->items = [];
        $this->selectedCategoryName = '';

        if ($value) {
            $category = ItemCategory::find($value);
            $this->selectedCategoryName = $category->name ?? '';

            // Get items that have stock in this warehouse for the selected category
            $this->items = Item::where('item_category_id', $value)
                ->whereHas('stocks', function ($q) {
                    $q->where('warehouse_id', $this->warehouseId);
                })
                ->with('stocks', function ($q) {
                    $q->where('warehouse_id', $this->warehouseId);
                })
                ->get();
        }
    }

    public function save()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $item = Item::with('category.unit')->findOrFail($this->itemId);
            $stock = Stock::where('warehouse_id', $this->warehouseId)
                ->where('item_id', $this->itemId)
                ->firstOrFail();

            $beforeQty = $stock->qty;
            $changeQty = $this->type === 'IN' ? $this->qty : -$this->qty;
            $afterQty = $beforeQty + $changeQty;

            if ($afterQty < 0) {
                $this->addError('qty', 'Stok tidak boleh negatif. Stok saat ini: ' . number_format($beforeQty, 2));
                DB::rollBack();
                return;
            }

            // Update stock
            $stock->update(['qty' => $afterQty]);

            // Create transaction
            $transaction = StockTransaction::create([
                'sudin_id' => $this->warehouse->sudin_id,
                'warehouse_id' => $this->warehouseId,
                'item_id' => $this->itemId,
                'type' => 'ADJUST',
                'qty' => $changeQty,
                'before_qty' => $beforeQty,
                'after_qty' => $afterQty,
                'user_id' => auth()->id(),
            ]);

            // Save documents
            $this->dispatch('saveDocuments', modelId: $transaction->id);

            DB::commit();

            session()->flash('message', 'Penyesuaian stok berhasil disimpan');
            $this->dispatch('adjustment-saved');
            $this->dispatch('close-modal', 'adjustment-modal');
            $this->dispatch('close-modal', 'lampiran-adjustment-modal');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('general', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function closeModal()
    {
        $this->dispatch('close-modal', 'adjustment-modal');
        $this->reset(['warehouseId', 'warehouse', 'itemCategoryId', 'itemId', 'qty', 'type', 'items', 'categories', 'selectedCategoryName']);
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.stock.adjustment-modal');
    }
}
