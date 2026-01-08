<?php

namespace App\Livewire\Permintaan\NonRab;

use App\Models\RequestModel;
use App\Models\Sudin;
use App\Models\Warehouse;
use App\Models\District;
use App\Models\Subdistrict;
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
    }

    public function updatedDistrictId($value)
    {
        $this->subdistrict_id = '';
    }

    public function save()
    {
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
            'rab_id' => null,
        ]);

        session()->flash('success', 'Permintaan berhasil dibuat');
        return redirect()->route('permintaan.nonRab.show', $request);
    }

    public function render()
    {
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
        ]);
    }
}
