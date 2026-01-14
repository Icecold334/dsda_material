<x-modal name="input-contract-number" :dismissable="false">
    <div class="p-6 space-y-4">
        <div class="flex items-center gap-2">
            <div class="text-lg text-primary-700">
                <a href="{{ route('delivery.index') }}" wire:navigate><i
                        class="fa-solid fa-circle-chevron-left"></i></a>
            </div>
            <div class="font-semibold text-2xl">Masukkan Nomor Kontrak</div>
        </div>

        <div class="flex">
            <input type="text" id="nomorContract"
                class="rounded-none rounded-s-lg bg-gray-50 border border-gray-300 text-gray-900 block flex-1 text-sm p-2.5"
                placeholder="Masukkan Nomor Kontrak" required>

            <input type="text" id="contractYear"
                class="rounded-none bg-gray-50 border border-gray-300 text-gray-900 block w-20 text-sm p-2.5"
                placeholder="Tahun" required>

            <button type="button" id="btnCariContract" class=" inline-flex items-center px-3 text-sm text-white bg-primary-600 hover:bg-primary-800
                        rounded-e-md transition">
                Cari
            </button>
        </div>
    </div>
</x-modal>
@push('scripts')
<script type="module">
    const btnCariContract = document.getElementById("btnCariContract");
    if (btnCariContract) {
        btnCariContract.addEventListener("click", async () => {


            const nomorContract = document.getElementById("nomorContract")?.value.trim();
            const tahun = document.getElementById("contractYear")?.value.trim();

            if (!nomorContract || !tahun) {
                showAlert({
                    type: "warning",
                    title: "Lengkapi Data!",
                    text: "Nomor kontrak dan tahun wajib diisi",
                    showConfirmButton: false,
                });
                return;
            }

            try {
                window.Livewire.dispatch('loading');
                const params = new URLSearchParams({
                    contractNumber: nomorContract,
                    year: tahun,
                });

                const res = await fetch(`{{ route('delivery.getContract') }}?${params.toString()}`, {
                    headers: {
                        "Accept": "application/json",
                    },
                });

                const data = await res.json();
                
                
                if (!res.ok) {
                    throw new Error(data.message || "Terjadi kesalahan");
                }
                if (data.status == 'error') {
                    showAlert({
                    type: "error",
                    title: "Gagal!",
                    text: data.data,
                    showConfirmButton: false,
                    })
                }else{
                    
                    if (data.data.is_api) {
                        const paramsApi = new URLSearchParams({
                            nomor_kontrak: nomorContract,
                            tahun: tahun,
                        });

                        const resApi = await fetch(`/contract/api/emonev?${paramsApi.toString()}`, {
                            headers: {
                                "Accept": "application/json",
                            },
                        });

                        const dataApi = await resApi.json();
                        window.Livewire.dispatch('confirmContract', {
                                    contract: data.data.id,
                                    data: dataApi.data,
                                    });
                    }else{
                        window.Livewire.dispatch('confirmContract', {
                                    contract: data.data.id
                                    });
                    }
                    
                }
                // window.Livewire.dispatch('close-modal', 'input-contract-number');
            } catch (err) {
                showConfirm({
                    title: "Gagal!",
                    text: "Daftarkan kontrak secara manual?",
                    type: "error",
                    confirmButtonText: "Ya",
                    cancelButtonText: "Tidak",
                    onConfirm: () => {
                        window.Livewire.dispatch('close-modal', 'input-contract-number');

                        window.Livewire.dispatch("proceedCreateContract", {
                            data: {
                                no_spk: nomorContract,
                                tahun_anggaran: tahun,
                                apiExist: false,
                            }
                        });
                    }
                });
            }
        });
    }


</script>
@endpush