<?php

namespace App\Livewire\Delivery;

use App\Models\Contract;
use App\Models\Delivery;
use App\Models\DeliveryItem;
use App\Models\Item;
use App\Models\Warehouse;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\ItemCategory;

class CreateTable extends Component
{
    public $listBarang = [], $itemCategories = [], $items, $warehouse, $contract, $itemCategory, $item, $unit = 'Satuan', $maxQty = 0, $qty, $disablAdd;

    public function mount()
    {
        $this->warehouse = Warehouse::all()->first();
        $this->contract = Contract::where('nomor', 'CTR-2026-001')->first();
        $this->itemCategories = ItemCategory::wherehas('items.ContractItems.contract', function ($contract) {
            return $contract->where('id', $this->contract->id);
        })->get();
        $this->items = collect(); // ✅ PENTING
        $this->checkAdd();
    }

    public function updatedItemCategory()
    {
        $this->item = null;
        $this->unit = ItemCategory::find($this->itemCategory)->unit->name;
        $this->items = Item::where('item_category_id', $this->itemCategory)
            ->whereHas('contractItems.contract', fn($q) => $q->whereId($this->contract->id))
            ->get();
        $this->checkAdd();
    }
    public function updatedItem()
    {
        if (!$this->item || !$this->contract) {
            $this->maxQty = 0;
            $this->checkAdd();
            return;
        }

        // Ambil qty dari contract item
        $contractItem = $this->contract->items()
            ->where('item_id', $this->item)
            ->first();

        if (!$contractItem) {
            $this->maxQty = 0;
            $this->checkAdd();
            return;
        }

        $qtyContract = $contractItem->qty;

        // Hitung total qty yang sudah dikirim sebelumnya untuk item ini dari contract yang sama
        $qtyDelivered = DeliveryItem::whereHas('delivery', function ($q) {
            $q->where('contract_id', $this->contract->id);
        })
            ->where('item_id', $this->item)
            ->sum('qty');

        // Max qty = qty kontrak - qty yang sudah dikirim
        $this->maxQty = $qtyContract - $qtyDelivered;
        $this->checkAdd();
    }



    #[On('fillCreateTable')]
    private function filled(Warehouse $warehouse = null, Contract $contract = null)
    {
        if ($warehouse) {
            $this->warehouse = $warehouse;
        }
        if ($contract) {
            $this->contract = $contract;
            $this->itemCategories = ItemCategory::wherehas('items.ContractItems.contract', function ($q) {
                return $q->where('id', $this->contract->id);
            })->get();
        }
    }

    private function checkAdd()
    {
        $this->disablAdd = !($this->qty <= $this->maxQty && $this->maxQty > 0);
    }

    public function render()
    {
        return view('livewire.delivery.create-table');
    }
}
