<x-modal name="{{ $itemCategory ? 'create-item-' . $itemCategory->id : 'create-item' }}" focusable>
    <form wire:submit.prevent="validateForm" class="p-6">
        <h2 class="text-lg font-medium text-gray-900">
            Tambah Spesifikasi
        </h2>

        <div class="mt-6 space-y-4">
            <div>
                <x-input-label for="spec" value="Spesifikasi" />
                <x-text-input id="spec" wire:model="spec" type="text" class="mt-1 block w-full"
                    placeholder="Masukkan spesifikasi" />
            </div>

            <div>
                <x-input-label for="sudin_id" value="Sudin" />
                <livewire:components.select-input wire:model.live="sudin_id" :options="$sudins->pluck('name', 'id')"
                    placeholder="-- Pilih Sudin --" :key="'sudin-select'" />
            </div>


            <div>
                <label class="flex items-center">
                    <input type="checkbox" wire:model="active"
                        class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <span class="ml-2 text-sm text-gray-700">Spesifikasi Aktif</span>
                </label>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button type="button"
                x-on:click="$dispatch('close-modal', '{{ $itemCategory ? 'create-item-' . $itemCategory->id : 'create-item' }}')">
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
                    title: "Konfirmasi Simpan Spesifikasi",
                    text: "Apakah anda yakin ingin menambahkan spesifikasi ini?",
                    type: "question",
                    confirmButtonText: "Ya, Simpan",
                    cancelButtonText: "Batal",
                    onConfirm: () => {
                        Swal.fire({
                            title: "Menyimpan Spesifikasi...",
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            },
                        });
                        Livewire.dispatch('confirm-save-item');
                    }
                });
            });
        });
    </script>
@endpush
