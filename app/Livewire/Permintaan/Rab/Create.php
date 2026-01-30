<?php

namespace App\Livewire\Permintaan\Rab;

use App\Models\Rab;
use App\Models\RequestModel;
use App\Models\RequestItem;
use App\Models\Warehouse;
use App\Models\Stock;
use Livewire\Attributes\Title;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Facades\Validator;

class Create extends Component
{
    #[Title('Buat Permintaan dengan RAB')]

    // Modal state
    public $showModal = true;
    public $showWarehouseModal = false;

    // Search RAB
    public $rab_nomor = '';
    public $rab = null;

    // Data dari RAB (readonly/disabled)
    public $name = '';
    public $sudin_id = '';
    public $district_id = '';
    public $subdistrict_id = '';
    public $address = '';
    public $panjang = '';
    public $lebar = '';
    public $tinggi = '';

    // Data yang bisa diisi
    public $nomor = '';
    public $warehouse_id = '';
    public $tanggal_permintaan = '';
    public $notes = '';

    // Items
    public $items = [];

    public function rules()
    {
        return [
            'rab_nomor' => 'required|string',
            'nomor' => 'required|string|max:255',
            'warehouse_id' => 'required|exists:warehouses,id',
            'tanggal_permintaan' => 'required|date',
            'notes' => 'nullable|string',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.qty_request' => 'required|numeric|min:0',
        ];
    }

    public function searchRab()
    {
        $validator = Validator::make(
            [
                'rab_nomor' => $this->rab_nomor,
            ],
            [
                'rab_nomor' => 'required|string',
            ],
            [
                'rab_nomor.required' => 'Nomor RAB wajib diisi',
                'rab_nomor.string' => 'Nomor RAB harus berupa teks',
            ]
        );

        if ($validator->fails()) {
            $this->dispatch('alert', type: 'error', title: 'Gagal!', text: $validator->errors()->first());
            return;
        }

        $rab = Rab::with(['sudin', 'district', 'subdistrict', 'user'])
            ->where('nomor', $this->rab_nomor)
            ->first();

        if (!$rab) {
            $this->dispatch('alert', type: 'error', title: 'Gagal!', text: 'RAB dengan nomor tersebut tidak ditemukan');
            return;
        }

        if ($rab->status !== 'approved') {
            $this->dispatch('alert', type: 'error', title: 'Gagal!', text: 'RAB belum disetujui, tidak dapat membuat permintaan');
            return;
        }

        $this->rab = $rab;

        // Mount data dari RAB
        $this->name = $rab->name;
        $this->sudin_id = $rab->sudin_id;
        $this->district_id = $rab->district_id;
        $this->subdistrict_id = $rab->subdistrict_id;
        $this->address = $rab->address ?? '';
        $this->panjang = $rab->panjang ?? '';
        $this->lebar = $rab->lebar ?? '';
        $this->tinggi = $rab->tinggi ?? '';

        // Tutup modal
        $this->showModal = false;
        $this->dispatch('close-modal', 'input-rab-number');

        // Initialize items dari RAB
        $this->initializeItems();

        session()->flash('rab_found', 'RAB ditemukan! Data telah dimuat.');
    }

    public function closeModal()
    {
        $this->showModal = false;
        return redirect()->route('permintaan.rab.index');
    }

    public function resetRab()
    {
        $this->rab = null;
        $this->rab_nomor = '';
        $this->name = '';
        $this->sudin_id = '';
        $this->district_id = '';
        $this->subdistrict_id = '';
        $this->address = '';
        $this->panjang = '';
        $this->lebar = '';
        $this->tinggi = '';
        $this->warehouse_id = '';
        $this->items = [];
        $this->showModal = true;
    }

    public function initializeItems()
    {
        if (!$this->rab)
            return;

        $this->items = [];
        $rabItems = $this->rab->items()->with('item.category.unit')->get();

        foreach ($rabItems as $rabItem) {
            $this->items[] = [
                'rab_item_id' => $rabItem->id,
                'item_id' => $rabItem->item_id,
                'item_code' => $rabItem->item->code ?? '-',
                'item_name' => $rabItem->item->spec ?? '-',
                'item_category' => $rabItem->item->category->name ?? '-',
                'item_unit' => $rabItem->item->category->unit->name ?? '',
                'qty_rab' => $rabItem->qty,
                'qty_request' => 0,
                'max_qty' => 0,
            ];
        }
    }

    public function openWarehouseModal()
    {
        $this->showWarehouseModal = true;

        $this->dispatch('openWarehouseModal', data: [
            'warehouses' => $this->getWarehousesWithStockInfo()->toArray(),
            'items' => $this->items,
        ]);
    }

    public function selectWarehouse($warehouseId)
    {
        $this->warehouse_id = $warehouseId;
        $this->showWarehouseModal = false;

        // Update max qty untuk setiap item berdasarkan stok gudang yang dipilih
        $this->updateItemsMaxQty();
    }

    protected $listeners = ['warehouseSelected' => 'selectWarehouse'];

    public function updateItemsMaxQty()
    {
        if (!$this->warehouse_id || empty($this->items))
            return;

        foreach ($this->items as $key => $item) {
            $stock = Stock::where('warehouse_id', $this->warehouse_id)
                ->where('item_id', $item['item_id'])
                ->first();

            $stockQty = $stock ? $stock->qty : 0;
            $rabQty = $item['qty_rab'];

            // Max qty adalah yang terkecil antara stok dan qty rab
            $this->items[$key]['max_qty'] = min($stockQty, $rabQty);

            // Reset qty_request jika melebihi max_qty
            if ($this->items[$key]['qty_request'] > $this->items[$key]['max_qty']) {
                $this->items[$key]['qty_request'] = $this->items[$key]['max_qty'];
            }
        }
    }

    public function updatedWarehouseId()
    {
        $this->updateItemsMaxQty();
    }

    public function getWarehousesWithStockInfo()
    {
        if (!$this->rab)
            return collect();

        $warehouses = Warehouse::where('sudin_id', $this->rab->sudin_id)
            ->with([
                'stocks' => function ($query) {
                    if (!empty($this->items)) {
                        $itemIds = collect($this->items)->pluck('item_id')->toArray();
                        $query->whereIn('item_id', $itemIds);
                    }
                },
                'stocks.item'
            ])
            ->get();

        return $warehouses->map(function ($warehouse) {
            $rabItemIds = collect($this->items)->pluck('item_id')->toArray();

            // Hitung total item dari RAB
            $totalRabItems = count($rabItemIds);

            // Gunakan collection stocks yang sudah di-load
            $warehouseStocks = $warehouse->stocks;

            // Hitung item yang tersedia di gudang (qty > 0)
            $availableItems = $warehouseStocks->filter(function ($stock) {
                return $stock->qty > 0;
            })->count();

            // Hitung item yang memenuhi kebutuhan RAB
            $fulfilledItems = 0;
            foreach ($this->items as $item) {
                $stock = $warehouseStocks->firstWhere('item_id', $item['item_id']);

                if ($stock && $stock->qty >= $item['qty_rab']) {
                    $fulfilledItems++;
                }
            }

            // Status: bisa dipilih jika minimal ada 1 item dengan stok > 0
            $canSelect = $availableItems > 0;

            // Fully meets jika semua item RAB terpenuhi
            $fullyMeets = $fulfilledItems === $totalRabItems;

            return [
                'id' => $warehouse->id,
                'name' => $warehouse->name,
                'address' => $warehouse->address ?? '-',
                'total_rab_items' => $totalRabItems,
                'available_items' => $availableItems,
                'fulfilled_items' => $fulfilledItems,
                'can_select' => $canSelect,
                'fully_meets' => $fullyMeets,
                'stocks' => $warehouseStocks->map(function ($stock) {
                    return [
                        'id' => $stock->id,
                        'item_id' => $stock->item_id,
                        'qty' => $stock->qty,
                    ];
                })->toArray(),
            ];
        });
    }

    public function validateForm()
    {
        if (!$this->rab) {
            $this->dispatch('alert', type: 'error', title: 'Gagal!', text: 'Silakan cari dan pilih RAB terlebih dahulu');
            return;
        }

        $validator = Validator::make(
            [
                'rab_nomor' => $this->rab_nomor,
                'nomor' => $this->nomor,
                'warehouse_id' => $this->warehouse_id,
                'tanggal_permintaan' => $this->tanggal_permintaan,
                'notes' => $this->notes,
            ],
            [
                'rab_nomor' => 'required|string',
                'nomor' => 'required|string|max:255',
                'warehouse_id' => 'required|exists:warehouses,id',
                'tanggal_permintaan' => 'required|date',
                'notes' => 'nullable|string',
            ],
            [
                'rab_nomor.required' => 'Nomor RAB wajib diisi',
                'nomor.required' => 'Nomor SPB wajib diisi',
                'nomor.string' => 'Nomor SPB harus berupa teks',
                'nomor.max' => 'Nomor SPB maksimal 255 karakter',
                'warehouse_id.required' => 'Gudang wajib dipilih',
                'warehouse_id.exists' => 'Gudang yang dipilih tidak valid',
                'tanggal_permintaan.required' => 'Tanggal permintaan wajib diisi',
                'tanggal_permintaan.date' => 'Format tanggal permintaan tidak valid',
                'notes.string' => 'Keterangan harus berupa teks',
            ]
        );

        if ($validator->fails()) {
            $this->dispatch('alert', type: 'error', title: 'Gagal!', text: $validator->errors()->first());
            return;
        }

        // Validasi minimal ada 1 item yang diminta
        $hasItems = collect($this->items)->filter(fn($item) => $item['qty_request'] > 0)->isNotEmpty();
        if (!$hasItems) {
            $this->dispatch('alert', type: 'error', title: 'Gagal!', text: 'Minimal harus ada 1 barang yang diminta');
            return;
        }

        $this->dispatch('validation-passed-create');
        return;
    }

    #[On('confirm-save-permintaan-rab')]
    public function confirmSave()
    {
        $request = RequestModel::create([
            'nomor' => $this->nomor,
            'name' => $this->name,
            'sudin_id' => $this->sudin_id,
            'warehouse_id' => $this->warehouse_id,
            'item_type_id' => $this->rab->item_type_id, // ambil dari RAB
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
            'rab_id' => $this->rab->id,
        ]);

        // Save items
        foreach ($this->items as $item) {
            if ($item['qty_request'] > 0) {
                RequestItem::create([
                    'request_id' => $request->id,
                    'item_id' => $item['item_id'],
                    'qty_request' => $item['qty_request'],
                ]);
            }
        }

        // Save documents
        $this->dispatch('saveDocuments', modelId: $request->id);

        session()->flash('success', 'Permintaan berhasil dibuat');
        return redirect()->route('permintaan.rab.show', $request);
    }

    public function render()
    {
        return view('livewire.permintaan.rab.create', [
            'warehousesWithStock' => $this->getWarehousesWithStockInfo(),
        ]);
    }
}
