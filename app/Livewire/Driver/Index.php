<?php

namespace App\Livewire\Driver;

use App\Models\Personnel;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

class Index extends Component
{
    #[Title('Daftar Driver')]

    public $editDriverId = null;

    #[On('driver-created')]
    #[On('driver-updated')]
    #[On('deleteDriver')]
    public function refreshData()
    {
        $this->dispatch('refresh-grid');
    }

    public function editDriver($driverId)
    {
        $this->editDriverId = $driverId;
        $this->dispatch('open-modal', 'edit-driver-' . $driverId);
    }

    #[On('deleteDriver')]
    public function deleteDriver($driverId)
    {
        $driver = Personnel::where('type', 'driver')->find($driverId);
        if ($driver) {
            $driver->delete();
            $this->dispatch('success-deleted', message: 'Driver berhasil dihapus');
            $this->dispatch('driver-deleted');
            $this->dispatch('refresh-grid');
        }
    }

    public function render()
    {
        return view('livewire.driver.index', [
            'drivers' => Personnel::where('type', 'driver')->with('sudin')->get(),
        ]);
    }

    protected function layoutData()
    {
        return [
            'title' => 'Daftar Driver',
        ];
    }
}
