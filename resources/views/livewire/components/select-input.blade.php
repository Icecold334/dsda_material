<div class="relative" 
     x-data="{ 
         open: @entangle('open').live,
         search: '',
         value: @entangle('value').live,
         options: [],
         cacheKey: @js($this->cacheKey),
         optionsCache: {},
         init() {
             // Load cache dari localStorage dengan TTL check
             try {
                 const cached = localStorage.getItem('select-input-cache');
                 if (cached) {
                     const parsedCache = JSON.parse(cached);
                     const now = Date.now();
                     const TTL = 5 * 60 * 1000; // 5 menit dalam milliseconds
                     
                     // Clean expired cache
                     Object.keys(parsedCache).forEach(key => {
                         if (!parsedCache[key].timestamp || (now - parsedCache[key].timestamp) > TTL) {
                             delete parsedCache[key];
                         }
                     });
                     
                     this.optionsCache = parsedCache;
                     // Update localStorage dengan cache yang sudah di-clean
                     localStorage.setItem('select-input-cache', JSON.stringify(this.optionsCache));
                 }
             } catch (e) {
                 console.error('Failed to load cache:', e);
             }
             
             // Check cache dulu sebelum load dari server
             if (this.optionsCache[this.cacheKey]) {
                 this.options = this.optionsCache[this.cacheKey].data;
             } else {
                 this.options = @js($this->options);
                 this.cacheOptions();
             }
             
             this.$watch('value', () => {
                 // Reset search when value changes from outside
                 if (!this.open) {
                     this.search = '';
                 }
             });
         },
         cacheOptions() {
             if (this.cacheKey && this.options.length > 0) {
                 this.optionsCache[this.cacheKey] = {
                     data: this.options,
                     timestamp: Date.now()
                 };
                 try {
                     localStorage.setItem('select-input-cache', JSON.stringify(this.optionsCache));
                 } catch (e) {
                     console.error('Failed to save cache:', e);
                 }
             }
         },
         updateOptions(newOptions, newCacheKey) {
             // Hanya update jika cache key berbeda
             if (newCacheKey !== this.cacheKey) {
                 this.cacheKey = newCacheKey;
                 
                 // Check cache dulu (dengan TTL check)
                 if (this.optionsCache[newCacheKey]) {
                     const now = Date.now();
                     const TTL = 5 * 60 * 1000; // 5 menit
                     
                     if ((now - this.optionsCache[newCacheKey].timestamp) <= TTL) {
                         this.options = this.optionsCache[newCacheKey].data;
                     } else {
                         // Cache expired, load fresh data
                         delete this.optionsCache[newCacheKey];
                         this.options = newOptions;
                         this.cacheOptions();
                     }
                 } else {
                     this.options = newOptions;
                     this.cacheOptions();
                 }
             }
         },
         get filteredOptions() {
             if (!this.search) return this.options;
             return this.options.filter(opt => 
                 opt.label.toLowerCase().includes(this.search.toLowerCase())
             );
         },
         get displayValue() {
             return this.value || '';
         }
     }" 
     @click.away="open = false"
     x-init="$watch('$wire.cacheKey', (newCacheKey) => {
         updateOptions(@js($this->options), newCacheKey);
     })">
    
    @if($freetext)
        <!-- Mode Freetext: Search Input -->
        <div class="relative">
            <input type="text" 
                :value="open ? search : displayValue"
                @input="search = $event.target.value"
                @focus="search = displayValue; open = true"
                @keydown.enter.prevent="if(search) { $wire.selectOption('', search); search = '' }"
                @keydown.escape="open = false; search = ''" 
                placeholder="{{ $placeholder }}" 
                {{ $disabled ? 'disabled' : '' }}
                class="w-full border border-gray-300 bg-white text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 pr-10 disabled:opacity-50 disabled:cursor-not-allowed">
            <button type="button" @click="open = !open" :disabled="{{ $disabled ? 'true' : 'false' }}"
                class="absolute inset-y-0 right-0 flex items-center px-2">
                <svg class="w-5 h-5 text-gray-400 transition-transform" :class="{ 'rotate-180': open }" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
        </div>
    @else
        <!-- Mode Normal: Button Select -->
        <button type="button" @click="open = !open" :disabled="{{ $disabled ? 'true' : 'false' }}"
            class="w-full border border-gray-300 bg-white text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-left flex items-center justify-between disabled:opacity-50 disabled:cursor-not-allowed">
            <span class="{{ !$value ? 'text-gray-400' : '' }}">
                {{ $this->selectedLabel }}
            </span>
            <svg class="w-5 h-5 text-gray-400 transition-transform" :class="{ 'rotate-180': open }" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
    @endif

    <!-- Dropdown -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg"
         style="display: none;">
        
        <!-- Search Input (hanya untuk mode normal) -->
        @if(!$freetext)
            <div class="p-2 border-b border-gray-300">
                <input type="text" x-model="search" @click.stop placeholder="Cari..."
                    class="w-full px-3 py-2 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
            </div>
        @endif

        <!-- Options List -->
        <div class="max-h-60 overflow-y-auto">
            <!-- Empty option -->
            <div @click="$wire.selectOption(''); open = false; search = ''"
                class="px-3 py-2 cursor-pointer hover:bg-indigo-50 {{ $value === '' || $value === null ? 'bg-indigo-100' : '' }}">
                <span class="text-gray-500">{{ $placeholder }}</span>
            </div>

            <!-- Options -->
            <template x-for="option in filteredOptions" :key="option.value">
                <div @click="$wire.selectOption(option.value, option.label); open = false; @if($freetext) value = option.label; @endif search = ''"
                    :class="{
                        'bg-indigo-100': @if($freetext) value == option.label @else '{{ $value }}' == option.value @endif,
                        'hover:bg-indigo-50': true
                    }"
                    class="px-3 py-2 cursor-pointer">
                    <span x-text="option.label"></span>
                </div>
            </template>

            <!-- No results -->
            <div x-show="filteredOptions.length === 0" class="px-3 py-2 text-gray-500 text-sm">
                Tidak ada data ditemukan
            </div>
        </div>
    </div>
</div>