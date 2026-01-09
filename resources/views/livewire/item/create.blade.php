<x-modal name="{{ $itemCategory ? 'create-item-' . $itemCategory->id : 'create-item' }}" focusable>
    <form wire:submit="save" class="p-6">
        <h2 class="text-lg font-medium text-gray-900">
            Tambah Barang Baru
        </h2>

        <div class="mt-6 space-y-4">
            <div>
                <x-input-label for="name" value="Nama Barang" />
                <x-text-input id="name" wire:model="name" type="text" class="mt-1 block w-full"
                    placeholder="Masukkan nama barang" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="sudin_id" value="Sudin" />
                <livewire:components.select-input
                    wire:model.live="sudin_id"
                    :options="$sudins->pluck('name', 'id')"
                    placeholder="-- Pilih Sudin --"
                    :key="'sudin-select'" />
                <x-input-error :messages="$errors->get('sudin_id')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="item_category_id" value="Kategori" />
                <livewire:components.select-input
                    wire:model="item_category_id"
                    :options="$categories->pluck('name', 'id')"
                    placeholder="-- Pilih Kategori --"
                    :key="'item-category-select-' . $sudin_id" />
                <x-input-error :messages="$errors->get('item_category_id')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="spec" value="Spesifikasi" />
                <x-text-input id="spec" wire:model="spec" type="text" class="mt-1 block w-full"
                    placeholder="Masukkan spesifikasi" />
                <x-input-error :messages="$errors->get('spec')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="unit" value="Satuan" />
                <x-text-input id="unit" wire:model="unit" type="text" class="mt-1 block w-full"
                    placeholder="Contoh: pcs, liter, meter" />
                <x-input-error :messages="$errors->get('unit')" class="mt-2" />
            </div>

            <div>
                <label class="flex items-center">
                    <input type="checkbox" wire:model="active"
                        class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <span class="ml-2 text-sm text-gray-700">Barang Aktif</span>
                </label>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button type="button"
                x-on:click="$dispatch('close-modal', '{{ $itemCategory ? 'create-item-' . $itemCategory->id : 'create-item' }}')">
                Batal
            </x-secondary-button>
            <x-primary-button type="submit">
                Simpan
            </x-primary-button>
        </div>
    </form>
</x-modal>