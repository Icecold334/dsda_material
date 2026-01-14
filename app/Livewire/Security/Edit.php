<?php

namespace App\Livewire\Security;

use App\Models\Personnel;
use App\Models\Sudin;
use Livewire\Component;

class Edit extends Component
{
    public Personnel $security;
    public $name = '';
    public $sudin_id = '';

    public function mount()
    {
        $this->name = $this->security->name;
        $this->sudin_id = $this->security->sudin_id;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'sudin_id' => 'nullable|exists:sudins,id',
        ];
    }

    public function update()
    {
        $this->validate();

        $this->security->update([
            'name' => $this->name,
            'sudin_id' => $this->sudin_id ?: null,
        ]);

        $this->dispatch('close-modal', 'edit-security-' . $this->security->id);
        $this->dispatch('success-updated', message: 'Security berhasil diperbarui');
        $this->dispatch('security-updated');
    }

    public function render()
    {
        return view('livewire.security.edit', [
            'sudins' => Sudin::orderBy('name')->get(),
        ]);
    }
}