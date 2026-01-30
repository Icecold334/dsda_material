<?php

namespace App\Livewire\User;

use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

class Index extends Component
{
    #[Title('Daftar Pengguna')]

    public $data = [];
    public function mount()
    {
        $this->data = [
            ["name" => "Nama", "id" => "name", "width" => "15%"],
            ["name" => "Email", "id" => "email", "width" => "15%"],
            ["name" => "NIP", "id" => "nip", "width" => "10%"],
            ["name" => "Sudin", "id" => "sudin", "width" => "12%"],
            ["name" => "Divisi", "id" => "division", "width" => "12%"],
            ["name" => "Jabatan", "id" => "position", "width" => "12%"],
            ["name" => "", "id" => "action", "width" => "14%"]
        ];
    }

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
            $this->dispatch('success-deleted', message: 'Pengguna berhasil dihapus');
            $this->dispatch('user-deleted');
            $this->dispatch('refresh-grid');
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
