<?php

namespace App\Livewire\User;

use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

class Index extends Component
{
    #[Title('Daftar Pengguna')]

    public $editUserId = null;

    #[On('user-created')]
    #[On('user-updated')]
    #[On('deleteUser')]
    public function refreshData()
    {
        $this->dispatch('refresh-grid');
    }

    public function editUser($userId)
    {
        $this->editUserId = $userId;
        $this->dispatch('open-modal', 'edit-user-' . $userId);
    }

    #[On('deleteUser')]
    public function deleteUser($userId)
    {
        $user = User::find($userId);
        if ($user) {
            $user->delete();
            $this->dispatch('refresh-grid');
            $this->dispatch('user-deleted');
        }
    }

    public function render()
    {
        return view('livewire.user.index', [
            'users' => User::all(),
        ]);
    }

    protected function layoutData()
    {
        return [
            'title' => 'Daftar Pengguna',
        ];
    }
}
