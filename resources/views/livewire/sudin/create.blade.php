<x-modal name="create-sudin" focusable>
    <form wire:submit.prevent="validateForm" class="p-6">
        <h2 class="text-lg font-medium text-gray-900">
            Tambah Sudin Baru
        </h2>

        <div class="mt-6 space-y-4">
            <div>
                <x-input-label for="name" value="Nama Sudin" />
                <x-text-input id="name" wire:model="name" type="text" class="mt-1 block w-full"
                    placeholder="Masukkan nama sudin" />
            </div>

            <div>
                <x-input-label for="short" value="Singkatan" />
                <x-text-input id="short" wire:model="short" type="text" class="mt-1 block w-full"
                    placeholder="Masukkan singkatan" />
            </div>

            <div>
                <x-input-label for="address" value="Alamat" />
                <textarea id="address" wire:model="address" rows="3"
                    class="mt-1 block w-full border-gray-300    focus:border-indigo-500 :border-indigo-600 focus:ring-indigo-500 :ring-indigo-600 rounded-md shadow-sm"
                    placeholder="Masukkan alamat"></textarea>
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

@push('scripts')
    <script type="module">
        document.addEventListener('livewire:init', () => {
            Livewire.on('validation-passed-create', () => {
                showConfirm({
                    title: "Konfirmasi Simpan Sudin",
                    text: "Apakah anda yakin ingin menambahkan sudin ini?",
                    type: "question",
                    confirmButtonText: "Ya, Simpan",
                    cancelButtonText: "Batal",
                    onConfirm: () => {
                        Swal.fire({
                            title: "Menyimpan Sudin...",
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            },
                        });
                        Livewire.dispatch('confirm-save-sudin');
                    }
                });
            });
        });
    </script>
@endpush
