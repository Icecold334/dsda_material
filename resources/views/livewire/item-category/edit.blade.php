<x-modal name="edit-item-category-{{ $itemCategory->id }}" focusable>
    <form wire:submit.prevent="validateForm" class="p-6">
        <h2 class="text-lg font-medium text-gray-900">
            Edit Barang
        </h2>

        <div class="mt-6 space-y-4">
            <div>
                <x-input-label for="name-{{ $itemCategory->id }}" value="Nama Barang" />
                <x-text-input id="name-{{ $itemCategory->id }}" wire:model="name" type="text" class="mt-1 block w-full"
                    placeholder="Masukkan nama kategori" />
            </div>

            <div>
                <x-input-label for="item_unit_id-{{ $itemCategory->id }}" value="Satuan" />
                <livewire:components.select-input wire:model="item_unit_id" :options="$units->pluck('name', 'id')"
                    placeholder="-- Pilih Satuan --" :key="'unit-select-' . $itemCategory->id" />
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button type="button"
                x-on:click="$dispatch('close-modal', 'edit-item-category-{{ $itemCategory->id }}')">
                Batal
            </x-secondary-button>
            <x-button type="submit">
                Update
            </x-button>
        </div>
    </form>
</x-modal>
@push('scripts')
    <script type="module">
        document.addEventListener('livewire:init', () => {
            Livewire.on('validation-passed-update', () => {
                showConfirm({
                    title: "Konfirmasi Update Barang",
                    text: "Apakah anda yakin ingin memperbarui barang ini?",
                    type: "question",
                    confirmButtonText: "Ya, Update",
                    cancelButtonText: "Batal",
                    onConfirm: () => {
                        Swal.fire({
                            title: "Memperbarui Barang...",
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            },
                        });
                        Livewire.dispatch('confirm-update-item-category');
                    }
                });
            });
        });
    </script>
@endpush
