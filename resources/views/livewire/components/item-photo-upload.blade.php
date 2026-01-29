<div>
    @if($existingPhoto)
        <!-- Tombol Lihat Foto -->
        <button type="button" @click="$dispatch('open-modal', 'view-photo-{{ $requestItemId }}')"
            class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
            Lihat Foto
        </button>

        <!-- Modal Preview Foto -->
        <x-modal name="view-photo-{{ $requestItemId }}" maxWidth="2xl">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Foto Barang</h3>
                    <button type="button" @click="$dispatch('close-modal', 'view-photo-{{ $requestItemId }}')"
                        class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="flex justify-center">
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($existingPhoto->file_path) }}" alt="Foto Barang"
                        class="max-w-full max-h-96 rounded-lg shadow-lg">
                </div>
                <div class="mt-4 flex justify-between items-center">
                    <div class="text-sm text-gray-600">
                        <p>Ukuran: {{ number_format($existingPhoto->size_kb, 2) }} KB</p>
                        <p>Diupload: {{ $existingPhoto->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <label
                        class="cursor-pointer inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        Ganti Foto
                        <input type="file" wire:model="photo" accept="image/*" class="hidden">
                    </label>
                </div>
            </div>
        </x-modal>
    @else
        <!-- Tombol Upload -->
        <label
            class="cursor-pointer inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
            </svg>
            Upload Foto
            <input type="file" wire:model="photo" accept="image/*" class="hidden">
        </label>
    @endif

    <!-- Loading Indicator -->
    <div wire:loading wire:target="photo" class="inline-flex items-center ml-2 text-blue-600 text-xs">
        <svg class="animate-spin h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
            </path>
        </svg>
        Memproses...
    </div>

    <!-- Modal Konfirmasi Upload -->
    @if($showConfirmModal)
        <x-modal name="confirm-upload-{{ $requestItemId }}" :show="true">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Konfirmasi Upload Foto</h3>

                @if($tempPhoto)
                    <div class="mb-4 flex justify-center">
                        <img src="{{ $tempPhoto->temporaryUrl() }}" alt="Preview" class="max-w-full max-h-64 rounded-lg shadow">
                    </div>
                    <p class="text-sm text-gray-600 mb-4 text-center">
                        Apakah Anda yakin ingin upload foto ini?
                    </p>
                @endif

                <div class="flex justify-end gap-2">
                    <x-secondary-button type="button" wire:click="cancelUpload" :disabled="$isUploading">
                        Batal
                    </x-secondary-button>
                    <x-primary-button type="button" wire:click="confirmUpload" :disabled="$isUploading">
                        <span wire:loading.remove wire:target="confirmUpload">
                            Ya, Upload
                        </span>
                        <span wire:loading wire:target="confirmUpload" class="inline-flex items-center">
                            <svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                                </circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Mengupload...
                        </span>
                    </x-primary-button>
                </div>
            </div>
        </x-modal>
    @endif

    @push('scripts')
        <script>
            document.addEventListener('livewire:init', () => {
                Livewire.on('alert', (event) => {
                    showAlert({
                        type: event[0].type,
                        title: event[0].title,
                        text: event[0].text
                    });
                });

                Livewire.on('photoUploaded', () => {
                    // Refresh grid jika ada
                    if (window.refreshGrid) {
                        window.refreshGrid();
                    }
                });
            });
        </script>
    @endpush
</div>