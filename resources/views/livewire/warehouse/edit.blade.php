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
                <label for="name-{{ $warehouse->id }}" class="block text-sm font-medium text-gray-700">
                    Nama Gudang <span class="text-red-500">*</span>
                </label>
                <input type="text" id="name-{{ $warehouse->id }}" wire:model="name"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    placeholder="Masukkan nama gudang">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="sudin_id-{{ $warehouse->id }}" class="block text-sm font-medium text-gray-700">
                    Sudin <span class="text-red-500">*</span>
                </label>
                <select id="sudin_id-{{ $warehouse->id }}" wire:model="sudin_id"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">-- Pilih Sudin --</option>
                    @foreach ($sudins as $sudin)
                        <option value="{{ $sudin->id }}">{{ $sudin->name }}</option>
                    @endforeach
                </select>
                @error('sudin_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="location-{{ $warehouse->id }}" class="block text-sm font-medium text-gray-700">
                    Lokasi
                </label>
                <input type="text" id="location-{{ $warehouse->id }}" wire:model="location"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    placeholder="Masukkan lokasi gudang">
                @error('location')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <button type="button" x-on:click="$dispatch('close-modal', 'edit-warehouse-{{ $warehouse->id }}')"
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