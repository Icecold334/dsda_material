<?php

namespace App\Livewire\Rab;

use App\Models\Item;
use App\Models\Rab;
use Livewire\Component;
use App\Models\RabAmendment;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
use App\Models\RabAmendmentItem;

class CreateAmendment extends Component
{
    #[Title('Buat Adendum RAB')]

    public $rabId;
    public $nomor = '';
    public $items = [];

    // Form tambah barang - sesuai RAB (tanpa tipe barang)
    public $item_category_id = '';
    public $item_id = '';
    public $jumlahBarang;
    public $hargaSatuanBarang;
    public $ppnBarang = 0;

    // Data untuk select
    public $barangs;
    public $units;

    // Untuk menyimpan item asli dari RAB
    public $originalItems = [];

    // Untuk menyimpan jumlah yang sudah diminta
    public $requestedQuantities = [];

    // Computed property untuk rab
    #[Computed]
    public function rab()
    {
        return Rab::findOrFail($this->rabId);
    }

    public function mount($rab)
    {
        $this->rabId = $rab;
        $rabModel = $this->rab;

        // Load items dari versi terbaru (amendment terakhir atau RAB asli)
        $latestVersion = $rabModel->latestVersion;

        if ($latestVersion instanceof RabAmendment) {
            // Jika ada amendment, ambil dari amendment terakhir
            foreach ($latestVersion->items as $item) {
                $requestedQty = $this->getRequestedQty($item->item_id);

                $this->items[] = [
                    'id' => (string) \Illuminate\Support\Str::uuid(),
                    'item_id' => $item->item_id,
                    'item_name' => $item->item->category->name . ' | ' . $item->item->spec,
                    'item_code' => $item->item->code,
                    'item_unit' => $item->item->category->unit->name,
                    'qty' => (float) $item->qty,
                    'price' => (float) $item->price,
                    'subtotal' => (float) $item->subtotal,
                    'is_original' => true,
                    'requested_qty' => $requestedQty,
                    'min_qty' => $requestedQty,
                ];

                $this->requestedQuantities[$item->item_id] = $requestedQty;
            }
        } else {
            // Jika belum ada amendment, ambil dari RAB asli
            foreach ($rabModel->items as $item) {
                $requestedQty = $this->getRequestedQty($item->item_id);

                $this->items[] = [
                    'id' => (string) \Illuminate\Support\Str::uuid(),
                    'item_id' => $item->item_id,
                    'item_name' => $item->item->category->name . ' | ' . $item->item->spec,
                    'item_code' => $item->item->code,
                    'item_unit' => $item->item->category->unit->name,
                    'qty' => (float) $item->qty,
                    'price' => (float) $item->price,
                    'subtotal' => (float) $item->subtotal,
                    'is_original' => true,
                    'requested_qty' => $requestedQty,
                    'min_qty' => $requestedQty,
                ];

                $this->requestedQuantities[$item->item_id] = $requestedQty;
            }
        }
    }

    public function updatedItemCategoryId($value)
    {
        $this->item_id = '';
    }

    public function getRequestedQty($itemId)
    {
        return $this->rab->getRequestedQuantity($itemId);
    }

    public function updateQty($index)
    {
        $item = $this->items[$index];
        $this->items[$index]['subtotal'] = $item['qty'] * $item['price'];

        // Validasi qty tidak boleh kurang dari yang sudah diminta
        if ($item['qty'] < $item['min_qty']) {
            session()->flash('error', 'Jumlah ' . $item['item_name'] . ' tidak boleh kurang dari yang sudah diminta (' . $item['min_qty'] . ')');
            $this->items[$index]['qty'] = $item['min_qty'];
            $this->items[$index]['subtotal'] = $item['min_qty'] * $item['price'];
        }
    }

    public function removeItem($index)
    {
        $item = $this->items[$index];

        // Hanya bisa hapus item yang bukan original
        if (!$item['is_original']) {
            unset($this->items[$index]);
            $this->items = array_values($this->items);
            session()->flash('success', 'Barang berhasil dihapus');
        } else {
            session()->flash('error', 'Tidak dapat menghapus barang asli RAB');
        }
    }

    public function saveItem()
    {
        $this->validate([
            'item_id' => 'required|exists:items,id',
            'jumlahBarang' => 'required|numeric|min:0.01',
            'hargaSatuanBarang' => 'required|numeric|min:0',
        ], [
            'item_id.required' => 'Pilih barang terlebih dahulu',
            'item_id.exists' => 'Barang tidak valid',
            'jumlahBarang.required' => 'Jumlah wajib diisi',
            'jumlahBarang.min' => 'Jumlah minimal 0.01',
            'hargaSatuanBarang.required' => 'Harga satuan wajib diisi',
            'hargaSatuanBarang.min' => 'Harga tidak boleh negatif',
        ]);

        $item = Item::with('category.unit')->find($this->item_id);

        // Cek apakah item sudah ada di list
        $exists = collect($this->items)->firstWhere('item_id', $item->id);
        if ($exists) {
            session()->flash('error', 'Item dengan spesifikasi yang sama sudah ada dalam daftar');
            return;
        }

        // Hitung jumlah yang sudah diminta untuk item ini
        $requestedQty = $this->getRequestedQty($item->id);

        // Tambahkan ke list items
        $this->items[] = [
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'item_id' => $item->id,
            'item_name' => $item->category->name . ' | ' . $item->spec,
            'item_code' => $item->code,
            'item_unit' => $item->category->unit->name,
            'qty' => (float) $this->jumlahBarang,
            'price' => (float) $this->hargaSatuanBarang,
            'subtotal' => (float) ($this->jumlahBarang * $this->hargaSatuanBarang),
            'is_original' => false,
            'requested_qty' => $requestedQty,
            'min_qty' => $requestedQty,
        ];

        // Reset form
        $this->item_type_id = '';
        $this->item_category_id = '';
        $this->item_id = '';
        $this->jumlahBarang = '';
        $this->hargaSatuanBarang = '';
        $this->ppnBarang = 0;

        session()->flash('success', 'Barang berhasil ditambahkan');
    }

    public function save()
    {
        $rabModel = $this->rab;

        $this->validate([
            'nomor' => 'required|string|unique:rab_amendments,nomor',
            'items' => 'required|array|min:1',
            'items.*.qty' => 'required|numeric|min:0.01',
            'items.*.price' => 'required|numeric|min:0',
        ], [
            'nomor.required' => 'Nomor adendum harus diisi',
            'nomor.unique' => 'Nomor adendum sudah digunakan',
            'items.required' => 'Minimal harus ada 1 item',
            'items.*.qty.required' => 'Jumlah harus diisi',
            'items.*.qty.min' => 'Jumlah minimal 0.01',
            'items.*.price.required' => 'Harga harus diisi',
            'items.*.price.min' => 'Harga tidak boleh negatif',
        ]);

        // Validasi tambahan: qty tidak boleh kurang dari requested
        foreach ($this->items as $item) {
            if ($item['qty'] < $item['min_qty']) {
                session()->flash('error', 'Jumlah ' . $item['item_name'] . ' tidak boleh kurang dari yang sudah diminta (' . $item['min_qty'] . ')');
                return;
            }
        }

        try {
            \DB::beginTransaction();

            // Hitung total
            $total = collect($this->items)->sum('subtotal');

            // Buat amendment
            $amendment = RabAmendment::create([
                'rab_id' => $rabModel->id,
                'amend_version' => $rabModel->getNextAmendmentVersion(),
                'nomor' => $this->nomor,
                'total' => $total,
                'status' => 'approved', // Langsung approved atau bisa 'draft'
            ]);

            // Simpan items
            foreach ($this->items as $item) {
                RabAmendmentItem::create([
                    'rab_amendment_id' => $amendment->id,
                    'item_id' => $item['item_id'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            \DB::commit();

            session()->flash('success', 'Adendum RAB berhasil dibuat');
            return redirect()->route('rab.show', $rabModel->id);

        } catch (\Exception $e) {
            \DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $rabModel = $this->rab;

        // Get item categories directly from RAB's item_type_id
        $itemCategories = collect();
        if ($rabModel->item_type_id && $rabModel->sudin_id) {
            $itemCategories = \App\Models\ItemCategory::where('item_type_id', $rabModel->item_type_id)
                ->whereHas('items', function ($query) use ($rabModel) {
                    $query->where('sudin_id', $rabModel->sudin_id)
                        ->where('active', true);
                })
                ->orderBy('name')
                ->get();
        }

        // Get available items from sudin and category
        $availableItems = collect();
        if ($rabModel->sudin_id && $this->item_category_id) {
            $availableItems = Item::where('sudin_id', $rabModel->sudin_id)
                ->where('item_category_id', $this->item_category_id)
                ->where('active', true)
                ->with('category.unit')
                ->orderBy('spec')
                ->get();
        }

        return view('livewire.rab.create-amendment', [
            'itemCategories' => $itemCategories,
            'availableItems' => $availableItems,
        ]);
    }
}