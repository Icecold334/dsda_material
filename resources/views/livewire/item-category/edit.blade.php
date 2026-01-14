<x-modal name="edit-item-category-{{ $itemCategory->id }}" focusable>
    <form wire:submit.prevent="update" class="p-6" x-data="{
        confirmUpdate() {
            showConfirm({
                title: 'Update Kategori?',
                text: 'Data kategori akan diperbarui.',
                confirmButtonText: 'Ya, update!',
                cancelButtonText: 'Batal',
                onConfirm: () => {
                    @this.update();
                }
            });
        }
    }">
        <h2 class="text-lg font-medium text-gray-900">
            Edit Barang
        </h2>

        <div class="mt-6 space-y-4">
            <div>
                <x-input-label for="name-{{ $itemCategory->id }}" value="Nama Barang" />
                <x-text-input id="name-{{ $itemCategory->id }}" wire:model="name" type="text" class="mt-1 block w-full"
                    placeholder="Masukkan nama kategori" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="item_unit_id-{{ $itemCategory->id }}" value="Satuan" />
                <livewire:components.select-input wire:model="item_unit_id" :options="$units->pluck('name', 'id')"
                    placeholder="-- Pilih Satuan --" :key="'unit-select-' . $itemCategory->id" />
                <x-input-error :messages="$errors->get('item_unit_id')" class="mt-2" />
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