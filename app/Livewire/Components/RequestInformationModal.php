<?php

namespace App\Livewire\Components;

use App\Models\Division;
use App\Models\Sudin;
use App\Models\Subdistrict;
use App\Models\Warehouse;
use Livewire\Component;

class RequestInformationModal extends Component
{
    public $mode = 'create'; // 'create' atau 'show'
    public $isRab = false;
    public $showModal = false;
    public $isFirstTime = true; // Track apakah ini pembukaan pertama

    // Data form
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

    // Data untuk show mode
    public $status = '';
    public $status_text = '';
    public $status_color = '';
    public $pemohon = '';
    public $item_type = '';
    public $rab_nomor = '';
    public $rab_tahun = '';
    public $rab_id = '';

    // Data untuk RAB (readonly)
    public $rab = null;

    protected $listeners = ['openRequestModal', 'setRequestData'];

    public function mount($mode = 'create', $isRab = false)
    {
        $this->mode = $mode;
        $this->isRab = $isRab;

        // Jika mode create, tampilkan modal otomatis
        if ($mode === 'create') {
            $this->showModal = true;
            $this->isFirstTime = true;
        }
    }

    public function rules()
    {
        $rules = [
            'nomor' => 'required|string|max:255',
            'name' => $this->isRab ? 'nullable' : 'required|string|max:255',
            'sudin_id' => $this->isRab ? 'nullable' : 'required|exists:sudins,id',
            'warehouse_id' => $this->isRab ? 'nullable' : 'required|exists:warehouses,id',
            'district_id' => $this->isRab ? 'nullable' : 'required|exists:divisions,id',
            'subdistrict_id' => $this->isRab ? 'nullable' : 'required|exists:subdistricts,id',
            'tanggal_permintaan' => 'required|date',
            'address' => $this->isRab ? 'nullable' : 'required|string',
            'panjang' => 'nullable|string|max:255',
            'lebar' => 'nullable|string|max:255',
            'tinggi' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ];

        return $rules;
    }

    public function updatedSudinId($value)
    {
        if ($this->mode === 'create' && !$this->isRab) {
            $this->warehouse_id = '';
            $this->district_id = '';
            $this->subdistrict_id = '';
            $this->dispatch('sudinChanged', $value);
        }
    }

    public function updatedDistrictId($value)
    {
        if ($this->mode === 'create' && !$this->isRab) {
            $this->subdistrict_id = '';
        }
    }

    public function updatedWarehouseId($value)
    {
        if ($this->mode === 'create') {
            $this->dispatch('warehouseChanged', $value);
        }
    }

    public function openRequestModal()
    {
        $this->showModal = true;
        // Jika sudah pernah save, ini bukan first time lagi
        if (!empty($this->nomor)) {
            $this->isFirstTime = false;
        }
    }

    public function setRequestData($data)
    {
        $this->nomor = $data['nomor'] ?? '';
        $this->name = $data['name'] ?? '';
        $this->sudin_id = $data['sudin_id'] ?? '';
        $this->warehouse_id = $data['warehouse_id'] ?? '';
        $this->district_id = $data['district_id'] ?? '';
        $this->subdistrict_id = $data['subdistrict_id'] ?? '';
        $this->tanggal_permintaan = $data['tanggal_permintaan'] ?? '';
        $this->address = $data['address'] ?? '';
        $this->panjang = $data['panjang'] ?? '';
        $this->lebar = $data['lebar'] ?? '';
        $this->tinggi = $data['tinggi'] ?? '';
        $this->notes = $data['notes'] ?? '';
        $this->status = $data['status'] ?? '';
        $this->status_text = $data['status_text'] ?? '';
        $this->status_color = $data['status_color'] ?? '';
        $this->pemohon = $data['pemohon'] ?? '';
        $this->item_type = $data['item_type'] ?? '';
        $this->rab_nomor = $data['rab_nomor'] ?? '';
        $this->rab_tahun = $data['rab_tahun'] ?? '';
        $this->rab_id = $data['rab_id'] ?? '';

        if (isset($data['rab'])) {
            $this->rab = $data['rab'];
        }
    }

    public function saveInformation()
    {
        if ($this->mode === 'show') {
            $this->closeModalForce();
            return;
        }

        $this->validate();

        // Dispatch event dengan data
        $this->dispatch('requestInformationSaved', [
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
        ]);

        // Setelah save pertama kali, set isFirstTime = false
        $this->isFirstTime = false;

        $this->closeModalForce();
    }

    public function closeModal()
    {
        // Hanya validasi jika ini pembukaan pertama kali dan belum ada data
        if ($this->mode === 'create' && $this->isFirstTime && empty($this->nomor)) {
            // Jika mode create dan belum ada data, tidak bisa tutup modal
            $this->addError('modal', 'Harap isi informasi permintaan terlebih dahulu');
            return;
        }

        $this->closeModalForce();
    }

    private function closeModalForce()
    {
        $this->showModal = false;
        $this->dispatch('close-modal', 'request-information-modal');
    }

    public function render()
    {
        $sudins = collect();
        $warehouses = collect();
        $districts = collect();
        $subdistricts = collect();

        if ($this->mode === 'create') {
            $sudins = Sudin::all();

            if ($this->sudin_id) {
                $warehouses = Warehouse::where('sudin_id', $this->sudin_id)->get();
                $districts = Division::where('sudin_id', $this->sudin_id)->get();
            }

            if ($this->district_id) {
                $subdistricts = Subdistrict::where('division_id', $this->district_id)->get();
            }
        } elseif ($this->mode === 'show') {
            // Untuk mode show, load data yang diperlukan untuk ditampilkan
            if ($this->sudin_id) {
                $sudins = Sudin::where('id', $this->sudin_id)->get();
                $warehouses = Warehouse::where('id', $this->warehouse_id)->get();
                $districts = Division::where('id', $this->district_id)->get();
                $subdistricts = Subdistrict::where('id', $this->subdistrict_id)->get();
            }

            // Untuk RAB
            if ($this->rab) {
                // Cast array to object if needed
                $rabData = is_array($this->rab) ? (object) $this->rab : $this->rab;
                $sudins = Sudin::where('id', $rabData->sudin_id)->get();
                $districts = Division::where('id', $rabData->district_id)->get();
                $subdistricts = Subdistrict::where('id', $rabData->subdistrict_id)->get();
            }
        }

        return view('livewire.components.request-information-modal', [
            'sudins' => $sudins,
            'warehouses' => $warehouses,
            'districts' => $districts,
            'subdistricts' => $subdistricts,
        ]);
    }
}
