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
        @if ($contract)
            @if ($isApi)
                <div class="grid grid-cols-2">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-700 border border-gray-200 rounded-lg">
                            <tbody class="divide-y divide-gray-200">
                                <tr>
                                    <td class="font-semibold w-1/3 bg-gray-50 px-4 py-2">Tahun Anggaran</td>
                                    <td class="px-4 py-2">{{ $contractData['tahun_anggaran'] }}</td>
                                </tr>
                                <tr>
                                    <td class="font-semibold bg-gray-50 px-4 py-2">Dinas / Sudin</td>
                                    <td class="px-4 py-2">{{ $contractData['dinas_sudin'] }}</td>
                                </tr>
                                <tr>
                                    <td class="font-semibold bg-gray-50 px-4 py-2">Bidang / Seksi</td>
                                    <td class="px-4 py-2">{{ $contractData['nama_bidang_seksi'] }}</td>
                                </tr>
                                <tr>
                                    <td class="font-semibold bg-gray-50 px-4 py-2">Kode Program</td>
                                    <td class="px-4 py-2">{{ $contractData['kode_program'] }}</td>
                                </tr>
                                <tr>
                                    <td class="font-semibold bg-gray-50 px-4 py-2">Program</td>
                                    <td class="px-4 py-2">{{ $contractData['program'] }}</td>
                                </tr>
                                <tr>
                                    <td class="font-semibold bg-gray-50 px-4 py-2">Kode Kegiatan</td>
                                    <td class="px-4 py-2">{{ $contractData['kode_kegiatan'] }}</td>
                                </tr>
                                <tr>
                                    <td class="font-semibold bg-gray-50 px-4 py-2">Kegiatan</td>
                                    <td class="px-4 py-2">{{ $contractData['kegiatan'] }}</td>
                                </tr>
                                <tr>
                                    <td class="font-semibold bg-gray-50 px-4 py-2">Kode Sub Kegiatan</td>
                                    <td class="px-4 py-2">{{ $contractData['kode_sub_kegiatan'] }}</td>
                                </tr>
                                <tr>
                                    <td class="font-semibold bg-gray-50 px-4 py-2">Sub Kegiatan</td>
                                    <td class="px-4 py-2">{{ $contractData['sub_kegiatan'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-700 border border-gray-200 rounded-lg">
                            <tbody class="divide-y divide-gray-200">
                                <tr>
                                    <td class="font-semibold bg-gray-50 px-4 py-2">Kode Rekening</td>
                                    <td class="px-4 py-2">{{ $contractData['kode_rekening'] }}</td>
                                </tr>
                                <tr>
                                    <td class="font-semibold bg-gray-50 px-4 py-2">Nama Penyedia</td>
                                    <td class="px-4 py-2">{{ $contractData['nama_penyedia'] ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-semibold bg-gray-50 px-4 py-2">Uraian Rekening</td>
                                    <td class="px-4 py-2">{{ $contractData['uraian_kode_rekening'] }}</td>
                                </tr>
                                <tr>
                                    <td class="font-semibold bg-gray-50 px-4 py-2">Nama Paket</td>
                                    <td class="px-4 py-2">{{ $contractData['nama_paket'] }}</td>
                                </tr>
                                <tr>
                                    <td class="font-semibold bg-gray-50 px-4 py-2">Nomor SPK</td>
                                    <td class="px-4 py-2">{{ $contractData['no_spk'] }}</td>
                                </tr>
                                <tr>
                                    <td class="font-semibold bg-gray-50 px-4 py-2">Tanggal SPK</td>
                                    <td class="px-4 py-2">
                                        {{ \Carbon\Carbon::parse($contractData['tgl_spk'])->translatedFormat('l, d F Y') }}
                                    </td>

                                </tr>
                                <tr>
                                    <td class="font-semibold bg-gray-50 px-4 py-2">Tanggal Akhir SPK</td>
                                    <td class="px-4 py-2">
                                        {{ \Carbon\Carbon::parse($contractData['tgl_akhir_spk'])->translatedFormat('l, d F Y')
                                                                }}
                                    </td>

                                </tr>
                                <tr>
                                    <td class="font-semibold bg-gray-50 px-4 py-2">Jenis Pengadaan</td>
                                    <td class="px-4 py-2">{{ $contractData['jenis_pengadaan'] }}</td>
                                </tr>
                                <tr>
                                    <td class="font-semibold bg-gray-50 px-4 py-2">Nilai Kontrak</td>
                                    <td class="px-4 py-2 font-semibold text-green-700">
                                        Rp {{ number_format($contractData['nilai_kontrak'], 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-700 border border-gray-200 rounded-lg">
                            <tbody class="divide-y divide-gray-200">
                                <tr>
                                    <td class="font-semibold w-1/3 bg-gray-50 px-4 py-2">Nomor Kontrak</td>
                                    <td class="px-4 py-2">{{ $contract->nomor ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-semibold w-1/3 bg-gray-50 px-4 py-2">Tahun Anggaran</td>
                                    <td class="px-4 py-2">{{
                    \Carbon\Carbon::parse($contract->tanggal_mulai)->translatedFormat('Y') ?? '-' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        @endif


        {{-- Footer --}}
        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
            <x-button variant="secondary" type="button" x-on:click="$dispatch('close-modal', 'confirm-contract')">
                {{ $showDetail ? "Tutup" : "Tidak" }}
            </x-button>

            @if (!$showDetail)
                @if ($isApi)
                    <x-button type="button" x-on:click="
                                                $dispatch('proceedCreateContract', {data:{{ json_encode($contractData) }}, isApi:true})
                                                ,$dispatch('close-modal', 'confirm-contract')
                                                ,$dispatch('close-modal', 'input-contract-number')">
                        Ya, Lanjutkan
                    </x-button>
                @else
                    <x-button type="button"
                        x-on:click="$dispatch('proceedCreateContract', {data:{{ json_encode($contractData) }}, isApi:false})">
                        Ya, Lanjutkan
                    </x-button>
                @endif
            @endif
        </div>

    </div>
</x-modal>
@push('scripts')
    <script type="module">
        Livewire.on("isNotApi", (payload = {}) => {

            showConfirm(
                {

                    type: "question",
                    title: "Konfirmasi",
                    text: "Kontrak belum terdaftar di e-Monev. Lanjutkan pengiriman barang?",
                    confirmButtonText: "Lanjutkan",
                    cancelButtonText: "Batal",
                    onConfirm: () => {
                        window.Livewire.dispatch('close-modal', 'confirm-contract');
                        window.Livewire.dispatch('close-modal', 'input-contract-number');
                        window.Livewire.dispatch("proceedCreateContractAgain", { contract: payload.id });
                        window.Livewire.dispatch("open-modal", 'choose-warehouse');
                    }
                }
            )

        });
    </script>
@endpush