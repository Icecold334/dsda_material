<div class="space-y-4">
    <div class="grid grid-cols-2">
        <div>
            <div class="text-3xl font-semibold">Buat Rencana Anggaran Biaya</div>
        </div>
        <div class="text-right flex gap-2 justify-end" x-data="{ fileCount: 0 }"
            @file-count-updated.window="fileCount = $event.detail">
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
            <x-button variant="secondary" href="{{ route('rab.index') }}" wire:navigate>
                Kembali
            </x-button>
        </div>
    </div>

    <form wire:submit.prevent="validateForm">
        <div class="grid grid-cols-2 gap-4">
            <x-card title="Informasi RAB">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <x-input-label for="nomor" value="Nomor RAB" />
                        <div class="mt-1 block w-full max-w-[500px]">
                            <x-text-input id="nomor" wire:model="nomor" placeholder="Kosongkan untuk auto generate"
                                type="text" class="w-full" />
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <x-input-label for="name" value="Nama Kegiatan" />
                        <div class="mt-1 block w-full max-w-[500px]">
                            <x-text-input id="name" wire:model="name" type="text" class="w-full"
                                placeholder="Masukkan nama kegiatan" />
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <x-input-label for="tahun" value="Tahun Anggaran" />
                        <div class="mt-1 block w-full max-w-[500px]">
                            <x-text-input id="tahun" wire:model="tahun" type="number" class="w-full"
                                placeholder="2026" min="2000" max="2100" />
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <x-input-label for="tanggal_mulai" value="Tanggal Mulai" />
                        <div class="mt-1 block w-full max-w-[500px]">
                            <x-text-input id="tanggal_mulai" wire:model="tanggal_mulai" type="date" class="w-full" />
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <x-input-label for="tanggal_selesai" value="Tanggal Selesai" />
                        <div class="mt-1 block w-full max-w-[500px]">
                            <x-text-input id="tanggal_selesai" wire:model="tanggal_selesai" type="date"
                                class="w-full" />
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <x-input-label for="sudin_id" value="Sudin" />
                        <div class="mt-1 block w-full max-w-[500px]">
                            <livewire:components.select-input wire:model.live="sudin_id" :options="$sudins->pluck('name', 'id')"
                                placeholder="-- Pilih Sudin --" :key="'sudin-select'" />
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <x-input-label for="district_id" value="Lokasi" />
                        <div class="input w-full max-w-[500px] flex flex-col gap-4">
                            <div class="grid-cols-2 flex gap-4">
                                <div class="w-full">
                                    <livewire:components.select-input wire:model.live="district_id" :options="$districts->pluck('name', 'id')"
                                        placeholder="-- Pilih Kecamatan --" :key="'district-select-' . $sudin_id" />
                                </div>
                                <div class="w-full">
                                    <livewire:components.select-input wire:model="subdistrict_id" :options="$subdistricts->pluck('name', 'id')"
                                        placeholder="-- Pilih Kelurahan --" :key="'subdistrict-select-' . $district_id" />
                                </div>
                            </div>
                            <textarea id="address" wire:model="address" rows="3"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full"
                                placeholder="Masukkan alamat lengkap"></textarea>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <x-input-label for="panjang" value="Dimensi" />
                        <div class="w-full max-w-[500px] grid grid-cols-3 gap-4">
                            <div class="w-full">
                                <x-text-input id="panjang" wire:model="panjang" type="text" class="w-full"
                                    placeholder="Panjang" />
                            </div>
                            <div class="w-full">
                                <x-text-input id="lebar" wire:model="lebar" type="text" class="w-full"
                                    placeholder="Lebar" />
                            </div>
                            <div class="w-full">
                                <x-text-input id="tinggi" wire:model="tinggi" type="text" class="w-full"
                                    placeholder="Tinggi" />
                            </div>
                        </div>
                    </div>
                </div>
            </x-card>
            <x-card title="Tambah Barang">
                <div class="space-y-4">
                    @if (!$sudin_id)
                        <div class="p-4 text-sm text-amber-800 rounded-lg bg-amber-50" role="alert">
                            Pilih Sudin terlebih dahulu untuk menambahkan barang
                        </div>
                    @else
                        <div class="flex items-center justify-between">
                            <x-input-label for="item_type_id" value="Tipe Barang" />
                            <div class="mt-1 block w-full max-w-[500px]">
                                <livewire:components.select-input wire:model.live="item_type_id" :options="$itemTypes->pluck('name', 'id')"
                                    placeholder="-- Pilih Tipe Barang --" :disabled="count($items) > 0" :key="'type-select-' . $sudin_id . '-' . count($items)" />
                                @if (count($items) > 0)
                                    <p class="mt-1 text-xs text-gray-500">Tipe barang tidak dapat diubah setelah ada
                                        item ditambahkan</p>
                                @endif
                            </div>
                        </div>

                        @if ($item_type_id)
                            <div class="flex items-center justify-between">
                                <x-input-label for="item_category_id" value="Kategori Barang" />
                                <div class="mt-1 block w-full max-w-[500px]">
                                    <livewire:components.select-input wire:model.live="item_category_id"
                                        :options="$itemCategories->pluck('name', 'id')" placeholder="-- Pilih Kategori --" :key="'category-select-' . $sudin_id . '-' . $item_type_id" />
                                </div>
                            </div>

                            @if ($item_category_id)
                                <div class="flex items-center justify-between">
                                    <x-input-label for="item_id" value="Spesifikasi Barang" />
                                    <div class="mt-1 block w-full max-w-[500px]">
                                        <livewire:components.select-input wire:model.live="item_id" :options="$availableItems->pluck('spec', 'id')"
                                            placeholder="-- Pilih Barang --" :key="'item-select-' . $item_category_id" />
                                    </div>
                                </div>
                            @endif

                            @if ($item_id)
                                <div class="flex items-center justify-between">
                                    <x-input-label for="qty" value="Jumlah" />
                                    <div class="mt-1 block w-full max-w-[500px]">
                                        <x-text-input id="qty" wire:model="qty" type="number" step="0.01"
                                            min="0.01" class="w-full" placeholder="0.00" />
                                    </div>
                                </div>
                            @endif
                        @endif

                        <div class="flex justify-end">
                            <x-button type="button" wire:click="addItem">
                                Tambah Item
                            </x-button>
                        </div>
                    @endif
                </div>
            </x-card>
        </div>
        <x-card title="Daftar Barang">
            @if (count($items) > 0)
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left rtl:text-right text-black shadow-lg">
                        <thead class="text-xs text-gray-700 uppercase bg-primary-200 text-center">
                            <tr>
                                <th scope="col" class="px-6 py-3">No</th>
                                <th scope="col" class="px-6 py-3">Kategori</th>
                                <th scope="col" class="px-6 py-3">Spesifikasi</th>
                                <th scope="col" class="px-6 py-3">Satuan</th>
                                <th scope="col" class="px-6 py-3">Jumlah</th>
                                <th scope="col" class="px-6 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $index => $item)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 text-center">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4">{{ $item['item_category'] }}</td>
                                    <td class="px-6 py-4">{{ $item['item_spec'] }}</td>
                                    <td class="px-6 py-4 text-center">{{ $item['item_unit'] }}</td>
                                    <td class="px-6 py-4 text-right">{{ number_format($item['qty'], 2) }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <x-button variant="danger" type="button"
                                            wire:click="removeItem({{ $index }})">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </x-button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left rtl:text-right text-black shadow-lg">
                        <thead class="text-xs text-gray-700 uppercase bg-primary-200 text-center">
                            <tr>
                                <th scope="col" class="px-6 py-3">No</th>
                                <th scope="col" class="px-6 py-3">Kategori</th>
                                <th scope="col" class="px-6 py-3">Spesifikasi</th>
                                <th scope="col" class="px-6 py-3">Satuan</th>
                                <th scope="col" class="px-6 py-3">Jumlah</th>
                                <th scope="col" class="px-6 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="bg-white border-b">
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    Belum ada barang ditambahkan
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endif
        </x-card>

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('rab.index') }}" wire:navigate
                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                Batal
            </a>
            <x-button type="submit">
                Simpan RAB
            </x-button>
        </div>
    </form>

    <!-- Modal Lampiran -->
    <livewire:components.document-upload mode="create" modelType="App\Models\Rab" category="lampiran_rab"
        label="Upload Lampiran" :multiple="true" accept="image/*,.pdf,.doc,.docx" modalId="lampiran-modal"
        :key="'doc-upload-lampiran'" />
</div>

@push('scripts')
    <script type="module">
        document.addEventListener('livewire:init', () => {
            Livewire.on('validation-passed-create', () => {
                showConfirm({
                    title: "Konfirmasi Simpan RAB",
                    text: "Apakah anda yakin ingin menyimpan RAB ini?",
                    type: "question",
                    confirmButtonText: "Ya, Simpan",
                    cancelButtonText: "Batal",
                    onConfirm: () => {
                        Swal.fire({
                            title: "Menyimpan RAB...",
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            },
                        });
                        Livewire.dispatch('confirm-save-rab');
                    }
                });
            });
        });
    </script>
@endpush
