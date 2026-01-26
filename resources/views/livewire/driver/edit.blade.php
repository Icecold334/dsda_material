<x-modal name="edit-driver-{{ $driver->id }}" focusable>
    <form wire:submit.prevent="validateForm" class="p-6" >
        <h2 class="text-lg font-medium text-gray-900">
            Edit Driver
        </h2>

        <div class="mt-6 space-y-4">
            <div>
                <x-input-label for="name" value="Nama Driver" />
                <x-text-input id="name" wire:model="name" type="text" class="mt-1 block w-full"
                    placeholder="Masukkan nama driver" />
            </div>

            <div>
                <x-input-label for="sudin_id" value="Sudin" />
                <livewire:components.select-input wire:model="sudin_id" :options="$sudins->pluck('name', 'id')"
                    placeholder="-- Pilih Sudin --" :key="'sudin-select-' . $driver->id" />
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button type="button" x-on:click="$dispatch('close-modal', 'edit-driver-{{ $driver->id }}')">
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
                    title: "Konfirmasi Update Driver",
                    text: "Apakah anda yakin ingin memperbarui driver ini?",
                    type: "question",
                    confirmButtonText: "Ya, Update",
                    cancelButtonText: "Batal",
                    onConfirm: () => {
                        Swal.fire({
                            title: "Menyimpan Driver...",
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            },
                        });
                        Livewire.dispatch('confirm-update-driver');
                    }
                });
            });
        });
    </script>
@endpush
