<div>
    <x-modal name="request-information-modal" :show="$showModal" :dismissable="$mode === 'show'" maxWidth="4xl">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-semibold text-gray-900">
                    {{ $mode === 'create' ? 'Informasi Permintaan' : 'Detail Informasi Permintaan' }}
                </h2>
                @if($mode === 'show')
                    <button type="button" @click="$dispatch('close-modal', 'request-information-modal')"
                        wire:click="closeModal"
                        class="text-gray-400 hover:text-gray-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                @endif
            </div>

            @error('modal')
                <div class="mb-4 p-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                    {{ $message }}
                </div>
            @enderror

            <form wire:submit.prevent="saveInformation">
                <div class="space-y-4">
                    <!-- Nomor SPB -->
                    <div class="grid grid-cols-3 gap-4 items-start">
                        <x-input-label for="nomor" value="Nomor SPB" class="pt-2" />
                        <div class="col-span-2">
                            <x-text-input 
                                id="nomor" 
                                wire:model="nomor" 
                                placeholder="Masukkan nomor SPB" 
                                type="text"
                                class="w-full" 
                                :disabled="$mode === 'show'" />
                            <x-input-error :messages="$errors->get('nomor')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Nama Permintaan -->
                    <div class="grid grid-cols-3 gap-4 items-start">
                        <x-input-label for="name" value="Nama Permintaan" class="pt-2" />
                        <div class="col-span-2">
                            <x-text-input 
                                id="name" 
                                wire:model="name" 
                                type="text" 
                                class="w-full"
                                placeholder="{{ $isRab && $rab ? 'Otomatis dari RAB' : 'Masukkan nama permintaan' }}" 
                                :disabled="$mode === 'show' || ($isRab && $rab)" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Sudin -->
                    <div class="grid grid-cols-3 gap-4 items-start">
                        <x-input-label for="sudin_id" value="Sudin" class="pt-2" />
                        <div class="col-span-2">
                            @if($mode === 'show' || ($isRab && $rab))
                                <x-text-input 
                                    type="text" 
                                    class="w-full bg-gray-100"
                                    value="{{ $rab?->sudin?->name ?? ($sudin_id ? $sudins->firstWhere('id', $sudin_id)?->name : '-') }}" 
                                    disabled />
                            @else
                                <livewire:components.select-input
                                    wire:model.live="sudin_id"
                                    :options="$sudins->pluck('name', 'id')"
                                    placeholder="-- Pilih Sudin --"
                                    :key="'modal-sudin-select'" />
                            @endif
                            <x-input-error :messages="$errors->get('sudin_id')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Gudang (Hanya untuk Non-RAB) -->
                    @if(!$isRab)
                        <div class="grid grid-cols-3 gap-4 items-start">
                            <x-input-label for="warehouse_id" value="Gudang" class="pt-2" />
                            <div class="col-span-2">
                                @if($mode === 'show')
                                    <x-text-input 
                                        type="text" 
                                        class="w-full bg-gray-100"
                                        value="{{ $warehouse_id ? $warehouses->firstWhere('id', $warehouse_id)?->name : '-' }}" 
                                        disabled />
                                @else
                                    <livewire:components.select-input
                                        wire:model.live="warehouse_id"
                                        :options="$warehouses->pluck('name', 'id')"
                                        placeholder="-- Pilih Gudang --"
                                        :key="'modal-warehouse-select-' . $sudin_id" />
                                @endif
                                <x-input-error :messages="$errors->get('warehouse_id')" class="mt-2" />
                            </div>
                        </div>
                    @endif

                    <!-- Tanggal Permintaan -->
                    <div class="grid grid-cols-3 gap-4 items-start">
                        <x-input-label for="tanggal_permintaan" value="Tanggal Permintaan" class="pt-2" />
                        <div class="col-span-2">
                            <x-text-input 
                                id="tanggal_permintaan" 
                                wire:model="tanggal_permintaan" 
                                type="date"
                                class="w-full" 
                                :disabled="$mode === 'show'" />
                            <x-input-error :messages="$errors->get('tanggal_permintaan')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Lokasi -->
                    <div class="grid grid-cols-3 gap-4 items-start">
                        <x-input-label for="district_id" value="Lokasi" class="pt-2" />
                        <div class="col-span-2 space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    @if($mode === 'show' || ($isRab && $rab))
                                        <x-text-input 
                                            type="text" 
                                            class="w-full bg-gray-100"
                                            value="{{ $rab?->district?->name ?? ($district_id ? $districts->firstWhere('id', $district_id)?->name : '-') }}" 
                                            disabled />
                                    @else
                                        <livewire:components.select-input
                                            wire:model.live="district_id"
                                            :options="$districts->pluck('name', 'id')"
                                            placeholder="-- Pilih Kecamatan --"
                                            :key="'modal-district-select-' . $sudin_id" />
                                    @endif
                                    <x-input-error :messages="$errors->get('district_id')" class="mt-2" />
                                </div>
                                <div>
                                    @if($mode === 'show' || ($isRab && $rab))
                                        <x-text-input 
                                            type="text" 
                                            class="w-full bg-gray-100"
                                            value="{{ $rab?->subdistrict?->name ?? ($subdistrict_id ? $subdistricts->firstWhere('id', $subdistrict_id)?->name : '-') }}" 
                                            disabled />
                                    @else
                                        <livewire:components.select-input
                                            wire:model="subdistrict_id"
                                            :options="$subdistricts->pluck('name', 'id')"
                                            placeholder="-- Pilih Kelurahan --"
                                            :key="'modal-subdistrict-select-' . $district_id" />
                                    @endif
                                    <x-input-error :messages="$errors->get('subdistrict_id')" class="mt-2" />
                                </div>
                            </div>
                            <textarea 
                                id="address" 
                                wire:model="address" 
                                rows="3"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full"
                                placeholder="Masukkan alamat lengkap"
                                @if($mode === 'show' || ($isRab && $rab)) disabled @endif></textarea>
                            <x-input-error :messages="$errors->get('address')" />
                        </div>
                    </div>

                    <!-- Dimensi -->
                    <div class="grid grid-cols-3 gap-4 items-start">
                        <x-input-label for="panjang" value="Dimensi (P x L x T)" class="pt-2" />
                        <div class="col-span-2 grid grid-cols-3 gap-4">
                            <div>
                                <x-text-input 
                                    id="panjang" 
                                    wire:model="panjang" 
                                    type="text"
                                    class="w-full" 
                                    placeholder="0" 
                                    :disabled="$mode === 'show' || ($isRab && $rab)" />
                                <x-input-error :messages="$errors->get('panjang')" class="mt-2" />
                            </div>
                            <div>
                                <x-text-input 
                                    id="lebar" 
                                    wire:model="lebar" 
                                    type="text"
                                    class="w-full" 
                                    placeholder="0" 
                                    :disabled="$mode === 'show' || ($isRab && $rab)" />
                                <x-input-error :messages="$errors->get('lebar')" class="mt-2" />
                            </div>
                            <div>
                                <x-text-input 
                                    id="tinggi" 
                                    wire:model="tinggi" 
                                    type="text"
                                    class="w-full" 
                                    placeholder="0" 
                                    :disabled="$mode === 'show' || ($isRab && $rab)" />
                                <x-input-error :messages="$errors->get('tinggi')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- Keterangan -->
                    <div class="grid grid-cols-3 gap-4 items-start">
                        <x-input-label for="notes" value="Keterangan" class="pt-2" />
                        <div class="col-span-2">
                            <textarea 
                                id="notes" 
                                wire:model="notes" 
                                rows="3"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full"
                                placeholder="Masukkan keterangan (opsional)"
                                @if($mode === 'show') disabled @endif></textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    @if($mode === 'show')
                        <x-secondary-button type="button" @click="$dispatch('close-modal', 'request-information-modal')" 
                            wire:click="closeModal">
                            Tutup
                        </x-secondary-button>
                    @else
                        <x-button type="submit">
                            Simpan & Lanjutkan
                        </x-button>
                    @endif
                </div>
            </form>
        </div>
    </x-modal>
</div>
