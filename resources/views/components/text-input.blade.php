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
    actualValue: @entangle($attributes->wire('model')),
    init() {
        this.actualValue = this.actualValue || '';
        this.displayValue = this.actualValue ? this.formatCurrency(this.actualValue.toString()) : '';
        this.$watch('actualValue', value => {
            this.displayValue = value ? this.formatCurrency(value.toString()) : '';
        });
    },
    updateValue(event) {
        let input = event.target.value;
        this.actualValue = input.replace(/[^\d]/g, '');
        this.displayValue = this.formatCurrency(this.actualValue);
    }
}">
    <input 
        type="text" 
        x-model="displayValue"
        @input="updateValue($event)"
        @disabled($disabled)
        {{ $attributes->except(['wire:model', 'wire:model.live', 'wire:model.defer', 'wire:model.lazy'])->merge(['class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm']) }}
        autocomplete="off"
    >
</div>
@else
<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm']) }}>
@endif
