<div class="space-y-4">
    <div class="grid grid-cols-2 ">
        <div class="">
            <div class="text-3xl font-semibold">Detail Transfer Permintaan</div>
        </div>
        <div class="text-right flex gap-2 justify-end">
            <x-primary-button href="{{ route('transfer.permintaan.index') }}" wire:navigate>
                Kembali
            </x-primary-button>
        </div>
    </div>
    <div>
        <x-card title="Detail Transfer">
            <div class="">
                <table class="table-auto w-full text-md space-y-2 ">
                    <tr>
                        <td class="font-semibold w-1/2">Tanggal Transfer</td>
                        <td>{{ $transfer->tanggal_transfer?->format('d/m/Y') ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Status</td>
                        <td><span
                                class="bg-{{ $transfer->status_color }}-600 text-{{ $transfer->status_color }}-100 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">{{
    $transfer->status_text }}</span></td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Sudin Peminta (Anda)</td>
                        <td>{{ $transfer->sudinPengirim?->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Sudin Diminta (Tujuan)</td>
                        <td>{{ $transfer->sudinPenerima?->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Pembuat</td>
                        <td>{{ $transfer->user?->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Keterangan</td>
                        <td>{{ $transfer->notes ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </x-card>
    </div>

    <div>
        <x-card title="Daftar Barang">
            <div data-grid data-api="{{ route('transfer.permintaan.show.json', $transfer) }}" data-columns='[
        { "name": "No", "id": "no", "width": "8%" },
        { "name": "Kode Barang", "id": "kode", "width": "12%" },
        { "name": "Barang", "id": "barang", "width": "20%" },
        { "name": "Spesifikasi", "id": "spec" },
        { "name": "Jumlah", "id": "qty", "width": "15%" },
        { "name": "Catatan", "id": "notes", "width": "15%" }
    ]' wire:ignore>
            </div>
        </x-card>
    </div>
</div>