<?php

namespace App\Livewire\Components;

use App\Models\Division;
use App\Models\Sudin;
use App\Models\Subdistrict;
use Livewire\Component;

class RabInformationModal extends Component
{
    public $mode = 'create'; // 'create' or 'show'
    public $showModal = false;

    // Form fields
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

    // For show mode
    public $rab_id = null;
    public $status = '';
    public $status_text = '';
    public $status_color = '';
    public $pembuat = '';
    public $total = 0;
    public $item_type = '';

    protected $listeners = [
        'openRabModal' => 'openRabModal',
        'setRabData' => 'setRabData',
    ];

    public function mount($mode = 'create')
    {
        $this->mode = $mode;
        $this->showModal = $mode === 'create';
        $this->tahun = now()->year;
    }

    public function rules()
    {
        $rules = [
            'nomor' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'tahun' => 'required|integer|min:2000|max:2100',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'sudin_id' => 'required|exists:sudins,id',
            'district_id' => 'required|exists:divisions,id',
            'subdistrict_id' => 'required|exists:subdistricts,id',
            'address' => 'required|string',
            'panjang' => 'nullable|string|max:255',
            'lebar' => 'nullable|string|max:255',
            'tinggi' => 'nullable|string|max:255',
        ];

        return $rules;
    }

    public function updatedSudinId($value)
    {
        if ($this->mode === 'create') {
            $this->district_id = '';
            $this->subdistrict_id = '';
        }
    }

    public function updatedDistrictId($value)
    {
        if ($this->mode === 'create') {
            $this->subdistrict_id = '';
        }
    }

    public function openRabModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        if ($this->mode === 'create') {
            return redirect()->route('rab.index');
        }

        $this->showModal = false;
        $this->dispatch('close-modal', 'rab-information-modal');
    }

    public function setRabData($data)
    {
        $this->rab_id = $data['id'] ?? null;
        $this->nomor = $data['nomor'] ?? '';
        $this->name = $data['name'] ?? '';
        $this->tahun = $data['tahun'] ?? now()->year;
        $this->tanggal_mulai = $data['tanggal_mulai'] ?? '';
        $this->tanggal_selesai = $data['tanggal_selesai'] ?? '';
        $this->sudin_id = $data['sudin_id'] ?? '';
        $this->district_id = $data['district_id'] ?? '';
        $this->subdistrict_id = $data['subdistrict_id'] ?? '';
        $this->address = $data['address'] ?? '';
        $this->panjang = $data['panjang'] ?? '';
        $this->lebar = $data['lebar'] ?? '';
        $this->tinggi = $data['tinggi'] ?? '';
        $this->status = $data['status'] ?? '';
        $this->status_text = $data['status_text'] ?? '';
        $this->status_color = $data['status_color'] ?? '';
        $this->pembuat = $data['pembuat'] ?? '';
        $this->total = $data['total'] ?? 0;
        $this->item_type = $data['item_type'] ?? '';
    }

    public function saveInformation()
    {
        if ($this->mode === 'show') {
            $this->showModal = false;
            $this->dispatch('close-modal', 'rab-information-modal');
            return;
        }

        $this->validate();

        // Dispatch event dengan data
        $this->dispatch('rabInformationSaved', [
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
        ]);

        $this->showModal = false;
        $this->dispatch('close-modal', 'rab-information-modal');
    }

    public function render()
    {
        $sudins = collect();
        $districts = collect();
        $subdistricts = collect();

        if ($this->mode === 'create') {
            $sudins = Sudin::all();
            if ($this->sudin_id) {
                $districts = Division::where('sudin_id', $this->sudin_id)->where('type', 'district')->get();
            }
            if ($this->district_id) {
                $subdistricts = Subdistrict::where('division_id', $this->district_id)->get();
            }
        } else {
            // Mode show - load all options to display selected values
            $sudins = Sudin::all();
            if ($this->sudin_id) {
                $districts = Division::where('sudin_id', $this->sudin_id)->where('type', 'district')->get();
            }
            if ($this->district_id) {
                $subdistricts = Subdistrict::where('division_id', $this->district_id)->get();
            }
        }

        return view('livewire.components.rab-information-modal', [
            'sudins' => $sudins,
            'districts' => $districts,
            'subdistricts' => $subdistricts,
        ]);
    }
}
