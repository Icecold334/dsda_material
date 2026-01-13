<div class="space-y-4">
    <div class="grid grid-cols-2">
        <div>
            <div class="text-3xl font-semibold">Buat Rencana Anggaran Biaya</div>
        </div>
        <div class="text-right flex gap-2 justify-end"
             x-data="{ fileCount: 0 }"
             @file-count-updated.window="fileCount = $event.detail">
            <x-secondary-button @click="$dispatch('open-modal', 'lampiran-modal')" type="button">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                </svg>
                Lampiran
                <span class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-indigo-600 rounded-full"
                      x-show="fileCount > 0"
                      x-text="fileCount">
                </span>
            </x-secondary-button>
            <x-primary-button href="{{ route('rab.index') }}" wire:navigate>
                Kembali
            </x-primary-button>
        </div>
    </div>

    <form wire:submit="save">
        <div class="grid grid-cols-1 gap-4">
            <x-card title="Informasi RAB">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <x-input-label for="nomor" value="Nomor RAB" />
                        <div class="mt-1 block w-full max-w-[500px]">
                            <x-text-input id="nomor" wire:model="nomor" placeholder="Kosongkan untuk auto generate"
                                type="text" class="w-full" />
                            <x-input-error :messages="$errors->get('nomor')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <x-input-label for="name" value="Nama Kegiatan" />
                        <div class="mt-1 block w-full max-w-[500px]">
                            <x-text-input id="name" wire:model="name" type="text" class="w-full"
                                placeholder="Masukkan nama kegiatan" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <x-input-label for="tahun" value="Tahun Anggaran" />
                        <div class="mt-1 block w-full max-w-[500px]">
                            <x-text-input id="tahun" wire:model="tahun" type="number" class="w-full"
                                placeholder="2026" min="2000" max="2100" />
                            <x-input-error :messages="$errors->get('tahun')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <x-input-label for="tanggal_mulai" value="Tanggal Mulai" />
                        <div class="mt-1 block w-full max-w-[500px]">
                            <x-text-input id="tanggal_mulai" wire:model="tanggal_mulai" type="date" class="w-full" />
                            <x-input-error :messages="$errors->get('tanggal_mulai')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <x-input-label for="tanggal_selesai" value="Tanggal Selesai" />
                        <div class="mt-1 block w-full max-w-[500px]">
                            <x-text-input id="tanggal_selesai" wire:model="tanggal_selesai" type="date"
                                class="w-full" />
                            <x-input-error :messages="$errors->get('tanggal_selesai')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <x-input-label for="sudin_id" value="Sudin" />
                        <div class="mt-1 block w-full max-w-[500px]">
                            <livewire:components.select-input wire:model.live="sudin_id"
                                :options="$sudins->pluck('name', 'id')" placeholder="-- Pilih Sudin --"
                                :key="'sudin-select'" />
                            <x-input-error :messages="$errors->get('sudin_id')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <x-input-label for="district_id" value="Lokasi" />
                        <div class="input w-full max-w-[500px] flex flex-col gap-4">
                            <div class="grid-cols-2 flex gap-4">
                                <div class="w-full">
                                    <livewire:components.select-input wire:model.live="district_id"
                                        :options="$districts->pluck('name', 'id')" placeholder="-- Pilih Kecamatan --"
                                        :key="'district-select-' . $sudin_id" />
                                    <x-input-error :messages="$errors->get('district_id')" class="mt-2" />
                                </div>
                                <div class="w-full">
                                    <livewire:components.select-input wire:model="subdistrict_id"
                                        :options="$subdistricts->pluck('name', 'id')" placeholder="-- Pilih Kelurahan --"
                                        :key="'subdistrict-select-' . $district_id" />
                                    <x-input-error :messages="$errors->get('subdistrict_id')" class="mt-2" />
                                </div>
                            </div>
                            <textarea id="address" wire:model="address" rows="3"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full"
                                placeholder="Masukkan alamat lengkap"></textarea>
                            <x-input-error :messages="$errors->get('address')" />
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <x-input-label for="panjang" value="Dimensi" />
                        <div class="w-full max-w-[500px] grid grid-cols-3 gap-4">
                            <div class="w-full">
                                <x-text-input id="panjang" wire:model="panjang" type="text" class="w-full"
                                    placeholder="Panjang" />
                                <x-input-error :messages="$errors->get('panjang')" class="mt-2" />
                            </div>
                            <div class="w-full">
                                <x-text-input id="lebar" wire:model="lebar" type="text" class="w-full"
                                    placeholder="Lebar" />
                                <x-input-error :messages="$errors->get('lebar')" class="mt-2" />
                            </div>
                            <div class="w-full">
                                <x-text-input id="tinggi" wire:model="tinggi" type="text" class="w-full"
                                    placeholder="Tinggi" />
                                <x-input-error :messages="$errors->get('tinggi')" class="mt-2" />
                            </div>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('rab.index') }}" wire:navigate
                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                Batal
            </a>
            <x-primary-button type="submit">
                Simpan RAB
            </x-primary-button>
        </div>
    </form>

    <!-- Modal Lampiran -->
    <livewire:components.document-upload 
        mode="create"
        modelType="App\Models\Rab"
        category="lampiran_rab"
        label="Upload Lampiran"
        :multiple="true"
        accept="image/*,.pdf,.doc,.docx"
        modalId="lampiran-modal"
        :key="'doc-upload-lampiran'" />
</div>
