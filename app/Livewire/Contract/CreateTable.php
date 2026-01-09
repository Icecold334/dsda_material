<?php

namespace App\Livewire\Contract;

use Livewire\Component;
use Livewire\Attributes\On;

class CreateTable extends Component
{
    public $contractNumber, $contractYear, $apiExist, $dataContract = [];

    #[On("confirmContract")]
    public function updateDataContract($data)
    {
        $this->dataContract = $data['dataContract'];
        $this->contractNumber = $data['no_spk'];
        $this->contractYear = $data['tahun_anggaran'];
        $this->apiExist = $this->dataContract ? true : false;
    }
    public function render()
    {
        return view('livewire.contract.create-table');
    }
}
