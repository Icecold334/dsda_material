<x-modal name="confirm-contract" maxWidth="4xl">
    <div class="p-6 space-y-6">

        {{-- Header --}}
        <div>
            <h2 class="text-3xl font-semibold text-gray-900">
                {{ $titleModal }}
            </h2>
            @if (!$showDetail)
            <p class="text-sm text-gray-500 ">
                Pastikan data kontrak berikut sudah benar sebelum dilanjutkan.
            </p>
            @endif
        </div>

        {{-- Body --}}
        @if ($dataContract)
        <div class="grid grid-cols-2">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700 border border-gray-200 rounded-lg">
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="font-semibold w-1/3 bg-gray-50 px-4 py-2">Tahun Anggaran</td>
                            <td class="px-4 py-2">{{ $dataContract['tahun_anggaran'] }}</td>
                        </tr>
                        <tr>
                            <td class="font-semibold bg-gray-50 px-4 py-2">Dinas / Sudin</td>
                            <td class="px-4 py-2">{{ $dataContract['dinas_sudin'] }}</td>
                        </tr>
                        <tr>
                            <td class="font-semibold bg-gray-50 px-4 py-2">Bidang / Seksi</td>
                            <td class="px-4 py-2">{{ $dataContract['nama_bidang_seksi'] }}</td>
                        </tr>
                        <tr>
                            <td class="font-semibold bg-gray-50 px-4 py-2">Kode Program</td>
                            <td class="px-4 py-2">{{ $dataContract['kode_program'] }}</td>
                        </tr>
                        <tr>
                            <td class="font-semibold bg-gray-50 px-4 py-2">Program</td>
                            <td class="px-4 py-2">{{ $dataContract['program'] }}</td>
                        </tr>
                        <tr>
                            <td class="font-semibold bg-gray-50 px-4 py-2">Kode Kegiatan</td>
                            <td class="px-4 py-2">{{ $dataContract['kode_kegiatan'] }}</td>
                        </tr>
                        <tr>
                            <td class="font-semibold bg-gray-50 px-4 py-2">Kegiatan</td>
                            <td class="px-4 py-2">{{ $dataContract['kegiatan'] }}</td>
                        </tr>
                        <tr>
                            <td class="font-semibold bg-gray-50 px-4 py-2">Kode Sub Kegiatan</td>
                            <td class="px-4 py-2">{{ $dataContract['kode_sub_kegiatan'] }}</td>
                        </tr>
                        <tr>
                            <td class="font-semibold bg-gray-50 px-4 py-2">Sub Kegiatan</td>
                            <td class="px-4 py-2">{{ $dataContract['sub_kegiatan'] }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700 border border-gray-200 rounded-lg">
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="font-semibold bg-gray-50 px-4 py-2">Kode Rekening</td>
                            <td class="px-4 py-2">{{ $dataContract['kode_rekening'] }}</td>
                        </tr>
                        <tr>
                            <td class="font-semibold bg-gray-50 px-4 py-2">Nama Penyedia</td>
                            <td class="px-4 py-2">{{ $dataContract['nama_penyedia'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="font-semibold bg-gray-50 px-4 py-2">Uraian Rekening</td>
                            <td class="px-4 py-2">{{ $dataContract['uraian_kode_rekening'] }}</td>
                        </tr>
                        <tr>
                            <td class="font-semibold bg-gray-50 px-4 py-2">Nama Paket</td>
                            <td class="px-4 py-2">{{ $dataContract['nama_paket'] }}</td>
                        </tr>
                        <tr>
                            <td class="font-semibold bg-gray-50 px-4 py-2">Nomor SPK</td>
                            <td class="px-4 py-2">{{ $dataContract['no_spk'] }}</td>
                        </tr>
                        <tr>
                            <td class="font-semibold bg-gray-50 px-4 py-2">Tanggal SPK</td>
                            <td class="px-4 py-2">
                                {{ \Carbon\Carbon::parse($dataContract['tgl_spk'])->translatedFormat('l, d F Y') }}
                            </td>

                        </tr>
                        <tr>
                            <td class="font-semibold bg-gray-50 px-4 py-2">Tanggal Akhir SPK</td>
                            <td class="px-4 py-2">
                                {{ \Carbon\Carbon::parse($dataContract['tgl_akhir_spk'])->translatedFormat('l, d F Y')
                                }}
                            </td>

                        </tr>
                        <tr>
                            <td class="font-semibold bg-gray-50 px-4 py-2">Jenis Pengadaan</td>
                            <td class="px-4 py-2">{{ $dataContract['jenis_pengadaan'] }}</td>
                        </tr>
                        <tr>
                            <td class="font-semibold bg-gray-50 px-4 py-2">Nilai Contract</td>
                            <td class="px-4 py-2 font-semibold text-green-700">
                                Rp {{ number_format($dataContract['nilai_kontrak'], 0, ',', '.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- Footer --}}
        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
            <button type="button"
                class="text-gray-700 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5"
                x-on:click="$dispatch('close-modal', 'confirm-contract')">
                {{ $showDetail ? "Tutup":"Tidak" }}
            </button>

            @if (!$showDetail)
            <button type="button"
                x-on:click="$dispatch('proceedCreateContract', {data:{{ json_encode($dataContract) }}}), $dispatch('close-modal', 'confirm-contract'),$dispatch('close-modal', 'input-contract-number')"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">
                Ya, Lanjutkan
            </button>
            @endif
        </div>

    </div>
</x-modal>