<x-modal name="edit-sudin-{{ $sudin->id }}" focusable>
    <form wire:submit.prevent="update" class="p-6" x-data="{
        confirmUpdate() {
            showConfirm({
                title: 'Update Sudin?',
                text: 'Data Sudin akan diperbarui.',
                confirmButtonText: 'Ya, update!',
                cancelButtonText: 'Batal',
                onConfirm: () => {
                    @this.update();
                }
            });
        }
    }">
        <h2 class="text-lg font-medium text-gray-900">
            Edit Sudin
        </h2>

        <div class="mt-6 space-y-4">
            <div>
                <x-input-label for="name-{{ $sudin->id }}" value="Nama Sudin" />
                <x-text-input id="name-{{ $sudin->id }}" wire:model="name" type="text" class="mt-1 block w-full"
                    placeholder="Masukkan nama sudin" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="short-{{ $sudin->id }}" value="Singkatan" />
                <x-text-input id="short-{{ $sudin->id }}" wire:model="short" type="text" class="mt-1 block w-full"
                    placeholder="Masukkan singkatan" />
                <x-input-error :messages="$errors->get('short')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="address-{{ $sudin->id }}" value="Alamat" />
                <textarea id="address-{{ $sudin->id }}" wire:model="address" rows="3"
                    class="mt-1 block w-full border-gray-300    focus:border-indigo-500 :border-indigo-600 focus:ring-indigo-500 :ring-indigo-600 rounded-md shadow-sm"
                    placeholder="Masukkan alamat"></textarea>
                <x-input-error :messages="$errors->get('address')" class="mt-2" />
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button type="button" x-on:click="$dispatch('close-modal', 'edit-sudin-{{ $sudin->id }}')">
                Batal
            </x-secondary-button>
            <x-primary-button type="button" @click="confirmUpdate()">
                Update
            </x-primary-button>
        </div>
    </form>
</x-modal>