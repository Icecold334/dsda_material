<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Livewire\Attributes\Modelable;

class SelectInput extends Component
{
    #[Modelable]
    public $value;

    public $options = [];
    public $placeholder = '-- Pilih --';
    public $disabled = false;
    public $search = '';
    public $open = false;

    public function mount($options = [], $placeholder = '-- Pilih --', $disabled = false)
    {
        $this->options = $this->processOptions($options);
        $this->placeholder = $placeholder;
        $this->disabled = $disabled;
    }

    public function updatedValue($value)
    {
        $this->search = '';
    }

    public function selectOption($optionValue)
    {
        $this->value = $optionValue;
        $this->open = false;
        $this->search = '';
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
        $this->search = '';
    }

    public function getFilteredOptionsProperty()
    {
        if (empty($this->search)) {
            return $this->options;
        }

        return collect($this->options)->filter(function ($option) {
            return stripos($option['label'], $this->search) !== false;
        })->values()->toArray();
    }

    public function getSelectedLabelProperty()
    {
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
