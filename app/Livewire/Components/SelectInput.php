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
    public $search = '';
    public $open = false;

    public function mount($options = [], $placeholder = '-- Pilih --', $disabled = false, $freetext = false)
    {
        $this->rawOptions = $options;
        $this->placeholder = $placeholder;
        $this->disabled = $disabled;
        $this->freetext = $freetext;
    }

    public function updatedValue($value)
    {
        // Jangan clear search saat value update
    }

    public function getOptionsProperty()
    {
        return $this->processOptions($this->rawOptions);
    }

    public function updatedSearch($value)
    {
        if (!empty($value)) {
            $this->open = true;
        }
    }

    public function selectOption($optionValue, $optionLabel = null)
    {
        if ($this->freetext) {
            // Mode freetext: simpan label sebagai value
            $this->value = $optionLabel ?? $optionValue;
            $this->search = $this->value;
        } else {
            // Mode normal: simpan value
            $this->value = $optionValue;
            $selected = collect($this->options)->firstWhere('value', $optionValue);
            $this->search = $selected ? $selected['label'] : '';
        }
        $this->open = false;
    }

    public function toggleDropdown()
    {
        if (!$this->disabled) {
            $this->open = !$this->open;
            if ($this->open && !$this->freetext) {
                $this->search = '';
            }
        }
    }

    public function openDropdown()
    {
        if (!$this->disabled) {
            $this->open = true;
        }
    }

    public function closeDropdown()
    {
        $this->open = false;

        // Untuk freetext, pastikan value tersimpan dari search
        if ($this->freetext && !empty($this->search)) {
            $this->value = $this->search;
        } else if (!$this->freetext) {
            // Untuk non-freetext, reset search
            $this->search = '';
        }
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