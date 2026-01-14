<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Livewire\Attributes\Modelable;

class SelectInput extends Component
{
    #[Modelable]
    public $value;

    public $rawOptions = [];
    public $placeholder = '-- Pilih --';
    public $disabled = false;
    public $freetext = false;
    public $open = false;

    public function mount($options = [], $placeholder = '-- Pilih --', $disabled = false, $freetext = false)
    {
        $this->rawOptions = $options;
        $this->placeholder = $placeholder;
        $this->disabled = $disabled;
        $this->freetext = $freetext;
    }

    public function getOptionsProperty()
    {
        return $this->processOptions($this->rawOptions);
    }

    public function selectOption($optionValue, $optionLabel = null)
    {
        if ($this->freetext) {
            // Mode freetext: simpan label sebagai value
            $this->value = $optionLabel ?? $optionValue;
        } else {
            // Mode normal: simpan value
            $this->value = $optionValue;
        }
        $this->open = false;
    }

    public function toggleDropdown()
    {
        if (!$this->disabled) {
            $this->open = !$this->open;
        }
    }

    public function closeDropdown()
    {
        $this->open = false;
    }

    public function getSelectedLabelProperty()
    {
        if ($this->freetext) {
            return $this->value ?: $this->placeholder;
        }

        $selected = collect($this->options)->firstWhere('value', $this->value);
        return $selected ? $selected['label'] : $this->placeholder;
    }

    private function processOptions($options)
    {
        if ($options instanceof \Illuminate\Support\Collection) {
            return $options->map(fn($label, $value) => [
                'value' => (string) $value,
                'label' => (string) $label
            ])->values()->toArray();
        }

        return collect($options)->map(fn($label, $value) => [
            'value' => (string) $value,
            'label' => (string) $label
        ])->values()->toArray();
    }

    public function render()
    {
        return view('livewire.components.select-input');
    }
}