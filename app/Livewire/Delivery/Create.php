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
    public $contract, $isApi = true, $warehouse;
    public function mount()
    {
        // $this->dispatch('open-modal', 'input-contract-number');
        $this->dispatch('open-modal', 'choose-warehouse');
        $this->warehouse = Warehouse::first();
        $this->contract = Contract::first();
        // $this->contractNumber = '20397/PN01.02';
    }

    #[On("confirmContract")]
    public function confirmResult()
    {
        $this->dispatch('endLoading');
        $this->dispatch('open-modal', 'confirm-contract');
    }

    #[On('proceedCreateContractAgain')]
    public function confirmContractAgain(Contract $contract)
    {
        $this->contract = $contract;
    }
    #[On('proceedWarehouse')]
    public function proceedWarehouse(Warehouse $warehouse)
    {
        $this->warehouse = $warehouse;
        $this->checkIfWarehouseAndContractFilled();
    }


    private function checkIfWarehouseAndContractFilled()
    {
        if ($this->contract && $this->warehouse) {
            $this->dispatch('fillCreateTable', warehouse: $this->warehouse->id, contract: $this->contract->id);
        }
    }

    public function render()
    {
        return view('livewire.delivery.create');
    }
}
