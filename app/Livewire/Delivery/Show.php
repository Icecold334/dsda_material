<?php

namespace App\Livewire\Delivery;

use Livewire\Component;
use App\Models\Delivery;
use Livewire\Attributes\Title;

class Show extends Component
{
    #[Title('Detail Delivery')]
    public Delivery $delivery;

    public function openInformasiModal()
    {
        $this->dispatch('open-modal', 'detail-informasi-modal');
    }

    public function openSuratJalanModal()
    {
        $this->dispatch('open-modal', 'surat-jalan-modal');
    }

    public function openFotoPengirimanModal()
    {
        $this->dispatch('open-modal', 'foto-pengiriman-modal');
    }

    public function render()
    {
        return view('livewire.delivery.show');
    }
}
