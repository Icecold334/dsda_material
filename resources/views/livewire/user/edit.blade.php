<x-modal name="edit-user-{{ $user->id }}" focusable>
    <form wire:submit.prevent="confirmUpdate" class="p-6" x-data="{
        confirmUpdate() {
            SwalConfirm.delete({
                eventName: 'confirmUpdateUser',
                eventData: { userId: '{{ $user->id }}' },
                title: 'Update Pengguna?',
                text: 'Data pengguna akan diperbarui.',
                confirmText: 'Ya, update!',
                cancelText: 'Batal'
            });
        }
    }">
        <h2 class="text-lg font-medium text-gray-900">
            Edit Pengguna
        </h2>

        <div class="mt-6 space-y-4">
            <div>
                <label for="name-{{ $user->id }}" class="block text-sm font-medium text-gray-700">
                    Nama <span class="text-red-500">*</span>
                </label>
                <input type="text" id="name-{{ $user->id }}" wire:model="name"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    placeholder="Masukkan nama">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email-{{ $user->id }}" class="block text-sm font-medium text-gray-700">
                    Email <span class="text-red-500">*</span>
                </label>
                <input type="email" id="email-{{ $user->id }}" wire:model="email"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    placeholder="email@example.com">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password-{{ $user->id }}" class="block text-sm font-medium text-gray-700">
                    Password Baru (Kosongkan jika tidak diubah)
                </label>
                <input type="password" id="password-{{ $user->id }}" wire:model="password"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    placeholder="Minimal 8 karakter">
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation-{{ $user->id }}" class="block text-sm font-medium text-gray-700">
                    Konfirmasi Password Baru
                </label>
                <input type="password" id="password_confirmation-{{ $user->id }}" wire:model="password_confirmation"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    placeholder="Ulangi password">
                @error('password_confirmation')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="sudin_id-{{ $user->id }}" class="block text-sm font-medium text-gray-700">
                    Sudin
                </label>
                <select id="sudin_id-{{ $user->id }}" wire:model="sudin_id"
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
                <label for="division_id-{{ $user->id }}" class="block text-sm font-medium text-gray-700">
                    Divisi
                </label>
                <select id="division_id-{{ $user->id }}" wire:model="division_id"
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
                <label for="position_id-{{ $user->id }}" class="block text-sm font-medium text-gray-700">
                    Jabatan
                </label>
                <select id="position_id-{{ $user->id }}" wire:model="position_id"
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
            <button type="button" x-on:click="$dispatch('close-modal', 'edit-user-{{ $user->id }}')"
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