<x-modal name="edit-district-{{ $district->id }}" focusable>
    <form wire:submit.prevent="confirmUpdate" class="p-6" x-data="{
        confirmUpdate() {
            SwalConfirm.delete({
                eventName: 'confirmUpdateDistrict',
                eventData: { districtId: '{{ $district->id }}' },
                title: 'Update Distrik?',
                text: 'Data Kecamatan akan diperbarui.',
                confirmText: 'Ya, update!',
                cancelText: 'Batal'
            });
        }
    }">
        <h2 class="text-lg font-medium text-gray-900">
            Edit Kecamatan
        </h2>

        <div class="mt-6 space-y-4">
            <div>
                <x-input-label for="name-{{ $district->id }}" value="Nama Kecamatan" />
                <x-text-input id="name-{{ $district->id }}" wire:model="name" type="text" class="mt-1 block w-full"
                    placeholder="Masukkan nama Kecamatan" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="sudin_id-{{ $district->id }}" value="Sudin" />
                <x-select-input id="sudin_id-{{ $district->id }}" wire:model="sudin_id" class="mt-1 block w-full"
                    placeholder="-- Pilih Sudin --" :options="$sudins->pluck('name', 'id')" />
                <x-input-error :messages="$errors->get('sudin_id')" class="mt-2" />
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button type="button"
                x-on:click="$dispatch('close-modal', 'edit-district-{{ $district->id }}')">
                Batal
            </x-secondary-button>
            <x-primary-button type="button" @click="confirmUpdate()">
                Update
            </x-primary-button>
        </div>
    </form>
</x-modal>