<x-modal name="edit-subdistrict-{{ $subdistrict->id }}" focusable>
    <form wire:submit.prevent="confirmUpdate" class="p-6" x-data="{
        confirmUpdate() {
            SwalConfirm.delete({
                eventName: 'confirmUpdateSubdistrict',
                eventData: { subdistrictId: '{{ $subdistrict->id }}' },
                title: 'Update Subdistrik?',
                text: 'Data subdistrik akan diperbarui.',
                confirmText: 'Ya, update!',
                cancelText: 'Batal'
            });
        }
    }">
        <h2 class="text-lg font-medium text-gray-900">
            Edit Subdistrik
        </h2>

        <div class="mt-6 space-y-4">
            <div>
                <label for="name-{{ $subdistrict->id }}" class="block text-sm font-medium text-gray-700">
                    Nama Subdistrik <span class="text-red-500">*</span>
                </label>
                <input type="text" id="name-{{ $subdistrict->id }}" wire:model="name"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    placeholder="Masukkan nama subdistrik">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <button type="button" x-on:click="$dispatch('close-modal', 'edit-subdistrict-{{ $subdistrict->id }}')"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                Batal
            </button>
            <button type="button" @click="confirmUpdate()"
                class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-md hover:bg-primary-700">
                Update
            </button>
        </div>
    </form>
</x-modal>