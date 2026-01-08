<x-modal name="create-security" focusable>
    <form wire:submit="save" class="p-6">
        <h2 class="text-lg font-medium text-gray-900">
            Tambah Security Baru
        </h2>

        <div class="mt-6 space-y-4">
            <div>
                <x-input-label for="name" value="Nama Security" />
                <x-text-input id="name" wire:model="name" type="text" class="mt-1 block w-full"
                    placeholder="Masukkan nama security" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="sudin_id" value="Sudin" />
                <x-select-input id="sudin_id" wire:model="sudin_id" class="mt-1 block w-full"
                    placeholder="-- Pilih Sudin --" :options="$sudins->pluck('name', 'id')" />
                <x-input-error :messages="$errors->get('sudin_id')" class="mt-2" />
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button type="button" x-on:click="$dispatch('close-modal', 'create-security')">
                Batal
            </x-secondary-button>
            <x-primary-button type="submit">
                Simpan
            </x-primary-button>
        </div>
    </form>
</x-modal>