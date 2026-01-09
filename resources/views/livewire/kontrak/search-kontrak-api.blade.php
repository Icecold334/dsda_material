<x-modal name="input-nomor-kontrak" :dismissable="false">
    <div class="p-6 space-y-4">
        <div class="flex items-center gap-2">
            <div class="text-lg text-primary-700">
                <a href="{{ route('kontrak.index') }}" wire:navigate><i class="fa-solid fa-circle-chevron-left"></i></a>
            </div>
            <div class="font-semibold text-2xl">Masukkan Nomor Kontrak</div>
        </div>

        {{-- <form method="GET" action="{{ route('kontrak.emonev') }}"> --}}
            <div class="flex">
                <input type="text" id="nomorKontrak"
                    class="rounded-none rounded-s-lg bg-gray-50 border border-gray-300 text-gray-900 block flex-1 text-sm p-2.5"
                    placeholder="Masukkan Nomor Kontrak" required>

                <input type="text" id="tahunKontrak"
                    class="rounded-none bg-gray-50 border border-gray-300 text-gray-900 block w-20 text-sm p-2.5"
                    placeholder="Tahun" required>

                <button type="button" id="btnCariKontrak" class=" inline-flex items-center px-3 text-sm text-white bg-primary-600 hover:bg-primary-800
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
    const btnCariKontrak = document.getElementById("btnCariKontrak");
    if (btnCariKontrak) {
        btnCariKontrak.addEventListener("click", async () => {
            window.Livewire.dispatch('loading');


            const nomorKontrak = document.getElementById("nomorKontrak")?.value.trim();
            const tahun = document.getElementById("tahunKontrak")?.value.trim();

            if (!nomorKontrak || !tahun) {
                showAlert({
                    type: "warning",
                    title: "Lengkapi Data!",
                    text: "Nomor kontrak dan tahun wajib diisi",
                    showConfirmButton: false,
                });
                return;
            }

            try {
                const params = new URLSearchParams({
                    nomor_kontrak: nomorKontrak,
                    tahun: tahun,
                });

                const res = await fetch(`/kontrak/api/emonev?${params.toString()}`, {
                    headers: {
                        "Accept": "application/json",
                    },
                });

                const data = await res.json();

                if (!res.ok) {
                    throw new Error(data.message || "Terjadi kesalahan");
                }
                // window.Livewire.dispatch('close-modal', 'input-nomor-kontrak');
                window.Livewire.dispatch('confirmKontrak', {
                            data: {
                                nomor_kontrak: nomorKontrak,
                                tahun: tahun,
                                apiExist: true,
                                dataKontrak: data.data,
                                }
                            });
            } catch (err) {
                showConfirm({
                    title: "Gagal!",
                    text: "Daftarkan kontrak secara manual?",
                    type: "error",
                    confirmButtonText: "Ya",
                    cancelButtonText: "Tidak",
                    onConfirm: () => {
                        window.Livewire.dispatch('close-modal', 'input-nomor-kontrak');

                        window.Livewire.dispatch("FillVar", {
                            data: {
                                nomor_kontrak: nomorKontrak,
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