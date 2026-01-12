<div class="relative" x-data="{ open: @entangle('open') }" @click.away="$wire.closeDropdown()">
    @if($freetext)
        <!-- Mode Freetext: Search Input -->
        <div class="relative">
            <input type="text" wire:model.live="search" wire:focus="openDropdown"
                @keydown.enter.prevent="$wire.selectOption('', $event.target.value); $wire.closeDropdown()"
                @keydown.escape="$wire.closeDropdown()" placeholder="{{ $placeholder }}" {{ $disabled ? 'disabled' : '' }}
                class="w-full border border-gray-300  bg-white  text-gray-900  focus:border-indigo-500 :border-indigo-600 focus:ring-1 focus:ring-indigo-500 :ring-indigo-600 rounded-md shadow-sm px-3 py-2 pr-10 disabled:opacity-50 disabled:cursor-not-allowed">
            <button type="button" wire:click="toggleDropdown" :disabled="{{ $disabled ? 'true' : 'false' }}"
                class="absolute inset-y-0 right-0 flex items-center px-2">
                <svg class="w-5 h-5 text-gray-400  transition-transform" :class="{ 'rotate-180': open }" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
        </div>
    @else
        <!-- Mode Normal: Button Select -->
        <button type="button" wire:click="toggleDropdown" :disabled="{{ $disabled ? 'true' : 'false' }}"
            class="w-full border border-gray-300  bg-white  text-gray-900  focus:border-indigo-500 :border-indigo-600 focus:ring-1 focus:ring-indigo-500 :ring-indigo-600 rounded-md shadow-sm px-3 py-2 text-left flex items-center justify-between disabled:opacity-50 disabled:cursor-not-allowed">
            <span class="{{ !$value ? 'text-gray-400 ' : '' }}">
                {{ $this->selectedLabel }}
            </span>
            <svg class="w-5 h-5 text-gray-400  transition-transform" :class="{ 'rotate-180': open }" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
    @endif

    <!-- Dropdown -->
    @if($open)
        <div class="absolute z-50 w-full mt-1 bg-white  border border-gray-300  rounded-md shadow-lg">
            <!-- Search Input (hanya untuk mode normal) -->
            @if(!$freetext)
                <div class="p-2 border-b border-gray-300 ">
                    <input type="text" wire:model.live="search" @click.stop placeholder="Cari..."
                        class="w-full px-3 py-2 border-gray-300    focus:border-indigo-500 :border-indigo-600 focus:ring-indigo-500 :ring-indigo-600 rounded-md shadow-sm text-sm">
                </div>
            @endif

            <!-- Options List -->
            <div class="max-h-60 overflow-y-auto">
                <!-- Empty option -->
                <div wire:click="selectOption('')"
                    class="px-3 py-2 cursor-pointer hover:bg-indigo-50 :bg-gray-700 {{ $value === '' || $value === null ? 'bg-indigo-100 ' : '' }}">
                    <span class="text-gray-500 ">{{ $placeholder }}</span>
                </div>

                <!-- Options -->
                @forelse($this->filteredOptions as $option)
                    <div wire:click="selectOption('{{ $option['value'] }}', '{{ addslashes($option['label']) }}')"
                        class="px-3 py-2 cursor-pointer hover:bg-indigo-50 :bg-gray-700  {{ ($freetext ? $value == $option['label'] : $value == $option['value']) ? 'bg-indigo-100 ' : '' }}">
                        <span>{{ $option['label'] }}</span>
                    </div>
                @empty
                    <div class="px-3 py-2 text-gray-500  text-sm">
                        Tidak ada data ditemukan
                    </div>
                @endforelse
            </div>
        </div>
    @endif
</div>