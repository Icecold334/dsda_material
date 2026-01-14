<?php

namespace App\Livewire\Security;

use App\Models\Personnel;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

class Index extends Component
{
    #[Title('Daftar Security')]

    public $editSecurityId = null;

    #[On('security-created')]
    #[On('security-updated')]
    #[On('deleteSecurity')]
    public function refreshData()
    {
        $this->dispatch('refresh-grid');
    }

    public function editSecurity($securityId)
    {
        $this->editSecurityId = $securityId;
        $this->dispatch('open-modal', 'edit-security-' . $securityId);
    }

    #[On('deleteSecurity')]
    public function deleteSecurity($securityId)
    {
        $security = Personnel::where('type', 'security')->find($securityId);
        if ($security) {
            $security->delete();
            $this->dispatch('success-deleted', message: 'Security berhasil dihapus');
            $this->dispatch('security-deleted');
            $this->dispatch('refresh-grid');
        }
    }

    public function render()
    {
        return view('livewire.security.index', [
            'securities' => Personnel::where('type', 'security')->with('sudin')->get(),
        ]);
    }

    protected function layoutData()
    {
        return [
            'title' => 'Daftar Security',
        ];
    }
}