<?php

namespace App\Livewire\Security;

use App\Models\Personnel;
use App\Models\Sudin;
use Livewire\Component;

class Create extends Component
{
    public $name = '';
    public $sudin_id = '';

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'sudin_id' => 'nullable|exists:sudins,id',
        ];
    }

    public function save()
    {
        $this->validate();

        Personnel::create([
            'name' => $this->name,
            'sudin_id' => $this->sudin_id ?: null,
            'type' => 'security',
        ]);

        $this->dispatch('close-modal', 'create-security');
        $this->dispatch('success-created', message: 'Security berhasil ditambahkan');
        $this->dispatch('security-created');

        $this->reset();
    }

    public function render()
    {
        return view('livewire.security.create', [
            'sudins' => Sudin::orderBy('name')->get(),
        ]);
    }
}