<x-modal name="edit-item-{{ $item->id }}" focusable>
    <form wire:submit.prevent="confirmUpdate" class="p-6" x-data="{
        confirmUpdate() {
            SwalConfirm.delete({
                eventName: 'confirmUpdateItem',
                eventData: { itemId: '{{ $item->id }}' },
                title: 'Update Barang?',
                text: 'Data barang akan diperbarui.',
                confirmText: 'Ya, update!',
                cancelText: 'Batal'
            });
        }
    }">
        <h2 class="text-lg font-medium text-gray-900">
            Edit Barang
        </h2>

        <div class="mt-6 space-y-4">
            <div>
                <x-input-label for="name-{{ $item->id }}" value="Nama Barang" />
                <x-text-input id="name-{{ $item->id }}" wire:model="name" type="text" class="mt-1 block w-full"
                    placeholder="Masukkan nama barang" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="sudin_id-{{ $item->id }}" value="Sudin" />
                <livewire:components.select-input
                    wire:model.live="sudin_id"
                    :options="$sudins->pluck('name', 'id')"
                    placeholder="-- Pilih Sudin --"
                    :key="'sudin-select-' . $item->id" />
                <x-input-error :messages="$errors->get('sudin_id')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="item_category_id-{{ $item->id }}" value="Kategori" />
                <livewire:components.select-input
                    wire:model="item_category_id"
                    :options="$categories->pluck('name', 'id')"
                    placeholder="-- Pilih Kategori --"
                    :key="'item-category-select-' . $item->id . '-' . $sudin_id" />
                <x-input-error :messages="$errors->get('item_category_id')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="spec-{{ $item->id }}" value="Spesifikasi" />
                <x-text-input id="spec-{{ $item->id }}" wire:model="spec" type="text" class="mt-1 block w-full"
                    placeholder="Masukkan spesifikasi" />
                <x-input-error :messages="$errors->get('spec')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="unit-{{ $item->id }}" value="Satuan" />
                <x-text-input id="unit-{{ $item->id }}" wire:model="unit" type="text" class="mt-1 block w-full"
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
            <x-secondary-button type="button" x-on:click="$dispatch('close-modal', 'edit-item-{{ $item->id }}')">
                Batal
            </x-secondary-button>
            <x-primary-button type="button" @click="confirmUpdate()">
                Update
            </x-primary-button>
        </div>
    </form>
</x-modal>