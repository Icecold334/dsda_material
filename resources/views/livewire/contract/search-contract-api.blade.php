<x-modal name="input-contract-number" :dismissable="false">
    <div class="p-6 space-y-4">
        <div class="flex items-center gap-2">
            <div class="text-lg text-primary-700">
                <a href="{{ route('contract.index') }}" wire:navigate><i
                        class="fa-solid fa-circle-chevron-left"></i></a>
            </div>
            <div class="font-semibold text-2xl">Masukkan Nomor Contract</div>
        </div>

        {{-- <form method="GET" action="{{ route('contract.emonev') }}"> --}}
            <div class="flex">
                <input type="text" id="nomorContract"
                    class="rounded-none rounded-s-lg bg-gray-50 border border-gray-300 text-gray-900 block flex-1 text-sm p-2.5"
                    placeholder="Masukkan Nomor Contract" required>

                <input type="text" id="contractYear"
                    class="rounded-none bg-gray-50 border border-gray-300 text-gray-900 block w-20 text-sm p-2.5"
                    placeholder="Tahun" required>

                <button type="button" id="btnCariContract" class=" inline-flex items-center px-3 text-sm text-white bg-primary-600 hover:bg-primary-800
                        rounded-e-md transition">
                    Cari
                </button>
            </div>
            {{--
        </form> --}}
    </div>
</x-modal>
@push('scripts')
<script type="module">
    const btnCariContract = document.getElementById("btnCariContract");
    if (btnCariContract) {
        btnCariContract.addEventListener("click", async () => {
            window.Livewire.dispatch('loading');


            const nomorContract = document.getElementById("nomorContract")?.value.trim();
            const tahun = document.getElementById("contractYear")?.value.trim();

            if (!nomorContract || !tahun) {
                showAlert({
                    type: "warning",
                    title: "Lengkapi Data!",
                    text: "Nomor contract dan tahun wajib diisi",
                    showConfirmButton: false,
                });
                return;
            }

            try {
                const params = new URLSearchParams({
                    nomor_kontrak: nomorContract,
                    tahun: tahun,
                });

                const res = await fetch(`/contract/api/emonev?${params.toString()}`, {
                    headers: {
                        "Accept": "application/json",
                    },
                });

                const data = await res.json();

                if (!res.ok) {
                    throw new Error(data.message || "Terjadi kesalahan");
                }
                // window.Livewire.dispatch('close-modal', 'input-contract-number');
                window.Livewire.dispatch('confirmContract', {
                            data: {
                                nomor_kontrak: nomorContract,
                                tahun: tahun,
                                apiExist: true,
                                dataContract: data.data,
                                }
                            });
            } catch (err) {
                showConfirm({
                    title: "Gagal!",
                    text: "Daftarkan contract secara manual?",
                    type: "error",
                    confirmButtonText: "Ya",
                    cancelButtonText: "Tidak",
                    onConfirm: () => {
                        window.Livewire.dispatch('close-modal', 'input-contract-number');

                        window.Livewire.dispatch("FillVar", {
                            data: {
                                nomor_kontrak: nomorContract,
                                tahun: tahun,
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