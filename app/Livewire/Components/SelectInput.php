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
    public $processedOptions = [];
    public $cacheKey = '';

    // Static cache storage untuk session
    protected static $optionsCache = [];

    public function mount($options = [], $placeholder = '-- Pilih --', $disabled = false, $freetext = false)
    {
        $this->rawOptions = $options;
        $this->placeholder = $placeholder;
        $this->disabled = $disabled;
        $this->freetext = $freetext;
        $this->cacheKey = $this->generateCacheKey($options);
        $this->processedOptions = $this->getOrCacheOptions($this->rawOptions);
    }

    public function updating($property, $value)
    {
        // Ketika parent component update options via parameter
        if ($property === 'rawOptions') {
            $this->processedOptions = $this->processOptions($value);
        }
    }

    public function getOptionsProperty()
    {
        return $this->processedOptions;
    }

    public function updatedRawOptions($value)
    {
        $newCacheKey = $this->generateCacheKey($this->rawOptions);

        // Hanya process jika cache key berbeda (data baru)
        if ($newCacheKey !== $this->cacheKey) {
            $this->cacheKey = $newCacheKey;
            $this->processedOptions = $this->getOrCacheOptions($this->rawOptions);

            // Reset value jika tidak ada di options baru (kecuali mode freetext)
            if (!$this->freetext && $this->value) {
                $valueExists = collect($this->processedOptions)->contains('value', $this->value);
                if (!$valueExists) {
                    $this->value = '';
                }
            }
        }
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

    /**
     * Generate cache key dari options
     */
    private function generateCacheKey($options)
    {
        if (empty($options)) {
            return 'empty';
        }

        // Convert to array untuk consistency
        $optionsArray = $options instanceof \Illuminate\Support\Collection
            ? $options->toArray()
            : (array) $options;

        // Generate hash dari options untuk cache key
        return md5(serialize($optionsArray));
    }

    /**
     * Get options dari cache atau process dan cache
     */
    private function getOrCacheOptions($options)
    {
        // Jika ada di cache, gunakan cache
        if (isset(self::$optionsCache[$this->cacheKey])) {
            return self::$optionsCache[$this->cacheKey];
        }

        // Process options baru dan simpan ke cache
        $processed = $this->processOptions($options);
        self::$optionsCache[$this->cacheKey] = $processed;

        return $processed;
    }

    public function render()
    {
        return view('livewire.components.select-input');
    }
}