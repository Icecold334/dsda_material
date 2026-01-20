<x-modal name="create-warehouse" focusable>
    <form wire:submit="save" class="p-6">
        <h2 class="text-lg font-medium text-gray-900">
            Tambah Gudang Baru
        </h2>

        <div class="mt-6 space-y-4">
            <div>
                <x-input-label for="name" value="Nama Gudang" />
                <x-text-input id="name" wire:model="name" type="text" class="mt-1 block w-full"
                    placeholder="Masukkan nama gudang" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="sudin_id" value="Sudin" />
                <livewire:components.select-input wire:model="sudin_id" :options="$sudins->pluck('name', 'id')"
                    placeholder="-- Pilih Sudin --" :key="'sudin-select'" />
                <x-input-error :messages="$errors->get('sudin_id')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="location" value="Lokasi" />
                <x-text-input id="location" wire:model="location" type="text" class="mt-1 block w-full"
                    placeholder="Masukkan lokasi gudang" />
                <x-input-error :messages="$errors->get('location')" class="mt-2" />
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button type="button" x-on:click="$dispatch('close-modal', 'create-warehouse')">
                Batal
            </x-secondary-button>
            <x-button type="submit">
                Simpan
            </x-button>
        </div>
    </form>
</x-modal>