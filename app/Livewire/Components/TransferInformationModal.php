<?php

namespace App\Livewire\Components;

use App\Models\Sudin;
use Livewire\Component;

class TransferInformationModal extends Component
{
    public $mode = 'create'; // 'create' or 'show'
    public $showModal = false;

    // Form fields
    public $sudin_pengirim_id = '';
    public $sudin_penerima_id = '';
    public $tanggal_transfer = '';
    public $notes = '';

    // For show mode
    public $transfer_id = null;
    public $status = '';
    public $status_text = '';
    public $status_color = '';
    public $pembuat = '';

    protected $listeners = [
        'openTransferModal' => 'openTransferModal',
        'setTransferData' => 'setTransferData',
    ];

    public function mount($mode = 'create')
    {
        $this->mode = $mode;
        $this->showModal = $mode === 'create';
        $this->tanggal_transfer = now()->format('Y-m-d');
    }

    public function rules()
    {
        return [
            'sudin_pengirim_id' => 'required|exists:sudins,id',
            'sudin_penerima_id' => 'required|exists:sudins,id|different:sudin_pengirim_id',
            'tanggal_transfer' => 'required|date',
            'notes' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'sudin_penerima_id.different' => 'Sudin Diminta harus berbeda dengan Sudin Peminta',
        ];
    }

    public function openTransferModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        if ($this->mode === 'create') {
            return redirect()->route('transfer.permintaan.index');
        }

        $this->showModal = false;
        $this->dispatch('close-modal', 'transfer-information-modal');
    }

    public function setTransferData($data)
    {
        $this->transfer_id = $data['id'] ?? null;
        $this->sudin_pengirim_id = $data['sudin_pengirim_id'] ?? '';
        $this->sudin_penerima_id = $data['sudin_penerima_id'] ?? '';
        $this->tanggal_transfer = $data['tanggal_transfer'] ?? '';
        $this->notes = $data['notes'] ?? '';
        $this->status = $data['status'] ?? '';
        $this->status_text = $data['status_text'] ?? '';
        $this->status_color = $data['status_color'] ?? '';
        $this->pembuat = $data['pembuat'] ?? '';
    }

    public function saveInformation()
    {
        if ($this->mode === 'show') {
            $this->showModal = false;
            $this->dispatch('close-modal', 'transfer-information-modal');
            return;
        }

        $this->validate();

        // Dispatch event dengan data
        $this->dispatch('transferInformationSaved', [
            'sudin_pengirim_id' => $this->sudin_pengirim_id,
            'sudin_penerima_id' => $this->sudin_penerima_id,
            'tanggal_transfer' => $this->tanggal_transfer,
            'notes' => $this->notes,
        ]);

        $this->showModal = false;
        $this->dispatch('close-modal', 'transfer-information-modal');
    }

    public function render()
    {
        $sudins = Sudin::orderBy('name')->get();

        return view('livewire.components.transfer-information-modal', [
            'sudins' => $sudins,
        ]);
    }
}
