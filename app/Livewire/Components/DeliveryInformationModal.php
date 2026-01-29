<?php

namespace App\Livewire\Components;

use App\Models\Warehouse;
use Livewire\Component;

class DeliveryInformationModal extends Component
{
    public $mode = 'create'; // 'create' atau 'show'
    public $showModal = false;
    public $isFirstTime = true; // Track apakah ini pembukaan pertama
    public $modalId = 'delivery-information-modal'; // Unique modal ID

    // Data form
    public $warehouse_id = '';
    public $tanggal_kirim = '';
    public $sudin_id = '';

    // Data untuk show mode
    public $warehouse_name = '';
    public $nomor_kontrak = '';
    public $delivery = null; // Untuk menampilkan data lengkap di show mode

    protected $listeners = ['openDeliveryModal', 'setDeliveryData'];

    public function mount($mode = 'create', $sudin_id = null, $nomor_kontrak = null, $warehouse_id = null, $tanggal_kirim = null, $modalId = 'delivery-information-modal', $delivery = null)
    {
        $this->mode = $mode;
        $this->sudin_id = $sudin_id;
        $this->nomor_kontrak = $nomor_kontrak;
        $this->modalId = $modalId;
        $this->delivery = $delivery;

        // Jika mode show, set warehouse_id dan tanggal_kirim
        if ($mode === 'show') {
            $this->warehouse_id = $warehouse_id;
            $this->tanggal_kirim = $tanggal_kirim;

            if ($warehouse_id) {
                $warehouse = Warehouse::find($warehouse_id);
                $this->warehouse_name = $warehouse ? $warehouse->name : '';
            }
        }

        // Jika mode create, tampilkan modal otomatis
        if ($mode === 'create') {
            $this->showModal = true;
            $this->isFirstTime = true;
        }
    }

    public function rules()
    {
        return [
            'warehouse_id' => 'required|exists:warehouses,id',
            'tanggal_kirim' => 'required|date',
        ];
    }

    public function openDeliveryModal()
    {
        $this->showModal = true;
        $this->dispatch('open-modal', $this->modalId);
    }

    public function closeModal()
    {
        // Hanya bisa tutup jika bukan first time atau mode show
        if (!$this->isFirstTime || $this->mode === 'show') {
            $this->showModal = false;
            $this->dispatch('close-modal', $this->modalId);
        }
    }

    public function setDeliveryData($data)
    {
        $this->warehouse_id = $data['warehouse_id'] ?? '';
        $this->tanggal_kirim = $data['tanggal_kirim'] ?? '';
        $this->nomor_kontrak = $data['nomor_kontrak'] ?? '';
        $this->sudin_id = $data['sudin_id'] ?? '';

        if ($this->mode === 'show') {
            $this->warehouse_name = $data['warehouse_name'] ?? '';
        }

        // Reset warehouse_id jika sudin berubah
        $this->warehouse_id = '';
    }

    public function saveInformation()
    {
        if ($this->mode === 'show') {
            $this->closeModal();
            return;
        }

        try {
            $this->validate();

            // Dispatch event ke parent component
            $this->dispatch('deliveryInformationSaved', [
                'warehouse_id' => $this->warehouse_id,
                'tanggal_kirim' => $this->tanggal_kirim,
            ]);

            $this->isFirstTime = false;
            $this->closeModal();

        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->addError('modal', 'Mohon lengkapi semua field yang diperlukan');
            throw $e;
        }
    }

    public function render()
    {
        $warehouses = $this->sudin_id
            ? Warehouse::where('sudin_id', $this->sudin_id)->get()
            : collect();

        return view('livewire.components.delivery-information-modal', [
            'warehouses' => $warehouses,
        ]);
    }
}
