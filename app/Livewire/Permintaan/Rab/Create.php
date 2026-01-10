<?php

namespace App\Livewire\Permintaan\Rab;

use App\Models\Rab;
use App\Models\RequestModel;
use App\Models\Warehouse;
use Livewire\Attributes\Title;
use Livewire\Component;

class Create extends Component
{
    #[Title('Buat Permintaan dengan RAB')]

    // Modal state
    public $showModal = true;

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

    public function rules()
    {
        return [
            'rab_nomor' => 'required|string',
            'nomor' => 'required|string|max:255',
            'warehouse_id' => 'required|exists:warehouses,id',
            'tanggal_permintaan' => 'required|date',
            'notes' => 'nullable|string',
        ];
    }

    public function searchRab()
    {
        $this->validate([
            'rab_nomor' => 'required|string',
        ]);

        $rab = Rab::with(['sudin', 'district', 'subdistrict', 'user'])
            ->where('nomor', $this->rab_nomor)
            ->first();

        if (!$rab) {
            $this->addError('rab_nomor', 'RAB dengan nomor tersebut tidak ditemukan');
            return;
        }

        if ($rab->status !== 'approved') {
            $this->addError('rab_nomor', 'RAB belum disetujui, tidak dapat membuat permintaan');
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
        $this->showModal = true;
    }

    public function save()
    {
        if (!$this->rab) {
            $this->addError('rab_nomor', 'Silakan cari dan pilih RAB terlebih dahulu');
            return;
        }

        $this->validate();

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
            'rab_id' => $this->rab->id,
        ]);

        // Save documents
        $this->dispatch('saveDocuments', modelId: $request->id);

        session()->flash('success', 'Permintaan berhasil dibuat');
        return redirect()->route('permintaan.rab.show', $request);
    }

    public function render()
    {
        return view('livewire.permintaan.rab.create', [
            'warehouses' => $this->rab
                ? Warehouse::where('sudin_id', $this->rab->sudin_id)->orderBy('name')->get()
                : collect(),
        ]);
    }
}
