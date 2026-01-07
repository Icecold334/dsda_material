<?php

namespace App\Livewire\User;

use App\Models\User;
use App\Models\Sudin;
use App\Models\Division;
use App\Models\Position;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;

class Create extends Component
{
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $sudin_id = '';
    public $division_id = '';
    public $position_id = '';

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'sudin_id' => 'nullable|exists:sudins,id',
            'division_id' => 'nullable|exists:divisions,id',
            'position_id' => 'nullable|exists:positions,id',
        ];
    }

    public function save()
    {
        $this->validate();

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'sudin_id' => $this->sudin_id ?: null,
            'division_id' => $this->division_id ?: null,
            'position_id' => $this->position_id ?: null,
        ]);

        session()->flash('message', 'Pengguna berhasil ditambahkan.');

        $this->dispatch('close-modal', 'create-user');
        $this->dispatch('user-created');

        $this->reset();
    }

    public function render()
    {
        return view('livewire.user.create', [
            'sudins' => Sudin::orderBy('name')->get(),
            'divisions' => Division::orderBy('name')->get(),
            'positions' => Position::orderBy('name')->get(),
        ]);
    }
}
