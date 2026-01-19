<?php

namespace App\Livewire\Delivery;

use App\Models\Contract;
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
        $this->contract = Contract::where('nomor', '20397/PN01.05')->first();
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
        $this->maxQty = 19;
        $this->checkAdd();
    }



    #[On('fillCreateTable')]
    private function filled(Warehouse $warehouse = null, Contract $contract = null)
    {
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
