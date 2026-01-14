<div class="space-y-4">
    <div class="{{ $nomorContract ? 'grid' :'hidden' }} grid grid-cols-2">
        <div class="">
            <div class="text-3xl font-semibold">Tambah Kontrak {{ $nomorContract ?? '' }} <span class="bg-{{ $apiExist ? 'success':'warning' }}-600 text-{{ $apiExist ? 'success' :'warning' }}-100
                    text-xs font-medium px-2.5 py-0.5 rounded-full">{{ $apiExist ? "Terdaftar pada sistem
                    e-monev":"Tidak terdaftar pada sistem e-monev" }}</span></div>
        </div>
        <div class="text-right">
            <button type="button" id="btnSaveContract"
                class="{{ $listCount > 0 ? '':'hidden' }} text-white bg-success-700 hover:bg-success-800 focus:ring-4 focus:ring-success-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2  focus:outline-none">Simpan
                Kontrak</button>
            @if ($apiExist)
            <button type="button" x-on:click="$dispatch('contractDetail')"
                class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2  focus:outline-none">Detail
                Kontrak</button>
            @endif

            <a href="{{ route('contract.index') }}" wire:navigate
                class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2  focus:outline-none">Kembali</a>
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