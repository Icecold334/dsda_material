<x-modal name="edit-item-category-{{ $itemCategory->id }}" focusable>
    <form wire:submit.prevent="confirmUpdate" class="p-6" x-data="{
        confirmUpdate() {
            SwalConfirm.delete({
                eventName: 'confirmUpdateItemCategory',
                eventData: { itemCategoryId: '{{ $itemCategory->id }}' },
                title: 'Update Kategori?',
                text: 'Data kategori akan diperbarui.',
                confirmText: 'Ya, update!',
                cancelText: 'Batal'
            });
        }
    }">
        <h2 class="text-lg font-medium text-gray-900">
            Edit Kategori Barang
        </h2>

        <div class="mt-6 space-y-4">
            <div>
                <x-input-label for="name-{{ $itemCategory->id }}" value="Nama Kategori" />
                <x-text-input id="name-{{ $itemCategory->id }}" wire:model="name" type="text" class="mt-1 block w-full"
                    placeholder="Masukkan nama kategori" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="sudin_id-{{ $itemCategory->id }}" value="Sudin" />
                <livewire:components.select-input wire:model="sudin_id" :options="$sudins->pluck('name', 'id')"
                    placeholder="-- Pilih Sudin --" :key="'sudin-select-' . $itemCategory->id" />
                <x-input-error :messages="$errors->get('sudin_id')" class="mt-2" />
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button type="button"
                x-on:click="$dispatch('close-modal', 'edit-item-category-{{ $itemCategory->id }}')">
                Batal
            </x-secondary-button>
            <x-primary-button type="button" @click="confirmUpdate()">
                Update
            </x-primary-button>
        </div>
    </form>
</x-modal>