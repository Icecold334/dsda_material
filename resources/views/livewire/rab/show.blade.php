<div class="space-y-4">
    <div class="grid grid-cols-2 ">
        <div class="">
            <div class="text-3xl font-semibold"> Rencana Anggaran Biaya #{{ $rab->nomor }}</div>
        </div>
        <div class="text-right flex gap-2 justify-end" x-data="{ fileCount: 0 }"
            @file-count-updated.window="fileCount = $event.detail">
            <x-secondary-button @click="$dispatch('open-modal', 'rab-information-modal')" type="button">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Informasi RAB
            </x-secondary-button>
            <x-secondary-button @click="$dispatch('open-modal', 'lampiran-modal')" type="button">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                </svg>
                Lampiran
                <span
                    class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-indigo-600 rounded-full"
                    x-show="fileCount > 0" x-text="fileCount">
                </span>
            </x-secondary-button>
            <x-button variant="secondary" href="{{ route('rab.index') }}" wire:navigate>
                Kembali
            </x-button>
        </div>
    </div>

    <div>
        <x-card title="Daftar Barang">
            <div data-grid data-api="{{ route('rab.show.json', $rab) }}" data-columns='[
        { "name": "Kode", "id": "code", "width": "20%" },
        { "name": "Spesifikasi", "id": "item" },
        { "name": "Jumlah", "id": "qty", "width": "15%" },
        { "name": "", "id": "action","width": "10%" , "sortable": false }
    ]' wire:ignore>
            </div>

        </x-card>
    </div>

    <!-- Modal Components -->
    <livewire:components.rab-information-modal mode="show" :key="'rab-info-modal-' . $rab->id" />

    <!-- Modal Lampiran -->
    <livewire:components.document-upload mode="show" modelType="App\Models\Rab" :modelId="$rab->id"
        category="lampiran_rab" label="Lampiran RAB" :multiple="true" accept="image/*,.pdf,.doc,.docx"
        modalId="lampiran-modal" :key="'doc-show-lampiran-' . $rab->id" />
</div>