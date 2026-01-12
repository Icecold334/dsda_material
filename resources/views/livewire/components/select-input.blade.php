<!-- default adalah binding id -->
<!-- mode freetext tambahkan attribute :freetext="true"  -->
<div class="relative" x-data="{ open: @entangle('open') }" @click.away="$wire.closeDropdown()">
    <!-- Search Input -->
    <div class="relative">
        <input type="text" wire:model.live="search" wire:focus="openDropdown"
            @keydown.enter.prevent="@if($freetext) $wire.selectOption('', $event.target.value); $wire.closeDropdown() @endif"
            @keydown.escape="$wire.closeDropdown()" placeholder="{{ $placeholder }}" {{ $disabled ? 'disabled' : '' }}
            class="w-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm px-3 py-2 pr-10 disabled:opacity-50 disabled:cursor-not-allowed">
        <button type="button" wire:click="toggleDropdown" :disabled="{{ $disabled ? 'true' : 'false' }}"
            class="absolute inset-y-0 right-0 flex items-center px-2">
            <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 transition-transform {{ $open ? 'rotate-180' : '' }}"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
    </div>

    <!-- Dropdown -->
    @if($open)
        <div
            class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-md shadow-lg">
            <!-- Options List -->
            <div class="max-h-60 overflow-y-auto">
                <!-- Empty option -->
                <div wire:click="selectOption('')"
                    class="px-3 py-2 cursor-pointer hover:bg-indigo-50 dark:hover:bg-gray-700 {{ $value === '' || $value === null ? 'bg-indigo-100 dark:bg-gray-600' : '' }}">
                    <span class="text-gray-500 dark:text-gray-400">{{ $placeholder }}</span>
                </div>

                <!-- Options -->
                @forelse($this->filteredOptions as $option)
                    <div wire:click="selectOption('{{ $option['value'] }}', '{{ addslashes($option['label']) }}')"
                        class="px-3 py-2 cursor-pointer hover:bg-indigo-50 dark:hover:bg-gray-700 dark:text-gray-300 {{ ($freetext ? $value == $option['label'] : $value == $option['value']) ? 'bg-indigo-100 dark:bg-gray-600' : '' }}">
                        <span>{{ $option['label'] }}</span>
                    </div>
                @empty
                    <div class="px-3 py-2 text-gray-500 dark:text-gray-400 text-sm">
                        Tidak ada data ditemukan
                    </div>
                @endforelse
            </div>
        </div>
    @endif
</div>