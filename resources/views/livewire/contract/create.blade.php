<div class="space-y-4">
    <div class="{{ $nomorContract ? 'grid' : 'hidden' }} grid grid-cols-2">
        <div class="">
            <div class="text-3xl font-semibold">Tambah Kontrak {{ $nomorContract ?? '' }} <span class="bg-{{ $apiExist ? 'success' : 'warning' }}-600 text-{{ $apiExist ? 'success' : 'warning' }}-100
                    text-xs font-medium px-2.5 py-0.5 rounded-full">{{ $apiExist ? "Terdaftar pada sistem
                    e-monev" : "Tidak terdaftar pada sistem e-monev" }}</span></div>
        </div>
        <div class="text-right">
            <x-button id="btnSaveContract" class="{{ $listCount > 0 ? '' : 'hidden' }}">Simpan
                Kontrak</x-button>
            @if ($apiExist)
                <x-button variant="info" type="button" x-on:click="$dispatch('contractDetail')"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 bg-white text-gray-700 border-gray-300 hover:bg-gray-50 active:bg-gray-100 focus:ring-indigo-500">Detail
                    Kontrak</x-button>
            @endif

            <a href="{{ route('contract.index') }}" wire:navigate
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 bg-white text-gray-700 border-gray-300 hover:bg-gray-50 active:bg-gray-100 focus:ring-indigo-500">Kembali</a>
        </div>
    </div>
    <livewire:contract.search-contract-api />
    <livewire:contract.confirm-contract-api />
    @if ($nomorContract)
        <livewire:contract.create-table />
    @endif
</div>
@push('scripts')
    <script type="module">
        let btnSaveContract = document.getElementById("btnSaveContract");
        if (btnSaveContract) {
            btnSaveContract.addEventListener("click", async () => {
                proceedSaveContract();
            });
            function proceedSaveContract() {
                showConfirm({
                    title: "Konfirmasi Simpan Kontrak",
                    text: "Apakah anda yakin ingin menyimpan kontrak ini beserta barang-barangnya?",
                    type: "question",
                    confirmButtonText: "Ya",
                    cancelButtonText: "Tidak",
                    onConfirm: () => {
                        Swal.fire({
                            title: "Menyimpan Kontrak...",
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            },
                        });
                        window.Livewire.dispatch('saveContract');
                    }
                });
            }
        }
    </script>

@endpush