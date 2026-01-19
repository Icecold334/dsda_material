<?php

namespace App\Livewire\Rab;

use App\Models\Rab;
use App\Models\Sudin;
use App\Models\District;
use App\Models\Subdistrict;
use App\Models\Warehouse;
use App\Models\Stock;
use App\Models\Item;
use App\Models\ItemCategory;
use Livewire\Attributes\Title;
use Livewire\Component;

class Create extends Component
{
    #[Title('Buat RAB')]

    public $nomor = '';
    public $name = '';
    public $tahun = '';
    public $tanggal_mulai = '';
    public $tanggal_selesai = '';
    public $sudin_id = '';
    public $district_id = '';
    public $subdistrict_id = '';
    public $address = '';
    public $panjang = '';
    public $lebar = '';
    public $tinggi = '';

    // Item form
    public $item_type_id = '';
    public $item_category_id = '';
    public $item_id = '';
    public $qty = '';

    // Items collection
    public $items = [];

    public function mount()
    {
        $this->tahun = now()->year;
    }

    public function rules()
    {
        return [
            'nomor' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'tahun' => 'required|integer|min:2000|max:2100',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'sudin_id' => 'required|exists:sudins,id',
            'district_id' => 'required|exists:districts,id',
            'subdistrict_id' => 'required|exists:subdistricts,id',
            'address' => 'required|string',
            'panjang' => 'nullable|string|max:255',
            'lebar' => 'nullable|string|max:255',
            'tinggi' => 'nullable|string|max:255',
        ];
    }

    public function updatedSudinId($value)
    {
        $this->item_type_id = '';
        $this->district_id = '';
        $this->subdistrict_id = '';
        $this->item_id = '';
        $this->items = [];
    }

    public function updatedDistrictId($value)
    {
        $this->subdistrict_id = '';
    }

    public function updatedItemTypeId($value)
    {
        $this->item_category_id = '';
        $this->item_id = '';
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
        ], [
            'item_id.required' => 'Pilih barang terlebih dahulu',
            'item_id.exists' => 'Barang tidak valid',
            'qty.required' => 'Jumlah harus diisi',
            'qty.numeric' => 'Jumlah harus berupa angka',
            'qty.min' => 'Jumlah minimal 0.01',
        ]);

        $item = Item::with('category.unit')->find($this->item_id);

        // Check if item already exists in the list
        if (collect($this->items)->contains('item_id', $this->item_id)) {
            $this->addError('item_id', 'Item sudah ada dalam daftar');
            return;
        }

        $this->items[] = [
            'item_id' => $this->item_id,
            'item_category' => $item->category->name ?? '-',
            'item_spec' => $item->spec,
            'item_unit' => $item->category->unit->name ?? '-',
            'qty' => $this->qty,
        ];

        // Reset form
        $this->item_category_id = '';
        $this->item_id = '';
        $this->qty = '';

        // Reset validation errors
        $this->resetValidation(['item_id', 'qty']);
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function save()
    {
        $this->validate();

        // Validate items
        if (empty($this->items)) {
            session()->flash('error', 'Minimal harus ada 1 item dalam RAB');
            return;
        }

        $rab = Rab::create([
            'nomor' => $this->nomor,
            'name' => $this->name,
            'tahun' => $this->tahun,
            'item_type_id' => $this->item_type_id,
            'tanggal_mulai' => $this->tanggal_mulai,
            'tanggal_selesai' => $this->tanggal_selesai,
            'sudin_id' => $this->sudin_id,
            'district_id' => $this->district_id,
            'subdistrict_id' => $this->subdistrict_id,
            'address' => $this->address,
            'panjang' => $this->panjang,
            'lebar' => $this->lebar,
            'tinggi' => $this->tinggi,
            'user_id' => auth()->id(),
            'total' => 0,
            'status' => 'draft',
        ]);

        // Save RAB items
        foreach ($this->items as $item) {
            $rab->items()->create([
                'item_id' => $item['item_id'],
                'qty' => $item['qty'],
                'price' => null,
                'subtotal' => null,
            ]);
        }

        // Save documents
        $this->dispatch('saveDocuments', modelId: $rab->id);

        session()->flash('success', 'RAB berhasil dibuat');
        return redirect()->route('rab.show', $rab);
    }

    public function render()
    {
        // Get item types
        $itemTypes = collect();
        if ($this->sudin_id) {
            $itemTypes = \App\Models\ItemType::where('active', true)
                ->whereHas('itemCategories.items', function ($query) {
                    $query->where('sudin_id', $this->sudin_id)
                        ->where('active', true);
                })
                ->orderBy('name')
                ->get();
        }

        // Get item categories from selected sudin and item type
        $itemCategories = collect();
        if ($this->sudin_id && $this->item_type_id) {
            $itemCategories = ItemCategory::where('item_type_id', $this->item_type_id)
                ->whereHas('items', function ($query) {
                    $query->where('sudin_id', $this->sudin_id)
                        ->where('active', true);
                })
                ->orderBy('name')
                ->get();
        }

        // Get available items from sudin and category
        $availableItems = collect();
        if ($this->sudin_id && $this->item_category_id) {
            $availableItems = Item::where('sudin_id', $this->sudin_id)
                ->where('item_category_id', $this->item_category_id)
                ->where('active', true)
                ->with('category.unit')
                ->orderBy('spec')
                ->get();
        }

        return view('livewire.rab.create', [
            'sudins' => Sudin::orderBy('name')->get(),
            'districts' => $this->sudin_id
                ? District::where('sudin_id', $this->sudin_id)->orderBy('name')->get()
                : collect(),
            'subdistricts' => $this->district_id
                ? Subdistrict::where('district_id', $this->district_id)->orderBy('name')->get()
                : collect(),
            'itemTypes' => $itemTypes,
            'itemCategories' => $itemCategories,
            'availableItems' => $availableItems,
        ]);
    }
}
