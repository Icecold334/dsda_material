<?php

namespace App\Livewire\InterSudinTransfer\Permintaan;

use App\Models\InterSudinTransfer;
use App\Models\Sudin;
use App\Models\Stock;
use App\Models\Item;
use App\Models\ItemCategory;
use Livewire\Attributes\Title;
use Livewire\Component;

class Create extends Component
{
    #[Title('Buat Transfer Permintaan')]

    public $sudin_pengirim_id = '';
    public $sudin_penerima_id = '';
    public $tanggal_transfer = '';
    public $notes = '';

    // Item form
    public $item_category_id = '';
    public $item_id = '';
    public $qty = '';
    public $item_notes = '';

    // Items collection
    public $items = [];

    public function rules()
    {
        return [
            'sudin_pengirim_id' => 'required|exists:sudins,id',
            'sudin_penerima_id' => 'required|exists:sudins,id|different:sudin_pengirim_id',
            'tanggal_transfer' => 'required|date',
            'notes' => 'nullable|string',
        ];
    }

    public function updatedSudinPengirimId($value)
    {
        $this->item_category_id = '';
        $this->item_id = '';
        $this->items = [];
    }

    public function updatedSudinPenerimaId($value)
    {
        $this->item_category_id = '';
        $this->item_id = '';
        $this->items = [];
    }

    public function updatedItemCategoryId($value)
    {
        $this->item_id = '';
    }

    public function addItem()
    {
        $this->validate([
            'item_id' => 'required|exists:items,id',
            'qty' => 'required|numeric|min:0.01',
        ]);

        $item = Item::with('category.unit')->find($this->item_id);

        // Check if item already exists in the list
        if (collect($this->items)->contains('item_id', $this->item_id)) {
            session()->flash('error', 'Item sudah ada dalam daftar');
            return;
        }

        // Get stock from Sudin Diminta to show available qty
        $stock = Stock::whereHas('warehouse', function ($q) {
            $q->where('sudin_id', $this->sudin_penerima_id);
        })
            ->where('item_id', $this->item_id)
            ->sum('qty');

        $this->items[] = [
            'item_id' => $this->item_id,
            'item_category' => $item->category->name ?? '-',
            'item_spec' => $item->spec,
            'item_unit' => $item->category->unit->name ?? '-',
            'qty' => $this->qty,
            'notes' => $this->item_notes,
            'stock_available' => $stock,
        ];

        // Reset form
        $this->item_category_id = '';
        $this->item_id = '';
        $this->qty = '';
        $this->item_notes = '';

        session()->flash('success', 'Item berhasil ditambahkan');
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        session()->flash('success', 'Item berhasil dihapus');
    }

    public function save()
    {
        $this->validate();

        // Validate items
        if (empty($this->items)) {
            session()->flash('error', 'Minimal harus ada 1 item dalam permintaan transfer');
            return;
        }

        $transfer = InterSudinTransfer::create([
            'sudin_pengirim_id' => $this->sudin_pengirim_id,
            'sudin_penerima_id' => $this->sudin_penerima_id,
            'user_id' => auth()->id(),
            'tanggal_transfer' => $this->tanggal_transfer,
            'status' => 'draft',
            'notes' => $this->notes,
        ]);

        // Save transfer items
        foreach ($this->items as $item) {
            $transfer->items()->create([
                'item_id' => $item['item_id'],
                'qty' => $item['qty'],
                'notes' => $item['notes'] ?? null,
            ]);
        }

        session()->flash('success', 'Transfer permintaan berhasil dibuat');
        return redirect()->route('transfer.permintaan.index');
    }

    public function render()
    {
        // Get item categories that have items in selected sudin
        $itemCategories = collect();
        if ($this->sudin_penerima_id) {
            $itemCategories = ItemCategory::whereHas('items', function ($query) {
                $query->where('sudin_id', $this->sudin_penerima_id)
                    ->where('active', true);
            })
                ->orderBy('name')
                ->get();
        }

        // Get available items from selected sudin and category
        $availableItems = collect();
        if ($this->sudin_penerima_id && $this->item_category_id) {
            $availableItems = Item::where('sudin_id', $this->sudin_penerima_id)
                ->where('item_category_id', $this->item_category_id)
                ->where('active', true)
                ->with('category.unit')
                ->orderBy('spec')
                ->get();
        }

        return view('livewire.inter-sudin-transfer.permintaan.create', [
            'sudins' => Sudin::orderBy('name')->get(),
            'itemCategories' => $itemCategories,
            'availableItems' => $availableItems,
        ]);
    }
}
