<div class="space-y-4">
    <div class="grid grid-cols-2 ">
        <div class="">
            <div class="text-3xl font-semibold"> Detail Permintaan #{{ $permintaan->nomor }}</div>
        </div>
        <div class="text-right flex gap-2 justify-end" x-data="{ fileCount: 0 }"
            @file-count-updated.window="fileCount = $event.detail">
            @if ($permintaan->status == 'draft')
                <x-primary-button wire:click='sendRequest'>
                    Ajukan Permintaan
                </x-primary-button>
            @endif
            <x-secondary-button @click="$dispatch('open-modal', 'request-information-modal')" type="button">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Informasi Permintaan
            </x-secondary-button>
            <x-secondary-button @click="$dispatch('open-modal', 'delivery-info-modal')" type="button">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Informasi Pengiriman
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
            <x-button variant="secondary" href="{{ route('permintaan.nonRab.index') }}" wire:navigate>
                Kembali
            </x-button>
        </div>
    </div>

    <!-- Modal Informasi Permintaan -->
    <livewire:components.request-information-modal :mode="'show'" :isRab="false" :key="'request-info-modal-show'" />

    <!-- Modal Informasi Pengiriman -->
    <livewire:components.delivery-info-modal :permintaan="$permintaan" :key="'delivery-info-modal-' . $permintaan->id" />

    <div>
        <x-card title="Daftar Barang">
            <div data-grid data-api="{{ route('permintaan.nonRab.show.json', $permintaan) }}" data-columns='[
        { "name": "No", "id": "no", "width": "8%" },
        { "name": "Kode Barang", "id": "kode", "width": "12%" },
        { "name": "Barang", "id": "barang", "width": "15%" },
        { "name": "Spesifikasi", "id": "spec" },
        { "name": "Jumlah Diminta", "id": "qty_request", "width": "15%" },
        { "name": "Jumlah Disetujui", "id": "qty_approved", "width": "15%" }
    ]' wire:ignore>
            </div>
        </x-card>
    </div>

    <!-- Modal Lampiran -->
    <livewire:components.document-upload mode="show" modelType="App\Models\RequestModel" :modelId="$permintaan->id"
        category="lampiran_permintaan" label="Lampiran Permintaan" :multiple="true" accept="image/*,.pdf,.doc,.docx"
        modalId="lampiran-modal" :key="'doc-show-lampiran-' . $permintaan->id" />

    <livewire:approval-panel :module="'permintaan'" :approvable-type="\App\Models\RequestModel::class"
        :approvable-id="$permintaan->id" />
</div>