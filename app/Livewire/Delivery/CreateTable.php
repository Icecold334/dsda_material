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
    public $listBarang = [], $itemCategories = [], $items, $warehouse, $contract, $itemCategory, $item, $unit = 'Satuan';

    public function mount()
    {
        $this->warehouse = Warehouse::all()->first();
        $this->contract = Contract::where('nomor', '20397/PN01.05')->first();
        $this->itemCategories = ItemCategory::wherehas('items.ContractItems.contract', function ($contract) {
            return $contract->where('id', $this->contract->id);
        })->get();
        $this->items = collect(); // ✅ PENTING
    }

    public function updatedItemCategory()
    {
        $this->item = null;
        $this->unit = ItemCategory::find($this->itemCategory)->unit->name;
        $this->items = Item::where('item_category_id', $this->itemCategory)
            ->whereHas('contractItems.contract', fn($q) => $q->whereId($this->contract->id))
            ->get();

    }



    #[On('fillCreateTable')]
    private function filled(Warehouse $warehouse = null, Contract $contract = null)
    {
    }

    public function render()
    {
        return view('livewire.delivery.create-table');
    }
}
