<?php

namespace App\Livewire\Rab;

use App\Models\Rab;
use Livewire\Attributes\Title;
use Livewire\Component;

class Show extends Component
{
    #[Title('Detail RAB')]
    public Rab $rab;

    public function mount()
    {
        // Dispatch RAB data to modal
        $this->dispatch('setRabData', [
            'id' => $this->rab->id,
            'nomor' => $this->rab->nomor,
            'name' => $this->rab->name,
            'tahun' => $this->rab->tahun,
            'tanggal_mulai' => $this->rab->tanggal_mulai?->format('Y-m-d'),
            'tanggal_selesai' => $this->rab->tanggal_selesai?->format('Y-m-d'),
            'sudin_id' => $this->rab->sudin_id,
            'district_id' => $this->rab->district_id,
            'subdistrict_id' => $this->rab->subdistrict_id,
            'address' => $this->rab->address,
            'panjang' => $this->rab->panjang,
            'lebar' => $this->rab->lebar,
            'tinggi' => $this->rab->tinggi,
            'status' => $this->rab->status,
            'status_text' => $this->rab->status_text,
            'status_color' => $this->rab->status_color,
            'pembuat' => $this->rab->user?->name,
            'total' => $this->rab->total,
            'item_type' => $this->rab->itemType?->name,
        ]);
    }

    public function render()
    {
        return view('livewire.rab.show');
    }
}
