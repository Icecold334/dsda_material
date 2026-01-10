<div class="space-y-4">
    <div class="grid grid-cols-2">
        <div>
            <div class="text-3xl font-semibold">Buat Permintaan Non RAB</div>
        </div>
        <div class="text-right">
            <x-primary-button href="{{ route('permintaan.nonRab.index') }}" wire:navigate
                >Kembali</x-primary-button>
        </div>
    </div>

    <form wire:submit="save">
        <div class="grid grid-cols-2 gap-4">
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
                            <x-text-input id="name" wire:model="name" type="text" class=" w-full"
                                placeholder="Masukkan nama permintaan" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <x-input-label for="sudin_id" value="Sudin" />
                        <div class="mt-1 block w-full max-w-[500px]">
                            <livewire:components.select-input
                                wire:model.live="sudin_id"
                                :options="$sudins->pluck('name', 'id')"
                                placeholder="-- Pilih Sudin --"
                                :key="'sudin-select'" />
                            <x-input-error :messages="$errors->get('sudin_id')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <x-input-label for="warehouse_id" value="Gudang" />
                        <div class="mt-1 block w-full max-w-[500px]">
                            <livewire:components.select-input
                                wire:model="warehouse_id"
                                :options="$warehouses->pluck('name', 'id')"
                                placeholder="-- Pilih Gudang --"
                                :key="'warehouse-select-' . $sudin_id" />
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
                                <div class="w-full ">
                                    <livewire:components.select-input
                                        wire:model.live="district_id"
                                        :options="$districts->pluck('name', 'id')"
                                        placeholder="-- Pilih Kecamatan --"
                                        :key="'district-select-' . $sudin_id" />
                                    <x-input-error :messages="$errors->get('district_id')" class="mt-2" />
                                </div>
                                <div class="w-full ">
                                    <livewire:components.select-input
                                        wire:model="subdistrict_id"
                                        :options="$subdistricts->pluck('name', 'id')"
                                        placeholder="-- Pilih Kelurahan --"
                                        :key="'subdistrict-select-' . $district_id" />
                                    <x-input-error :messages="$errors->get('subdistrict_id')" class="mt-2" />
                                </div>
                            </div>
                            <textarea id="address" wire:model="address" rows="3"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm  block w-full "
                                placeholder="Masukkan alamat lengkap"></textarea>
                            <x-input-error :messages="$errors->get('address')" />
                        </div>

                    </div>
                    <div class="flex items-center justify-between">
                        <x-input-label for="panjang" value="Dimensi" />
                        <div class="w-full max-w-[500px] grid grid-cols-3 gap-4">
                            <div class="w-full">
                                <x-text-input id="panjang" wire:model="panjang" type="text"
                                    class="mt-1 block w-full max-w-[500px]" placeholder="0" />
                                <x-input-error :messages="$errors->get('panjang')" class="mt-2" />
                            </div>
                            <div class="w-full"> <x-text-input id="lebar" wire:model="lebar" type="text"
                                    class="mt-1 block w-full max-w-[500px]" placeholder="0" />
                                <x-input-error :messages="$errors->get('lebar')" class="mt-2" />
                            </div>
                            <div class="w-full"><x-text-input id="tinggi" wire:model="tinggi" type="text"
                                    class="mt-1 block w-full max-w-[500px]" placeholder="0" />
                                <x-input-error :messages="$errors->get('tinggi')" class="mt-2" />
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



            <x-card title="Lampiran">
                <div class="space-y-4">
                    <livewire:components.document-upload 
                        mode="create"
                        modelType="App\Models\RequestModel"
                        category="lampiran_permintaan"
                        label="Upload Lampiran"
                        :multiple="true"
                        accept="image/*,.pdf,.doc,.docx"
                        :key="'doc-upload-lampiran'" />
                </div>
            </x-card>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('permintaan.nonRab.index') }}" wire:navigate
                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                Batal
            </a>
            <x-primary-button type="submit">
                Simpan Permintaan
            </x-primary-button>
        </div>
    </form>
</div>