<?php

namespace App\Livewire\District;

use App\Models\Division;
use App\Models\Sudin;
use Livewire\Component;

class Edit extends Component
{
    public Division $district;  // tetap pakai nama $district untuk backward compatibility
    public $name = '';
    public $sudin_id = '';

    public function mount()
    {
        $this->name = $this->district->name;
        $this->sudin_id = $this->district->sudin_id;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'sudin_id' => 'required|exists:sudins,id',
        ];
    }

    public function update()
    {
        $this->validate();

        $this->district->update([
            'name' => $this->name,
            'sudin_id' => $this->sudin_id,
        ]);

        $this->dispatch('close-modal', 'edit-district-' . $this->district->id);
        $this->dispatch('success-updated', message: 'Kecamatan berhasil diperbarui');
        $this->dispatch('district-updated');
    }

    public function render()
    {
        return view('livewire.district.edit', [
            'sudins' => Sudin::orderBy('name')->get(),
        ]);
    }
}