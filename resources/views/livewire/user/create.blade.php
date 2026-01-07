<x-modal name="create-user" focusable>
    <form wire:submit="save" class="p-6">
        <h2 class="text-lg font-medium text-gray-900">
            Tambah Pengguna Baru
        </h2>

        <div class="mt-6 space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">
                    Nama <span class="text-red-500">*</span>
                </label>
                <input type="text" id="name" wire:model="name"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    placeholder="Masukkan nama">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">
                    Email <span class="text-red-500">*</span>
                </label>
                <input type="email" id="email" wire:model="email"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    placeholder="email@example.com">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">
                    Password <span class="text-red-500">*</span>
                </label>
                <input type="password" id="password" wire:model="password"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    placeholder="Minimal 8 karakter">
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                    Konfirmasi Password <span class="text-red-500">*</span>
                </label>
                <input type="password" id="password_confirmation" wire:model="password_confirmation"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    placeholder="Ulangi password">
                @error('password_confirmation')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="sudin_id" class="block text-sm font-medium text-gray-700">
                    Sudin
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
                <label for="division_id" class="block text-sm font-medium text-gray-700">
                    Divisi
                </label>
                <select id="division_id" wire:model="division_id"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">-- Pilih Divisi --</option>
                    @foreach ($divisions as $division)
                        <option value="{{ $division->id }}">{{ $division->name }}</option>
                    @endforeach
                </select>
                @error('division_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="position_id" class="block text-sm font-medium text-gray-700">
                    Jabatan
                </label>
                <select id="position_id" wire:model="position_id"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">-- Pilih Jabatan --</option>
                    @foreach ($positions as $position)
                        <option value="{{ $position->id }}">{{ $position->name }}</option>
                    @endforeach
                </select>
                @error('position_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <button type="button" x-on:click="$dispatch('close-modal', 'create-user')"
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