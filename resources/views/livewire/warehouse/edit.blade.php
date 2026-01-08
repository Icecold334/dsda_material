<x-modal name="edit-warehouse-{{ $warehouse->id }}" focusable>
    <form wire:submit.prevent="confirmUpdate" class="p-6" x-data="{
        confirmUpdate() {
            SwalConfirm.delete({
                eventName: 'confirmUpdateWarehouse',
                eventData: { warehouseId: '{{ $warehouse->id }}' },
                title: 'Update Gudang?',
                text: 'Data gudang akan diperbarui.',
                confirmText: 'Ya, update!',
                cancelText: 'Batal'
            });
        }
    }">
        <h2 class="text-lg font-medium text-gray-900">
            Edit Gudang
        </h2>

        <div class="mt-6 space-y-4">
            <div>
                <x-input-label for="name-{{ $warehouse->id }}" value="Nama Gudang" />
                <x-text-input id="name-{{ $warehouse->id }}" wire:model="name" type="text" class="mt-1 block w-full"
                    placeholder="Masukkan nama gudang" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="sudin_id-{{ $warehouse->id }}" value="Sudin" />
                <x-select-input id="sudin_id-{{ $warehouse->id }}" wire:model="sudin_id" class="mt-1 block w-full"
                    placeholder="-- Pilih Sudin --" :options="$sudins->pluck('name', 'id')" />
                <x-input-error :messages="$errors->get('sudin_id')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="location-{{ $warehouse->id }}" value="Lokasi" />
                <x-text-input id="location-{{ $warehouse->id }}" wire:model="location" type="text"
                    class="mt-1 block w-full" placeholder="Masukkan lokasi gudang" />
                <x-input-error :messages="$errors->get('location')" class="mt-2" />
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button type="button"
                x-on:click="$dispatch('close-modal', 'edit-warehouse-{{ $warehouse->id }}')">
                Batal
            </x-secondary-button>
            <x-primary-button type="button" @click="confirmUpdate()">
                Update
            </x-primary-button>
        </div>
    </form>
</x-modal>