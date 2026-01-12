<div class="space-y-4">
    <div class="grid grid-cols-2 ">
        <div class="">
            <div class="text-3xl font-semibold"> Rencana Anggaran Biaya #{{ $rab->nomor }}</div>
        </div>
        <div class="text-right">
            <a href="{{ route('rab.index') }}" wire:navigate
                class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2  focus:outline-none">Kembali</a>
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4">

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

        <x-card title="Dokumen RAB">
            <livewire:components.document-upload mode="show" modelType="App\Models\Rab" :modelId="$rab->id"
                category="lampiran_rab" label="Lampiran RAB" :key="'doc-show-' . $rab->id" />
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
</div>