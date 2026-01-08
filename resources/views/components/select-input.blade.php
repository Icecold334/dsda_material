@props(['disabled' => false, 'placeholder' => '-- Pilih --', 'options' => []])

@php
    $wireModel = $attributes->wire('model')->value();
    $id = $attributes->get('id', 'select-' . uniqid());
@endphp

<div x-data="{
    open: false,
    search: '',
    selected: @entangle($wireModel),
    placeholder: '{{ $placeholder }}',
    options: {{ json_encode(collect($options)->map(fn($label, $value) => ['value' => $value, 'label' => $label])->values()) }},
    get filteredOptions() {
        if (this.search === '') {
            return this.options;
        }
        return this.options.filter(option => 
            option.label.toLowerCase().includes(this.search.toLowerCase())
        );
    },
    get selectedLabel() {
        const option = this.options.find(opt => opt.value == this.selected);
        return option ? option.label : this.placeholder;
    },
    selectOption(value) {
        this.selected = value;
        this.open = false;
        this.search = '';
    }
}" @click.away="open = false" class="relative">
    <!-- Hidden select for form compatibility -->
    <select {{ $disabled ? 'disabled' : '' }} x-model="selected" class="hidden" {!! $attributes->except(['class', 'wire:model', 'wire:model.live', 'wire:model.defer']) !!}>
        <option value="">{{ $placeholder }}</option>
        @foreach($options as $value => $label)
            <option value="{{ $value }}">{{ $label }}</option>
        @endforeach
    </select>

    <!-- Custom Select Button -->
    <button type="button" @click="open = !open" :disabled="{{ $disabled ? 'true' : 'false' }}"
        class="w-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm px-3 py-2 text-left flex items-center justify-between disabled:opacity-50 disabled:cursor-not-allowed">
        <span x-text="selectedLabel" :class="{'text-gray-400 dark:text-gray-500': !selected}"></span>
        <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 transition-transform" :class="{'rotate-180': open}"
            fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <!-- Dropdown -->
    <div x-show="open" x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-md shadow-lg"
        style="display: none;">

        <!-- Search Input -->
        <div class="p-2 border-b border-gray-300 dark:border-gray-700">
            <input type="text" x-model="search" @click.stop placeholder="Cari..."
                class="w-full px-3 py-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm">
        </div>

        <!-- Options List -->
        <div class="max-h-60 overflow-y-auto">
            <!-- Empty option -->
            <div @click="selectOption('')" class="px-3 py-2 cursor-pointer hover:bg-indigo-50 dark:hover:bg-gray-700"
                :class="{'bg-indigo-100 dark:bg-gray-600': selected === ''}">
                <span class="text-gray-500 dark:text-gray-400">{{ $placeholder }}</span>
            </div>

            <!-- Options -->
            <template x-for="option in filteredOptions" :key="option.value">
                <div @click="selectOption(option.value)"
                    class="px-3 py-2 cursor-pointer hover:bg-indigo-50 dark:hover:bg-gray-700 dark:text-gray-300"
                    :class="{'bg-indigo-100 dark:bg-gray-600': selected == option.value}">
                    <span x-text="option.label"></span>
                </div>
            </template>

            <!-- No results -->
            <div x-show="filteredOptions.length === 0" class="px-3 py-2 text-gray-500 dark:text-gray-400 text-sm">
                Tidak ada data ditemukan
            </div>
        </div>
    </div>
</div>