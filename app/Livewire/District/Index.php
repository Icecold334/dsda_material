<?php

namespace App\Livewire\District;

use App\Models\Division;
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
        $district = Division::find($districtId);
        if ($district && $district->type === 'district') {
            $district->delete();
            $this->dispatch('success-deleted', message: 'Kecamatan berhasil dihapus');
            $this->dispatch('district-deleted');
            $this->dispatch('refresh-grid');
        }
    }

    public function render()
    {
        return view('livewire.district.index', [
            'districts' => Division::districts()->with('sudin')->get(),
        ]);
    }

    protected function layoutData()
    {
        return [
            'title' => 'Daftar Kecamatan',
        ];
    }
}