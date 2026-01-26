<x-modal name="edit-item-{{ $item->id }}" focusable>
    <form wire:submit.prevent="validateForm" class="p-6">
        <h2 class="text-lg font-medium text-gray-900">
            Edit Spesifikasi
        </h2>

        <div class="mt-6 space-y-4">
            <div>
                <x-input-label for="spec-{{ $item->id }}" value="Spesifikasi" />
                <x-text-input id="spec-{{ $item->id }}" wire:model="spec" type="text" class="mt-1 block w-full"
                    placeholder="Masukkan spesifikasi" />
            </div>

            <div>
                <x-input-label for="sudin_id-{{ $item->id }}" value="Sudin" />
                <livewire:components.select-input wire:model.live="sudin_id" :options="$sudins->pluck('name', 'id')"
                    placeholder="-- Pilih Sudin --" :key="'sudin-select-' . $item->id" />
            </div>

            <div>
                <x-input-label for="item_category_id-{{ $item->id }}" value="Barang" />
                <livewire:components.select-input wire:model="item_category_id" :options="$categories->pluck('name', 'id')"
                    placeholder="-- Pilih Barang --" :key="'item-category-select-' . $item->id . '-' . $sudin_id" />
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
            <x-secondary-button type="button" x-on:click="$dispatch('close-modal', 'edit-item-{{ $item->id }}')">
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
                    title: "Konfirmasi Update Spesifikasi",
                    text: "Apakah anda yakin ingin memperbarui spesifikasi ini?",
                    type: "question",
                    confirmButtonText: "Ya, Update",
                    cancelButtonText: "Batal",
                    onConfirm: () => {
                        Swal.fire({
                            title: "Memperbarui Spesifikasi...",
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            },
                        });
                        Livewire.dispatch('confirm-update-item');
                    }
                });
            });
        });
    </script>
@endpush
