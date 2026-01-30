<?php

namespace App\Livewire\Permintaan\NonRab;

use App\Models\Item;
use App\Models\User;
use App\Models\Stock;
use App\Models\Sudin;
use Livewire\Component;
use App\Models\Division;
use App\Models\Warehouse;
use App\Models\Subdistrict;
use App\Models\ItemCategory;
use App\Models\RequestModel;
use Livewire\Attributes\Title;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Validator;

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
    public $item_type_id = '';
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
            'tanggal_permintaan' => 'required|date',
            'district_id' => 'required|exists:divisions,id',
            'subdistrict_id' => 'required|exists:subdistricts,id',
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
        $this->item_type_id = '';
        $this->item_category_id = '';
        $this->item_id = '';
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
        $validator = Validator::make(
            [
                'item_id' => $this->item_id,
                'qty_request' => $this->qty_request,
            ],
            [
                'item_id' => 'required|exists:items,id',
                'qty_request' => 'required|numeric|min:0.01',
            ],
            [
                'item_id.required' => 'Pilih barang terlebih dahulu',
                'item_id.exists' => 'Barang tidak valid',
                'qty_request.required' => 'Jumlah harus diisi',
                'qty_request.numeric' => 'Jumlah harus berupa angka',
                'qty_request.min' => 'Jumlah minimal 0.01',
            ]
        );

        if ($validator->fails()) {
            $this->dispatch('alert', type: 'error', title: 'Gagal!', text: $validator->errors()->first());
            return;
        }

        $item = Item::with('category.unit')->find($this->item_id);
        $stock = Stock::where('warehouse_id', $this->warehouse_id)
            ->where('item_id', $this->item_id)
            ->first();

        // Check if item already exists in the list
        if (collect($this->items)->contains('item_id', $this->item_id)) {
            $this->dispatch('alert', type: 'error', title: 'Gagal!', text: 'Item sudah ada dalam daftar');
            return;
        }

        // Check if qty_request exceeds available stock
        $stockAvailable = $stock ? $stock->qty : 0;
        if ($this->qty_request > $stockAvailable) {
            $this->dispatch('alert', type: 'error', title: 'Gagal!', text: 'Jumlah yang diminta melebihi stok tersedia (' . number_format($stockAvailable, 2) . ' ' . ($item->category->unit->name ?? '') . ')');
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

        $this->dispatch('alert', type: 'success', title: 'Berhasil!', text: 'Item berhasil ditambahkan ke daftar');
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        session()->flash('success', 'Item berhasil dihapus');
    }

    public function validateForm()
    {
        $validator = Validator::make(
            [
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
                'notes' => $this->notes,
            ],
            $this->rules(),
            [
                'nomor.required' => 'Nomor SPB wajib diisi',
                'nomor.string' => 'Nomor SPB harus berupa teks',
                'nomor.max' => 'Nomor SPB maksimal 255 karakter',
                'name.required' => 'Nama permintaan wajib diisi',
                'name.string' => 'Nama permintaan harus berupa teks',
                'name.max' => 'Nama permintaan maksimal 255 karakter',
                'sudin_id.required' => 'Sudin wajib dipilih',
                'sudin_id.exists' => 'Sudin yang dipilih tidak valid',
                'warehouse_id.required' => 'Gudang wajib dipilih',
                'warehouse_id.exists' => 'Gudang yang dipilih tidak valid',
                'district_id.required' => 'Kecamatan wajib dipilih',
                'district_id.exists' => 'Kecamatan yang dipilih tidak valid',
                'subdistrict_id.required' => 'Kelurahan wajib dipilih',
                'subdistrict_id.exists' => 'Kelurahan yang dipilih tidak valid',
                'tanggal_permintaan.required' => 'Tanggal permintaan wajib diisi',
                'tanggal_permintaan.date' => 'Format tanggal permintaan tidak valid',
                'address.required' => 'Alamat wajib diisi',
                'address.string' => 'Alamat harus berupa teks',
                'panjang.string' => 'Panjang harus berupa teks',
                'panjang.max' => 'Panjang maksimal 255 karakter',
                'lebar.string' => 'Lebar harus berupa teks',
                'lebar.max' => 'Lebar maksimal 255 karakter',
                'tinggi.string' => 'Tinggi harus berupa teks',
                'tinggi.max' => 'Tinggi maksimal 255 karakter',
                'notes.string' => 'Keterangan harus berupa teks',
            ]
        );

        if ($validator->fails()) {
            $this->dispatch('alert', type: 'error', title: 'Gagal!', text: $validator->errors()->first());
            return;
        }

        // Validate items
        if (empty($this->items)) {
            $this->dispatch('alert', type: 'error', title: 'Gagal!', text: 'Minimal harus ada 1 item dalam permintaan');
            return;
        }

        $this->dispatch('validation-passed-create');
        return;
    }

    #[On('confirm-save-permintaan')]
    public function confirmSave()
    {
        $request = RequestModel::create([
            'nomor' => $this->nomor,
            'name' => $this->name,
            'sudin_id' => $this->sudin_id,
            'warehouse_id' => $this->warehouse_id,
            'item_type_id' => $this->item_type_id,
            'district_id' => $this->district_id,
            'subdistrict_id' => $this->subdistrict_id,
            'tanggal_permintaan' => $this->tanggal_permintaan,
            'address' => $this->address,
            'panjang' => $this->panjang,
            'lebar' => $this->lebar,
            'tinggi' => $this->tinggi,
            'user_id' => User::first()->id,
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
            'item_type_id' => $this->item_type_id,
            'district_id' => $this->district_id,
            'subdistrict_id' => $this->subdistrict_id,
            'tanggal_permintaan' => $this->tanggal_permintaan,
            'address' => $this->address,
            'panjang' => $this->panjang,
            'lebar' => $this->lebar,
            'tinggi' => $this->tinggi,
            'user_id' => User::first()->id,
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
        // Get item types
        $itemTypes = collect();
        if ($this->warehouse_id && $this->sudin_id) {
            $itemTypes = \App\Models\ItemType::where('active', true)
                ->whereHas('itemCategories.items', function ($query) {
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

        // Get item categories that have stock in selected warehouse and item type
        $itemCategories = collect();
        if ($this->warehouse_id && $this->sudin_id && $this->item_type_id) {
            $itemCategories = ItemCategory::where('item_type_id', $this->item_type_id)
                ->whereHas('items', function ($query) {
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
                ? Division::districts()->where('sudin_id', $this->sudin_id)->orderBy('name')->get()
                : collect(),
            'subdistricts' => $this->district_id
                ? Subdistrict::where('division_id', $this->district_id)->orderBy('name')->get()
                : collect(),
            'itemTypes' => $itemTypes,
            'itemCategories' => $itemCategories,
            'availableItems' => $availableItems,
        ]);
    }
}
