<x-modal name="create-sudin" focusable>
    <form wire:submit="save" class="p-6">
        <h2 class="text-lg font-medium text-gray-900">
            Tambah Sudin Baru
        </h2>

        <div class="mt-6 space-y-4">
            <div>
                <x-input-label for="name" value="Nama Sudin" />
                <x-text-input id="name" wire:model="name" type="text" class="mt-1 block w-full"
                    placeholder="Masukkan nama sudin" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="short" value="Singkatan" />
                <x-text-input id="short" wire:model="short" type="text" class="mt-1 block w-full"
                    placeholder="Masukkan singkatan" />
                <x-input-error :messages="$errors->get('short')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="address" value="Alamat" />
                <textarea id="address" wire:model="address" rows="3"
                    class="mt-1 block w-full border-gray-300    focus:border-indigo-500 :border-indigo-600 focus:ring-indigo-500 :ring-indigo-600 rounded-md shadow-sm"
                    placeholder="Masukkan alamat"></textarea>
                <x-input-error :messages="$errors->get('address')" class="mt-2" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-input-label for="postal_code" value="Kode Pos" />
                    <x-text-input id="postal_code" wire:model="postal_code" type="text" class="mt-1 block w-full"
                        placeholder="Masukkan kode pos" />
                    <x-input-error :messages="$errors->get('postal_code')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="phone" value="Telepon" />
                    <x-text-input id="phone" wire:model="phone" type="text" class="mt-1 block w-full"
                        placeholder="Masukkan nomor telepon" />
                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button type="button" x-on:click="$dispatch('close-modal', 'create-sudin')">
                Batal
            </x-secondary-button>
            <x-button type="submit">
                Simpan
            </x-button>
        </div>
    </form>
</x-modal>