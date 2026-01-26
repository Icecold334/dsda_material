<div>
    <x-modal name="{{ $modalId }}" maxWidth="md">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                Dokumen Permintaan
            </h2>

            <div class="space-y-3">
                <!-- Button Surat Jalan -->
                <a href="{{ route('download-pdf.surat-jalan.non-rab', $permintaanId) }}" target="_blank"
                    class="w-full flex items-center justify-between px-4 py-3 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200 group">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-gray-600 group-hover:text-primary-600" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="text-left">
                            <div class="text-sm font-medium text-gray-900">Surat Jalan</div>
                            <div class="text-xs text-gray-500">Generate dan download surat jalan</div>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-primary-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>

                <!-- Tambahkan button dokumen lainnya di sini sesuai kebutuhan -->
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button @click="$dispatch('close-modal', '{{ $modalId }}')" type="button">
                    Tutup
                </x-secondary-button>
            </div>
        </div>
    </x-modal>
</div>