<?php

namespace App\Livewire\Contract;

use Livewire\Component;
use App\Models\Contract;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;

class Show extends Component
{
    #[Title('Detail Kontrak')]

    public Contract $contract;
    public $showVersion = 'latest'; // 'latest', 'original', or amendment_id

    public function mount(Contract $contract)
    {
        // Load contract with items
        $this->contract = $contract->load('items.item.category.unit');
    }

    #[Computed]
    public function currentVersion()
    {
        if ($this->showVersion === 'original') {
            return $this->contract;
        } elseif ($this->showVersion === 'latest') {
            return $this->contract->latestVersion;
        } else {
            // Show specific amendment
            return $this->contract->amendments()->find($this->showVersion);
        }
    }

    #[Computed]
    public function amendments()
    {
        return $this->contract->amendments()->with('items.item.category.unit')->get();
    }

    public function switchVersion($version)
    {
        $this->showVersion = $version;
    }

    public function render()
    {
        return view('livewire.contract.show');
    }
}
