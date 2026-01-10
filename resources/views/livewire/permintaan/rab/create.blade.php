<div class="space-y-4">
    <div class="grid grid-cols-2">
        <div>
            <div class="text-3xl font-semibold">Buat Permintaan dengan RAB</div>
        </div>
        <div class="text-right">
            <x-primary-button href="{{ route('permintaan.rab.index') }}" wire:navigate>Kembali</x-primary-button>
        </div>
    </div>

    <!-- Modal Pencarian RAB -->
    <x-modal name="input-rab-number" :show="$showModal" :dismissable="false">
        <div class="p-6 space-y-4">
            <div class="flex items-center gap-2">
                <div class="text-lg text-primary-700">
                    <a href="{{ route('permintaan.rab.index') }}" wire:navigate>
                        <i class="fa-solid fa-circle-chevron-left"></i>
                    </a>
                </div>
                <div class="font-semibold text-2xl">Masukkan Nomor RAB</div>
            </div>

            <div class="flex">
                <input type="text" wire:model="rab_nomor" wire:keydown.enter="searchRab"
                    class="rounded-none rounded-s-lg bg-gray-50 border border-gray-300 text-gray-900 block flex-1 text-sm p-2.5"
                    placeholder="Masukkan Nomor RAB" />

                <button type="button" wire:click="searchRab"
                    class="inline-flex items-center px-3 text-sm text-white bg-primary-600 hover:bg-primary-800 rounded-e-md transition">
                    Cari
                </button>
            </div>

            @error('rab_nomor')
                <div class="text-sm text-red-600">{{ $message }}</div>
            @enderror
        </div>
    </x-modal>

    @if (session('rab_found'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
            {{ session('rab_found') }}
        </div>
    @endif

    @if ($rab)
        <form wire:submit="save">
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-4">
                    <!-- Card Informasi Permintaan -->
                    <x-card title="Informasi Permintaan">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <x-input-label for="nomor" value="Nomor SPB" />
                                <div class="mt-1 block w-full max-w-[500px]">
                                    <x-text-input id="nomor" wire:model="nomor" placeholder="Masukkan nomor SPB" type="text"
                                        class="w-full" />
                                    <x-input-error :messages="$errors->get('nomor')" class="mt-2" />
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <x-input-label for="name" value="Nama Permintaan" />
                                <div class="mt-1 block w-full max-w-[500px]">
                                    <x-text-input id="name" wire:model="name" type="text" class="w-full bg-gray-100"
                                        placeholder="Otomatis dari RAB" disabled />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <x-input-label for="sudin_id" value="Sudin" />
                                <div class="mt-1 block w-full max-w-[500px]">
                                    <x-text-input type="text" class="w-full bg-gray-100"
                                        value="{{ $rab?->sudin?->name ?? '-' }}" disabled />
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <x-input-label for="warehouse_id" value="Gudang" />
                                <div class="mt-1 block w-full max-w-[500px]">
                                    <livewire:components.select-input wire:model="warehouse_id"
                                        :options="$warehouses->pluck('name', 'id')" placeholder="-- Pilih Gudang --"
                                        :key="'warehouse-select-' . ($rab?->id ?? 'empty')" />
                                    <x-input-error :messages="$errors->get('warehouse_id')" class="mt-2" />
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <x-input-label for="tanggal_permintaan" value="Tanggal Permintaan" />
                                <div class="mt-1 block w-full max-w-[500px]">
                                    <x-text-input id="tanggal_permintaan" wire:model="tanggal_permintaan" type="date"
                                        class="w-full" />
                                    <x-input-error :messages="$errors->get('tanggal_permintaan')" class="mt-2" />
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <x-input-label for="district_id" value="Lokasi" />
                                <div class="input w-full max-w-[500px] flex flex-col gap-4">
                                    <div class="grid-cols-2 flex gap-4">
                                        <div class="w-full">
                                            <x-text-input type="text" class="w-full bg-gray-100"
                                                value="{{ $rab?->district?->name ?? '-' }}" disabled />
                                        </div>
                                        <div class="w-full">
                                            <x-text-input type="text" class="w-full bg-gray-100"
                                                value="{{ $rab?->subdistrict?->name ?? '-' }}" disabled />
                                        </div>
                                    </div>
                                    <textarea id="address" wire:model="address" rows="3"
                                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full bg-gray-100"
                                        placeholder="Otomatis dari RAB" disabled></textarea>
                                    <x-input-error :messages="$errors->get('address')" />
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <x-input-label for="panjang" value="Dimensi" />
                                <div class="w-full max-w-[500px] grid grid-cols-3 gap-4">
                                    <div class="w-full">
                                        <x-text-input id="panjang" wire:model="panjang" type="text"
                                            class="w-full bg-gray-100" placeholder="0" disabled />
                                    </div>
                                    <div class="w-full">
                                        <x-text-input id="lebar" wire:model="lebar" type="text" class="w-full bg-gray-100"
                                            placeholder="0" disabled />
                                    </div>
                                    <div class="w-full">
                                        <x-text-input id="tinggi" wire:model="tinggi" type="text" class="w-full bg-gray-100"
                                            placeholder="0" disabled />
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <x-input-label for="notes" value="Keterangan" />
                                <div class="mt-1 block w-full max-w-[500px]">
                                    <textarea id="notes" wire:model="notes" rows="3"
                                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full"
                                        placeholder="Masukkan keterangan (opsional)"></textarea>
                                    <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                                </div>
                            </div>
                        </div>
                    </x-card>
                </div>

                <x-card title="Lampiran">
                    <div class="space-y-4">
                        <livewire:components.document-upload mode="create" modelType="App\Models\RequestModel"
                            category="lampiran_permintaan" label="Upload Lampiran" :multiple="true"
                            accept="image/*,.pdf,.doc,.docx" :key="'doc-upload-lampiran'" />
                    </div>
                </x-card>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('permintaan.rab.index') }}" wire:navigate
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                    Batal
                </a>
                <x-primary-button type="submit">
                    Simpan Permintaan
                </x-primary-button>
            </div>
        </form>
    @endif
</div>