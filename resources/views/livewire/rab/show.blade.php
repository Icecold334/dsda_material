<div class="space-y-4">
    <div class="grid grid-cols-2 ">
        <div class="">
            <div class="text-3xl font-semibold"> Rencana Anggaran Biaya #{{ $rab->nomor }}</div>
        </div>
        <div class="text-right flex gap-2 justify-end" x-data="{ fileCount: 0 }"
            @file-count-updated.window="fileCount = $event.detail">
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
        <x-card title="Detail RAB">
            <div class="">
                <table class="table-auto w-full text-md space-y-2 ">
                    <tr>
                        <td class="font-semibold w-1/2">Nomor RAB</td>
                        <td>{{ $rab->nomor }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Nama Kegiatan</td>
                        <td>{{ $rab->name }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Tahun Anggaran</td>
                        <td>{{ $rab->tahun }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Tanggal Mulai</td>
                        <td>{{ $rab->tanggal_mulai?->format('d/m/Y') ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Tanggal Selesai</td>
                        <td>{{ $rab->tanggal_selesai?->format('d/m/Y') ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Status</td>
                        <td><span
                                class="bg-{{ $rab->status_color }}-600 text-{{ $rab->status_color }}-100 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">{{
    $rab->status_text }}</span></td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Pembuat</td>
                        <td>{{ $rab->user?->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Total Anggaran</td>
                        <td>Rp {{ number_format($rab->total, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Sudin</td>
                        <td>{{ $rab->sudin?->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Tipe Barang</td>
                        <td>{{ $rab->itemType?->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Kecamatan</td>
                        <td>{{ $rab->district?->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Kelurahan</td>
                        <td>{{ $rab->subdistrict?->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Alamat</td>
                        <td>{{ $rab->address ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Panjang</td>
                        <td>{{ $rab->panjang ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Lebar</td>
                        <td>{{ $rab->lebar ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Tinggi</td>
                        <td>{{ $rab->tinggi ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </x-card>
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

    <!-- Modal Lampiran -->
    <livewire:components.document-upload mode="show" modelType="App\Models\Rab" :modelId="$rab->id"
        category="lampiran_rab" label="Lampiran RAB" :multiple="true" accept="image/*,.pdf,.doc,.docx"
        modalId="lampiran-modal" :key="'doc-show-lampiran-' . $rab->id" />
</div>