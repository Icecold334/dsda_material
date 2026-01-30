<div class="space-y-4">
    <div class="grid grid-cols-2 ">
        <div class="">
            <div class="text-3xl font-semibold"> Detail Permintaan #{{ $permintaan->nomor }}</div>
        </div>
        <div class="text-right flex gap-2 justify-end" x-data="{ fileCount: 0 }"
            @file-count-updated.window="fileCount = $event.detail">

            @if ($permintaan->status == 'draft' && $permintaan->user_id == auth()->id())
                <x-primary-button id="confirmSubmit">
                    Ajukan Permintaan
                </x-primary-button>
                <x-button id="confirmDelete" variant="danger">
                    Hapus Permintaan
                </x-button>
            @elseif($permintaan->status != 'draft')
                <livewire:approval-panel :module="'permintaan'" :approvable-type="\App\Models\RequestModel::class"
                    :approvable-id="$permintaan->id" />
            @endif
            <x-secondary-button @click="$dispatch('open-modal', 'request-information-modal')" type="button">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Informasi Permintaan
            </x-secondary-button>
            @if($permintaan->status == 'approved')
                <x-secondary-button @click="$dispatch('open-modal', 'foto-pengiriman-modal')" type="button">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Foto Pengiriman
                </x-secondary-button>
                <x-secondary-button @click="$dispatch('open-modal', 'delivery-info-modal')" type="button">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Informasi Pengiriman
                </x-secondary-button>
            @endif
            <x-secondary-button @click="$dispatch('open-modal', 'lampiran-modal')" type="button">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                </svg>
                Lampiran
                <span
                    class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-indigo-600 rounded-full"
                    x-show="fileCount > 0" x-text="fileCount">
                </span>
            </x-secondary-button>
            <x-secondary-button @click="$dispatch('open-modal', 'document-modal')" type="button">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                Lihat Dokumen
            </x-secondary-button>
            <x-button variant="secondary" href="{{ route('permintaan.nonRab.index') }}" wire:navigate>
                Kembali
            </x-button>
        </div>
    </div>

    <!-- Modal Informasi Permintaan -->
    <livewire:components.request-information-modal :mode="'show'" :isRab="false" :key="'request-info-modal-show'" />

    <!-- Modal Informasi Pengiriman -->
    <livewire:components.delivery-info-modal :permintaan="$permintaan" :key="'delivery-info-modal-' . $permintaan->id" />

    <div>
        @php
        $data = [
        [ "name"=> "No", "id"=> "no", "width"=> "8%" ],
        [ "name"=> "Kode Barang", "id"=> "kode", "width"=> "12%" ],
        [ "name"=> "Barang", "id"=> "barang", "width"=> "15%" ],
        [ "name"=> "Spesifikasi", "id"=> "spec" ],
        [ "name"=> "Jumlah Diminta", "id"=> "qty_request", "width"=> "12%" ],
        [ "name"=> "Jumlah Disetujui", "id"=> "qty_approved", "width"=> "12%" ],
        [ "name"=> "Foto", "id"=> "foto", "width"=> "12%", "className"=>"text-center" ]
        ];
        $hideColumns = [];

        if (false) {
        $hideColumns[] = 'foto';
        }

        if (false) {
        $hideColumns[] = 'qty_approved';
        }

        $data = array_values(array_filter($data, function ($col) use ($hideColumns) {
        return !in_array($col['id'], $hideColumns);
        }));
        @endphp

        <x-card title="Daftar Barang">
            <div data-grid data-api="{{ route('permintaan.nonRab.show.json', $permintaan) }}" data-columns='[
        { "name": "No", "id": "no", "width": "8%" },
        { "name": "Kode Barang", "id": "kode", "width": "12%" },
        { "name": "Barang", "id": "barang", "width": "15%" },
        { "name": "Spesifikasi", "id": "spec" },
        { "name": "Jumlah Diminta", "id": "qty_request", "width": "12%" },
        { "name": "Jumlah Disetujui", "id": "qty_approved", "width": "12%" },
        { "name": "Foto", "id": "foto", "width": "12%" }
    ]' wire:ignore>
            </div>
        </x-card>
    </div>

    <!-- Modal Lampiran -->
    <livewire:components.document-upload mode="show" modelType="App\Models\RequestModel" :modelId="$permintaan->id"
        category="lampiran_permintaan" label="Lampiran Permintaan" :multiple="true" accept="image/*,.pdf,.doc,.docx"
        modalId="lampiran-modal" :key="'doc-show-lampiran-' . $permintaan->id" />

    <!-- Modal Foto Pengiriman -->

    @if(\App\Models\Document::where('documentable_type', \App\Models\RequestModel::class)->where('documentable_id', $permintaan->id)->where('category', 'pickup_photo')->exists())
        <livewire:components.document-upload mode="show" modelType="App\Models\RequestModel" :modelId="$permintaan->id"
            category="pickup_photo" label="Foto Pengiriman" :multiple="true" accept="image/*"
            modalId="foto-pengiriman-modal" :key="'doc-show-foto-pengiriman-' . $permintaan->id" />
    @else
        <livewire:components.document-upload mode="create" modelType="App\Models\RequestModel" :modelId="$permintaan->id"
            category="pickup_photo" label="Foto Pengiriman" :multiple="true" accept="image/*"
            modalId="foto-pengiriman-modal" :key="'doc-create-foto-pengiriman-' . $permintaan->id" :autoUpload="true" />
    @endif
    <!-- Modal Lihat Dokumen -->
    <livewire:components.document-modal :permintaanId="$permintaan->id" :key="'document-modal-' . $permintaan->id" />

    @push('scripts')
        <script type="module">
            const confirmSubmitBtn = document.getElementById("confirmSubmit");
            if (confirmSubmitBtn) {
                confirmSubmitBtn.addEventListener("click", () => {
                    showConfirm(
                        {
                            type: "question",
                            title: "Konfirmasi",
                            text: "Yakin ingin mengirim permintaan ini?",
                            confirmButtonText: "Lanjutkan",
                            cancelButtonText: "Batal",
                            onConfirm: (e) => {
                                window.Livewire.dispatch('confirmSubmit');
                            }
                        }
                    )
                });
            }

            const confirmDeleteBtn = document.getElementById("confirmDelete");
            if (confirmDeleteBtn) {
                confirmDeleteBtn.addEventListener("click", () => {
                    showConfirm(
                        {
                            type: "question",
                            title: "Konfirmasi",
                            text: "Yakin ingin menghapus permintaan ini?",
                            confirmButtonText: "Lanjutkan",
                            cancelButtonText: "Batal",
                            onConfirm: (e) => {
                                window.Livewire.dispatch('confirmDelete');
                            }
                        }
                    )
                });
            }
            document.addEventListener('deleteSuccess', function ({ detail }) {

                showAlert(
                    {
                        type: "success",
                        title: "Berhasil!",
                        text: "Hapus permintaan berhasil!",
                        onClose: (e) => {
                            Livewire.navigate("{{ route('permintaan.nonRab.index') }}");
                        }
                    }
                )

            });

            document.addEventListener('showAlert', function ({ detail }) {
                showAlert(detail);
            });

            let currentUploadItemId = null;

            // Event delegation untuk tombol foto
            document.addEventListener('click', function (e) {
                // Handle view photo button
                if (e.target.closest('.btn-view-photo')) {
                    const btn = e.target.closest('.btn-view-photo');
                    const photoUrl = btn.getAttribute('data-photo-url');

                    Swal.fire({
                        imageUrl: photoUrl,
                        imageAlt: 'Foto Barang',
                        showConfirmButton: false,
                        showCloseButton: true,
                        width: 'auto',
                        customClass: {
                            image: 'max-h-96'
                        }
                    });
                }

                // Handle upload photo button
                if (e.target.closest('.btn-upload-photo')) {
                    const btn = e.target.closest('.btn-upload-photo');
                    currentUploadItemId = btn.getAttribute('data-item-id');

                    // Open file input
                    Swal.fire({
                        title: 'Upload Foto Barang',
                        html: '<input type="file" id="photo-file-input" accept="image/*" class="swal2-input" style="display:block;">',
                        showCancelButton: true,
                        confirmButtonText: 'Upload',
                        cancelButtonText: 'Batal',
                        preConfirm: () => {
                            const fileInput = document.getElementById('photo-file-input');
                            const file = fileInput.files[0];

                            if (!file) {
                                Swal.showValidationMessage('Pilih foto terlebih dahulu');
                                return false;
                            }

                            if (!file.type.startsWith('image/')) {
                                Swal.showValidationMessage('File harus berupa gambar');
                                return false;
                            }

                            if (file.size > 5120 * 1024) {
                                Swal.showValidationMessage('Ukuran file maksimal 5MB');
                                return false;
                            }

                            return file;
                        }
                    }).then((result) => {
                        if (result.isConfirmed && result.value) {
                            uploadPhoto(result.value);
                        }
                    });
                }
            });

            function uploadPhoto(file) {
                const formData = new FormData();
                formData.append('photo', file);
                formData.append('_token', '{{ csrf_token() }}');

                // Show loading
                Swal.fire({
                    title: 'Mengupload...',
                    text: 'Mohon tunggu',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch('{{ route("permintaan.nonRab.item.upload-photo", ["permintaan" => $permintaan->id, "item" => "ITEM_ID"]) }}'.replace('ITEM_ID', currentUploadItemId), {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message || 'Foto berhasil diupload',
                            timer: 2000
                        }).then(() => {
                            location.reload();
                        });
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat upload foto'
                        });
                    });
            }
        </script>
    @endpush
</div>