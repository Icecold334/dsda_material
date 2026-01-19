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

    public function updatedQty()
    {
        $this->checkAdd();
    }

    public function save()
    {
        // Validasi
        if (!$this->item || !$this->qty || $this->qty <= 0) {
            $this->dispatch('showAlert', [
                'type' => 'warning',
                'title' => 'Gagal!',
                'text' => 'Pilih item dan masukkan jumlah yang valid',
            ]);
            return;
        }

        if ($this->qty > $this->maxQty) {
            $this->dispatch('showAlert', [
                'type' => 'warning',
                'title' => 'Gagal!',
                'text' => 'Jumlah melebihi maksimal yang tersedia',
            ]);
            return;
        }

        // Ambil data item
        $itemData = Item::with('category.unit')->find($this->item);

        // Cek apakah item sudah ada di list
        $existingIndex = collect($this->listBarang)->search(function ($barang) {
            return $barang['item_id'] == $this->item;
        });

        if ($existingIndex !== false) {
            // Update qty jika sudah ada
            $this->listBarang[$existingIndex]['qty'] += $this->qty;
        } else {
            // Tambah item baru
            $this->listBarang[] = [
                'item_id' => $this->item,
                'namaBarang' => $itemData->category->name,
                'spesifikasiBarang' => $itemData->spec,
                'qty' => $this->qty,
                'unit' => $itemData->category->unit->name,
            ];
        }

        // Reset form
        $this->reset(['item', 'qty', 'maxQty']);
        $this->items = collect();
        $this->itemCategory = null;
        $this->unit = 'Satuan';

        $this->dispatch('showAlert', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Barang berhasil ditambahkan',
        ]);
    }

    public function removeItem($index)
    {
        if (isset($this->listBarang[$index])) {
            unset($this->listBarang[$index]);
            $this->listBarang = array_values($this->listBarang);

            $this->dispatch('showAlert', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Barang berhasil dihapus',
            ]);
        }
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
        $this->disablAdd = !($this->item && $this->qty > 0 && $this->qty <= $this->maxQty && $this->maxQty > 0);
    }

    public function render()
    {
        return view('livewire.delivery.create-table');
    }
}
