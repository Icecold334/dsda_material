<?php

namespace App\Livewire\Rab;

use Livewire\Component;
use App\Models\Rab;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;

class Show extends Component
{
    #[Title('Detail RAB')]

    public Rab $rab;
    public $showVersion = 'latest'; // 'latest', 'original', or amendment_id

    public function mount(Rab $rab)
    {
        // Load RAB with items
        $this->rab = $rab->load('items.item.category.unit');
    }

    #[Computed]
    public function currentVersion()
    {
        if ($this->showVersion === 'original') {
            return $this->rab->load('items.item.category.unit');
        } elseif ($this->showVersion === 'latest') {
            return $this->rab->latestVersion->load('items.item.category.unit');
        } else {
            // Show specific amendment
            return $this->rab->amendments()->with('items.item.category.unit')->find($this->showVersion);
        }
    }

    #[Computed]
    public function amendments()
    {
        return $this->rab->amendments()->with('items.item.category.unit')->get();
    }

    public function render()
    {
        return view('livewire.rab.show');
    }
}
