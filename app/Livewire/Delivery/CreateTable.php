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
    public $listBarang = [], $itemCategories = [], $items, $warehouse, $contract, $itemCategory, $item, $unit = 'Satuan', $maxQty = 0, $qty, $disablAdd, $tanggal_kirim;

    public function mount($warehouse = null, $contract = null, $tanggal_kirim = null)
    {
        $this->items = collect();
        $this->itemCategories = collect();

        if ($warehouse) {
            $this->warehouse = Warehouse::find($warehouse);
        }

        if ($contract) {
            $this->contract = Contract::find($contract);

            if ($this->contract) {
                // Ambil versi terbaru kontrak (amendment terakhir atau kontrak asli)
                $latestVersion = $this->contract->latestVersion;

                // Ambil item IDs dari versi terbaru
                if ($latestVersion instanceof \App\Models\ContractAmendment) {
                    // Jika ada amendment, ambil dari amendment terakhir
                    $itemIds = \DB::table('contract_amendment_items')
                        ->where('contract_amendment_id', $latestVersion->id)
                        ->pluck('item_id');
                } else {
                    // Jika belum ada amendment, ambil dari kontrak asli
                    $itemIds = \DB::table('contract_items')
                        ->where('contract_id', $this->contract->id)
                        ->pluck('item_id');
                }

                // Ambil kategori barang dari items tersebut
                $categoryIds = \DB::table('items')
                    ->whereIn('id', $itemIds)
                    ->pluck('item_category_id')
                    ->unique();

                // Filter kategori yang minimal punya 1 spesifikasi dengan stock tersisa
                $this->itemCategories = ItemCategory::whereIn('id', $categoryIds)
                    ->get()
                    ->filter(function ($category) use ($itemIds, $latestVersion) {
                        // Cek apakah kategori ini punya minimal 1 item dengan stock tersisa
                        $items = Item::where('item_category_id', $category->id)
                            ->whereIn('id', $itemIds)
                            ->get();

                        foreach ($items as $item) {
                            // Ambil qty dari versi terbaru
                            if ($latestVersion instanceof \App\Models\ContractAmendment) {
                                $contractItem = $latestVersion->items()
                                    ->where('item_id', $item->id)
                                    ->first();
                            } else {
                                $contractItem = $this->contract->items()
                                    ->where('item_id', $item->id)
                                    ->first();
                            }

                            if (!$contractItem) {
                                continue;
                            }

                            $qtyContract = $contractItem->qty;

                            // Hitung qty yang sudah dikirim
                            $qtyDelivered = DeliveryItem::whereHas('delivery', function ($q) {
                                $q->where('contract_id', $this->contract->id);
                            })
                                ->where('item_id', $item->id)
                                ->sum('qty');

                            // Hitung qty di list saat ini
                            $qtyInCurrentList = collect($this->listBarang)
                                ->where('item_id', $item->id)
                                ->sum('qty');

                            // Hitung sisa stock
                            $remainingQty = $qtyContract - $qtyDelivered - $qtyInCurrentList;

                            // Jika ada minimal 1 item dengan stock > 0, tampilkan kategori ini
                            if ($remainingQty > 0) {
                                return true;
                            }
                        }

                        return false;
                    });
            }
        }

        if ($tanggal_kirim) {
            $this->tanggal_kirim = $tanggal_kirim;
        }

        $this->checkAdd();
    }

    public function updatedItemCategory()
    {
        $this->item = null;
        $this->unit = ItemCategory::find($this->itemCategory)->unit->name;

        // Ambil versi terbaru kontrak
        $latestVersion = $this->contract->latestVersion;

        // Ambil semua items dari kategori ini yang ada di versi terbaru kontrak
        $allItems = Item::where('item_category_id', $this->itemCategory)->get();

        // Filter items yang masih punya stock tersisa
        $this->items = $allItems->filter(function ($item) use ($latestVersion) {
            // Ambil qty dari versi terbaru
            if ($latestVersion instanceof \App\Models\ContractAmendment) {
                $contractItem = $latestVersion->items()
                    ->where('item_id', $item->id)
                    ->first();
            } else {
                $contractItem = $this->contract->items()
                    ->where('item_id', $item->id)
                    ->first();
            }

            if (!$contractItem) {
                return false;
            }

            $qtyContract = $contractItem->qty;

            // Hitung qty yang sudah dikirim
            $qtyDelivered = DeliveryItem::whereHas('delivery', function ($q) {
                $q->where('contract_id', $this->contract->id);
            })
                ->where('item_id', $item->id)
                ->sum('qty');

            // Hitung qty di list saat ini
            $qtyInCurrentList = collect($this->listBarang)
                ->where('item_id', $item->id)
                ->sum('qty');

            // Hitung sisa stock
            $remainingQty = $qtyContract - $qtyDelivered - $qtyInCurrentList;

            // Hanya tampilkan item dengan stock > 0
            return $remainingQty > 0;
        });

        $this->checkAdd();
    }
    public function updatedItem()
    {
        if (!$this->item || !$this->contract) {
            $this->maxQty = 0;
            $this->checkAdd();
            return;
        }

        // Ambil versi terbaru kontrak
        $latestVersion = $this->contract->latestVersion;

        // Ambil qty dari versi terbaru
        if ($latestVersion instanceof \App\Models\ContractAmendment) {
            $contractItem = $latestVersion->items()
                ->where('item_id', $this->item)
                ->first();
        } else {
            $contractItem = $this->contract->items()
                ->where('item_id', $this->item)
                ->first();
        }

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

        // Hitung qty yang sudah ditambahkan di list barang saat ini
        $qtyInCurrentList = collect($this->listBarang)
            ->where('item_id', $this->item)
            ->sum('qty');

        // Max qty = qty kontrak - qty yang sudah dikirim - qty di list saat ini
        $this->maxQty = $qtyContract - $qtyDelivered - $qtyInCurrentList;
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

        // Reset form dan recalculate maxQty untuk item yang sama
        $currentItem = $this->item;
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

    public function submit()
    {
        // Validasi
        if (empty($this->listBarang)) {
            $this->dispatch('showAlert', [
                'type' => 'warning',
                'title' => 'Gagal!',
                'text' => 'Belum ada barang yang ditambahkan',
            ]);
            return;
        }

        if (!$this->warehouse || !$this->contract) {
            $this->dispatch('showAlert', [
                'type' => 'warning',
                'title' => 'Gagal!',
                'text' => 'Data kontrak atau gudang belum lengkap',
            ]);
            return;
        }

        try {
            \DB::beginTransaction();

            // Generate nomor delivery
            $lastDelivery = Delivery::whereYear('created_at', date('Y'))->latest()->first();
            $lastNumber = $lastDelivery ? intval(substr($lastDelivery->nomor, -4)) : 0;
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            $nomor = 'DEL-' . date('Y') . '-' . $newNumber;

            // Buat delivery
            $delivery = Delivery::create([
                'nomor' => $nomor,
                'contract_id' => $this->contract->id,
                'warehouse_id' => $this->warehouse->id,
                'sudin_id' => $this->contract->sudin_id,
                'user_id' => auth()->id(),
                'status' => 'pending',
                'tanggal_delivery' => $this->tanggal_kirim ?? now()->format('Y-m-d'),
            ]);

            // Simpan items
            foreach ($this->listBarang as $barang) {
                DeliveryItem::create([
                    'delivery_id' => $delivery->id,
                    'item_id' => $barang['item_id'],
                    'qty' => $barang['qty'],
                ]);
            }

            // Simpan dokumen (surat jalan & foto pengiriman)
            $this->dispatch('saveDocuments', modelId: $delivery->id);

            \DB::commit();

            $this->dispatch('showAlert', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Pengiriman berhasil dibuat dengan nomor: ' . $nomor,
            ]);

            // Redirect ke halaman show
            return redirect()->route('delivery.show', $delivery->id);

        } catch (\Exception $e) {
            \DB::rollBack();

            $this->dispatch('showAlert', [
                'type' => 'error',
                'title' => 'Gagal!',
                'text' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ]);
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
