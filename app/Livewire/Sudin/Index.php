<?php

namespace App\Livewire\Sudin;

use App\Models\Sudin;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

class Index extends Component
{
    #[Title('Daftar Sudin')]

    public $editSudinId = null;

    #[On('sudin-created')]
    #[On('sudin-updated')]
    #[On('deleteSudin')]
    public function refreshData()
    {
        $this->dispatch('refresh-grid');
    }

    public function editSudin($sudinId)
    {
        $this->editSudinId = $sudinId;
        $this->dispatch('open-modal', 'edit-sudin-' . $sudinId);
    }

    #[On('deleteSudin')]
    public function deleteSudin($sudinId)
    {
        $sudin = Sudin::find($sudinId);
        if ($sudin) {
            $sudin->delete();
            $this->dispatch('refresh-grid');
            $this->dispatch('sudin-deleted');
        }
    }

    public function render()
    {
        return view('livewire.sudin.index', [
            'sudins' => Sudin::all(),
        ]);
    }

    protected function layoutData()
    {
        return [
            'title' => 'Daftar Sudin',
        ];
    }
}