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
    public $listBarang = [], $itemCategories = [], $items, $warehouse, $contract, $itemCategory, $item;

    public function mount()
    {
        $this->warehouse = Warehouse::first();
        $this->contract = Contract::where('nomor', '20397/PN01.02')->first();
        $this->itemCategories = ItemCategory::wherehas('items.ContractItems.contract', function ($contract) {
            return $contract->where('id', $this->contract->id);
        })->get();
        $this->items = collect(); // ✅ PENTING
    }

    public function updatedItemCategory()
    {
        $this->items = Item::whereHas('contractItems.contract', function ($contract) {
            return $contract->where('id', $this->contract->id);
        })->get();
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
