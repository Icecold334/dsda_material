<?php

namespace App\Livewire\District;

use App\Models\District;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

class Index extends Component
{
    #[Title('Daftar Kecamatan')]

    public $editDistrictId = null;

    #[On('district-created')]
    #[On('district-updated')]
    #[On('deleteDistrict')]
    public function refreshData()
    {
        $this->dispatch('refresh-grid');
    }

    public function editDistrict($districtId)
    {
        $this->editDistrictId = $districtId;
        $this->dispatch('open-modal', 'edit-district-' . $districtId);
    }

    #[On('deleteDistrict')]
    public function deleteDistrict($districtId)
    {
        $district = District::find($districtId);
        if ($district) {
            $district->delete();
            $this->dispatch('refresh-grid');
            $this->dispatch('district-deleted');
        }
    }

    public function render()
    {
        return view('livewire.district.index', [
            'districts' => District::with('sudin')->get(),
        ]);
    }

    protected function layoutData()
    {
        return [
            'title' => 'Daftar Kecamatan',
        ];
    }
}