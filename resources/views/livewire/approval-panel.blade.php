<div>
    <x-primary-button x-on:click="$dispatch('open-modal','approval-panel')">
        {{ $this->buttonLabel }}
    </x-primary-button>
    <x-modal name="approval-panel" :show="true" maxWidth="4xl">
        <div class="p-6 space-y-4">
            <div class="flex items-center gap-2">
                <div class="font-semibold text-2xl">Daftar Persetujuan</div>
            </div>

            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-black shadow-lg">
                    <thead class="text-xs text-gray-700 uppercase bg-primary-200 text-center">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Nama
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Jabatan
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Diproses
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Catatan
                            </th>
                        </tr>
                    </thead>
                    <tbody>

                        @forelse ($approvals as $approval)
                        <tr class="even:bg-primary-100 odd:bg-primary-50 border-primary-200">
                            <td class="px-6 py-4 font-medium text-primary-900">
                                {{ $approval->approver_user?->name ?? '-' }}
                                @if($approval->approver_user && $approval->approver_user->is_plt)
                                <span class="text-xs text-red-600">(PLT)</span>
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                {{ $approval->position->name }}
                                {{ $approval->division?->name }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                <span
                                    class="bg-{{ $approval->status_color }}-600 text-{{ $approval->status_color }}-100 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">{{
                                    $approval->status_text }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                {{ $approval->approved_at?->translatedFormat('l, d M Y H:i:s') ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                {{ $approval->notes ?? '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">
                                Belum ada approval
                            </td>
                        </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
            <div class="w-full text-center">
                <button type="button" wire:click="approve" @disabled(! $canApprove || ! $extraReady)
                    class="px-3 py-2 rounded bg-blue-600 text-white disabled:opacity-50">
                    Approve
                </button>

                <button type="button" wire:click="$toggle('showRejectForm')" @disabled(! $canApprove)
                    class="px-3 py-2 rounded bg-red-600 text-white disabled:opacity-50">
                    Tolak
                </button>
            </div>
        </div>
    </x-modal>
    {{-- @push('scripts')
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
                    if (data.status == 'error') {
                        showAlert({
                            type: "error",
                            title: "Gagal!",
                            text: data.data,
                            showConfirmButton: false,
                        })
                    } else {
                        window.Livewire.dispatch('confirmContract', {
                            data: {
                                nomor_kontrak: nomorContract,
                                tahun: tahun,
                                apiExist: true,
                                dataContract: data.data,
                            }
                        });
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
    @endpush --}}
</div>