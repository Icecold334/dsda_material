<?php

namespace App\Livewire\Sudin;

use App\Models\Sudin;
use Livewire\Component;

class Create extends Component
{
    public $name = '';
    public $short = '';
    public $address = '';

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'short' => 'nullable|string|max:50',
            'address' => 'nullable|string',
        ];
    }

    public function save()
    {
        $this->validate();

        Sudin::create([
            'name' => $this->name,
            'short' => $this->short,
            'address' => $this->address,
        ]);

        session()->flash('message', 'Sudin berhasil ditambahkan.');

        $this->dispatch('close-modal', 'create-sudin');
        $this->dispatch('sudin-created');

        $this->reset();
    }

    public function render()
    {
        return view('livewire.sudin.create');
    }
}