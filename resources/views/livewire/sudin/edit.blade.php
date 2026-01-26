<x-modal name="edit-sudin-{{ $sudin->id }}" focusable>
    <form wire:submit.prevent="validateForm" class="p-6">
        <h2 class="text-lg font-medium text-gray-900">
            Edit Sudin
        </h2>

        <div class="mt-6 space-y-4">
            <div>
                <x-input-label for="name-{{ $sudin->id }}" value="Nama Sudin" />
                <x-text-input id="name-{{ $sudin->id }}" wire:model="name" type="text" class="mt-1 block w-full"
                    placeholder="Masukkan nama sudin" />
            </div>

            <div>
                <x-input-label for="short-{{ $sudin->id }}" value="Singkatan" />
                <x-text-input id="short-{{ $sudin->id }}" wire:model="short" type="text" class="mt-1 block w-full"
                    placeholder="Masukkan singkatan" />
            </div>

            <div>
                <x-input-label for="address-{{ $sudin->id }}" value="Alamat" />
                <textarea id="address-{{ $sudin->id }}" wire:model="address" rows="3"
                    class="mt-1 block w-full border-gray-300    focus:border-indigo-500 :border-indigo-600 focus:ring-indigo-500 :ring-indigo-600 rounded-md shadow-sm"
                    placeholder="Masukkan alamat"></textarea>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button type="button" x-on:click="$dispatch('close-modal', 'edit-sudin-{{ $sudin->id }}')">
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
                    title: "Konfirmasi Update Sudin",
                    text: "Apakah anda yakin ingin memperbarui sudin ini?",
                    type: "question",
                    confirmButtonText: "Ya, Update",
                    cancelButtonText: "Batal",
                    onConfirm: () => {
                        Swal.fire({
                            title: "Memperbarui Sudin...",
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            },
                        });
                        Livewire.dispatch('confirm-update-sudin');
                    }
                });
            });
        });
    </script>
@endpush
