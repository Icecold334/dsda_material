<x-modal name="create-item-type" focusable>
    <form wire:submit="save" class="p-6">
        <h2 class="text-lg font-medium text-gray-900">
            Tambah Tipe Barang Baru
        </h2>

        <div class="mt-6 space-y-4">
            <div>
                <x-input-label for="name" value="Nama Tipe Barang" />
                <x-text-input id="name" wire:model="name" type="text" class="mt-1 block w-full"
                    placeholder="Masukkan nama Tipe Barang" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <label class="flex items-center">
                    <input type="checkbox" wire:model="active"
                        class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500">
                    <span class="ml-2 text-sm text-gray-600">Aktif</span>
                </label>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button type="button" x-on:click="$dispatch('close-modal', 'create-item-type')">
                Batal
            </x-secondary-button>
            <x-primary-button type="submit">
                Simpan
            </x-primary-button>
        </div>
    </form>
</x-modal>