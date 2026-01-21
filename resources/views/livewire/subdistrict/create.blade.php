<x-modal name="create-subdistrict-{{ $district->id }}" focusable>
    <form wire:submit="save" class="p-6">
        <h2 class="text-lg font-medium text-gray-900">
            Tambah Kelurahan Baru
        </h2>

        <div class="mt-6 space-y-4">
            <div>
                <x-input-label for="name-create" value="Nama Kelurahan" />
                <x-text-input id="name-create" wire:model="name" type="text" class="mt-1 block w-full"
                    placeholder="Masukkan nama Kelurahan" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button type="button"
                x-on:click="$dispatch('close-modal', 'create-subdistrict-{{ $district->id }}')">
                Batal
            </x-secondary-button>
            <x-button type="submit">
                Simpan
            </x-button>
        </div>
    </form>
</x-modal>