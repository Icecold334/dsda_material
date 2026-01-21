<x-modal name="edit-subdistrict-{{ $subdistrict->id }}" focusable>
    <form wire:submit.prevent="update" class="p-6" x-data="{
        confirmUpdate() {
            showConfirm({
                title: 'Update Kelurahan?',
                text: 'Data kelurahan akan diperbarui.',
                confirmButtonText: 'Ya, update!',
                cancelButtonText: 'Batal',
                onConfirm: () => {
                    @this.update();
                }
            });
        }
    }">
        <h2 class="text-lg font-medium text-gray-900">
            Edit Kelurahan
        </h2>

        <div class="mt-6 space-y-4">
            <div>
                <x-input-label for="name-{{ $subdistrict->id }}" value="Nama Kelurahan" />
                <x-text-input id="name-{{ $subdistrict->id }}" wire:model="name" type="text" class="mt-1 block w-full"
                    placeholder="Masukkan nama Kelurahan" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button type="button"
                x-on:click="$dispatch('close-modal', 'edit-subdistrict-{{ $subdistrict->id }}')">
                Batal
            </x-secondary-button>
            <x-button type="submit" @click="confirmUpdate()">
                Update
            </x-button>
        </div>
    </form>
</x-modal>