<x-modal name="edit-district-{{ $district->id }}" focusable>
    <form wire:submit.prevent="update" class="p-6" x-data="{
        confirmUpdate() {
            showConfirm({
                title: 'Update Kecamatan?',
                text: 'Data kecamatan akan diperbarui.',
                confirmButtonText: 'Ya, update!',
                cancelButtonText: 'Batal',
                onConfirm: () => {
                    @this.update();
                }
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
                <livewire:components.select-input wire:model="sudin_id" :options="$sudins->pluck('name', 'id')"
                    placeholder="-- Pilih Sudin --" :key="'sudin-select-' . $district->id" />
                <x-input-error :messages="$errors->get('sudin_id')" class="mt-2" />
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button type="button"
                x-on:click="$dispatch('close-modal', 'edit-district-{{ $district->id }}')">
                Batal
            </x-secondary-button>
            <x-button type="submit" @click="confirmUpdate()">
                Update
                </x-button>
        </div>
    </form>
</x-modal>