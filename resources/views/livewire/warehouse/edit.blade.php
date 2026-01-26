<x-modal name="edit-warehouse-{{ $warehouse->id }}" focusable>
    <form wire:submit.prevent="validateForm" class="p-6">
        <h2 class="text-lg font-medium text-gray-900">
            Edit Gudang
        </h2>

        <div class="mt-6 space-y-4">
            <div>
                <x-input-label for="name-{{ $warehouse->id }}" value="Nama Gudang" />
                <x-text-input id="name-{{ $warehouse->id }}" wire:model="name" type="text" class="mt-1 block w-full"
                    placeholder="Masukkan nama gudang" />
            </div>

            <div>
                <x-input-label for="sudin_id-{{ $warehouse->id }}" value="Sudin" />
                <livewire:components.select-input wire:model="sudin_id" :options="$sudins->pluck('name', 'id')"
                    placeholder="-- Pilih Sudin --" :key="'sudin-select-' . $warehouse->id" />
            </div>

            <div>
                <x-input-label for="location-{{ $warehouse->id }}" value="Lokasi" />
                <x-text-input id="location-{{ $warehouse->id }}" wire:model="location" type="text"
                    class="mt-1 block w-full" placeholder="Masukkan lokasi gudang" />
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button type="button"
                x-on:click="$dispatch('close-modal', 'edit-warehouse-{{ $warehouse->id }}')">
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
                    title: "Konfirmasi Update Gudang",
                    text: "Apakah anda yakin ingin memperbarui gudang ini?",
                    type: "question",
                    confirmButtonText: "Ya, Update",
                    cancelButtonText: "Batal",
                    onConfirm: () => {
                        Swal.fire({
                            title: "Memperbarui Gudang...",
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            },
                        });
                        Livewire.dispatch('confirm-update-warehouse');
                    }
                });
            });
        });
    </script>
@endpush
