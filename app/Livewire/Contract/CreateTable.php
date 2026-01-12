<?php

namespace App\Livewire\Contract;

use App\Models\Item;
use App\Models\ItemUnit;
use App\Models\Sudin;
use App\Models\User;
use Livewire\Component;
use App\Models\Contract;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use App\Models\ItemCategory;
use Illuminate\Support\Facades\Validator;

class CreateTable extends Component
{
    public $namaBarang, $spesifikasiBarang, $satuanBarang, $jumlahBarang, $hargaSatuanBarang, $ppnBarang = 0;
    public $barangs, $units;
    public $listBarang = [];
    public $contractNumber, $contractYear, $apiExist = false, $dataContract = [];

    public function mount()
    {
        $this->barangs = ItemCategory::all();
        $this->units = ItemUnit::all();
        // isi $listBarang dengan data awal untuk kebutuhan testing
        $this->listBarang = [
            [
                'namaBarang' => 'Contoh Barang 1',
                'spesifikasiBarang' => 'Spesifikasi Barang 1',
                'satuanBarang' => 'Unit',
                'jumlahBarang' => 10,
                'hargaSatuanBarang' => 50000,
                'ppnBarang' => 11,
            ],
            [
                'namaBarang' => 'Contoh Barang 2',
                'spesifikasiBarang' => 'Spesifikasi Barang 2',
                'satuanBarang' => 'Pcs',
                'jumlahBarang' => 5,
                'hargaSatuanBarang' => 75000,
                'ppnBarang' => 0,
            ],
            [
                'namaBarang' => 'Contoh Barang 3',
                'spesifikasiBarang' => 'Spesifikasi Barang 3',
                'satuanBarang' => 'Box',
                'jumlahBarang' => 20,
                'hargaSatuanBarang' => 20000,
                'ppnBarang' => 12,
            ],

        ];
        $this->dispatch('listCountUpdated', count($this->listBarang));

    }

    #[On("proceedCreateContractAgain")]
    public function updateDataContract($data)
    {
        $this->dataContract = $data['dataContract'];
        $this->contractNumber = $data['no_spk'];
        $this->contractYear = $data['tahun_anggaran'];
        $this->apiExist = $data['apiExist'];
    }


    public function save()
    {
        $validator = Validator::make(
            [
                'namaBarang' => $this->namaBarang,
                'spesifikasiBarang' => $this->spesifikasiBarang,
                'satuanBarang' => $this->satuanBarang,
                'jumlahBarang' => $this->jumlahBarang,
                'hargaSatuanBarang' => $this->hargaSatuanBarang,
                'ppnBarang' => $this->ppnBarang,
            ],
            [
                'namaBarang' => 'required|string',
                'spesifikasiBarang' => 'required|string',
                'satuanBarang' => 'required|string',
                'jumlahBarang' => 'required|numeric|min:1',
                'hargaSatuanBarang' => 'required|numeric|min:0',
                // 'ppnBarang' => 'in:0,11,12',
            ],
            [
                'namaBarang.required' => 'Nama barang wajib diisi.',
                'spesifikasiBarang.required' => 'Spesifikasi barang wajib diisi.',
                'satuanBarang.required' => 'Satuan barang wajib diisi.',
                'jumlahBarang.required' => 'Jumlah barang wajib diisi.',
                'jumlahBarang.numeric' => 'Jumlah barang harus berupa angka.',
                'jumlahBarang.min' => 'Jumlah barang minimal 1.',
                'hargaSatuanBarang.required' => 'Harga satuan barang wajib diisi.',
                'hargaSatuanBarang.numeric' => 'Harga satuan barang harus berupa angka.',
                'hargaSatuanBarang.min' => 'Harga satuan barang minimal 0.',
                // 'ppnBarang.required' => 'PPN barang wajib diisi.',
                // 'ppnBarang.in' => 'PPN barang harus bernilai 0 atau 10.',
            ]
        );

        if ($validator->fails()) {
            // dispatch kalau gagal
            $this->dispatch('alert', type: 'error', title: 'Gagal!', text: $validator->errors()->first());

            return;
        }
        $this->listBarang[] = [
            'namaBarang' => $this->namaBarang,
            'spesifikasiBarang' => $this->spesifikasiBarang,
            'satuanBarang' => $this->satuanBarang,
            'jumlahBarang' => $this->jumlahBarang,
            'hargaSatuanBarang' => $this->hargaSatuanBarang,
            'ppnBarang' => $this->ppnBarang,
        ];
        $this->dispatch('listCountUpdated', count($this->listBarang));

        // reset input setelah disave
        $this->resetExcept(['listBarang', 'contractNumber', 'contractYear', 'apiExist', 'dataContract', 'barangs', 'units']);

    }
    public function removeItem($index)
    {
        if (isset($this->listBarang[$index])) {
            unset($this->listBarang[$index]);
            // Reindex array setelah penghapusan
            $this->listBarang = array_values($this->listBarang);
            $this->dispatch('listCountUpdated', count($this->listBarang));
        }

    }

    #[On("saveContract")]
    public function saveContract()
    {
        $sudin = Sudin::all()->first(); // ambil sudin pertama untuk testing
        $contract = Contract::create([
            'is_api' => $this->apiExist,
            'sudin_id' => $sudin->id,
            'nomor' => $this->contractNumber,
            'tanggal_mulai' => $this->dataContract['tgl_spk'] ?? null,
            'tanggal_selesai' => $this->dataContract['tgl_akhir_spk'] ?? null,
            'user_id' => User::all()->first()->id,
        ]);
        // simpan list barang ke tabel
        foreach ($this->listBarang as $item) {
            $unit = ItemUnit::firstOrCreate(['slug' => Str::slug($item['satuanBarang'])], ['name' => $item['satuanBarang']]);
            $itemCategory = ItemCategory::firstOrCreate(
                [
                    'slug' => Str::slug($item['namaBarang'])
                ],
                [
                    'name' => $item['namaBarang'],
                    'item_unit_id' => $unit->id,
                ]
            );
            $itemSave = Item::firstOrCreate(
                [
                    'slug' => Str::slug($item['spesifikasiBarang']),
                    'item_category_id' => $itemCategory->id,
                ],
                [
                    'sudin_id' => $sudin->id,
                    'code' => 'ITEM-' . strtoupper(Str::random(8)),
                    'spec' => $item['spesifikasiBarang'],

                ]
            );
            $contract->items()->create([
                'item_id' => $itemSave->id,
                'qty' => $item['jumlahBarang'],
                'price' => $item['hargaSatuanBarang'],
                'subtotal' => $item['jumlahBarang'] * $item['hargaSatuanBarang'],
            ]);
        }
        $this->dispatch('alert', type: 'success', title: 'Berhasil!', text: 'Kontrak berhasil disimpan.');
        return $this->redirect(route('contract.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.contract.create-table');
    }
}
