<x-modal name="edit-security-{{ $security->id }}" focusable>
    <form wire:submit.prevent="validateForm" class="p-6">
        <h2 class="text-lg font-medium text-gray-900">
            Edit Security
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
                    placeholder="-- Pilih Sudin --" :key="'sudin-select-' . $security->id" />
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button type="button"
                x-on:click="$dispatch('close-modal', 'edit-security-{{ $security->id }}')">
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
                    title: "Konfirmasi Update Security",
                    text: "Apakah anda yakin ingin memperbarui security ini?",
                    type: "question",
                    confirmButtonText: "Ya, Update",
                    cancelButtonText: "Batal",
                    onConfirm: () => {
                        Swal.fire({
                            title: "Memperbarui Security...",
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            },
                        });
                        Livewire.dispatch('confirm-update-security');
                    }
                });
            });
        });
    </script>
@endpush