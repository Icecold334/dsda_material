<?php

namespace App\Livewire\Driver;

use App\Models\Personnel;
use App\Models\Sudin;
use Livewire\Component;

class Edit extends Component
{
    public Personnel $driver;
    public $name = '';
    public $sudin_id = '';

    public function mount()
    {
        $this->name = $this->driver->name;
        $this->sudin_id = $this->driver->sudin_id;
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

        $this->driver->update([
            'name' => $this->name,
            'sudin_id' => $this->sudin_id ?: null,
        ]);

        $this->dispatch('close-modal', 'edit-driver-' . $this->driver->id);
        $this->dispatch('success-updated', message: 'Driver berhasil diperbarui');
        $this->dispatch('driver-updated');
    }

    public function render()
    {
        return view('livewire.driver.edit', [
            'sudins' => Sudin::orderBy('name')->get(),
        ]);
    }
}
