<?php

namespace App\Livewire\Contract;

use App\Models\Item;
use App\Models\Contract;
use Livewire\Component;
use App\Models\ContractAmendment;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
use App\Models\ContractAmendmentItem;

class CreateAmendment extends Component
{
    #[Title('Buat Adendum Kontrak')]

    public $contractId;
    public $nomor = '';
    public $items = [];

    // Form tambah barang
    public $namaBarang;
    public $spesifikasiBarang;
    public $satuanBarang;
    public $jumlahBarang;
    public $hargaSatuanBarang;
    public $ppnBarang = 0;

    // Data untuk select
    public $barangs;
    public $units;

    // Untuk menyimpan item asli dari kontrak
    public $originalItems = [];

    // Untuk menyimpan jumlah yang sudah dikirim
    public $deliveredQuantities = [];

    // Computed property untuk contract
    #[Computed]
    public function contract()
    {
        return Contract::findOrFail($this->contractId);
    }

    public function mount($contract)
    {
        $this->contractId = $contract;
        $contractModel = $this->contract;

        // Load data untuk select
        $this->barangs = \App\Models\ItemCategory::all();
        $this->units = \App\Models\ItemUnit::all();

        // Load items dari versi terbaru (amendment terakhir atau kontrak asli)
        $latestVersion = $contractModel->latestVersion;

        if ($latestVersion instanceof ContractAmendment) {
            // Jika ada amendment, ambil dari amendment terakhir
            foreach ($latestVersion->items as $item) {
                $deliveredQty = $this->getDeliveredQty($item->item_id);

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
                    'delivered_qty' => $deliveredQty,
                    'min_qty' => $deliveredQty,
                ];

                $this->deliveredQuantities[$item->item_id] = $deliveredQty;
            }
        } else {
            // Jika belum ada amendment, ambil dari kontrak asli
            foreach ($contractModel->items as $item) {
                $deliveredQty = $this->getDeliveredQty($item->item_id);

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
                    'delivered_qty' => $deliveredQty,
                    'min_qty' => $deliveredQty,
                ];

                $this->deliveredQuantities[$item->item_id] = $deliveredQty;
            }
        }

        $this->originalItems = collect($this->items)->pluck('item_id')->toArray();
    }

    private function getDeliveredQty($itemId)
    {
        $contractModel = $this->contract;
        // Hitung total yang sudah dikirim dari semua delivery untuk item ini
        return \App\Models\DeliveryItem::whereHas('delivery', function ($q) use ($contractModel) {
            $q->where('contract_id', $contractModel->id);
        })->where('item_id', $itemId)->sum('qty');
    }

    public function saveItem()
    {
        $this->validate([
            'namaBarang' => 'required|string',
            'spesifikasiBarang' => 'required|string',
            'satuanBarang' => 'required|string',
            'jumlahBarang' => 'required|numeric|min:0.01',
            'hargaSatuanBarang' => 'required|numeric|min:0',
        ], [
            'namaBarang.required' => 'Nama barang wajib diisi',
            'spesifikasiBarang.required' => 'Spesifikasi wajib diisi',
            'satuanBarang.required' => 'Satuan wajib diisi',
            'jumlahBarang.required' => 'Jumlah wajib diisi',
            'jumlahBarang.min' => 'Jumlah minimal 0.01',
            'hargaSatuanBarang.required' => 'Harga satuan wajib diisi',
            'hargaSatuanBarang.min' => 'Harga tidak boleh negatif',
        ]);


        // Cari atau buat unit
        $unit = \App\Models\ItemUnit::firstOrCreate(
            ['slug' => \Illuminate\Support\Str::slug($this->satuanBarang)],
            ['name' => $this->satuanBarang]
        );

        // Cari tipe barang default (ambil pertama)
        $itemType = \App\Models\ItemType::first();
        if (!$itemType) {
            session()->flash('error', 'Tipe barang belum tersedia. Silakan tambahkan tipe barang terlebih dahulu.');
            return;
        }

        // Cari atau buat category
        $category = \App\Models\ItemCategory::firstOrCreate(
            ['slug' => \Illuminate\Support\Str::slug($this->namaBarang)],
            [
                'name' => $this->namaBarang,
                'item_unit_id' => $unit->id,
                'item_type_id' => $itemType->id,
            ]
        );

        // Cari atau buat item
        $contractModel = $this->contract;
        $item = Item::firstOrCreate(
            [
                'slug' => \Illuminate\Support\Str::slug($this->spesifikasiBarang),
                'item_category_id' => $category->id,
            ],
            [
                'sudin_id' => $contractModel->sudin_id,
                'code' => 'ITEM-' . strtoupper(\Illuminate\Support\Str::random(8)),
                'spec' => $this->spesifikasiBarang,
            ]
        );

        // Cek apakah item sudah ada di list
        $exists = collect($this->items)->firstWhere('item_id', $item->id);
        if ($exists) {
            session()->flash('error', 'Item dengan spesifikasi yang sama sudah ada dalam daftar');
            return;
        }

        // Hitung jumlah yang sudah dikirim untuk item ini
        $deliveredQty = $this->getDeliveredQty($item->id);

        // Tambahkan ke list items
        $this->items[] = [
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'item_id' => $item->id,
            'item_name' => $category->name . ' | ' . $item->spec,
            'item_code' => $item->code,
            'item_unit' => $unit->name,
            'qty' => (float) $this->jumlahBarang,
            'price' => (float) $this->hargaSatuanBarang,
            'subtotal' => (float) ($this->jumlahBarang * $this->hargaSatuanBarang),
            'is_original' => false,
            'delivered_qty' => $deliveredQty,
            'min_qty' => $deliveredQty,
        ];

        // Reset form
        $this->reset(['namaBarang', 'spesifikasiBarang', 'satuanBarang', 'jumlahBarang', 'hargaSatuanBarang', 'ppnBarang']);

        session()->flash('success', 'Barang berhasil ditambahkan');
    }

    public function updateQty($index, $qty)
    {
        $item = $this->items[$index];

        // Validasi: qty tidak boleh kurang dari yang sudah dikirim
        if ($qty < $item['min_qty']) {
            session()->flash('error', 'Jumlah tidak boleh kurang dari yang sudah dikirim (' . $item['min_qty'] . ')');
            return;
        }

        $this->items[$index]['qty'] = (float) $qty;
        $this->items[$index]['subtotal'] = $qty * $this->items[$index]['price'];
    }

    public function updatePrice($index, $price)
    {
        $this->items[$index]['price'] = (float) $price;
        $this->items[$index]['subtotal'] = $this->items[$index]['qty'] * $price;
    }

    public function removeItem($index)
    {
        $item = $this->items[$index];

        // Cek apakah item adalah item original
        if ($item['is_original']) {
            session()->flash('error', 'Item original tidak bisa dihapus. Anda hanya bisa mengurangi jumlahnya.');
            return;
        }

        unset($this->items[$index]);
        $this->items = array_values($this->items);

        session()->flash('success', 'Item berhasil dihapus');
    }

    public function save()
    {
        $contractModel = $this->contract;

        $this->validate([
            'nomor' => 'required|string|unique:contract_amendments,nomor',
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

        // Validasi tambahan: qty tidak boleh kurang dari delivered
        foreach ($this->items as $item) {
            if ($item['qty'] < $item['min_qty']) {
                session()->flash('error', 'Jumlah ' . $item['item_name'] . ' tidak boleh kurang dari yang sudah dikirim (' . $item['min_qty'] . ')');
                return;
            }
        }

        try {
            \DB::beginTransaction();

            // Hitung total
            $total = collect($this->items)->sum('subtotal');

            // Buat amendment
            $amendment = ContractAmendment::create([
                'contract_id' => $contractModel->id,
                'amend_version' => $contractModel->getNextAmendmentVersion(),
                'nomor' => $this->nomor,
                'total' => $total,
                'status' => 'approved', // Langsung approved atau bisa 'draft'
            ]);

            // Simpan items
            foreach ($this->items as $item) {
                ContractAmendmentItem::create([
                    'contract_amendment_id' => $amendment->id,
                    'item_id' => $item['item_id'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            \DB::commit();

            session()->flash('success', 'Adendum kontrak berhasil dibuat');
            return redirect()->route('contract.show', $contractModel->id);

        } catch (\Exception $e) {
            \DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.contract.create-amendment');
    }
}
