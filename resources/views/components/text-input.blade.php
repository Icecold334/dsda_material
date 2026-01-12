@props(['disabled' => false, 'currency' => false])

@if($currency)
<div x-data="{
    formatCurrency(value) {
        let numbers = value.replace(/[^\d]/g, '');
        if (numbers === '') return '';
        let formatted = parseInt(numbers).toLocaleString('id-ID');
        return 'Rp ' + formatted;
    },
    displayValue: '',
    actualValue: '',
    init() {
        this.actualValue = this.$refs.input.value || '';
        this.displayValue = this.actualValue ? this.formatCurrency(this.actualValue) : '';
    },
    updateValue(event) {
        let input = event.target.value;
        this.actualValue = input.replace(/[^\d]/g, '');
        this.displayValue = this.formatCurrency(this.actualValue);
        this.$refs.hidden.value = this.actualValue;
        this.$refs.input.value = this.actualValue;
    }
}" x-init="init()">
    <input 
        type="text" 
        x-model="displayValue"
        @input="updateValue($event)"
        @disabled($disabled)
        {{ $attributes->except(['wire:model', 'wire:model.live', 'wire:model.defer', 'wire:model.lazy'])->merge(['class' => 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm']) }}
        autocomplete="off"
    >
    <input 
        type="hidden" 
        x-ref="hidden"
        {{ $attributes->only(['wire:model', 'wire:model.live', 'wire:model.defer', 'wire:model.lazy', 'name']) }}
    >
    <input type="hidden" x-ref="input" value="{{ $attributes->get('value', '') }}">
</div>
@else
<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm']) }}>
@endif
