<?php

namespace App\Livewire\Driver;

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
            'type' => 'driver',
        ]);
        $this->dispatch('close-modal', 'create-driver');
        $this->dispatch('driver-created');

        $this->reset();
    }

    public function render()
    {
        return view('livewire.driver.create', [
            'sudins' => Sudin::orderBy('name')->get(),
        ]);
    }
}
