<?php

namespace App\Livewire\Sudin;

use App\Models\Sudin;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

class Index extends Component
{
    #[Title('Daftar Sudin')]
    public $data = [];
    public function mount()
    {
        $this->data = [
            ["name" => "Nama", "id" => "name", "width" => "20%"],
            ["name" => "Singkatan", "id" => "short", "width" => "10%"],
            ["name" => "Alamat", "id" => "address", "width" => "25%"],
            ["name" => "Kode Pos", "id" => "postal_code", "width" => "10%"],
            ["name" => "Telepon", "id" => "phone", "width" => "15%"],
            ["name" => "", "id" => "action", "width" => "10%"],
        ];
    }
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
            $this->dispatch('success-deleted', message: 'Sudin berhasil dihapus');
            $this->dispatch('sudin-deleted');
            $this->dispatch('refresh-grid');
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