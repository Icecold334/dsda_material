<?php

namespace App\Livewire\Delivery;

use App\Models\Contract;
use App\Models\Warehouse;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;

class Create extends Component
{
    #[Title('Tambah Pengiriman')]

    // Modal state
    public $showModal = true;
    public $showDeliveryModal = false;

    // Search Contract
    public $contract_nomor = '';
    public $contract = null;

    // Data informasi pengiriman
    public $warehouse_id = '';
    public $tanggal_kirim = '';

    // Flag untuk menandakan informasi sudah terisi
    public $informationFilled = false;

    // Modal states untuk dokumen
    public $showSuratJalanModal = false;
    public $showFotoPengirimanModal = false;

    public function rules()
    {
        return [
            'contract_nomor' => 'required|string',
            'warehouse_id' => 'required|exists:warehouses,id',
            'tanggal_kirim' => 'required|date',
        ];
    }

    public function searchContract()
    {
        $this->validate([
            'contract_nomor' => 'required|string',
        ]);

        $contract = Contract::where('nomor', $this->contract_nomor)->first();

        if (!$contract) {
            $this->addError('contract_nomor', 'Kontrak dengan nomor tersebut tidak ditemukan');
            return;
        }

        $this->contract = $contract;
        $this->showModal = false;

        // Tutup modal pencarian kontrak
        $this->dispatch('close-modal', 'input-contract-number');

        // Buka modal informasi pengiriman (sudin_id sudah di-pass via property)
        $this->dispatch('open-modal', 'delivery-information-modal');

        session()->flash('contract_found', 'Kontrak ditemukan! Silakan isi informasi pengiriman.');
    }

    #[On('deliveryInformationSaved')]
    public function handleDeliveryInformationSaved($data)
    {
        $this->warehouse_id = $data['warehouse_id'];
        $this->tanggal_kirim = $data['tanggal_kirim'];
        $this->informationFilled = true;

        session()->flash('success', 'Informasi pengiriman berhasil disimpan! Silakan tambahkan barang.');
    }

    public function openSuratJalanModal()
    {
        $this->showSuratJalanModal = true;
        $this->dispatch('open-modal', 'surat-jalan-modal');
    }

    public function openFotoPengirimanModal()
    {
        $this->showFotoPengirimanModal = true;
        $this->dispatch('open-modal', 'foto-pengiriman-modal');
    }

    public function render()
    {
        return view('livewire.delivery.create');
    }
}
