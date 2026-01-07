<?php

namespace App\Livewire\User;

use App\Models\User;
use App\Models\Sudin;
use App\Models\Division;
use App\Models\Position;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class Edit extends Component
{
    public User $user;
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $sudin_id = '';
    public $division_id = '';
    public $position_id = '';

    public function mount()
    {
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->sudin_id = $this->user->sudin_id;
        $this->division_id = $this->user->division_id;
        $this->position_id = $this->user->position_id;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'sudin_id' => 'nullable|exists:sudins,id',
            'division_id' => 'nullable|exists:divisions,id',
            'position_id' => 'nullable|exists:positions,id',
        ];
    }

    #[\Livewire\Attributes\On('confirmUpdateUser')]
    public function update($userId = null)
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'sudin_id' => $this->sudin_id ?: null,
            'division_id' => $this->division_id ?: null,
            'position_id' => $this->position_id ?: null,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        $this->user->update($data);

        $this->dispatch('close-modal', 'edit-user-' . $this->user->id);
        $this->dispatch('user-updated');
    }

    public function render()
    {
        return view('livewire.user.edit', [
            'sudins' => Sudin::orderBy('name')->get(),
            'divisions' => Division::orderBy('name')->get(),
            'positions' => Position::orderBy('name')->get(),
        ]);
    }
}
