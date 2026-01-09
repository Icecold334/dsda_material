<?php

namespace App\Livewire\Kontrak;

use Livewire\Component;
use Livewire\Attributes\On;

class ConfirmKontrakApi extends Component
{
    public $dataKontrak = [];

    #[On("confirmKontrak")]
    public function confirmKontrak($data)
    {
        $this->dataKontrak = $data['dataKontrak'];
    }
    public function render()
    {
        return view('livewire.kontrak.confirm-kontrak-api');
    }
}
