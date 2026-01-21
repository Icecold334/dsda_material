<?php

namespace App\Livewire\District;

use Livewire\Component;
use App\Models\Division;
use App\Models\Subdistrict;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;

class Show extends Component
{
    #[Title('Detail Kecamatan')]

    public Division $district;  // tetap pakai nama $district untuk backward compatibility
    public $editSubdistrictId = null;

    #[On('subdistrict-created')]
    #[On('subdistrict-updated')]
    #[On('deleteSubdistrict')]
    public function refreshSubdistricts()
    {
        $this->dispatch('refresh-grid');
    }

    #[On('deleteSubdistrict')]
    public function deleteSubdistrict($subdistrictId)
    {
        $subdistrict = Subdistrict::find($subdistrictId);
        if ($subdistrict && $subdistrict->division_id === $this->district->id) {
            $subdistrict->delete();
            $this->dispatch('refresh-grid');
            $this->dispatch('subdistrict-deleted');
        }
    }

    public function render()
    {
        return view('livewire.district.show', [
            'subdistricts' => $this->district->subdistricts,
        ]);
    }
}