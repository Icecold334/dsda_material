<div class="space-y-4">
    <div class="grid grid-cols-2 ">
        <div class="">
            <div class="text-3xl font-semibold"> Detail Permintaan #{{ $permintaan->nomor }}</div>
        </div>
        <div class="text-right">
            <a href="{{ route('permintaan.nonRab.index') }}" wire:navigate
                class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2  focus:outline-none">Kembali</a>
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4">

        <x-card title="Detail Permintaan">
            <div class="">
                <table class="table-auto w-full text-md space-y-2 ">
                    <tr>
                        <td class="font-semibold w-1/2">Nomor SPB</td>
                        <td>{{ $permintaan->nomor }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Nama Permintaan</td>
                        <td>{{ $permintaan->name }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Tanggal Permintaan</td>
                        <td>{{ $permintaan->tanggal_permintaan?->format('d/m/Y') ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Status</td>
                        <td><span
                                class="bg-{{ $permintaan->status_color }}-600 text-{{ $permintaan->status_color }}-100 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">{{
    $permintaan->status_text }}</span></td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Pemohon</td>
                        <td>{{ $permintaan->user?->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Sudin</td>
                        <td>{{ $permintaan->sudin?->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Gudang</td>
                        <td>{{ $permintaan->warehouse?->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Kecamatan</td>
                        <td>{{ $permintaan->district?->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Kelurahan</td>
                        <td>{{ $permintaan->subdistrict?->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Alamat</td>
                        <td>{{ $permintaan->address ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Panjang</td>
                        <td>{{ $permintaan->panjang ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Lebar</td>
                        <td>{{ $permintaan->lebar ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Tinggi</td>
                        <td>{{ $permintaan->tinggi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Keterangan</td>
                        <td>{{ $permintaan->notes ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </x-card>

        <x-card title="Dokumen Permintaan">
            <livewire:components.document-upload mode="show" modelType="App\Models\RequestModel"
                :modelId="$permintaan->id" category="lampiran_permintaan" label="Lampiran Permintaan" :key="'doc-show-' . $permintaan->id" />
        </x-card>
    </div>

    <div>
        <x-card title="Daftar Barang">
            <div data-grid data-api="{{ route('permintaan.rab.show.json', $permintaan) }}" data-columns='[
        { "name": "Kode Barang", "id": "kode" },
        { "name": "Item", "id": "item" },
        { "name": "Jumlah", "id": "qty", "width": "15%" },
        { "name": "", "id": "action","width": "10%" , "sortable": false }
    ]' wire:ignore>
            </div>

        </x-card>
    </div>
</div>