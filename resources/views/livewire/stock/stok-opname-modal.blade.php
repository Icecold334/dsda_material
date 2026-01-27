<div class="">
    <x-modal name="stok-opname" maxWidth="2xl">
        <div class="p-4 flex flex-col items-start gap-4 w-full">
            <h1 class="text-xl font-semibold">Stok Opname Gudang {{ $warehouse->name ?? "-" }}</h1>

            @if (session()->has('message'))
                <div class="w-full p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('message') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="w-full p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif

            @if ($uploadedFile)
                <div class="w-full p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded">
                    File berhasil diupload: {{ $uploadedFile->getClientOriginalName() }}
                </div>
            @endif

            <div class="cta grid grid-cols-3 gap-4 items-center text-center w-full">
                <x-button variant="info" wire:click="downloadTemplate" type="button">
                    Download Template
                </x-button>

                <label for="file-upload" class="cursor-pointer">
                    <div
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 bg-green-600 hover:bg-green-700 active:bg-green-900 focus:ring-green-500 text-white">
                        Upload File SO
                    </div>
                    <input id="file-upload" type="file" wire:model="file" accept=".xlsx,.xls" class="hidden">
                </label>

                <x-button variant="danger" wire:click="processStokOpname" type="button" :disabled="!$uploadedFile">
                    Jalankan Penyesuaian
                </x-button>
            </div>

            @if ($file)
                <div class="w-full text-sm text-gray-600">
                    <div wire:loading wire:target="file" class="text-blue-600">
                        Mengupload file...
                    </div>
                </div>
            @endif

            <div class="w-full h-[1px] bg-slate-200"></div>

            <div class="section flex flex-col items-start gap-2 w-full">
                <x-input-label>Petunjuk :</x-input-label>
                <div class="flex flex-col w-full text-sm items-start gap-2 text-slate-600">
                    <p>1. Klik <strong>Download Template</strong> untuk mendapatkan format Excel stok opname.</p>
                    <p>2. <strong>Hapus baris contoh</strong> yang ada di baris pertama.</p>
                    <p>3. Isi <strong>Stok Aktual</strong> dengan hasil perhitungan fisik barang.</p>
                    <p>4. Isi <strong>Tanggal Opname</strong> sesuai kapan stok opname dilakukan (format: YYYY-MM-DD).
                    </p>
                    <p>5. Upload kembali file tersebut melalui tombol <strong>Upload File SO</strong>.</p>
                    <p>6. Klik <strong>Jalankan Penyesuaian</strong> untuk mencatat hasil opname dan memperbarui stok
                        sistem.</p>
                </div>
            </div>

            @if (count($results) > 0)
                <div class="w-full h-[1px] bg-slate-200"></div>
                <div class="section flex flex-col items-start gap-2 w-full">
                    <x-input-label>Hasil Proses :</x-input-label>
                    <div class="w-full overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Kode Spek
                                    </th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama Barang
                                    </th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Qty Sistem
                                    </th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Qty Real
                                    </th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Selisih</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($results as $result)
                                    <tr>
                                        <td class="px-3 py-2">
                                            @if ($result['status'] === 'success')
                                                <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">Berhasil</span>
                                            @else
                                                <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800">Gagal</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2">{{ $result['kode_spek'] }}</td>
                                        <td class="px-3 py-2">{{ $result['item_name'] ?? $result['message'] ?? '-' }}</td>
                                        <td class="px-3 py-2">{{ $result['qty_system'] ?? '-' }}</td>
                                        <td class="px-3 py-2">{{ $result['qty_real'] ?? '-' }}</td>
                                        <td class="px-3 py-2">
                                            @if (isset($result['selisih']))
                                                <span class="{{ $result['selisih'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ $result['selisih'] > 0 ? '+' : '' }}{{ $result['selisih'] }}
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <div class="w-full h-[1px] bg-slate-200"></div>
            <div class="button justify-end w-full items-end flex">
                <x-button variant="primary" x-on:click="$dispatch('close-modal', 'stok-opname')" type="button">
                    Tutup
                </x-button>
            </div>
        </div>
    </x-modal>
</div>