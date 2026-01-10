<?php

namespace App\Livewire\Rab;

use App\Models\Rab;
use App\Models\Sudin;
use App\Models\District;
use App\Models\Subdistrict;
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
            'district_id' => 'nullable|exists:districts,id',
            'subdistrict_id' => 'nullable|exists:subdistricts,id',
            'address' => 'nullable|string',
            'panjang' => 'nullable|string|max:255',
            'lebar' => 'nullable|string|max:255',
            'tinggi' => 'nullable|string|max:255',
        ];
    }

    public function updatedSudinId($value)
    {
        $this->district_id = '';
        $this->subdistrict_id = '';
    }

    public function updatedDistrictId($value)
    {
        $this->subdistrict_id = '';
    }

    public function save()
    {
        $this->validate();

        $rab = Rab::create([
            'nomor' => $this->nomor,
            'name' => $this->name,
            'tahun' => $this->tahun,
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

        // Save documents
        $this->dispatch('saveDocuments', modelId: $rab->id);

        session()->flash('success', 'RAB berhasil dibuat');
        return redirect()->route('rab.show', $rab);
    }

    public function render()
    {
        return view('livewire.rab.create', [
            'sudins' => Sudin::orderBy('name')->get(),
            'districts' => $this->sudin_id
                ? District::where('sudin_id', $this->sudin_id)->orderBy('name')->get()
                : collect(),
            'subdistricts' => $this->district_id
                ? Subdistrict::where('district_id', $this->district_id)->orderBy('name')->get()
                : collect(),
        ]);
    }
}
