<div>
    <x-primary-button x-on:click="$dispatch('open-modal','approval-panel')">
        {{ $this->buttonLabel }}
    </x-primary-button>
    <x-modal name="approval-panel" :show="false" maxWidth="4xl">
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
                                {{ $approval->approved_at?->translatedFormat('l, d F Y H:i:s') ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                {{ $approval->notes ?? '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                Belum ada approval
                            </td>
                        </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
            <div class="w-full text-center">
                <button type="button" id="confirmApprove" @disabled(!$canApprove || !$extraReady)
                    class="px-3 py-2 rounded bg-primary-600 text-white disabled:opacity-50">
                    Setuju
                </button>

                <button type="button" id="confirmReject" @disabled(!$canApprove || !$extraReady)
                    class="px-3 py-2 rounded bg-red-600 text-white disabled:opacity-50">
                    Tolak
                </button>
            </div>
        </div>
    </x-modal>
    @push('scripts')
    <script type="module">
        document.getElementById("confirmApprove").addEventListener("click", () => {
            showConfirm(
                {
                    type: "question",
                    title: "Konfirmasi",
                    text: "Apakah Anda yakin ingin menyetujui permintaan ini?",
                    confirmButtonText: "Lanjutkan",
                    cancelButtonText: "Batal",
                    onConfirm: (e) => {
                        window.Livewire.dispatch('confirmApprove');
                    }
                }
            )
        });
        document.getElementById("confirmReject").addEventListener("click", () => {
            Swal.fire({
                title: "Keterangan",
                input: "textarea",
                inputPlaceholder: "Masukkan keterangan",
                inputAttributes: {
                    'aria-label': 'Keterangan penolakan'
                },
                showCancelButton: true,
                confirmButtonText: "Lanjutkan",
                cancelButtonText: "Batal",
                preConfirm: (value) => {
                    if (!value) {
                        Swal.showValidationMessage("Keterangan wajib diisi");
                        return false;
                    }
                    return value;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const notes = result.value;
                    window.Livewire.dispatch('confirmReject',{rejectReason:notes});


                    Swal.fire({
                        icon: "success",
                        title: "Penolakan Dikirim",
                        text: "Permintaan berhasil ditolak.",
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        });



    </script>
    @endpush
</div>