<?php

namespace App\Livewire\Permintaan\NonRab;

use App\Models\RequestModel;
use App\Models\Sudin;
use App\Models\Warehouse;
use App\Models\District;
use App\Models\Subdistrict;
use App\Models\Stock;
use App\Models\Item;
use App\Models\ItemCategory;
use Livewire\Attributes\Title;
use Livewire\Component;

class Create extends Component
{
    #[Title('Buat Permintaan Non RAB')]

    public $nomor = '';
    public $name = '';
    public $sudin_id = '';
    public $warehouse_id = '';
    public $district_id = '';
    public $subdistrict_id = '';
    public $tanggal_permintaan = '';
    public $address = '';
    public $panjang = '';
    public $lebar = '';
    public $tinggi = '';
    public $notes = '';

    // Item form
    public $item_category_id = '';
    public $item_id = '';
    public $qty_request = '';

    // Items collection
    public $items = [];

    public function rules()
    {
        return [
            'nomor' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'sudin_id' => 'required|exists:sudins,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'district_id' => 'required|exists:districts,id',
            'subdistrict_id' => 'required|exists:subdistricts,id',
            'tanggal_permintaan' => 'required|date',
            'address' => 'required|string',
            'panjang' => 'nullable|string|max:255',
            'lebar' => 'nullable|string|max:255',
            'tinggi' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ];
    }

    public function updatedSudinId($value)
    {
        $this->warehouse_id = '';
        $this->district_id = '';
        $this->subdistrict_id = '';
        $this->item_id = '';
        $this->items = [];
    }

    public function updatedDistrictId($value)
    {
        $this->subdistrict_id = '';
    }

    public function updatedWarehouseId($value)
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
            'qty_request' => 'required|numeric|min:0.01',
        ]);

        $item = Item::with('category.unit')->find($this->item_id);
        $stock = Stock::where('warehouse_id', $this->warehouse_id)
            ->where('item_id', $this->item_id)
            ->first();

        // Check if item already exists in the list
        if (collect($this->items)->contains('item_id', $this->item_id)) {
            session()->flash('error', 'Item sudah ada dalam daftar');
            return;
        }

        // Check if qty_request exceeds available stock
        $stockAvailable = $stock ? $stock->qty : 0;
        if ($this->qty_request > $stockAvailable) {
            session()->flash('error', 'Jumlah yang diminta melebihi stok tersedia (' . number_format($stockAvailable, 2) . ' ' . ($item->category->unit->name ?? '') . ')');
            return;
        }

        $this->items[] = [
            'item_id' => $this->item_id,
            'item_category' => $item->category->name ?? '-',
            'item_spec' => $item->spec,
            'item_unit' => $item->category->unit->name ?? '-',
            'qty_request' => $this->qty_request,
            'stock_available' => $stock ? $stock->qty : 0,
        ];

        // Reset form
        $this->item_category_id = '';
        $this->item_id = '';
        $this->qty_request = '';

        session()->flash('success', 'Item berhasil ditambahkan');
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        session()->flash('success', 'Item berhasil dihapus');
    }

    public function getFileCountProperty()
    {
        // Get file count from child DocumentUpload component
        foreach ($this->getComponents() as $component) {
            if ($component instanceof \App\Livewire\Components\DocumentUpload) {
                return $component->getFileCount();
            }
        }
        return 0;
    }

    public function save()
    {
        $this->validate();

        // Validate items
        if (empty($this->items)) {
            session()->flash('error', 'Minimal harus ada 1 item dalam permintaan');
            return;
        }

        $request = RequestModel::create([
            'nomor' => $this->nomor,
            'name' => $this->name,
            'sudin_id' => $this->sudin_id,
            'warehouse_id' => $this->warehouse_id,
            'district_id' => $this->district_id,
            'subdistrict_id' => $this->subdistrict_id,
            'tanggal_permintaan' => $this->tanggal_permintaan,
            'address' => $this->address,
            'panjang' => $this->panjang,
            'lebar' => $this->lebar,
            'tinggi' => $this->tinggi,
            'user_id' => auth()->id(),
            'notes' => $this->notes,
            'status' => 'draft',
            'rab_id' => null,
        ]);

        // Save request items
        foreach ($this->items as $item) {
            $request->items()->create([
                'item_id' => $item['item_id'],
                'qty_request' => $item['qty_request'],
            ]);
        }

        // Save documents
        $this->dispatch('saveDocuments', modelId: $request->id);

        session()->flash('success', 'Permintaan berhasil dibuat');
        return redirect()->route('permintaan.nonRab.index');
    }

    public function render()
    {
        // Get item categories that have stock in selected warehouse
        $itemCategories = collect();
        if ($this->warehouse_id && $this->sudin_id) {
            $itemCategories = ItemCategory::whereHas('items', function ($query) {
                $query->where('sudin_id', $this->sudin_id)
                    ->where('active', true)
                    ->whereHas('stocks', function ($q) {
                        $q->where('warehouse_id', $this->warehouse_id)
                            ->where('qty', '>', 0);
                    });
            })
                ->orderBy('name')
                ->get();
        }

        // Get available items from stock in selected warehouse and category
        $availableItems = collect();
        if ($this->warehouse_id && $this->sudin_id && $this->item_category_id) {
            $availableItems = Item::where('sudin_id', $this->sudin_id)
                ->where('item_category_id', $this->item_category_id)
                ->where('active', true)
                ->with([
                    'category.unit',
                    'stocks' => function ($query) {
                        $query->where('warehouse_id', $this->warehouse_id);
                    }
                ])
                ->whereHas('stocks', function ($query) {
                    $query->where('warehouse_id', $this->warehouse_id)
                        ->where('qty', '>', 0);
                })
                ->orderBy('spec')
                ->get();
        }

        return view('livewire.permintaan.non-rab.create', [
            'sudins' => Sudin::orderBy('name')->get(),
            'warehouses' => $this->sudin_id
                ? Warehouse::where('sudin_id', $this->sudin_id)->orderBy('name')->get()
                : collect(),
            'districts' => $this->sudin_id
                ? District::where('sudin_id', $this->sudin_id)->orderBy('name')->get()
                : collect(),
            'subdistricts' => $this->district_id
                ? Subdistrict::where('district_id', $this->district_id)->orderBy('name')->get()
                : collect(),
            'itemCategories' => $itemCategories,
            'availableItems' => $availableItems,
        ]);
    }
}
