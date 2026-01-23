<div>
    <x-modal name="rab-information-modal" :show="$showModal" :dismissable="$mode === 'show'" maxWidth="4xl" >
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">
                    {{ $mode === 'create' ? 'Informasi RAB' : 'Detail Informasi RAB' }}
                </h2>
                <button wire:click="closeModal" type="button"
                    class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <!-- Left Column -->
                <div class="space-y-4">
                    <div>
                        <x-input-label for="modal_nomor" value="Nomor RAB" />
                        <x-text-input id="modal_nomor" wire:model="nomor" type="text" class="mt-1 block w-full"
                            placeholder="Kosongkan untuk auto generate" :disabled="$mode === 'show'" />
                        <x-input-error :messages="$errors->get('nomor')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="modal_name" value="Nama Kegiatan" />
                        <x-text-input id="modal_name" wire:model="name" type="text" class="mt-1 block w-full"
                            placeholder="Masukkan nama kegiatan" :disabled="$mode === 'show'" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="modal_tahun" value="Tahun Anggaran" />
                        <x-text-input id="modal_tahun" wire:model="tahun" type="number" class="mt-1 block w-full"
                            placeholder="2026" min="2000" max="2100" :disabled="$mode === 'show'" />
                        <x-input-error :messages="$errors->get('tahun')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="modal_tanggal_mulai" value="Tanggal Mulai" />
                        <x-text-input id="modal_tanggal_mulai" wire:model="tanggal_mulai" type="date"
                            class="mt-1 block w-full" :disabled="$mode === 'show'" />
                        <x-input-error :messages="$errors->get('tanggal_mulai')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="modal_tanggal_selesai" value="Tanggal Selesai" />
                        <x-text-input id="modal_tanggal_selesai" wire:model="tanggal_selesai" type="date"
                            class="mt-1 block w-full" :disabled="$mode === 'show'" />
                        <x-input-error :messages="$errors->get('tanggal_selesai')" class="mt-2" />
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    <div>
                        <x-input-label for="modal_sudin_id" value="Sudin" />
                        <livewire:components.select-input wire:model.live="sudin_id" :options="$sudins->pluck('name', 'id')"
                            placeholder="-- Pilih Sudin --" :disabled="$mode === 'show'"
                            :key="'modal-sudin-select-' . $mode" />
                        <x-input-error :messages="$errors->get('sudin_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="modal_district_id" value="Kecamatan" />
                        <livewire:components.select-input wire:model.live="district_id"
                            :options="$districts->pluck('name', 'id')" placeholder="-- Pilih Kecamatan --"
                            :disabled="$mode === 'show'" :key="'modal-district-select-' . $sudin_id . '-' . $mode" />
                        <x-input-error :messages="$errors->get('district_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="modal_subdistrict_id" value="Kelurahan" />
                        <livewire:components.select-input wire:model="subdistrict_id"
                            :options="$subdistricts->pluck('name', 'id')" placeholder="-- Pilih Kelurahan --"
                            :disabled="$mode === 'show'"
                            :key="'modal-subdistrict-select-' . $district_id . '-' . $mode" />
                        <x-input-error :messages="$errors->get('subdistrict_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="modal_address" value="Alamat" />
                        <textarea id="modal_address" wire:model="address" rows="3" :disabled="$mode === 'show'"
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full mt-1"
                            placeholder="Masukkan alamat lengkap"></textarea>
                        <x-input-error :messages="$errors->get('address')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label value="Dimensi" />
                        <div class="grid grid-cols-3 gap-2 mt-1">
                            <div>
                                <x-text-input wire:model="panjang" type="text" class="w-full"
                                    placeholder="Panjang" :disabled="$mode === 'show'" />
                                <x-input-error :messages="$errors->get('panjang')" class="mt-2" />
                            </div>
                            <div>
                                <x-text-input wire:model="lebar" type="text" class="w-full" placeholder="Lebar"
                                    :disabled="$mode === 'show'" />
                                <x-input-error :messages="$errors->get('lebar')" class="mt-2" />
                            </div>
                            <div>
                                <x-text-input wire:model="tinggi" type="text" class="w-full"
                                    placeholder="Tinggi" :disabled="$mode === 'show'" />
                                <x-input-error :messages="$errors->get('tinggi')" class="mt-2" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <x-secondary-button wire:click="closeModal" type="button">
                    {{ $mode === 'create' ? 'Batal' : 'Tutup' }}
                </x-secondary-button>
                @if ($mode === 'create')
                    <x-button wire:click="saveInformation" type="button">
                        Simpan
                    </x-button>
                @endif
            </div>
        </div>
    </x-modal>
</div>
