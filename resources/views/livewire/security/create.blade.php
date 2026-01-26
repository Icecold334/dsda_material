<x-modal name="create-security" focusable>
    <form wire:submit.prevent="validateForm" class="p-6">
        <h2 class="text-lg font-medium text-gray-900">
            Tambah Security Baru
        </h2>

        <div class="mt-6 space-y-4">
            <div>
                <x-input-label for="name" value="Nama Security" />
                <x-text-input id="name" wire:model="name" type="text" class="mt-1 block w-full"
                    placeholder="Masukkan nama security" />
            </div>

            <div>
                <x-input-label for="sudin_id" value="Sudin" />
                <livewire:components.select-input wire:model="sudin_id" :options="$sudins->pluck('name', 'id')"
                    placeholder="-- Pilih Sudin --" :key="'sudin-select'" />
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button type="button" x-on:click="$dispatch('close-modal', 'create-security')">
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
                    title: "Konfirmasi Simpan Security",
                    text: "Apakah anda yakin ingin menambahkan security ini?",
                    type: "question",
                    confirmButtonText: "Ya, Simpan",
                    cancelButtonText: "Batal",
                    onConfirm: () => {
                        Swal.fire({
                            title: "Menyimpan Security...",
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            },
                        });
                        Livewire.dispatch('confirm-save-security');
                    }
                });
            });
        });
    </script>
@endpush
