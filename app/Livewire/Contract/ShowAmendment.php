<?php

namespace App\Livewire\Contract;

use App\Models\Contract;
use Livewire\Component;
use App\Models\ContractAmendment;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;

class ShowAmendment extends Component
{
    #[Title('Detail Adendum Kontrak')]

    public $contractId;
    public $amendmentId;

    #[Computed]
    public function contract()
    {
        return Contract::findOrFail($this->contractId);
    }

    #[Computed]
    public function amendment()
    {
        return ContractAmendment::findOrFail($this->amendmentId);
    }

    public function mount($contract, $amendment)
    {
        $this->contractId = $contract;
        $this->amendmentId = $amendment;
    }

    public function render()
    {
        return view('livewire.contract.show-amendment');
    }
}
