<?php

namespace App\Livewire\District;

use App\Models\District;
use App\Models\Sudin;
use Livewire\Component;

class Edit extends Component
{
    public District $district;
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

    #[\Livewire\Attributes\On('confirmUpdateDistrict')]
    public function update($districtId = null)
    {
        $this->validate();

        $this->district->update([
            'name' => $this->name,
            'sudin_id' => $this->sudin_id,
        ]);

        $this->dispatch('close-modal', 'edit-district-' . $this->district->id);
        $this->dispatch('district-updated');
    }

    public function render()
    {
        return view('livewire.district.edit', [
            'sudins' => Sudin::orderBy('name')->get(),
        ]);
    }
}