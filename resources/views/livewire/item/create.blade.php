<x-modal name="{{ $itemCategory ? 'create-item-' . $itemCategory->id : 'create-item' }}" focusable>
    <form wire:submit="save" class="p-6">
        <h2 class="text-lg font-medium text-gray-900">
            Tambah Barang Baru
        </h2>

        <div class="mt-6 space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">
                    Nama Barang <span class="text-red-500">*</span>
                </label>
                <input type="text" id="name" wire:model="name"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    placeholder="Masukkan nama barang">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="sudin_id" class="block text-sm font-medium text-gray-700">
                    Sudin <span class="text-red-500">*</span>
                </label>
                <select id="sudin_id" wire:model="sudin_id"
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
                <label for="item_category_id" class="block text-sm font-medium text-gray-700">
                    Kategori
                </label>
                <select id="item_category_id" wire:model="item_category_id"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('item_category_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="spec" class="block text-sm font-medium text-gray-700">
                    Spesifikasi
                </label>
                <input type="text" id="spec" wire:model="spec"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    placeholder="Masukkan spesifikasi">
                @error('spec')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="unit" class="block text-sm font-medium text-gray-700">
                    Satuan
                </label>
                <input type="text" id="unit" wire:model="unit"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    placeholder="Contoh: pcs, liter, meter">
                @error('unit')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="flex items-center">
                    <input type="checkbox" wire:model="active"
                        class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <span class="ml-2 text-sm text-gray-700">Barang Aktif</span>
                </label>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <button type="button"
                x-on:click="$dispatch('close-modal', '{{ $itemCategory ? 'create-item-' . $itemCategory->id : 'create-item' }}')"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                Batal
            </button>
            <button type="submit"
                class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-md hover:bg-primary-700">
                Simpan
            </button>
        </div>
    </form>
</x-modal>